<?php
    $ids = array();
    $counter=1;
    $num = Input::get('num');
    $date = Input::get('date');
    $specialist_id = Input::get('specialist_id');

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
{!! Form::open(array('method' => 'POST', 'url' => 'complete_booking', "data-toggle" => "validator", 'id' => 'inputform')) !!}
<div role="main" class="main shop">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="featured-boxes">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="featured-box featured-box-primary align-left mt-xlg">
                                <div class="box-content">
                                    <h4 class="heading-primary text-uppercase mb-md">Customer Information</h4>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label>First Name:</label>
                                                <input name="first_name" type="text" class="form-control" placeholder="John" value="@if (!empty($client)){{ $client->first_name }}@endif" data-error="Please fill out your first name" @if (!empty($client)) disabled @else required="true"  @endif>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Last Name:</label>
                                                <input name="last_name" type="text" class="form-control" value="@if (!empty($client)){{ $client->last_name }}@endif" placeholder="Martins" @if (!empty($client)) disabled @else required="true"  @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>Email:</label>
                                                <input name="email" type="email" class="form-control" value="@if (!empty($client)){{ $client->email }}@endif" placeholder="test@gmail.com" @if (!empty($client)) disabled @else required="true"  @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-8">
                                                <label>Phone:</label>
                                                <input name="phone_number" id="phone_number" type="text" class="form-control"  value="@if (!empty($client)){{ $client->phone_number }}@endif" placeholder="(xxx)xxx-xxxx" @if (!empty($client)) disabled @else required="true"  @endif>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Type:</label>
                                                <select name="phone_type" class="form-control" @if (!empty($client)) disabled @else required="true"  @endif>
                                                    <option value="1"@if (!empty($client)) @if ($client->phone_type == 1) selected @endif @endif>Work</option>
                                                    <option value="2"@if (!empty($client)) @if ($client->phone_type == 2) selected @endif @endif>Home</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <textarea name="special" class="form-control" rows="5" placeholder="Special Requests (Optional)" ></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    <button type="submit" class="btn btn-primary">Complete Booking</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="featured-box featured-box-primary align-left mt-xlg">
                                <div class="box-content">
                                    <h4 class="heading-primary text-uppercase mb-md">Estimated Costs</h4>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <span>Appointment Date: {{ \Carbon\Carbon::createFromFormat("Y-m-d", Input::get("date"))->format("M d, Y") }} @ {{ date("g:i a", strtotime($aptTime->time_start)) }}</span>
                                                <hr />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                @foreach($services as $service)
                                                    <span>{{ $service->name }} ({{ $service->duration }})</span>
                                                    <br />
                                                @endforeach
                                                <span>Sales Tax (13%)</span>
                                                    <br />
                                                    <br />
                                                    <span>Total:</span>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                @foreach($services as $service)
                                                    <?php $total_cost += $service->cost; ?>
                                                    <span>$ {{ number_format($service->cost,2) }}</span>
                                                        <br />
                                                    @endforeach
                                                <span>$ {{ number_format($total_cost * 0.13,2) }}</span>
                                                    <br />
                                                    <br />
                                                    <span>$ {{ number_format((($total_cost * 0.13) + $total_cost),2) }}</span>
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
</div>
<input id="date" name="date" type="hidden" value="{{ $date }}">
<input id="num" name="num" type="hidden" value="{{ $num }}">
<input name="step" type="hidden" value="4">
<input name="specialist_id" type="hidden" value="{{ $specialist_id }}">
@for ($i = 0;$i < $num; $i++)
    <input name="service_{{ $i + 1 }}" type="hidden" value="{{ Input::get("service_" . ($i + 1)) }}">
@endfor
@for ($i = 0;$i < $num; $i++)
    <input name="service_{{ $i + 1 }}_time" type="hidden" value="{{ Input::get("service_" . ($i + 1) . "_time") }}">
    @endfor
{!! Form::close() !!}
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
    $( "#inputform" ).validate({
            rules: {
            first_name: {
                    required: true
                },
            last_name: {
                    required: true
                },
            email: {
                    email: true,
                            required: true
                },
            phone_number: {
                    required: true,
                            regex: /^(\([0-9]{3}\)|[0-9]{3}-)[0-9]{3}-[0-9]{4}$/
                }
        }
    });
</script>
@endsection