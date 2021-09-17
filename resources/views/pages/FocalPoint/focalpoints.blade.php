@extends('main')
@section('subtitle',' Focal Points')
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
                                <p class="card-title">Focal Points</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                <a href="{{route('focalpointAdd')}}" id="add-new-post" class="add-hbtn">
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Mobile</th>
                                    <th>Account Name</th>
                                    <th>Account Email</th>
                                    <th>Company</th>
                                    <th>Status</th>
{{--                                    <th style="color: black">Status</th>--}}
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
                    <h4 class="modal-title" id="postCrudModal">Reset Password</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <div class="form-group">
                            <label for="name">Password</label>
                            <div class="col-sm-12">
                                <input type="password" id="password" name="password" placeholder="enter passsword" required="">
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="name">Confirm Password</label>
                            <div class="col-sm-12">
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="confirm password" required="">
                            </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="reset-password-btn" value="create">Reset Password
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
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Contacts',
                    exportOptions: {
                        columns: [ 1,2,3,4,5,6,7,8 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('focalpointController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email' },
                    { data: 'telephone', name: 'telephone' },
                    { data: 'mobile', name: 'mobile'},
                    { data: 'account_name', name: 'account_name'},
                    { data: 'account_email', name: 'account_email'},
                    { data: 'company_name', name: 'company_name'},
                    { data: 'status', render:function (data){ if(data == 1) { return "<p style='color: green'>Active</p>"} else{ return "<p style='color: red'>InActive</p>" }}},
                    {data: 'action', name: 'action', orderable: false},
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
                $('#postCrudModal').html("Add New Post");
                //$('#ajax-crud-modal').modal('show');
            });

            $('body').on('click', '#reset_password', function () {
                //alert('iam here');
                //$('#btn-save').val("create-company");
                $('#user_id').val($(this).data('id'));
                //$('#postForm').trigger("reset");
                $('#postCrudModal').html("Reset Password");
                $('#ajax-crud-modal').modal('show');
            });

            $('body').on('click', '#reset-password-btn', function () {
                //alert('iam here');
                //$('#btn-save').val("create-company");
                var userId = $('#user_id').val();
                var password = $('#password').val();
                $.ajax({
                    type: "get",
                    url: "focalpointController/reset_password/"+userId+"/"+password,
                    success: function (data) {
                        //alert(data);
                        $('#ajax-crud-modal').modal('hide');
                        // var oTable = $('#laravel_datatable').dataTable();
                        // oTable.fnDraw(false);
                        alert('done');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        //alert('failure');
                    }
                });
            });
        });
    </script>
@endsection
