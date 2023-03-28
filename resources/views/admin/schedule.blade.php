<?php
    $month=Input::get("month");
    if (is_null($month)) {
        $month=date("m");
    }
?>
@extends("admin.master")

@section("schedule_nav","nav-active")
@section("page_header", "Schedule")

@section("custom_css")
    <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="/assets/vendor/fullcalendar/fullcalendar.print.css" media="print" />
    <link rel="stylesheet" href="/assets/vendor/select2/css/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/select2-bootstrap-theme/select2-bootstrap.css" />
    <link rel="stylesheet" href="/assets/vendor/pnotify/pnotify.custom.css" />
    <style>

        #external-events {
            float: left;
            width: 150px;
            padding: 0 10px;
            background: #eee;
            text-align: left;
        }

        #external-events h4 {
            font-size: 16px;
            margin-top: 0;
            padding-top: 1em;
        }

        #external-events .fc-event {
            margin: 10px 0;
            cursor: pointer;
        }

        #external-events p {
            margin: 1.5em 0;
            font-size: 11px;
            color: #666;
        }

        #external-events p input {
            margin: 0;
            vertical-align: middle;
        }

    </style>
@endsection
@section("body")
        <section class="panel">
            <div class="panel-body">
                <div class="row">
                    @if (isset($schedule))
                        <div class="col-md-6">
                            <div id="calendar_empty"></div>
                        </div>
                        <div class="col-md-4">
                            <section class="panel panel-primary">
                                <header class="panel-heading">
                                    <h2 class="panel-title">Calendar Settings</h2>
                                </header>
                                <div class="panel-body" style="background-color: #eee">
                                    <p class="h4 text-weight-light">Draggable Events:</p>
                                    <hr />
                                    <div id='external-events'>
                                        <div class="fc-event label label-default" data-event-class="fc-event-default">Work</div>
                                        <div class="fc-event label label-primary" data-event-class="fc-event-primary">Lunch</div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <a class="mb-xs mt-xs mr-xs modal-with-zoom-anim btn btn-success" href="#modalSave">Save Calendar</a>
                                </div>
                            </section>
                        </div>
                    @else
                        <div class="col-md-6">
                            <div id="calendar"></div>
                        </div>
                        <div class="col-md-4">
                            <section class="panel panel-primary">
                                <header class="panel-heading">
                                    <h2 class="panel-title">Calendar Settings</h2>
                                </header>
                                <div class="panel-body" style="background-color: #eee">
                                    {!! Form::open(array("method" => "GET")) !!}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="h4 text-weight-light">Select month:</p>
                                            <select name="month">
                                                @for ($i = date("m"); $i <= date("m") + 3; $i++)
                                                    <option {{ (Input::get("month") == $i ? "selected" : "") }} value="{{ $i }}">{{ date("F", mktime(0, 0, 0, $i, 10)) }}</option>
                                                @endfor
                                            </select>
                                            <br />
                                            <br />
                                            <button type="submit" class="btn btn-primary">View Month</button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                    <div id="dragevents" style="display: none;">
                                        <hr />
                                        <p class="h4 text-weight-light">Draggable Events:</p>
                                        <div id='external-events'>
                                            <div class="fc-event label label-default" data-event-class="fc-event-default">Work</div>
                                            <div class="fc-event label label-primary" data-event-class="fc-event-primary">Lunch</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <a class="mb-xs mt-xs mr-xs modal-with-zoom-anim btn btn-success" href="#modalUpdate">Update Calendar</a>
                                    <a id="delete_calendar" class="mb-xs mt-xs mr-xs modal-with-zoom-anim btn btn-danger" href="#modalDeleteCalendar">Delete Calendar</a>
                                    <a id="delete_day" style="display: none;" class="mb-xs mt-xs mr-xs modal-with-zoom-anim btn btn-warning" href="#modalDeleteDay">Delete Day</a>
                                </div>
                            </section>
                        </div>
                    @endif
                </div>
                <div id="modalSave" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Are you sure?</h2>
                        </header>
                        <div class="panel-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fa fa-question-circle"></i>
                                </div>
                                <div class="modal-text">
                                    <p>Are you sure that you want to save your calendar schedule?</p>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary modal-confirm-save">Confirm</button>
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
                <div id="modalUpdate" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Are you sure?</h2>
                        </header>
                        <div class="panel-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fa fa-question-circle"></i>
                                </div>
                                <div class="modal-text">
                                    <p>Are you sure that you want to update your calendar schedule?</p>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary modal-confirm-update">Confirm</button>
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
                <div id="modalDeleteCalendar" class="zoom-anim-dialog modal-block modal-header-color modal-block-danger mfp-hide">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Are you sure?</h2>
                        </header>
                        <div class="panel-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fa fa-times-circle"></i>
                                </div>
                                <div class="modal-text">
                                    <p>Are you sure you want to delete your calendar schedule? This process cannot be reverted!</p>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-danger modal-confirm-delete">Confirm</button>
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
                <div id="modalDeleteDay" class="zoom-anim-dialog modal-block modal-header-color modal-block-danger mfp-hide">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Are you sure?</h2>
                        </header>
                        <div class="panel-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fa fa-times-circle"></i>
                                </div>
                                <div class="modal-text">
                                    <p>Are you sure you want to delete the current calendar day? This process cannot be reverted!</p>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-danger modal-delete-date">Confirm</button>
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
            </div>
        </section>
