@extends("admin.master")

@section("appointments_nav","nav-active")
@section("page_header", "Appointments list")

@section("body")
    <div class="col-md-7 col-xs-12">
        <section class="panel panel-primary">
            <header class="panel-heading">
                <h2 class="panel-title">My Appointment for the week</h2>
            </header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" style="margin-bottom:0px">
                            <tr>
                                <th>Service Name</th>
                                <th>Client Name</th>
                                <th>Special Requests</th>
                                <th>Date(yyyy/mm/dd)</th>
                                <th>Time</th>
                            </tr>
                            @foreach($bookings as $appointment)
                                <tr>
                                    <td>{{ $appointment->service_name }}</td>
                                    <td>{{ $appointment->first_name }}</td>
                                    <td>{{ $appointment->description }}</td>
                                    <td>{{ $appointment->calendar_date }}</td>
                                    <td>{{ date("g:i a", strtotime($appointment->time_start)) }} - {{  date("g:i a", strtotime($appointment->time_end)) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection