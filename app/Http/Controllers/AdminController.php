<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use View;
use DB;
use DateTime;
use Illuminate\Support\Facades\Input;
use Hash;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

class AdminController extends Controller
{
    // Checks if user is logged in
    private function isUserLoggedIn() {
        if (Auth::check("admin")) {
            $status = DB::table("Stylist")->where("id",Auth::user("admin")->id)->select("status","account_type")->first();
            if ($status->status != "activated") {
                $this->logout();
                return \Redirect::to('admin/')->send();
            }else {
                $user = Auth::user("admin");
                $data = [
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'account_type' => $user->account_type
                ];
                View::share('user', $data);
            }
        } else {
            return \Redirect::to('admin/')->send();
        }
    }

    public function loginView()
    {
        if (Auth::check("admin")) {
            return redirect("admin/dashboard");
        } else {
            return view("admin.login");
        }
    }

    public function login()
    {
        $input = Input::all();
        if(Auth::attempt("admin",['email' => $input['email'], 'password' => $input['password'], "status" => "activated"])) {
           return redirect("admin/dashboard");
        } else {
            $status = DB::table("Stylist")->where("email",$input['email'])->first();
            if (count($status) > 0) {
                if ($status->status != "activated") {
                    return redirect("admin/login")->with("error", "User is de-activated. Please contact the admin");
                } else {
                    return redirect("admin/login")->with("error", "Email/Password is invalid! Please try again.");
                }
            } else {
                return redirect("admin/login")->with("error", "Email/Password is invalid! Please try again.");
            }

        }
    }

    public function logout()
    {
        Auth::logout("admin");
        return redirect("admin/login")->with("success","You've been logged out successfully!");
    }

    public function dashboardView() {
        $this->isUserLoggedIn();
        return view("admin/dashboard");
    }

    public function scheduleView() {
        $this->isUserLoggedIn();
        $id = Auth::user("admin")->id;
        if (is_null(Input::get("month"))) {
            $month = sprintf("%02d",date("m"));
        } else {
            $month = sprintf("%02d",Input::get("month"));
        }
        if(DB::table("StylistDailySchedule")->where("stylist",$id)->where("calendar_date","LIKE","2016-" . $month . "-%")->count() > 0) {
            return view("admin/schedule");
        }
        if (empty($schedule)){
            return view("admin/schedule")->with("schedule","empty");
        }
    }

    public function appointmentsView() {
        $this->isUserLoggedIn();
        $data = DB::table("Appointments")
            ->join("StylistDailySchedule",function($join) {
                $join->on("Appointments.schedule_id", "=", "StylistDailySchedule.id");
            })
            ->join("Clients", function($join) {
                $join->on("Clients.id", "=", "Appointments.client_id");
            })
            ->select("Appointments.description","Appointments.id","Appointments.schedule_id","Appointments.service_name","StylistDailySchedule.time_start AS time_start",
                "StylistDailySchedule.time_end AS time_end", "StylistDailySchedule.calendar_date AS calendar_date", "Clients.first_name")
            ->where("Appointments.stylist_id","=",Auth::user("admin")->id)
            ->orderBy("StylistDailySchedule.calendar_date","asc")
            ->get();

        return view('admin/appointments')->with("bookings", $data);
    }

    public function get_events() {

        $id = Auth::id("admin");
        $input = Input::all();
        $month = $input['month'];
        $query =  DB::table("StylistDailySchedule")->where("stylist",$id)->where("calendar_date",">=","2016-" . $month ."-01")->get();
        $data = array();

        foreach($query as $entry) {
            $tmp = array(
                        "id"    => $entry->id,
                        "title"  => $entry->title,
                        "start" => $entry->calendar_date . 'T' . $entry->time_start,
                        "end"   => $entry->calendar_date . 'T' . $entry->time_end
               );
            $data[] = $tmp; // Appends $tmp to $data
        }

        return $data;
    }

