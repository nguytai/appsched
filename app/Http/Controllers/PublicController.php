<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use Hash;
use View;
use DB;
use DateTime;
use Illuminate\Support\Facades\Input;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

class PublicController extends Controller
{
    public function __construct() {
        //$this->middleware('auth', ['except' => ['appointment','/']]);
        if (Auth::check("user")) {
            $user = Auth::user("user");
            $data = [
                'name' => $user->first_name . ' ' . $user->last_name
            ];
            View::share('user', $data);
        }
    }

    public function homeView() {
        return view("home");
    }

    public function clientBookingView(){
        if (Auth::check("user")) {
            // get the user id
            $data = DB::table("Appointments")
                ->join("StylistDailySchedule",function($join) {
                    $join->on("Appointments.schedule_id", "=", "StylistDailySchedule.id")
                        ->where("client_id", "=", Auth::user("user")->id );
                })
                ->join("Stylist",function($join) {
                    $join->on("Appointments.stylist_id", "=", "Stylist.id")
                        ->where("client_id", "=", Auth::user("user")->id);
                })
                ->select("Appointments.id","Appointments.schedule_id","Appointments.service_name","StylistDailySchedule.time_start AS time_start",
                    "StylistDailySchedule.time_end AS time_end", "StylistDailySchedule.calendar_date AS calendar_date",
                    "Stylist.first_name AS first_name")
                ->get();
            return view('client_appointment_view')->with("bookings", $data);
        }
    }

    public function deleteAppointment() {
        $input = Input::all();
        $schedule = DB::table("StylistDailySchedule")->where("id","=",$input['schedule_id'])->first();
        $concatdatetime = $schedule->calendar_date." ".$schedule->time_start;
        $date1 = date('Y-m-d g:i a',strtotime($concatdatetime));
        $date2 = date('Y-m-d g:i a', time());
        $timedifference = round((strtotime($date1)-strtotime($date2))/60/60,1);

        if($timedifference > 12) {
            DB::table("StylistDailySchedule")->where("id", "=", $input['schedule_id'])->update(["status" => "available"]);
            DB::table("Appointments")->where("id", "=", $input['id'])->delete();
            return array(Input::all(), true);
        }
        return array(Input::all(), false);
    }

    public function appointmentView() {
        $input = Input::all();
        $data=array();
        if (isset($input['step'])) {
            if ($input['step'] == 2) {
                $scheduleCollection = DB::table("StylistDailySchedule")
                    ->where(function($query){
                        $query//->where("stylist","1")
                              ->where("calendar_date","=",Input::get("date"))
                              ->where("status","!=","lunch");
                    })
                    ->orderBy('time_start','asc')
                    ->groupBy("time_start")
                    ->distinct()
                    ->get();
                foreach ($scheduleCollection as $item) {
                    $specialistAvailable=DB::table("StylistDailySchedule")
                        ->select("stylist")
                        ->where("time_start","=",$item->time_start)
                        ->where("calendar_date","=",$item->calendar_date)
                        ->where("status","!=","booked")
                        ->where("status","!=","lunch")
                        ->get();
                    $available="";
                    foreach($specialistAvailable as $specialist) {
                        $availableCollection=DB::table("Stylist")->where("id","=",$specialist->stylist)->first();
                        if ($available != "") { $available.=", "; }
                        $available.= $availableCollection->first_name;
                    }
                    $data[] = array
                    (
                        "id"        => $item->id,
                        "time_start" => $item->time_start,
                        "time_end"  => $item->time_end,
                        "status"    => $item->status,
                        "stylist"   => ($available == "" ? "-" : $available)
                    );
                }
                //print_r($data);
                $tmp=DB::table("StylistDailySchedule")
                    ->join("Stylist","Stylist.id","=","StylistDailySchedule.stylist")
                    ->select("Stylist.id as id","Stylist.first_name as first_name")
                    ->where("StylistDailySchedule.calendar_date","=",Input::get("date"))
                    ->groupBy("Stylist.first_name")
                    ->distinct()
                    ->get();

                return view("timeslot")->with("timeslots",$data)->with("available",$tmp);
            } else if ($input['step'] == 3) {
                $user="";
                if (Auth::user("user")){
                    $user=DB::table("Clients")->select("first_name","last_name","phone_number","phone_type", "email")->where("id","=",Auth::user("user")->id)->first();
                }
                $time=DB::table("StylistDailySchedule")->select("time_start")->where("id","=",$input['service_1_time'])->first();
                return view("checkout")->with("client",$user)->with("aptTime",$time);
            }
        }

        //$services = DB::table("Services")->where("stylist","1")->get();
        $services = DB::table("Services")->groupBy("name")->distinct()->get();
        $stylists = DB::table("Stylist")->select("id","first_name","last_name")->get();

        return view("appointment")->with("services",$services)->with("stylists",$stylists);
    }

