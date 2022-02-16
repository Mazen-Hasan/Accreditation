@extends('main')
@section('subtitle',' Add Event')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">Template Management - New</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id">
                            <br>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group col">
                                        <label>Field Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="field-name" name="field-name" value="" required
                                                   placeholder="enter field name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group col">
                                        <label>Field Type</label>
                                        <div class="col-sm-12">
                                            <input list="filedType">
                                            <datalist id="filedType">
                                                @foreach ($filedTypes as $filedType)
                                                    <option id="{{ $filedType->key }}"
                                                            @if ($filedType->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $filedType->value }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group col">
                                        <a href="javascript:void(0)" id="add-new-field" class="add-hbtn">
                                            <i>
                                                <img src="{{ asset('images/add.png') }}" alt="Add">
                                            </i>
                                            <span class="dt-hbtn">Add</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div id="newRow">

                            </div>
                        </form>
                        <div class="col-sm-offset-2 col-sm-2">
                            <button type="submit" id="btn-save" value="create">Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="loader-modal" tabindex="-1" data-backdrop="static" data-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 250px">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="col-sm-10">
                            <label class="loading">
                                loading...
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
                $('#ajax-crud-modal').modal('show');
            });
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function (form) {
                    $('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    $('#loader-modal').modal('show');
                    // alert($('#postForm').serialize());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('EventController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Add successfully');
                            window.location.href = "{{ route('events')}}";
                            // var oTable = $('#laravel_datatable').dataTable();
                            // oTable.fnDraw(false);
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }
        ;

        $("#add-new-field").click(function () {

            var html = '<div class="row" id="inputFormRow"><div class="col-md-5"><div class="form-group col">' +
                '<label>' + $('#field-name').val() + '</label></div></div>' +
                '<div class="col-md-5"><div class="form-group col">' +
                '<label>' + $('#filedType').val() + '</label></div></div>' +
                '<div class="col-md-1">' +
                '<button id="removeRow" type="button" class="btn btn-danger">Remove</button></div></div>';

            $('#newRow').append(html);

            $('#field-name').val('');
        });

        // remove row
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
        });
    </script>
@endsection
