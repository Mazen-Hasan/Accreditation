@extends('main')
@section('subtitle',' Users')
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
                            <div class="col-md-10">
                                <p class="card-title">Users</p>
                            </div>
                        	<div class="col-md-1 align-content-md-center">
                                <div class="search-container">
                                    <input class="search expandright" id="search" type="text" placeholder="Search">
                                    <label class="search-button search-button-icon" for="search">
                                        <i class="icon-search"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel" title="Export to excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                </a>
                                <span class="dt-hbtn"></span>
                                <a href="{{route('userAdd')}}" id="add-new-user" class="add-hbtn" title="Add">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role_ID</th>
                                    <th>Account</th>
                                    <th>Email</th>
                                    <th>Role</th>
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
                        <div class="row">
                            <div class="col-sm-11">
                                <input style="margin-left: 16px; width: 103%" type="password"
                                       id="password" name="password" placeholder="enter password"
                                       required="" onblur="checkPassword()"/>
                            </div>
                            <div class="col-sm-1" id="eye">
                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <div class="row">
                            <div class="col-sm-11">
                                <input style="margin-left: 16px; width: 103%" type="password"
                                       id="confirm_password" name="confirm_password"
                                       placeholder="confirm password" required="" onblur="checkPassword()"/>
                            </div>
                            <div class="col-sm-1" id="eye">
                                <i class="fa fa-eye-slash" id="togglePasswordConfirm"></i>
                            </div>
                        </div>
                        <label id="lbl_error" class="error" for="name"></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12">
                        <button type="submit" id="reset-password-btn" value="create">Reset Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="loader-modal" tabindex="-1" data-backdrop="static" data-keyboard="false"
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

            $('#laravel_datatable').DataTable({

                dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Users',
                    exportOptions: {
                        columns: [2, 3, 4]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('userController.index') }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'user_id', name: 'user_id', 'visible': false},
                    {data: 'role_id', name: 'role_id', 'visible': false},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'email', name: 'email'},
                    {data: 'role_name', name: 'role_name'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('body').on('click', '#reset_password', function () {
                $('#user_id').val($(this).data('id'));
                $('#postCrudModal').html("Reset Password");
                $('#lbl_error').html('');
                $('#togglePassword').removeClass('fa fa-eye');
                $('#togglePassword').addClass('fa fa-eye-slash');
                $('#togglePasswordConfirm').removeClass('fa fa-eye');
                $('#togglePasswordConfirm').addClass('fa fa-eye-slash');
                $('#password').attr('type', 'password');
                $('#confirm_password').attr('type', 'password');
                $('#password').val('');
                $('#confirm_password').val('');
                $('#ajax-crud-modal').modal('show');
            });

            $('#togglePassword').click(function () {
                var type = $('#password').attr('type') === 'password' ? 'text' : 'password';
                $('#password').attr('type', type);
                if (type === 'text') {
                    $('#togglePassword').removeClass('fa fa-eye-slash');
                    $('#togglePassword').addClass('fa fa-eye');
                } else {
                    $('#togglePassword').removeClass('fa fa-eye');
                    $('#togglePassword').addClass('fa fa-eye-slash');
                }
            });

            $('#togglePasswordConfirm').click(function () {
                var type = $('#confirm_password').attr('type') === 'password' ? 'text' : 'password';
                $('#confirm_password').attr('type', type);
                if (type === 'text') {
                    $('#togglePasswordConfirm').removeClass('fa fa-eye-slash');
                    $('#togglePasswordConfirm').addClass('fa fa-eye');
                } else {
                    $('#togglePasswordConfirm').removeClass('fa fa-eye');
                    $('#togglePasswordConfirm').addClass('fa fa-eye-slash');
                }
            });

            $('body').on('click', '#reset-password-btn', function () {
                var userId = $('#user_id').val();
                var password = $('#password').val();
                var confirm_password = $('#confirm_password').val();
                if (password !== confirm_password) {
                    $('#lbl_error').html('Please enter the same password');
                } else {
                    $('#loader-modal').modal('show');
                	var url = "{{ route('userControllerResetPassword', [':userId',':password']) }}";
                    url = url.replace(':userId', userId);
                    url = url.replace(':password', password);

                    $.ajax({
                        type: "get",
                        // url: "userController/reset_password/" + userId + "/" + password,
                        url: url,
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#ajax-crud-modal').modal('hide');
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                        }
                    });
                }
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

        	 var oTable = $('#laravel_datatable').DataTable();

            $('#search').on('keyup', function () {
                oTable.search(this.value).draw();
            });
        })

        function checkPassword() {
            var password = $('#password').val();
            var confirm_password = $('#confirm_password').val();
            if (password !== confirm_password) {
                $('#lbl_error').html('Please enter the same password');
            } else {
                $('#lbl_error').html('');
            }
        }

    </script>
@endsection