    public function get_availability() {
        $input = Input::all();

        $totalSpecialists = DB::table("Stylist")->select("id","first_name")->get();

        // Use this when Calendar bug is fixed
        $month = (isset($input['month']) ? date("Y-m-d",strtotime(date("d") . "-" . $input['month'] . "-" . date("Y")))  : date('Y-m-d'));

        //$month = (isset($input['month']) ? date("Y-m-d",strtotime("01" . "-" . $input['month'] . "-" . date("Y")))  : date('Y-m-d'));

        $data = array();
        foreach ($totalSpecialists as $specialist) {
            $query =  DB::table("StylistDailySchedule")
                ->where(function($query) use ($specialist,$month){
                    $query->where("stylist","=",$specialist->id)
                        ->where("calendar_date",">=",$month);//);date('Y-m-d')); // Change this to todays date :)
                })
                ->groupby('calendar_date')
                ->distinct()
                ->get();

            foreach($query as $entry) {
                $tmp = array(
                    "id"    => $entry->id,
                    "title"  => $specialist->first_name,
                    "start" => $entry->calendar_date . 'T' . "14:30:00",//$entry->time_start,
                    "end"   => $entry->calendar_date . 'T' . "15:00:00",//$entry->time_end
                );
                $data[] = $tmp; // Appends $tmp to $data
            }
        }
        return $data;
    }

    public function get_time() {
        $input = Input::all();
        $data="";
        $timeslotCollection=DB::table("StylistDailySchedule")
            ->where("stylist","=",$input['id'])
            ->where("calendar_date","=",$input['date'])
            ->where("status","!=","booked")
            ->where("status","!=","lunch")
            ->orderBy("time_start","asc")
            ->get();

        foreach ($timeslotCollection as $timeslot) {
            $data.="<option value=\"$timeslot->id\">" . date("g:i a", strtotime($timeslot->time_start)) . "</option>";
        }

        return $data;
    }
    public function complete_booking() {
        $input = Input::all();
        $authUser=null;
        $isNewUser=false;
        //check if user is logged in
        if(!Auth::check("user")){
            $authUser = DB::table("Clients")->where('email',"=",$input['email'])->get();
            //check if temp user already exists
            if($authUser == null){
                //temp user does not exist so save
                //set the temp account to unavtivated
                DB::table("Clients")->insert(
                    [
                        "first_name" => $input['first_name'],
                        "last_name" => $input['last_name'],
                        "email" => $input['email'],
                        "phone_number" => $input['phone_number'],
                        "phone_type" => $input['phone_type'],
                        "status" => "unactivated"
                    ]
                );
            }
        }
        for ($i = 0; $i < $input['num']; $i++){
            //save to schedule table
            DB::table("StylistDailySchedule")->where('id',$input['service_' . ($i + 1) . '_time'])->update(array("status" => "booked"));
            $stylist_id = DB::table("Stylist")->where('id', "=", $input['specialist_id'])->first();
            if (Auth::check("user")) {
                $client = Auth::user("user")->id;
                $authUser=true;
            }else {
                $tmp = DB::table("Clients")->select("id","username")->where('email',"=",$input['email'])->first();
                $client = $tmp->id;
                if ($tmp->username == null) {
                    $isNewUser=true;
                }
            }

            $service_name = DB::table("Services")->where('id', "=", intval($input['service_' . ($i + 1)]))->first();

            DB::table("Appointments")->insert(
                array(
                    "schedule_id" => $input['service_' . ($i + 1) . '_time'],
                    "stylist_id" => $stylist_id->id,
                    "service_name" => $service_name->name,
                    "client_id" => $client,
                    "description" => (isset($input['special']) ? $input['special'] : "N/A")
                )
            );
        }
        if ($isNewUser) {
            return view("/complete_booking")->with("old_data",Input::all());
        } else {
            return view("/complete_booking")->with("old_data",Input::all())->with("existingUser",$authUser);
        }

    }