@endsection

@section("custom_scripts")
    <script src="/assets/vendor/moment/moment.min.js"></script>
    <script src="/assets/vendor/fullcalendar/fullcalendar.js"></script>
    <script src="/assets/vendor/select2/js/select2.js"></script>
    <script src="/assets/vendor/pnotify/pnotify.custom.js"></script>
    <script src="/assets/javascripts/ui-elements/examples.modals.js"></script>
    <!--<script src="/assets/javascripts/pages/examples.calendar.js"></script>-->
    <script>
        var arrayOfEvents = [];
        var arrayOfEventsToDelete = [];
        var arrayOfEventsToResize = [];

        function saveEvents() {
            if (arrayOfEvents.length > 0) {
                var data = "numOfEvents=" + arrayOfEvents.length;
                // You can get all events out of the array here
                for (var i = 0 ; i < arrayOfEvents.length ; i++) {
                    var event = arrayOfEvents[i];
                    data += "&id"    + i + "=" + event._id
                            + "&title" + i + "=" + event.title
                            + "&start" + i + "=" + event.start
                            + "&end"   + i + "=" + event.end;
                }

                $.ajax({
                    type: "GET",
                    url: "create-events",
                    data: data,
                    success: function(response){
                        console.log(response);
                        location.reload();
                    }
                });

                console.log(data);
            } else {
                new PNotify({
                    title: 'Failed!',
                    text: 'There are no events to be created!',
                    type: 'failed'
                });
            }
        }

        function deleteCalendar() {
            $.ajax({
                type: "GET",
                url: "delete-calendar",
                data: {
                    month:{{ $month }}
                },
                success: function(response){
                    console.log("return is: " + response);
                    $("#calendar").fullCalendar('removeEvents');
                    location.reload();
                }
            });
        }

        function deleteCalendarDate() {
            $.ajax({
                type: "GET",
                url: "delete-date",
                data: {
                    day:$("#calendar").fullCalendar( 'getDate' ).format()
                },
                success: function(response){
                    console.log(response);
                    location.reload();
                }
            });
        }


        function updateSchedule()
        {
            // check if user request to update
            if (arrayOfEvents.length > 0) {
                var data1 = "numOfEvents=" + arrayOfEvents.length;

                // You can get all events out of the array here
                for (var i = 0; i < arrayOfEvents.length; i++) {
                    var event1 = arrayOfEvents[i];
                    data1 += "&id" + i + "=" + event1.id
                            + "&title" + i + "=" + event1.title
                            + "&start" + i + "=" + event1.start
                            + "&end" + i + "=" + event1.end;
                }
                // Make your ajax post here
                $.ajax({
                    type: "GET",
                    url: "update-events",
                    data: data1,
                    success: function(response){
                        console.log(response);
                    }
                });
            }
            // check if user request to delete
            if(arrayOfEventsToDelete.length > 0) {
                var data2 = "numOfEvents=" + arrayOfEventsToDelete.length;
                // You can get all events out of the array here
                for (var i = 0; i < arrayOfEventsToDelete.length; i++) {
                    var event2 = arrayOfEventsToDelete[i];
                    console.log("here");
                    data2 += "&id" + i + "=" + event2.id
                            + "&title" + i + "=" + event2.title
                            + "&start" + i + "=" + event2.start
                            + "&end" + i + "=" + event2.end;
                }
                // Make your ajax post here
                $.ajax({
                    type: "GET",
                    url: "delete-events",
                    data: data2,
                    success: function(response){
                        console.log(response);
                    }
                });
            }
            // reload page when done
            if(arrayOfEvents.length > 0 || arrayOfEventsToDelete.length > 0){
                new PNotify({
                    title: 'Success!',
                    text: 'Calendar Updated Successfully!',
                    type: 'success'
                });
                location.reload();
            } else {
                new PNotify({
                    title: 'Failed!',
                    text: 'There are no events to be updated!',
                    type: 'failed'
                });
            }
        }
        $(document).ready(function() {


            /* initialize the external events
             -----------------------------------------------------------------*/

            $('#external-events .fc-event').each(function() {

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



            /* initialize empty calendar
             -----------------------------------------------------------------*/
            var $text = "This schedule will be duplicated for the whole month";

            $('#calendar_empty').fullCalendar({
                defaultView: 'agendaDay',
                header: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                defaultDate:'2016-{{ $month }}-01',
                titleFormat: "[Setup Schedule for ]MMMM YYYY",
                dayNames:[$text,$text,$text,$text,$text,$text,$text],
                allDaySlot: false,
                eventOverlap: false,
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                eventReceive: function(event) {
                    arrayOfEvents.push(event);
                    console.log("EventReceive: ");
                    console.log(event.start.format());
                }
                });
            /*================================================================*/
            /* EMPTY CALENDAR END                                             */
            /*================================================================*/


            /* initialize the calendar
             -----------------------------------------------------------------*/

            $('#calendar').fullCalendar({

                dayClick: function(date, jsEvent, view){
                    console.log("Clicked on: " + date.format());
                    $('#calendar').fullCalendar('changeView',"agendaDay");
                    $('#calendar').fullCalendar('gotoDate', date);
                },
                header: {
                    left: ' ', //'prev,next today'
                    center: 'title',
                    right: 'month,agendaDay'
                },
                editable: true,
                defaultDate:'2016-{{ $month }}-01',
                eventLimit: true, // allow "more" link when too many events
                allDaySlot: false,
                eventOverlap: false,
                events: {
                    url: 'get-events',
                    data: {
                        month:{{ $month }}
                    },
                    error: function() {
                        $('#script-warning').show();
                    }
                },
                loading: function(bool) {
                    $('#loading').toggle(bool);
                },
                droppable: true, // this allows things to be dropped onto the calendar
                eventReceive: function(event) {

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    //var copiedEventObject = $.extend({}, event);
                    //arrayOfEvents.push(copiedEventObject);
                    arrayOfEvents.push(event);
                    console.log("EventReceive: ");
                    console.log(event.start.format());
                },
                eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
                    var matched = false;
                    alert(event.title + " was dropped on " + event.start.format());
                    console.log(arrayOfEvents);
                    for (var i = 0 ; i < arrayOfEvents.length ; i++) {
                        if (arrayOfEvents[i].id != null) {
                            if (arrayOfEvents[i].id == event.id) {
                                arrayOfEvents[i] = event;
                                console.log("Event id Matched! Replacing it.");
                                matched = true;
                                break;
                            }
                        } else {
                            if (arrayOfEvents[i]._id == event._id) {
                                arrayOfEvents[i] =  event;
                                console.log("Event _id Matched! Replacing it.");
                                matched = true;
                                break;
                            }
                        }
                    }

                    if (matched == false) {
                        //var copiedEventObject = $.extend({}, event);
                        console.log(event);
                        arrayOfEvents.push(event);
                        console.log(arrayOfEvents);
                        console.log("Event not matched! Pushing it.")
                    }
                },
                eventRender: function(event, element) {
                    element.find('.fc-content').prepend('<span style="margin:1px" class="removeEvent glyphicon glyphicon-trash pull-right" id="Delete"></span>');
                    element.find(".removeEvent").click(function(e) {
                        $('#calendar').fullCalendar('removeEvents',event._id);
                        // find duplicate
                        foreach(int i = 0; i < arrayOfEvents.length; i++){
                            if(arrayOfEvents[i] == event){
                                if(!arrayOfEvents[i+1])
                                    arrayOfEvents.pop();
                                else{
                                    var temp = arrayOfEvents[i+1]
                                }

                            }
                        }
                        arrayOfEventsToDelete.push(event);
                    });
                },
                eventDrop: function(event, delta, revertFunc) {
                    var matched = false;
                    console.log(event);
                    //alert(event.title + " was dropped on " + event.start.format());
                    for (var i = 0 ; i < arrayOfEvents.length ; i++) {
                        if (arrayOfEvents[i].id != null) {
                            if (arrayOfEvents[i].id == event.id) {
                                arrayOfEvents[i] = event;
                                console.log("Event id Matched! Replacing it.");
                                matched = true;
                                break;
                            }
                        } else {
                            if (arrayOfEvents[i]._id == event._id) {
                                arrayOfEvents[i] =  event;
                                console.log("Event _id Matched! Replacing it.");
                                matched = true;
                                break;
                            }
                        }
                    }

                    if (matched == false) {
                        //var copiedEventObject = $.extend({}, event);
                        arrayOfEvents.push(event);
                        console.log("Event not matched! Pushing it.")
                    }
                },
                viewRender: function(view,element) {
                    if(view.name == "agendaDay") {
                        $("#delete_day").show();
                        $("#dragevents").show();
                        $("#delete_calendar").hide();
                    } else {
                        $("#dragevents").hide();
                        $("#delete_day").hide();
                        $("#delete_calendar").show();
                    }
                }
            });
        });
    </script>
@endsection