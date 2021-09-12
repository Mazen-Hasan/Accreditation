
@extends('main')
@section('subtitle',' Edit Contact')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <br>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card"  style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">Contact Management - Edit</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id" value="{{$post->id}}">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" value="{{$post->name}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Middle Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="middle_name" name="middle_name" value="{{$post->middle_name}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="last_name" name="last_name" value="{{$post->last_name}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Email</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="email" name="email" value="{{$post->email}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="telephone" name="telephone" value="{{$post->telephone}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Mobile</label>
                                        <div class="col-sm-12">
                                            <input type="text"id="mobile" name="mobile" value="{{$post->mobile}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Status</label>
                                        <div class="col-sm-12">
                                            <select id="status" name="status" value="" required="">
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
                            <div class="col-sm-offset-2 col-sm-2">
                                <button type="submit" id="btn-save" value="create">Edit
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
                        <a href="javascript:void(0)" class="ha_btn" id="add-new-post">Add Contact Title</a>
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
                        <input type="hidden" name="contact_id" id="contact_id" value="{{$post->id}}">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="col-sm-12">
                                <select id="contactTitle" name="contactTitle" value="" required="">
                                    @foreach ($titlesSelectOptions as $titlesSelectOptions)
                                        <option value="{{ $titlesSelectOptions->key }}"
                                            @if ($titlesSelectOptions->key == 1)
                                                selected="selected"
                                            @endif
                                        >{{ $titlesSelectOptions->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button id="btn-contact_title-save" value="create">Save
                            </button>
                        </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
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
    </script>
@endsection
