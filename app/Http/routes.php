<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', "PublicController@homeView");

Route::get('/', function() {
    return redirect("/appointment");
});

Route::get('/appointment', "PublicController@appointmentView");

Route::get('/get-availability', "PublicController@get_availability");

Route::post('/get-time', "PublicController@get_time");

Route::post('/complete_booking', "PublicController@complete_booking");

route::post('/log_register', "PublicController@log_register");

Route::post('/register', "PublicController@register");

Route::get('/register', "PublicController@registerView");

Route::get('/login', "PublicController@loginView");

Route::post('/login', "PublicController@login");

Route::get('/logout', "PublicController@logout");

Route::get('/edit', "PublicController@editView");

Route::post('/edit', "PublicController@editViewPost");

Route::get('/client_appointment_view', "PublicController@clientBookingView");

Route::post("/delete_appointment", "PublicController@deleteAppointment");

Route::group(array("prefix" => "admin"), function () {

    Route::get('/', function () {
        return redirect("admin/login");
    });


    Route::get('login', "AdminController@loginView");

    Route::post('login', "AdminController@login");

    Route::get('logout', "AdminController@logout");

    Route::get('dashboard', "AdminController@dashboardView");

    Route::get('schedule', "AdminController@scheduleView");

    Route::get("get-events","AdminController@get_events");

    Route::get("create-events","AdminController@create_events");

    Route::get("update-events","AdminController@update_events");

    Route::get("delete-calendar","AdminController@delete_calendar");

    Route::get("delete-date","AdminController@delete_day");

    Route::get("delete-events","AdminController@delete_events");

    Route::get("services","AdminController@servicesView");

    Route::get("appointments","AdminController@appointmentsView");

    Route::post("add_service", "AdminController@addService");

    Route::post("delete_service", "AdminController@deleteService");

    Route::post("update_services", "AdminController@updateServices");

    Route::get("stylists","AdminController@stylistsView");

    Route::post("add_stylist", "AdminController@addStylist");

    Route::post("update_stylists", "AdminController@updateStylists");

    Route::post("check_appointment", "AdminController@hasAppointmentStylist");

    Route::get("get-events-appointment","AdminController@get_events_appointment");

    Route::get("get-events-schedule","AdminController@get_events_schedule");
    
});
