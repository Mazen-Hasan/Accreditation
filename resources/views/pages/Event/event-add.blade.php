@extends('main')
@section('subtitle',' Add Event')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
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
                            <input type="hidden" name="post_id" id="post_id">
                            <p class="card-description">
                                Event Form
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="name" name="name" value="" required="" placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Period</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="period" name="period" value="" required=""placeholder="enter period"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Event Admin</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="event_admin" name="event_admin" required="">
                                                @foreach ($eventAdmins as $eventAdmin)
                                                    <option value="{{ $eventAdmin->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventAdmin->key == 1)
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
                                        <label class="col-form-label">Accreditation Period</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="accreditation_period" name="accreditation_period" value="" placeholder="enter accreditation period" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Owner</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="owner" name="owner" required="">
                                                @foreach ($owners as $owner)
                                                    <option value="{{ $owner->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                                @if ($owner->key == 1)
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
                                        <label class=" col-form-label">Organizer</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="organizer" name="organizer" required="">
                                                @foreach ($organizers as $organizer)
                                                    <option value="{{ $organizer->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($organizer->key == 1)
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
                                        <label class=" col-form-label">Location</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="location" name="location" value="" placeholder="enter location"  required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Size</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="size" name="size" placeholder="enter size" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Security Officer</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="security_officer" name="security_officer" required="">
                                                @foreach ($securityOfficers as $securityOfficer)
                                                    <option value="{{ $securityOfficer->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($securityOfficer->key == 1)
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
                                        <label class=" col-form-label">Security Option</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="approval_option" name="approval_option" required="">
                                                @foreach ($approvalOptions as $approvalOption)
                                                    <option value="{{ $approvalOption->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($approvalOption->key == 1)
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
                                        <label class=" col-form-label">Event Type</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="event_type" name="event_type" required="">
                                                @foreach ($eventTypes as $eventType)
                                                    <option value="{{ $eventType->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventType->key == 1)
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
                                        <label class=" col-form-label">Status</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="status" name="status" required="">
                                                @foreach ($eventStatuss as $eventStatus)
                                                    <option value="{{ $eventStatus->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventStatus->key == 1)
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
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Registration Form Template</label>
                                        <div class="col-sm-12">
                                           <select class="input100 minimal" id="event_form" name="event_form" required="">
                                                @foreach ($eventForms as $eventForm)
                                                    <option value="{{ $eventForm->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventForm->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventForm->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-2">
                                <button type="submit" class="login100-form-btn" id="btn-save" value="create">Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
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

            {{--$('#laravel_datatable').DataTable({--}}
            {{--    processing: true,--}}
            {{--    serverSide: true,--}}
            {{--    ajax: {--}}
            {{--        url: "{{ route('dtable-posts.index') }}",--}}
            {{--        type: 'GET',--}}
            {{--    },--}}
            {{--    columns: [--}}
            {{--        { data: 'id', name: 'id', 'visible': false},--}}
            {{--        { data: 'title', name: 'title' },--}}
            {{--        { data: 'body', name: 'body' },--}}
            {{--        { data: 'created_at', name: 'created_at' },--}}
            {{--        {data: 'action', name: 'action', orderable: false},--}}
            {{--    ],--}}
            {{--    order: [[0, 'desc']]--}}
            {{--});--}}

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
                $('#ajax-crud-modal').modal('show');
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
                    $('#post_id').val('');
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
                            $('#btn-save').html('Add successfully');
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
