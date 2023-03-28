@extends("master")

@section("bookings_nav","nav-active")
@section("page_header", "Booking List")
@section("body")
    {!! Form::open(array('method' => 'get', 'id' => 'form1',"data-toggle" => "validator")) !!}
    <div role="main" class="main shop">
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
                                    <a>My Appointments</a>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Service name</th>
                                                <th>Specialist name</th>
                                                <th>Date(yyyy/mm/dd)</th>
                                                <th>Time</th>
                                                <th></th>
                                            </tr>
                                            @foreach($bookings as $appointment)
                                                <tr>
                                                    <td>{{ $appointment->service_name }}</td>
                                                    <td>{{ $appointment->first_name}}</td>
                                                    <td>{{ $appointment->calendar_date }}</td>
                                                    <td>{{ date("g:i a", strtotime($appointment->time_start)) }} - {{  date("g:i a", strtotime($appointment->time_end)) }}</td>
                                                    <td><button type="button" class="btn btn-danger" onclick="delete_appointment({{ $appointment->id }},{{ $appointment->schedule_id }})"><i class="fa fa-trash"></i> Delete</button></td>
                                                </tr>
                                            @endforeach
                                        </table>
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
@endsection
@section("custom_scripts")
    <script src="/assets/vendor/moment/moment.min.js"></script>
    <script src="/assets/vendor/fullcalendar/fullcalendar.js"></script>
    <script>
        var total_services=1

        function delete_appointment($id, $scheduleID) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url:'delete_appointment',
                method:'POST',
                data: {
                    id : $id,
                    schedule_id : $scheduleID
                },
                success: function(data) {
                    if(data[1]) {
                        console.log(data[0])
                        location.reload();
                    }
                    else
                        alert("Cannot delete an appointment that has passed or is due in less than 12 hours!");
                },
                error: function (data) {

                }
            });
        }
    </script>
@endsection