    public function register() {
        $input = Input::all();
        if($input['first_name'] != "" && $input['last_name'] != "" &&
        $input['username'] != "" && $input['email'] != "") {
            //format phone number (xxx)xxx-xxxx
            $format_phone = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $input['phone_number']) . "\n";
            $data = array(
                "first_name" => $input['first_name'],
                "last_name" => $input['last_name'],
                "phone_number" => $format_phone,
                "phone_type" => $input['phone_type'],
                "username" => $input['username'],
                "password" => Hash::make($input['password']),
                "email" => $input['email'],
                "status" => "activated",
                "activation_key" => md5(time() + $input['password'])
            );

            $authUser = DB::table("Clients")->where('email', "=", $input['email'])->first();

            if ($authUser) {
                if ($authUser->status == "unactivated") {
                    DB::table("Clients")->where("id", "=", $authUser->id)->update($data);
                } else if ($authUser->status == "activated") {
                    return view("/register")->with("fail", "Username already exists!");
                } else {
                    DB::table("Clients")->insert($data);
                }
                return view("/register")->with("success", "true");
            }
            return view("/register")->with("fail", "true");
        }
        return view("/register")->with("fail", "false");
    }

    public function log_register() {
        $input = Input::all();
        if($input['first_name'] != "" && $input['last_name'] != "" && $input['username'] != "" && $input['email'] != "") {
            $formatted_phone = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $input['phone_number']);//format phone number (xxx)xxx-xxxx
            $data = array(
                "first_name" => $input['first_name'],
                "last_name" => $input['last_name'],
                "phone_number" => $formatted_phone,
                "phone_type" => $input['phone_type'],
                "username" => $input['username'],
                "password" => Hash::make($input['password']),
                "email" => $input['email'],
                "status" => "activated",
                "activation_key" => md5(time() + $input['password'])
            );

            $authEmail = DB::table("Clients")->where('email', $input['email'])->first();
            $authUsername = DB::table("Clients")->where('username', $input['username'])->first();

            if (!$authUsername) {
                if ($authEmail) {
                    if ($authEmail->status == "unactivated") {
                        DB::table("Clients")->where("id", "=", $authEmail->id)->update($data);
                    } else if ($authEmail->status == "activated") {
                        return redirect("/login")->with("login_error", "Username already exists!</strong> Please try again.");
                    } else {
                        DB::table("Clients")->insert($data);
                    }
                    return view("/register")->with("success", "true");
                } else {
                    DB::table("Clients")->insert($data);
                    return view("/register")->with("success", "true");
                }
            } else {
                return redirect("/login")->with("login_error", "Username already exists!</strong> Please try again.");
            }
        } else {
            return redirect("/login")->with("login_error", "Please fill out all data in the page!</strong> Please try again.");
        }
    }
    public function registerView() {
        return view("/register");
    }

    public function loginView()
    {
        return view("login");
    }

    public function login()
    {
        $input = Input::all();
        if (Auth::attempt("user",['email' => $input['log_username'], 'password' =>  $input['log_password']])) {
            return redirect("appointment");
        } else if (Auth::attempt("user",['username' => $input['log_username'], 'password' =>  $input['log_password']])) {
            return redirect("appointment");
        }
        else {
            return redirect("login")->with("login_error","Username/Password is invalid!</strong> Please try again.");
        }
    }

    public function logout() {
        Auth::logout("user");
        return redirect("/");
    }

    public function editView() {
        $id = Auth::user("user")->id;
        $data = DB::table("Clients")->where("id","=",$id)->first();
        return view("edit")->with("user", $data);
    }
    public function editViewPost() {
        $id = Auth::user("user")->id;
        $username = Auth::user("user")->username;
        $input = Input::all();
        $validator = true;


        if (strlen($input['password'] < 6)) {

        } else {

        }
        if (($input['password'] != $input['password_again'])) {

        } else {
            $newPassword = Hash::make($input['password']);
            $data = array (
                "first_name"    => $input['first_name'],
                "last_name"     => $input['last_name'],
                "phone_number"  => $input['phone_number'],
                "phone_type"    => $input['phone_type'],
                "password"      => $newPassword
            );
            DB::table("Clients")->where('id',$id)->update($data);
            return redirect()->back()->with('alert-success', 'Successful! Your information has been updated...');
        }
        /*if($validator) {
            if ( !empty($input['oldPassword']) && isset($input['oldPassword']) && isset($input['newPassword']) && isset($input['newPasswordRepeat'])) {
                $oldPass = $input['oldPassword'];
                $newPass = $input['newPassword'];
                $newPassRepeat = $input['newPasswordRepeat'];
                $auth = Auth::attempt("user",['username' => $username, 'password' =>  $oldPass]);
                if ($auth && $newPass == $newPassRepeat) {
                    $data['password'] = Hash::make($input['newPassword']);
                    DB::table("Clients")->where('id',$id)->update($data);
                } else {
                    return redirect()->back()->withInput()->with('alert-danger', 'Oops! Something has gone wrong...');
                }
            } else {
                DB::table("Clients")->where('id',$id)->update($data);
            }
            return redirect()->back()->with('alert-success', 'Successful! Your information has been updated...');
        }*/
    }
}