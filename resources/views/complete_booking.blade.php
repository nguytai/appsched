<?php
$ids = array();
$counter=1;
$num = Input::get('num');
$date = Input::get('date');

$total_cost=0.00;

for($i = 0;$i < Input::get('num'); $i++) {
    $ids[$i] = Input::get('service_' . ($i + 1));
}

for($i = 0;$i < Input::get('num'); $i++) {
    $time[$i] = Input::get('service_' . ($i + 1) . '_time');
}

$services = DB::table("Services")->whereIn("id",$ids)->get();
$service_times = DB::table("StylistDailySchedule")->select("time_start")->whereIn("id",$time)->get();

?>
@extends("master")

@section("body")
    <div role="main" class="main shop">
        <div class="container">
            @if(session("login_error"))
                <div class="alert alert-danger" role="alert">
                    <strong><i class="fa fa-warning"></i>{!! session("login_error")  !!}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="featured-boxes">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="featured-box featured-box-primary align-left mt-xlg">
                                    <div class="box-content">
                                        <h4 class="heading-primary text-uppercase mb-md">Booking Successful!</h4>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span>Your booking has been successful! We will be sending you a email confirmation of your booking (This feature is not yet enabled)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!isset($existingUser))
                <div class="row">
                    <div class="col-md-12">
                        <div class="featured-boxes">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="featured-box featured-box-primary align-left mt-xlg">
                                        <div class="box-content">
                                            <h4 class="heading-primary text-uppercase mb-md">Registration Information</h4>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <span>It looks like you're not registered :(</span>
                                                            <br />
                                                            <span>Would you like to register and keep track of all your future bookings?</span>
                                                            <span>We've done the heavy lifting :) All we need is a username and password.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {!! Form::open(array('method' => 'POST', 'url' => 'register', 'id' => 'regform')) !!}
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label>First Name:</label>
                                                        <input name="first_name" type="text" value="{{ $old_data['first_name'] }}" placeholder="" class="form-control input-lg" required="true">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Last Name:</label>
                                                        <input name="last_name" type="text"value="{{ $old_data['last_name'] }}" placeholder="" class="form-control input-lg" required="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label>Username</label>
                                                        <input name="username" type="text" value="" class="form-control input-lg" required="true">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>E-mail Address</label>
                                                        <input type="text" value="{{ $old_data['email'] }}" class="form-control input-lg" disabled>
                                                        <input name="email" type="hidden" type="text" value="{{ $old_data['email'] }}" class="form-control input-lg">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-8">
                                                        <label>Phone Number</label>
                                                        <input name="phone_number" id="phone_number" type="text" value="{{ $old_data['phone_number'] }}" placeholder="(xxx)xxx-xxxx" class="form-control input-lg" required="true">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Type</label>
                                                        <select name="phone_type" class="form-control input-lg" required="true">
                                                            <option value="Work" selected>Work</option>
                                                            <option value="Home">Home</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label>Password</label>
                                                        <input name="password" id="password" type="password" value="" class="form-control input-lg" required="true">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Re-enter Password</label>
                                                        <input name="password_again" id="password_again" type="password" value="" class="form-control input-lg" required="true">                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="submit" value="Register" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section("custom_scripts")
<script>
    $.validator.addMethod(
            "regex",
            function(value, element, regexp){
                    var val = new RegExp(regexp);
                    return this.optional(element) || val.test(value);
                },
            "Use format (xxx)xxx-xxxx OR xxx-xxx-xxxx"
    )
    $( "#regform" ).validate({
            rules: {
            first_name: {
                    required: true
                },
            last_name: {
                    required: true
                },
            username: {
                    required: true
                },
            email: {
                    email: true,
                            required: true
                },
            password: {
                    required:true,
                            rangelength:[6,15]
                },
            password_again: {
                    equalTo: "#password"
                },
            phone_number: {
                    required: true,
                            regex: /^(\([0-9]{3}\)|[0-9]{3}-)[0-9]{3}-[0-9]{4}$/
                }
        }
    });
</script>
@endsection