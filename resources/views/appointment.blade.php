@extends("master")
@section("appointment_nav","active")
@section("custom_css")
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.print.css" media="print" />
    <link rel="stylesheet" href="/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
@endsection
@section("body")
    <div role="main" class="main shop">
        <div class="container">
            {!! Form::open(array('method' => 'get', 'id' => 'form1',"data-toggle" => "validator")) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="featured-boxes">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="featured-box featured-box-primary align-left mt-xlg">
                                        <div class="box-content">
                                            <h4 class="heading-primary text-uppercase mb-md">Please Select a Specialist and Service(s)</h4>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-5">
                                                            <label>Select a Service</label>
                                                            <select id="service_1" onchange="removeEmpty(this)" name="service_1" class="form-control" required>
                                                                <option value="0" selected></option>
                                                                @if(isset($services))
                                                                    @foreach($services as $service)
                                                                        <option value="{{ $service->id }}">{{ $service->name }} [{{ $service->duration }} - ${{ $service->cost }}]</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Add/Remove Extra Services</label>
                                                            <br />
                                                            <button id="btn_add_service" onclick="addService()" type="button" class="btn btn-success">+</button>
                                                            <button id="btn_remove_service" style="display: none;" onclick="removeService()" type="button" class="btn btn-danger">-</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="extra_services">

                                                </div>
                                                <!-- Template Starts Here -->
                                                <div id="extra_service_template" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label>Select a Service</label>
                                                            <select id="" onchange="removeEmpty(this)" name="" class="form-control" required>
                                                                <option value="0" selected></option>
                                                                @if(isset($services))
                                                                    @foreach($services as $service)
                                                                        <option value="{{ $service->id }}">{{ $service->name }} [{{ $service->duration }} - ${{ $service->cost }}]</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3"></div>
                                                    </div>
                                                    <br/>
                                                </div>
                                                <!-- Template Ends Here -->
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <div id="datepicker-container" class="form-group date" data-provide="datepicker1">
                                                                <label>Select a month</label>
                                                                <input id="input_calendar_date" type="text" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label style="visibility: hidden;">a</label>
                                                            <br />
                                                            <button id="calendar-search" type="button" class="btn btn-primary">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div id="calendar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input id="date" name="date" type="hidden" value="">
                <input id="num" name="num" type="hidden" value="">
                <input name="step" type="hidden" value="2">
            {!! Form::close() !!}
        </div>
    </div>
    <div id="hiddendiv" style="visibility: hidden;"></div>
@endsection
@section("custom_scripts")
    <script src="/assets/vendor/moment/moment.min.js"></script>
    <script src="/assets/vendor/fullcalendar/fullcalendar.js"></script>
    <script src="/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script>
        var total_services=1

        function removeEmpty(id){
            if ($(id).find('[value="0"]')) {
                $(id).find('[value="0"]').remove();
            }
            $(id).attr("style","");
        }
        function addService() {
            if (!(total_services > 3)) {
                $("#extra_services").append('<div id="extra_service_' + (total_services + 1 ) + '">' + $("#extra_service_template").html() + '</div>');
                total_services++;
                if (total_services > 0) {
                    $("#btn_remove_service").show();
                    $("#stylist").prop('disabled',true);
                }
                $('#extra_service_' + total_services).find('select').attr('id', 'service_' + total_services);
                $('#extra_service_' + total_services).find('select').attr('name', 'service_' + total_services);
            }
        }

        function removeService() {
            $("#extra_service_" + total_services).remove();
            total_services--;
            if (total_services <= 1) {
                $("#btn_remove_service").hide();
                $("#stylist").prop('disabled',false);
            }
        }

        $(document).ready(function() {

            $("#calendar-search").click(function () {
                error=false;
                for (i=1; i<= total_services && !error; i++) {
                    if ($("#service_" + i + " option:selected").val() == 0) {
                        $("#service_" + i).attr("style","border-color:red")
                        error=true;
                    }
                }
                if (error) {
                    alert("Please select a service");
                } else {
                    if ($("#input_calendar_date").val() == "") {
                        $("#input_calendar_date").attr("style","border-color:red");
                        alert("Please choose a month");
                    } else {
                        $("#input_calendar_date").attr("style","");
                        initCalendar();
                    }
                }
            });

            $('#datepicker-container input').datepicker({
                format: {
                    /*
                     * Say our UI should display a week ahead,
                     * but textbox should store the actual date.
                     * This is useful if we need UI to select local dates,
                     * but store in UTC
                     */
                    toDisplay: function (date, format, language) {
                        var d = new Date(date);
                        d.setDate(d.getDate() + 1);
                        return moment(d.toISOString()).format("MMMM");
                    },
                    toValue: function (date, format, language) {
                        var d = new Date(date);
                        d.setDate(d.getDate());
                        console.log("a");
                        return moment(d.toISOString()).format("YYYY-MM-DD");
                    }
                },
                minViewMode: 1,
                startDate: new Date(),
                orientation: "bottom auto",
                autoclose: true
            });

            /* initialize the external events
             -----------------------------------------------------------------*/

            $('#external-events .fc-event').each(function () {

                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });


            /* initialize the calendar
             -----------------------------------------------------------------*/

            function initCalendar() {
                if ($('#calendar').children().length > 0 ) {
                    $('#calendar').fullCalendar('destroy');
                }

                $('#calendar').fullCalendar({
                    header: {
                        left: '', //'prev,next today'
                        center: 'title',
                        right: ''
                    },
                    defaultDate: moment('2016-' + $("#input_calendar_date").val() + '-01','YYYY-MMMM-DD'),//.format("MM YYYY"),
                    droppable:      false, // this allows things to be dropped onto the calendar
                    editable:       false,
                    eventLimit:     true, // allow "more" link when too many events
                    allDaySlot:     false,
                    eventOverlap:   false,
                    timeFormat: ' ',
                    disableResizing: true,
                    events: {
                        url: 'get-availability',
                        data: {month: $("#input_calendar_date").val()},
                        color: 'yellow',   // a non-ajax option
                        textColor: 'black', // a non-ajax option
                        error: function () {
                            $('#script-warning').show();
                        },
                        success: function(doc) {
                            console.log("Data received!");
                            console.log(doc);
                        }
                    },
                    /*dayRender: function (date, cell) {
                        if (date.isAfter(moment()) && date < moment().endOf('month')) {
                            $(cell).addClass('fc-today');
                        }
                    },*/
                    dayClick: function (date, jsEvent, view) {
                        console.log("Clicked on: " + date.format());
                        //if (moment().format("YYYY-MM-DD") === date.format("YYYY-MM-DD") || date.isAfter(moment())) {
                            console.log("Clicked on: " + date.format());
                            $("#num").val(total_services);
                            $("#date").val(date.format());
                            $("#form1").submit();
                            //$('#calendar').fullCalendar('changeView', "agendaDay");
                            //$('#calendar').fullCalendar('gotoDate', date);
                        //}
                    }
                });
            }
        });
    </script>
@endsection