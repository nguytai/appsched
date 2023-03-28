@extends("master")
@section("login_nav","active")
@section("body")
    <div role="main" class="main">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <hr class="tall">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="featured-boxes">
                    @if(session("login_error"))
                        <div class="alert alert-danger" role="alert">
                            <strong><i class="fa fa-warning"></i>{!! session("login_error")  !!}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="featured-box featured-box-primary align-left mt-xlg">
                                <div class="box-content">
                                    <h4 class="heading-primary text-uppercase mb-md">I'm a Returning Customer</h4>
                                    {!! Form::open(array("method" => "POST", 'id' => 'regform1')) !!}
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label>Username or E-mail Address</label>
                                                    <input name="log_username" placeholder="Email" tabindex="1" type="text" value="" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <a class="pull-right" href="#">(Forgot Password?)</a>
                                                    <label>Password</label>
                                                    <input name="log_password" id="log_password" tabindex="2" placeholder="password" type="password" value="" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span class="remember-box checkbox">
                                                    <label for="rememberme">
                                                        <input type="checkbox" id="rememberme" name="rememberme">Remember Me
                                                    </label>
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="submit" value="Login" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="featured-box featured-box-primary align-left mt-xlg">
                                <div class="box-content">
                                    <h4 class="heading-primary text-uppercase mb-md">Register An Account</h4>
                                    {!! Form::open(array('id' => 'regform2', 'method' => 'POST', 'url' => 'log_register')) !!}
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label>First Name:</label>
                                                    <input name="first_name" type="text" tabindex="3" value="" placeholder="" class="form-control input-lg">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Last Name:</label>
                                                    <input name="last_name" type="text" tabindex="4" value="" placeholder="" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label>Username</label>
                                                    <input name="username" type="text" tabindex="5" value="" class="form-control input-lg">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>E-mail Address</label>
                                                    <input name="email" type="text"  tabindex="6" value="" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-8">
                                                    <label>Phone Number</label>
                                                    <input name="phone_number" tabindex="7" id="phone_number" type="text" placeholder="(xxx)xxx-xxxx OR xxx-xxx-xxxx" class="form-control input-lg">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Type</label>
                                                    <select name="phone_type" class="form-control input-lg" required>
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
                                                    <input name="password" tabindex="8" id="password" type="password" value="" class="form-control input-lg">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Re-enter Password</label>
                                                    <input name="password_again" tabindex="9" id="password_again" type="password" value="" class="form-control input-lg">
                                                </div>
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
        $( "#regform2" ).validate({
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
        $( "#regform1" ).validate({
            rules: {
                log_username: {
                    email: false,
                    required: true
                },
                log_password: {
                    required: true,
                    rangelength: [6, 15]
                }
            }
        });
    </script>
@endsection