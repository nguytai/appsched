
@extends("admin.master")

@section("appointments_nav","nav-active")
@section("page_header", "Appointments list")

@section("custom_css")
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.print.css" media="print" />
    <link rel="stylesheet" href="/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
@endsection

@section("custom_css")
    <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.print.css" media="print" />
    <link rel="stylesheet" href="/assets/vendor/select2/css/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/select2-bootstrap-theme/select2-bootstrap.css" />
    <link rel="stylesheet" href="/assets/vendor/pnotify/pnotify.custom.css" />
@endsection
@section("body")
    <div class="row">
        {!! Form::open(array("method" => "POST", "url" => "admin/update_stylists")) !!}
        @if(session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Oh snap!</strong> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Well done!</strong> {!! session('success') !!}
            </div>
            @endif

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <p></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                            <button id="no_button" type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="viewModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">View Details</h4>
                        </div>
                        <div class="modal-body">

                            <div id='calendar'></div>

                            <div id="inner-content">
                                <p>View the stylist schedule or appointment.</p>

                                <button id="weekly_button" type="button" class="btn btn-default">View Appointments</button>
                                <button id="schedule_button" type="button" class="btn btn-default">View Schedule</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button style="display:none" id='back' type='button' class='btn btn-default'>Back</button>
                            <button id="close" type="button" class="btn btn-default">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <div class="col-md-7 col-xs-12">
                <section class="panel panel-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title">Stylist List</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-condensed mb-none">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Password</th>
                                        <th>Schedule/App</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $counter=0;?>
                                    @foreach($stylists as $stylist)
                                        <tr>
                                            <td>{{ ++$counter }}</td>
                                            <input type="hidden" name="id_{{$counter}}" value="{{ $stylist->id }}">
                                            <input type="hidden" name="status_{{$counter}}" value="{{ $stylist->status }}">
                                            <td>
                                                <input name="first_name_{{$counter}}" type="text" class="form-control" value="{{ $stylist->first_name }}"/>
                                            </td>
                                            <td>
                                                <input name="last_name_{{$counter}}" type="text" class="form-control" value="{{ $stylist->last_name }}"/>
                                            </td>
                                            <td>
                                                <input name="email_{{$counter}}" type="text" class="form-control" value="{{ $stylist->email }}"/>
                                            </td>
                                            <td>
                                                <input name="phone_{{$counter}}" type="text" class="form-control" value="{{ $stylist->phone }}"/>
                                            </td>
                                            <td>
                                                <input type="password" name="password_{{$counter}}" type="text" class="form-control"/>
                                            </td>
                                            <td>
                                                <a style="width:100%;" href="#viewModal" class="btn btn-primary" data-toggle="modal" data-stylist="{{$stylist->first_name ." ". $stylist->last_name}}" data-stylist-id="{{$stylist->id}}" data-backdrop="static" data-keyboard="false">View</a>
                                            </td>
                                            @if($stylist->status == 'deactivated')
                                                <td>
                                                    <button id="status_button_{{$counter}}" type="button" class="btn btn-danger" style="width:100%" onclick="change_status({{$counter}}, {{$stylist->id}})">
                                                        Deactivated
                                                    </button>
                                                </td>
                                            @endif
                                            @if($stylist->status == 'activated')
                                                <td>
                                                    <button id="status_button_{{$counter}}" type="button" class="btn btn-success" style="width:100%" onclick="change_status({{$counter}}, {{$stylist->id}})">
                                                        Activated
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <input type="hidden" name="total" value="{{ $counter }}">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-success">Save Modifications</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            {!! Form::close() !!}
            {!! Form::open(array("method" => "POST", "url" => "admin/add_stylist")) !!}
            <div class="col-md-5 col-xs-12">
                <section class="panel panel-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title">Add New Stylist</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">First Name</span>
                                            <input name="first_name" type="text" class="form-control" placeholder="E.g. Barb" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Last Name</span>
                                            <input name="last_name" type="text" class="form-control" placeholder="E.g. Czegel" required>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="input-group">
                                    <span class="input-group-addon">Email</span>
                                    <input id="email" name="email" type="text" class="form-control" placeholder="E.g. example@email.com" required>
                                </div>
                                <br/>
                                <div class="input-group">
                                    <span class="input-group-addon">Phone</span>
                                    <input name="phone" type="text" class="form-control" placeholder="E.g. 555-555-5555" required>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Password</span>
                                            <input name="password" type="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Confirm</span>
                                            <input name="confirm_password" type="password" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary">Add New Stylist</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            {!! Form::close() !!}
    </div>
@endsection

@section("custom_scripts")
    <script src="/assets/vendor/moment/moment.min.js"></script>
    <script src="/assets/vendor/fullcalendar/fullcalendar.js"></script>
    <script src="/assets/vendor/select2/js/select2.js"></script>
    <script src="/assets/vendor/pnotify/pnotify.custom.js"></script>
    <script src="/assets/javascripts/ui-elements/examples.modals.js"></script>
    <script>
        var runCount = 0;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var modal = $('#viewModal');
        var content =  $('#viewModal .modal-body #inner-content');
        var back = $('#back');
        var close = $('#close');
        var cal = $('#calendar');

        close.on('click', function() {
            cal.fullCalendar('removeEvents');
            modal.modal('toggle');
            cal.hide();
        });

        back.on('click', function() {
            cal.fullCalendar('removeEvents');
            content.show();
            back.hide();
            cal.hide();
        });

        modal.on('show.bs.modal', function(e) {
            var stylistId = $(e.relatedTarget).data('stylist-id');
            var stylist = $(e.relatedTarget).data('stylist');
            content.show();

            console.log('stylist:' + stylist);

            $(e.currentTarget).find(".modal-title").text("View Details: " + stylist);


            $("#weekly_button").off('click').click(function(){
                initCalendar(stylistId);
                cal.show();
                back.show();
                content.hide();

                console.log("Weekly button pressed");
                    $.ajax({
                        url: "get-events-appointment",
                        data: {
                            'id': stylistId
                        },
                        success: function (data) {
                            console.log("get-events-appointment sucess");
                            cal.fullCalendar('removeEvents');
                            cal.fullCalendar('addEventSource', data);
                            cal.fullCalendar('rerenderEvents');
                        }
                    });

            });

            $("#schedule_button").off('click').click(function(){
                initCalendar(stylistId);
                cal.show();
                back.show();
                content.hide();

                console.log("Weekly button pressed");

                    $.ajax({
                        url: "get-events-schedule",
                        data: {
                            'id': stylistId
                        },
                        success: function (data) {
                            console.log("get-events-appointment sucess");
                            cal.fullCalendar('removeEvents');
                            cal.fullCalendar('addEventSource', data);
                            cal.fullCalendar('rerenderEvents');
                        }
                    });
            });
        });

        function initCalendar($id){
            if (runCount == 0) {

                /* initialize the calendar
                 -----------------------------------------------------------------*/
                console.log("initCalendar -- Stylist id: " + $id);
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev, next',
                        center: 'title',
                        right: 'agendaDay, agendaWeek, month',
                    },
                    editable: false,
                    eventLimit: true, // allow "more" link when too many events
                    allDaySlot: false,
                    eventOverlap: false,
                    defaultView: 'agendaWeek',

                    eventRender: function (event, element) {
                        if(event.client) {
                            element.find('.fc-title').append(" (" + event.client + ")");
                        }
                    },
                    eventClick: function(event, jsEvent, view) {
                        if(event.client) {
                            alert
                            (
                                'Service: ' + event.title + '\n' +
                                'Client: ' + event.client + '\n' +
                                'Start Time: ' + moment(event.start).format('MMM Do h:mm A') + '\n' +
                                'End Time: ' + moment(event.end).format('MMM Do h:mm A') + '\n' +
                                'Description: ' + event.description
                            );
                        } else {
                            alert
                            (
                                'Title: ' + event.title + '\n' +
                                'Start Time: ' + moment(event.start).format('MMM Do h:mm A') + '\n' +
                                'End Time: ' + moment(event.end).format('MMM Do h:mm A')
                            );
                        }
                    }
                });
                runCount++;
            }
        }

        function change_status($row, $id) {
            var row = $row;

            var button = $("#status_button_" + row);
            var status = $("[name=" + "status_"+ row + "]");

            if(status.val() == "deactivated"){
                button.removeClass("btn-danger").addClass("btn-success").text("Activated");
                status.val("activated");
            } else {
                button.removeClass("btn-success").addClass("btn-danger").text("Deactivated");
                status.val("deactivated");

                $.ajax({
                    url : "check_appointment",
                    type: "POST",
                    data: {
                        'id' : $id
                    },
                    success: function(data)
                    {
                        if(data > 0) {
                            $("#myModal .modal-title").html("Attention: Deactivated stylist has active appointments");
                            $("#myModal .modal-body p").html("Deactivated Stylist Has Active Appointments. No further appointments will be allowed for this stylist. <b>Are you sure you want to continue?</b>");
                        } else {
                            $("#myModal .modal-title").html("Successfully deactivated stylist.");
                            $("#myModal .modal-body p").html("No further appointments will be allowed for this stylist. <b>Are you sure you want to continue?</b>");
                        }
                        $("#myModal").modal();
                        $("#myModal #no_button").on("click", function(){
                            button.removeClass("btn-danger").addClass("btn-success").text("Activated");;
                            status.val("activated");
                        });
                    },
                    error: function ()
                    {
                        console.log("error");
                    }
                });
            }
        }
    </script>
@endsection
