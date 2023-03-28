@extends("admin.master")
@section("services_nav","nav-active")
@section("page_header", "Services")
@section("body")
    @if($user['account_type'] == "admin")
    <div class="row">
        {!! Form::open(array("method" => "POST", "url" => "admin/update_services")) !!}
        @if(session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Oh snap!</strong> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Well done!</strong> {!! session('success') !!}.
            </div>
        @endif

        <div class="col-md-7 col-xs-12">
            <section class="panel panel-primary">
                <header class="panel-heading">
                    <h2 class="panel-title">Services List</h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-condensed mb-none">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service Name</th>
                                    <th>Duration</th>
                                    <th>Description</th>
                                    <th>Cost</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $counter=0;?>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>{{ ++$counter }}</td>
                                            <td>
                                                <input type="hidden" name="id_{{$counter}}" value="{{ $service->id }}">
                                                <input name="service_name_{{$counter}}" type="text" class="form-control" value="{{ $service->name }}"/>
                                            </td>
                                            <td>
                                                {{ $service->duration }}
                                            </td>
                                            <td>
                                                <input name="description_{{$counter}}" type="text" class="form-control" value="{{ $service->description }}"/>
                                            </td>
                                            <td>
                                                <input name="cost_{{$counter}}" style="max-width: 80px;" type="text" class="form-control" value="{{ $service->cost }}"/>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger" onclick="delete_service({{ $service->id }})">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    Delete
                                                </button>
                                            </td>
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
        {!! Form::open(array("method" => "POST", "url" => "admin/add_service")) !!}
        <div class="col-md-5 col-xs-12">
            <section class="panel panel-primary">
                <header class="panel-heading">
                    <h2 class="panel-title">Add Service</h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Service Name</span>
                                <input name="name" type="text" class="form-control" placeholder="E.g Pedicure">
                            </div>
                            <br/>
                            <div class="input-group">
                                <span class="input-group-addon">Description</span>
                                <input name="description" type="text" class="form-control" placeholder="Description about service">
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">Duration</span>
                                        <input type="text" class="form-control" placeholder="" value="30 mins" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">Cost</span>
                                        <input name="price" type="text" class="form-control" placeholder="20.00">
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
                                <button type="submit" class="btn btn-primary">Add New Service</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        {!! Form::close() !!}
    </div>
    @else
        <div class="row">
            {!! Form::open(array("method" => "POST", "url" => "admin/update_services")) !!}
            @if(session('error'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Oh snap!</strong> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Well done!</strong> {!! session('success') !!}.
                </div>
            @endif

            <div class="col-md-7 col-xs-12">
                <section class="panel panel-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title">Services List</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <table class="table table-condensed mb-none">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service Name</th>
                                        <th>Duration</th>
                                        <th>Description</th>
                                        <th>Cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $counter=0;?>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>{{ ++$counter }}</td>
                                            <td>
                                                {{ $service->name }}
                                            </td>
                                            <td>
                                                {{ $service->duration }}
                                            </td>
                                            <td>
                                                {{ $service->description }}
                                            </td>
                                            <td>
                                                {{ $service->cost }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <input type="hidden" name="total" value="{{ $counter }}">
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
            {!! Form::close() !!}
        </div>
    @endif
@endsection

@section("custom_scripts")
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    function delete_service($id){
        var formData = {id:$id}; //Array
        $.ajax({
            url : "delete_service",
            type: "POST",
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.log("error");
            }
        });
    }
</script>
@endsection