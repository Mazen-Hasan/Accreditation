@extends('main')
@section('subtitle',' Event Types')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
    <script src="{{ URL::asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('js/pdfmake.min.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-8">
                                <h4 class="card-title">Event Types</h4>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                <a href="javascript:void(0)" id="add-new-post" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Event Type</th>
                                    <th style="color: black">Status</th>
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
    </div>
    <div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="postCrudModal"></h4>
                </div>
                <div class="modal-body">
                    <form id="postForm" name="postForm" class="form-horizontal">
                        <input type="hidden" name="post_id" id="post_id">
                        <div class="form-group">
                            <label for="name">Event Type</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" placeholder="enter name" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <div class="col-sm-12">
                                <select id="status" name="status" value="" required="">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="submit" id="btn-save" value="create">Save
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-element-confirm-modal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_element_id">
                        <input type="hidden" id="action_button">
                        <label class="col-sm-12 confirm-text" id="confirmText"></label>
                    </div>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">Cancel</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes">Yes</button>
                        </div>
                    </div>
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
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Event-Types',
                    exportOptions: {
                        columns: [ 1,2 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('eventTypeController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name' },
                    { data: 'status', render:function (data){ if(data == 1) { return "<p style='color: green'>Active</p>"} else{ return "<p style='color: red'>InActive</p>" }}},
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click( function() {
                $('#laravel_datatable').DataTable().button( '.buttons-excel' ).trigger();
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("New Event Type");
                $('#ajax-crud-modal').modal('show');
            });


            $('body').on('click', '.edit-post', function () {
                var post_id = $(this).data('id');
                //alert(post_id);
                $.get('eventTypeController/'+post_id+'/edit', function (data) {
                    $('#name-error').hide();
                    $('#email-error').hide();
                    $('#postCrudModal').html("Edit Event Type");
                    $('#btn-save').val("edit-post");
                    $('#ajax-crud-modal').modal('show');
                    $('#post_id').val(data.id);
                    $('#name').val(data.name);
                    $('#status').val(data.status);
                })
            });

            $('body').on('click', '#delete-post', function () {
                var post_id = $(this).data("id");
                $('#confirmTitle').html('Delete Event Type');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('delete');
                var confirmText =  'Are You sure want to delete ?';
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
                // confirm("Are You sure want to delete !");
                // $.ajax({
                //     type: "get",
                //     url: "eventTypeController/destroy/"+post_id,
                //     success: function (data) {
                //         var oTable = $('#laravel_datatable').dataTable();
                //         oTable.fnDraw(false);
                //     },
                //     error: function (data) {
                //         console.log('Error:', data);
                //     }
                // });
            });
            $('body').on('click', '#activate-title', function () {
                var post_id = $(this).data("id");
                $('#confirmTitle').html('Activate Event Type');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('activate');
                var confirmText =  "Are You sure want to activate ?!";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
                // confirm("Are You sure want to activate ?!");
                // $.ajax({
                //     type: "get",
                //     url: "eventTypeController/changeStatus/"+post_id+"/1",
                //     success: function (data) {
                //         var oTable = $('#laravel_datatable').dataTable();
                //         oTable.fnDraw(false);
                //     },
                //     error: function (data) {
                //         console.log('Error:', data);
                //     }
                // });
            });
            $('body').on('click', '#deActivate-title', function () {
                var post_id = $(this).data("id");
                $('#confirmTitle').html('Deactivate Event Type');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('deactivate');
                var confirmText =  "Are You sure want to deactivate ?!";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
                // confirm("Are You sure want to deActivate ?!");
                // $.ajax({
                //     type: "get",
                //     url: "eventTypeController/changeStatus/"+post_id+"/0",
                //     success: function (data) {
                //         var oTable = $('#laravel_datatable').dataTable();
                //         oTable.fnDraw(false);
                //     },
                //     error: function (data) {
                //         console.log('Error:', data);
                //     }
                // });
            });
            $('#delete-element-confirm-modal button').on('click', function(event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function() {
                    if($button[0].id === 'btn-yes'){
                        var post_id = $('#curr_element_id').val();
                        var action_button = $('#action_button').val();
                        if(action_button == 'delete'){
                            $.ajax({
                                type: "get",
                                url: "eventTypeController/destroy/"+post_id,
                                success: function (data) {
                                    var oTable = $('#laravel_datatable').dataTable();
                                    oTable.fnDraw(false);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                        if(action_button == 'activate'){
                            $.ajax({
                                type: "get",
                                url: "eventTypeController/changeStatus/"+post_id+"/1",
                                success: function (data) {
                                    var oTable = $('#laravel_datatable').dataTable();
                                    oTable.fnDraw(false);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                        if(action_button == 'deactivate'){
                            $.ajax({
                                type: "get",
                                url: "eventTypeController/changeStatus/"+post_id+"/0",
                                success: function (data) {
                                    var oTable = $('#laravel_datatable').dataTable();
                                    oTable.fnDraw(false);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
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

                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('eventTypeController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Save Changes');
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
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