    public function create_events() {
        $input = Input::all();
        $id = Auth::id("admin");
        $data = array();

        $total_days_in_month = cal_days_in_month(CAL_GREGORIAN,date('m', $input['start0'] / 1000),date('Y', $input['start0'] / 1000));

        for ($i = 0; $i < $input['numOfEvents']; $i++) {
            $epoch_start = $input['start' . $i] / 1000;
            $epoch_end = ($input['end' . $i] == "null" ? ($input['start' . $i] / 1000) + 7200 : ($input['end' . $i] / 1000));

            for($day = 1; $day <= $total_days_in_month; $day++) {
                for($q=$epoch_start; $q < $epoch_end; $q+=1800) {
                    $tmp = array(
                        "stylist"        => $id,
                        "title"          => $input['title' . $i],
                        "calendar_date"  => (date('Y-m-'. $day , $q)),
                        "time_start"     => (date('H:i:s', $q)),
                        "time_end"       => (date('H:i:s', $q + 1800)),
                        "status"         => ($input['title' . $i] == "Work" ? "available" : "lunch")
                    );
                    if(date("l",strtotime(date('Y-m-'. $day , $q))) != 'Sunday' && date("l",strtotime(date('Y-m-'. $day , $q))) != 'Saturday') {
                        $data[] = $tmp; // Appends $tmp to $data
                    }
                }
            }
        }
        DB::table('StylistDailySchedule')->insert($data);

        return "[{status:success}]";
    }

    public function update_events() {
        $input = Input::all();
        $id = Auth::id("admin");
        $data_new = array();

        for ($i = 0; $i < $input['numOfEvents']; $i++) {
            $epoch_start = $input['start' . $i] / 1000;
            $epoch_end = ($input['end' . $i] == "null" ? ($input['start' . $i] / 1000) + 7200 : ($input['end' . $i] / 1000));

            for($q=$epoch_start; $q < $epoch_end; $q+=1800) {
                $tmp = array(
                    "stylist"        => $id,
                    "title"          => $input['title' . $i],
                    "calendar_date"  => (date('Y-m-d', $q)),
                    "time_start"     => (date('H:i:s', $q)),
                    "time_end"       => (date('H:i:s', $q + 1800)),
                    "status"         => ($input['title' . $i] == "Work" ? "available" : "lunch")
                );
                if ($input['id' . $i] == 'undefined') {
                    $data_new[] = $tmp; // Appends $tmp to $data
                } else {
                    DB::table("StylistDailySchedule")->where("id",$input['id' . $i])->update($tmp);
                }
            }

        }
        if (count($data_new)) {
            DB::table('StylistDailySchedule')->insert($data_new);
        }

        return $data_new;
    }

    public function delete_calendar() {
        $id = Auth::id("admin");
        if (is_null(Input::get("month"))) {
            $month = sprintf("%02d",date("m"));
        } else {
            $month = sprintf("%02d",Input::get("month"));
        }
        DB::table("StylistDailySchedule")->where("calendar_date",'LIKE', '%2016-' . $month . '%')->where("stylist",$id)->delete();
        return "success";
    }

    public function delete_day() {
        $input = Input::all();
        $id = Auth::id("admin");
        DB::table("StylistDailySchedule")->where("calendar_date","=",$input['day'])->where("stylist",$id)->delete();
        //return Input::all();
    }

    public function delete_events(){
        $input = Input::all();
        for ($i = 0; $i < $input['numOfEvents']; $i++) {
             DB::table("StylistDailySchedule")->where("id", $input['id' . $i])->delete();
        }
    }

    public function servicesView() {
        $this->isUserLoggedIn();
        $id = Auth::id("admin");
        $services = DB::table("Services")->groupBy("name")->distinct()->get();//->where("stylist",$id)->get();
        return view("admin/services")->with("services",$services);
    }

    public function addService() {
        $input = Input::all();

        $exists=DB::table("Services")->where("name",$input['name'])->get();
        if ($exists == null) {
            $data = array(
                "stylist"       => Auth::id("admin"),
                "name"          => $input['name'],
                "duration"      => "30 mins",
                "description"   => $input['description'],
                "cost"          => number_format($input['price'],2)
            );

            DB::table("Services")->insert($data);
            return redirect("admin/services")->with("success","Service name <b>\"" . $input['name'] . "\"</b> added successfully!");
        }
        else
        {
            return redirect("admin/services")->with("error","Service already exists in the database! Service has not been changed/modified.");
        }
    }

    public function deleteService(){
        $input=Input::all();
        DB::table("Services")->where("id",$input['id'])->delete();
    }

