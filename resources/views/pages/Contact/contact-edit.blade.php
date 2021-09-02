
@extends('main')
@section('subtitle',' Edit Contact')
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
                        <h4 class="card-title">Contact Management</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id" value="{{$post->id}}">
                            <p class="card-description">
                                Contact Form
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name" name="name" value="{{$post->name}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Middle Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{$post->middle_name}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Last Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{$post->last_name}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="email" name="email" value="{{$post->email}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Telephone</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="telephone" name="telephone" value="{{$post->telephone}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Mobile</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="mobile" name="mobile" value="{{$post->mobile}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group col">--}}
{{--                                        <label class="col-sm-3 col-form-label">Event Type</label>--}}
{{--                                        <div class="col-sm-9">--}}
{{--                                            <select class="form-control" id="event_type" name="event_type" value="" required="">--}}
{{--                                                @foreach ($titles as $title)--}}
{{--                                                    <option value="{{ $title->key }}"--}}
{{--                                                            @if ($key == old('myselect', $model->option))--}}
{{--                                                            @if ($title->key == 1)--}}
{{--                                                            selected="selected"--}}
{{--                                                        @endif--}}
{{--                                                    >{{ $title->value }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="status" name="status" value="" required="">
                                                @foreach ($contactStatuss as $contactStatus)
                                                    <option value="{{ $contactStatus->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($contactStatus->key == $post->status)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $contactStatus->value }}</option>
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
                                        <h4 class="card-title">Contact Title Table</h4>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Title</th>
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
                        <a href="javascript:void(0)" class="btn btn-info ml-3" id="add-new-post">Add New Contact Title</a>
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
                        <input type="hidden" name="contact_id" id="contact_id" value="{{$post->id}}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-12">
                                {{--                                <input class="form-control" id="status" name="status" value="" required="">--}}
                                <select class="form-control" id="contactTitle" name="contactTitle" value="" required="">
                                    @foreach ($titlesSelectOptions as $titlesSelectOptions)
                                        <option value="{{ $titlesSelectOptions->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                @if ($titlesSelectOptions->key == 1)
                                                selected="selected"
                                            @endif
                                        >{{ $titlesSelectOptions->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary" id="btn-contact_title-save" value="create">Save
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
                    url: "{{ route('contactController.edit',[$post->id]) }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'title_label', name: 'title_label'},
                    // {
                    //     data: 'status', render: function (data) {
                    //         if (data == 1) {
                    //             return "<p style='color: green'>Active</p>"
                    //         } else {
                    //             return "<p style='color: red'>InActive</p>"
                    //         }
                    //     }
                    // },
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Contact Title");
                $('#ajax-crud-modal').modal('show');
            });
            // $('body').on('click', '.edit-post', function () {
            //     var post_id = $(this).data('id');
            //     //alert(post_id);
            //     $.get('titleController/' + post_id + '/removeContactTitle', function (data) {
            //         $('#name-error').hide();
            //         $('#email-error').hide();
            //         $('#postCrudModal').html("Edit Title");
            //         $('#btn-save').val("edit-post");
            //         $('#ajax-crud-modal').modal('show');
            //         $('#post_id').val(data.id);
            //         $('#title_label').val(data.title_label);
            //         $('#status').val(data.status);
            //     })
            // });
            $('body').on('click', '#remove-contact_title', function () {
                var post_id = $(this).data("id");
                // var contact_id = $('#contact_id').val();
                confirm("Are You sure want to remove contact title ?!");
                $.ajax({
                    type: "get",
                    url: "../contactController/removeContactTitle/"+post_id,
                    success: function (data) {
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('body').on('click', '#btn-contact_title-save', function () {
                var contact_id = $('#contact_id').val();
                var title_id = $('#contactTitle').val();
                //alert('hey hey');
                //confirm("Are You sure want to deActivate ?!");
                $.ajax({
                    type: "get",
                    url: "../contactController/storeContactTitle/"+contact_id+"/"+title_id,
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
        });
        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function(form) {
                    //$('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    //alert($('#post_id').val());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('contactController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Edited successfully');
                            window.location.href = "{{ route('contacts')}}";
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
        {{--if ($("#contactTitleForm").length > 0) {--}}
        {{--    $("#contactTitleForm").validate({--}}
        {{--        submitHandler: function(form) {--}}
        {{--            //$('#post_id').val('');--}}
        {{--            var contact_id = ('#post_id').val();--}}
        {{--            var title_id = ('#contactTitle').val();--}}
        {{--            var actionType = $('#btn-save').val();--}}
        {{--            $('#btn-contact_title-save').html('Sending..');--}}
        {{--            alert($('#contactTitleForm').serialize());--}}
        {{--            $.ajax({--}}
        {{--                data: $('#contactTitleForm').serialize(),--}}
        {{--                --}}{{--url: "{{ route('contactController.storeContactTitle') }}",--}}
        {{--                url: "contactController/storeContactTitle/"+contact_id+"/"+title_id,--}}
        {{--                type: "POST",--}}
        {{--                dataType: 'json',--}}
        {{--                success: function (data) {--}}
        {{--                    alert(data);--}}
        {{--                    $('#contactTitleForm').trigger("reset");--}}
        {{--                    $('#ajax-crud-modal').modal('hide');--}}
        {{--                    $('#btn-contact_title-save').html('added successfully');--}}
        {{--                    var oTable = $('#laravel_datatable').dataTable();--}}
        {{--                    oTable.fnDraw(false);--}}
        {{--                },--}}
        {{--                error: function (data) {--}}
        {{--                    console.log('Error:', data);--}}
        {{--                    $('#btn-save').html('Save Changes');--}}
        {{--                }--}}
        {{--            });--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}
    </script>
@endsection
