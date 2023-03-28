@extends("master")
@section("register_nav","nav-active")
@section("body")
    @if (isset($success))
        <div role="main" class="main shop">
            <div class="container">
                <br />
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <section class="panel panel-primary">
                            <div class="panel-heading">Successful Registration!</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span>Your registration has been successful! We will be sending you an activation link via email (This feature is not yet enabled)</span>
                                        <br />
                                        <br />
                                        <span>Redirecting you to the homepage in 5 seconds...</span>
                                        <script>
                                            setTimeout(function () {
                                                window.location.href = "appointment"; //will redirect to your blog page (an ex: blog.html)
                                            }, 5000); //will call the function after 2 secs.
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    @else
        {!! Form::open(array("role" => "form",'method' => 'POST', 'url' => 'register', "data-toggle" => "validator", 'id' => 'regform')) !!}
        <div class="row" style="padding-top:30px">
            <div class="col-md-6">
                <section class="panel panel-success">
                    <div class="panel-heading" style="color: white;">Registration</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">First Name:</span>
                                    <input name="first_name" type="text" class="form-control" placeholder="John" required>
                                </div>
                                <div>
                                    <label> </label>
                                    <label id="first_name-error" class="error" for="first_name" style=""></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Last Name:</span>
                                    <input name="last_name" type="text" class="form-control" placeholder="Martins" required>
                                </div>
                                <div>
                                    <label> </label>
                                    <label id="last_name-error" class="error" for="last_name" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Email:</span>
                                    <input name="email" type="email" class="form-control" placeholder="test@gmail.com" required>
                                </div>
                                <div>
                                    <label> </label>
                                    <label id="email-error" class="error" for="email" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Username:</span>
                                    <input name="username" type="text" class="form-control" placeholder="username" required>
                                </div>
                                <div>
                                    <label> </label>
                                    <label id="username-error" class="error" for="username" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Phone:</span>
                                    <input name="phone_number" type="text" class="form-control" placeholder="(xxx)xxx-xxxx" required>
                                </div>
                                <div>
                                    <label> </label>
                                    <label id="phone_number-error" class="error" for="phone_number"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Type:</span>
                                    <select name="phone_type" class="form-control" required>
                                        <option selected>Work</option>
                                        <option>Home</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Password:</span>
                                    <input name="password" id="password" type="password" class="form-control" placeholder="******" required>
                                </div>
                                <div>
                                    <label> </label>
                                    <label id="password-error" class="error" for="password"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Password Again:</span>
                                    <input name="password_again" id="password_again" type="password" class="form-control" placeholder="******" required>
                                </div>
                                <div>
                                    <label> </label>
                                    <label id="password_again-error" class="error" for="password_again"></label>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-success">Complete Registration</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        {!! Form::close() !!}
    @endif
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
                    regex: "^(\([0-9]{3}\)|[0-9]{3}-)[0-9]{3}-[0-9]{4}$"
                }
            }
        });
    </script>
@endsection