    public function updateServices() {
        $input = Input::all();

        for($i=1; $i <= $input['total']; $i++){
            $tmp = array(
                "name"          => $input['service_name_'.$i],
                "duration"      => "30 mins",
                "description"   => $input['description_'.$i],
                "cost"          => number_format( $input['cost_'.$i],2)
            );
            //$data = $tmp; // Appends $tmp to $date
            $service_name = DB::table("Services")->select("name")->where("id",$input['id_'.$i])->first();

            DB::table("Services")->where("id",$input['id_'.$i])->update($tmp);
            DB::table("Services")->where("name",$service_name->name)->update($tmp);
            //DB::table("Services")->where("name",$input['service_name_'.$i])->update($tmp);
        }
        return redirect("admin/services");
    }

    public function updateStylists() {
        $input = Input::all();
        for($i=1; $i <= $input['total']; $i++){
            $tmp = array(
                "first_name"    => $input['first_name_'.$i],
                "last_name"     => $input['last_name_'.$i],
                "email"         => $input['email_'.$i],
                "phone"         => $input['phone_'.$i],
                "status"        => $input['status_'.$i]
            );
            DB::table("Stylist")->where("id",$input['id_'.$i])->update($tmp);
        }
        return redirect("admin/stylists")->with("success","Changes successfully made!");
    }

    public function stylistsView()
    {
        $this->isUserLoggedIn();
        if (Auth::user("admin")->account_type == "admin") {
            $data = DB::table("Stylist")->where("account_type","!=","admin")->get();
            return view('admin/stylists')->with("stylists", $data);
        } else {
            return redirect('admin/dashboard');
        }
    }

    public function addStylist() {
        $input = Input::all();

        if (isset($input['password']) && isset($input['confirm_password'])) {
            if ($input['password'] != $input['confirm_password']) {
                return redirect("admin/stylists")->with("error","Password and confirmation password are not the same!");
            }
        }

        $exists=DB::table("Stylist")->where("email",$input['email'])->get();

        if ($exists == null) {
            $newPassword = Hash::make($input['password']);
            $data = array(
                "first_name"    => $input['first_name'],
                "last_name"     => $input['last_name'],
                "email"         => $input['email'],
                "phone"         => $input['phone'],
                "status"         => 'activated',
                "account_type"  => "stylist",
                "password"      => $newPassword
            );
            DB::table("Stylist")->insert($data);
            return redirect("admin/stylists")->with("success","Stylist <b>\"" . $input['first_name'] . "\"</b> added successfully!");
        }
        else
        {
            return redirect("admin/stylists")->with("error","Stylist's email address already exists in the database!");
        }
    }

    public function hasAppointmentStylist() {
        $input = Input::all();
        $id = $input['id'];
        $c = DB::table("Appointments")->where("stylist_id", $id)->where("status", "activated")->count();
        return $c;
    }

    public function get_events_appointment() {
        $input = Input::all();
        $id = $input['id'];

        $query = DB::table("Appointments")
            ->join("StylistDailySchedule",function($join) {
                $join->on("Appointments.schedule_id", "=", "StylistDailySchedule.id");
            })
            ->join("Clients", function($join) {
                $join->on("Clients.id", "=", "Appointments.client_id");
            })
            ->select
            (
                "Appointments.id",
                "Appointments.schedule_id",
                "Appointments.description",
                "Appointments.service_name",
                "StylistDailySchedule.time_start AS time_start",
                "StylistDailySchedule.time_end AS time_end",
                "StylistDailySchedule.calendar_date AS calendar_date",
                "Clients.first_name",
                "Clients.last_name"
            )
            ->where("Appointments.stylist_id","=",$id)
            ->orderBy("StylistDailySchedule.calendar_date","asc")
            ->get();

        $data = array();

        foreach($query as $entry) {
            $tmp = array(
                "id"        => $entry->id,
                "title"     => $entry->service_name,
                "client"    => $entry->first_name . ' ' . $entry->last_name,
                "description"  => $entry->description,
                "start"     => $entry->calendar_date . 'T' . $entry->time_start,
                "end"       => $entry->calendar_date . 'T' . $entry->time_end
            );
            $data[] = $tmp; // Appends $tmp to $data
        }

        return $data;
    }

    public function get_events_schedule() {
        $input = Input::all();
        $id = $input['id'];

        $query =  DB::table("StylistDailySchedule")->where("stylist",$id)->get();
        $data = array();

        foreach($query as $entry) {
            $tmp = array(
                "id"    => $entry->id,
                "title"  => $entry->title,
                "start" => $entry->calendar_date . 'T' . $entry->time_start,
                "end"   => $entry->calendar_date . 'T' . $entry->time_end
            );
            $data[] = $tmp; // Appends $tmp to $data
        }

        return $data;
    }
}