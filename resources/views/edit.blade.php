<?php
$options = ['Work', 'Home'];
?>
@extends("master")

@section("body")
    <div role="main" class="main shop">
        {!! Form::open(['method' => 'POST', 'URL' => 'editViewPost']) !!}
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <hr class="tall">
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a>Edit Account</a>
                                </h4>
                            </div>
                            <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label>First Name</label>
                                                <input name="first_name" type="text" value="{{ $user->first_name }}" class="form-control input-lg">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Last Name</label>
                                                <input name="last_name" type="text" value="{{ $user->last_name }}" class="form-control input-lg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label>Username</label>
                                                <input name="username" type="text" value="{{ $user->username }}" class="form-control input-lg" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Email</label>
                                                <input name="email" type="text" value="{{ $user->email }}" class="form-control input-lg" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-8">
                                                <label>Phone Number</label>
                                                <input name="phone_number" value="{{ $user->phone_number }}" type="text" placeholder="(xxx)xxx-xxxx" class="form-control input-lg">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Type</label>
                                                <select name="phone_type" class="form-control input-lg" required>
                                                    <option value="1" @if ($user->phone_type == 1) selected @endif >Work</option>
                                                    <option value="2" @if ($user->phone_type == 2) selected @endif>Home</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label>New Password</label>
                                                <input name="password" type="password" value="" class="form-control input-lg">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Re-enter New Password</label>
                                                <input name="password_again" type="password" value="" class="form-control input-lg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#saveModal">
                                                    Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title" id="largeModalLabel">Attention!</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to save these changes? This process is irreversible!</p>
                                                <p>If you're unsure, press cancel and re-verify your changes.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                <button class="btn btn-primary modal-confirm-update">Confirm</button>
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
        {!! Form::close() !!}
    </div>
@endsection
@section("custom_scripts")

@endsection