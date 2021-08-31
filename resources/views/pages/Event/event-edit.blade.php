
@extends('main')
@section('subtitle',' Edit Event')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{--    {!! Html::style('vendors/select2/select2.min.css'); !!}--}}
{{--    {!! Html::style('vendors/select2-bootstrap-theme/select2-bootstrap.min.css'); !!}--}}
    <link rel="stylesheet" href="{{ URL::asset('vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('vendors/select2/select2.min.css') }}">
{{--    <link rel="stylesheet" href="vendors/select2/select2.min.css">--}}
{{--    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card"  style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">Event Management</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id" value="{{$post->id}}">
                            <p class="card-description">
                                Event Form
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name" name="name" value="{{$post->name}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Period</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="period" name="period" value="{{$post->period}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Event Admin</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="event_admin" name="event_admin"  required="">
                                                @foreach ($eventAdmins as $eventAdmin)
                                                    <option value="{{ $eventAdmin->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventAdmin->key == $post->eventAdmin)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventAdmin->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Accreditation Period</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="accreditation_period" name="accreditation_period" value="{{$post->accreditation_period}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Owner</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="owner" name="owner" required="">
                                                @foreach ($owners as $owner)
                                                    <option value="{{ $owner->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($owner->key == $post->owner)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $owner->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Organizer</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="organizer" name="organizer" value="{{$post->organizer}}" required="">
                                                @foreach ($organizers as $organizer)
                                                    <option value="{{ $organizer->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($organizer->key == $post->organizer)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $organizer->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--                            <p class="card-description">--}}
                            {{--                                Address--}}
                            {{--                            </p>--}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Location</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="location" name="location" value="{{$post->location}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Size</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="size" name="size" value="{{$post->size}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Security Officer</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="security_officer" name="security_officer"  required="">
                                                @foreach ($securityOfficers as $securityOfficer)
                                                    <option value="{{ $securityOfficer->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($securityOfficer->key == $post->security_officer)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $securityOfficer->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Security Option</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="approval_option" name="approval_option" required="">
                                                @foreach ($approvalOptions as $approvalOption)
                                                    <option value="{{ $approvalOption->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($approvalOption->key == $post->approval_option)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $approvalOption->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Event Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="event_type" name="event_type" required="">
                                                @foreach ($eventTypes as $eventType)
                                                    <option value="{{ $eventType->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventType->key == $post->event_type)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventType->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="status" name="status" required="">
                                                @foreach ($eventStatuss as $eventStatus)
                                                    <option value="{{ $eventStatus->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventStatus->key == $post->status)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventStatus->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col">
                                        <label class="col-sm-3 col-form-label">Registration Form Template</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="event_form" name="event_form"  required="">
                                                @foreach ($eventForms as $eventForm)
                                                    <option value="{{ $eventForm->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventForm->key == $post->event_form)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventForm->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary" id="btn-save" value="create">Edit
                                </button>
                            </div>
                        </form>
                        <br><br>
                        <div class="row">
                            <div class="col-lg-6 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Security Category Table</h4>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Security Category</th>
                                                    {{--                                                    <th style="color: black">Status</th>--}}
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        {{--        <a href="{{route('eventAdd')}}" class="btn btn-info ml-3" id="add-new-Title">Add New Evant</a>--}}
                        <a href="javascript:void(0)" class="btn btn-info ml-3" id="add-new-post">Add New Event Security Category</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="postCrudModal"></h4>
                </div>
                <div class="modal-body">
                    {{--                    <form id="contactTitleForm" name="contactTitleForm" class="form-horizontal">--}}
                    <input type="hidden" name="event_id" id="event_id" value="{{$post->id}}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Security Category</label>
                        <div class="col-sm-12">
                            {{--                                <input class="form-control" id="status" name="status" value="" required="">--}}
                            <select class="form-control" id="eventSecurityCategory" name="eventSecurityCategory" value="" required="">
                                @foreach ($securityCategories as $securityCategory)
                                    <option value="{{ $securityCategory->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                            @if ($securityCategory->key == 1)
                                            selected="selected"
                                        @endif
                                    >{{ $securityCategory->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button class="btn btn-primary" id="btn-event-security-category-save" value="create">Save
                        </button>
                    </div>
                    {{--                    </form>--}}
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('vendors/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('js/file-upload.js') }}"></script>
    <script src="{{ URL::asset('js/typeahead.js') }}"></script>
    <script src="{{ URL::asset('js/select2.js') }}"></script>
{{--    {!! Html::script('vendors/typeahead.js/typeahead.bundle.min.js'); !!}--}}
{{--    {!! Html::script('vendors/select2/select2.min.js'); !!}--}}
{{--    {!! Html::script('js/file-upload.js'); !!}--}}
{{--    {!! Html::script('js/typeahead.js'); !!}--}}
{{--    {!! Html::script('js/select2.js'); !!}--}}

{{--    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>--}}
{{--    <script src="vendors/select2/select2.min.js"></script>--}}
{{--    <script src="js/file-upload.js"></script>--}}
{{--    <script src="js/typeahead.js"></script>--}}
{{--    <script src="js/select2.js"></script>--}}
    <script>
        $(document).ready( function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#laravel_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('EventController.edit',[$post->id]) }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Event Security Category");
                $('#ajax-crud-modal').modal('show');
            });

            $('body').on('click', '#remove-event-security-category', function () {
                var post_id = $(this).data("id");
                // var event_id = $('#event_id').val();
                //alert(event_id + " "+ post_id);
                confirm("Are You sure want to remove event security category ?!");
                $.ajax({
                    type: "get",
                    url: "../EventController/remove/"+post_id,
                    success: function (data) {
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('body').on('click', '#btn-event-security-category-save', function () {
                var event_id = $('#event_id').val();
                var eventSecurityCategory = $('#eventSecurityCategory').val();
                //alert('hey hey');
                //confirm("Are You sure want to deActivate ?!");
                $.ajax({
                    type: "get",
                    url: "../EventController/storeEventSecurityCategory/"+event_id+"/"+eventSecurityCategory,
                    success: function (data) {
                        //alert(data);
                        $('#ajax-crud-modal').modal('hide');
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });


            // $('body').on('click', '.edit-post', function () {
            //     var post_id = $(this).data('id');
            //     $.get('dtable-posts/'+post_id+'/edit', function (data) {
            //         $('#name-error').hide();
            //         $('#email-error').hide();
            //         $('#postCrudModal').html("Edit Post");
            //         $('#btn-save').val("edit-post");
            //         $('#ajax-crud-modal').modal('show');
            //         $('#post_id').val(data.id);
            //         $('#title').val(data.title);
            //         $('#body').val(data.body);
            //     })
            // });
            //
            // $('body').on('click', '#delete-post', function () {
            //     var post_id = $(this).data("id");
            //     confirm("Are You sure want to delete !");
            //     $.ajax({
            //         type: "get",
            //         url: "dtable-posts/destroy/"+post_id,
            //         success: function (data) {
            //             var oTable = $('#laravel_datatable').dataTable();
            //             oTable.fnDraw(false);
            //         },
            //         error: function (data) {
            //             console.log('Error:', data);
            //         }
            //     });
            // });
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function(form) {
                    //$('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    //alert($('#postForm').serialize());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('EventController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Edited successfully');
                            window.location.href = "{{ route('events')}}";
                            // var oTable = $('#laravel_datatable').dataTable();
                            // oTable.fnDraw(false);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }
    </script>
@endsection
