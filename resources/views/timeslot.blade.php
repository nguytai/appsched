<?php
$ids = array();
$counter=1;
$service_time=1;
$num = Input::get('num');
$date = Input::get('date');
$services = array();
$tmp = array();
for($i = 0;$i < Input::get('num'); $i++) {
    //$ids[$i] = Input::get('service_' . ($i + 1));
    $services[] = DB::table("Services")->where("id",Input::get('service_' . ($i + 1)))->first();
}
//$services = DB::table("Services")->whereIn("id",$ids)->get();
?>
@extends("master")

@section("body")

    {!! Form::open(array('method' => 'get', 'id' => 'form1',"data-toggle" => "validator", 'onsubmit' => 'ValidateForm()')) !!}
    <div role="main" class="main shop">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="featured-boxes">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="featured-box featured-box-primary align-left mt-xlg">
                                    <div class="box-content">
                                        <h4 class="heading-primary text-uppercase mb-md">Select a Specialist and Timeslot</h4>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label>Specialist:</label>
                                                    <select id="specialist_id" name="specialist_id" class="form-control">
                                                        <option value="0" selected></option>
                                                        @foreach($available as $specialist)
                                                            <option value="{{ $specialist->id }}">{{ $specialist->first_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    @foreach($services as $service)
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="basic-addon1">{{ $service->name }} [{{ $service->duration }} - ${{ $service->cost }}]</span>
                                                            <select id="service_{{ $service_time }}_time" name="service_{{ $service_time++ }}_time" class="form-control" required>
                                                                @if(isset($timeslots))
                                                                    <option value="0" selected></option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <br />
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="featured-box featured-box-primary align-left mt-xlg">
                                    <div class="box-content">
                                        <h4 class="heading-primary text-uppercase mb-md">Time Slot - {{ \Carbon\Carbon::createFromFormat("Y-m-d", Input::get("date"))->format("M d, Y") }}</h4>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <table class="table table-condensed mb-none">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Time Start</th>
                                                            <th>Time End</th>
                                                            <th>Specialist Available</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(isset($timeslots))
                                                            @foreach($timeslots as $timeslot)
                                                                @if ($timeslot['stylist'] != "-")
                                                                    <tr>
                                                                        <td>{{ $counter++ }}</td>
                                                                        <td>{{ date("g:i a", strtotime($timeslot['time_start'])) }}</td>
                                                                        <td>{{ date("g:i a", strtotime($timeslot['time_end'])) }}</td>
                                                                        <td>{{ $timeslot['stylist'] }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="pull-right">
                                <div class="col-md-12 pull-right"></div>
                                <button type="submit" class="btn btn-primary">Continue Booking</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input id="date" name="date" type="hidden" value="{{ $date }}">
    <input id="num" name="num" type="hidden" value="{{ $num }}">
    <input name="step" type="hidden" value="3">
    @for ($i = 0;$i < $num; $i++)
        <input name="service_{{ $i + 1 }}" type="hidden" value="{{ Input::get("service_" . ($i + 1)) }}">
    @endfor
    {!! Form::close() !!}
@endsection
@section(("custom_scripts"))
    <script>
        function ValidateForm(){
            var count1, count2;
            for(var i = 0;i<$('#num').val();i++){
                count1 = i+1;
                for(var j = i+1;j<$('#num').val();j++){
                    count2 = j+1;
                    if(document.getElementById("service_"+count1.valueOf()+"_time").value == document.getElementById("service_"+count2.valueOf()+"_time").value){
                        event.preventDefault();
                        alert("Cannot have duplicate times slots");
                        return false;
                    }
                }
            }
        }
        $('#specialist_id').on('change', function() {
            $(this).find('[value="0"]').remove();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            console.log();
            $.ajax({
                url:'get-time',
                type: 'post',
                data: {
                    'id' : $("#specialist_id option:selected").val(),
                    'date' : $("#date").val()
                },
                success: function( data, textStatus, jQxhr) {
                    for ($i = 1; $i <= $("#num").val(); $i++) {
                        $("#service_" + $i + "_time").find('option').remove();
                        $("#service_" + $i + "_time").append(data);
                    }
                    console.log(data);
                },
                error: function( jQxhr, textStatus, errorThrown) {
                    console.log("error");
                }
            })
        });
    </script>
@endsection