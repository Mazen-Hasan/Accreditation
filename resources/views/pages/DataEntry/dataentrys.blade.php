@extends('main')
@section('subtitle',' Data Entries')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
    <script src="{{ URL::asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('js/pdfmake.min.js') }}"></script>
@endsection
@section('custom_navbar')
    <li id="subsidiaries_nav" class="nav-item">
        <a class="nav-link {{ str_contains( Request::route()->getName(),'subCompanies') =="1" ? "active" : "" }}"
           href="{{ route('subCompanies',[$companyId,$eventId]) }} ">
            <i class="logout">
                <img src="{{ asset('images/menu.png') }}" alt="My Sidries">
            </i>
            <span class="menu-title">Subsidiaries</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ str_contains( Request::route()->getName(),'dataentrys') =="1" ? "active" : "" }}"
           href="{{ route('dataentrys',[$companyId,$eventId]) }}">
            <i class="logout">
                <img src="{{ asset('images/menu.png') }}" alt="Data Entry">
            </i>
            <span class="menu-title">Data Entry</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ str_contains( Request::route()->getName(),'focalpoints') =="1" ? "active" : "" }}"
           href="{{ route('focalpoints') }}">
            <i class="logout">
                <img src="{{ asset('images/user_mng.png') }}" alt="Subsidiaries Accounts">
            </i>
            <span class="menu-title">Subsidiaries Accounts</span>
        </a>
    </li>
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" id="company_id" value={{$companyId}}>
                        <input type="hidden" id="event_id" value={{$eventId}}>
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-11">
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{ route('company-admin') }} ">
                                        <span>My Events:</span>
                                    </a>
                                    <a class="url-nav" href="{{route('companyParticipants',[$companyId ,$eventId])}}">
                                        <span>{{$event_name}} / {{$company_name}} </span>
                                    </a>
                                    / Data Entries
                                </h4>
                            </div>
                            <div class="col-md-1 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel" title="Export to excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                </a>
                                @if($event_status < 3)
                                    <a href="{{route('dataentryAdd',[$companyId,$eventId])}}" id="add-new-post"
                                       class="add-hbtn" title="Add">
                                        <i>
                                            <img src="{{ asset('images/add.png') }}" alt="Add">
                                        </i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Telephone</th>
                                    <th>Mobile</th>
                                    <th>Account Name</th>
                                    <th>Account Email</th>
                                    <th>Status</th>
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
                            <input type="password" id="password" name="password" placeholder="enter passsword"
                                   required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <div class="col-sm-12">
                            <input type="password" id="confirm_password" name="confirm_password"
                                   placeholder="confirm password" required="">
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var companyId = $('#company_id').val();
            $('#laravel_datatable').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Contacts',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    // url: "/dataentrys/"+ companyId,
                    url: "{{ route('dataentrys',[$companyId,$eventId]) }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'name', name: 'name'},
                    // {data: 'email', name: 'email'},
                    {data: 'telephone', name: 'telephone'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'account_name', name: 'account_name'},
                    {data: 'account_email', name: 'account_email'},
                    // {data: 'company_name', name: 'company_name'},
                    {
                        data: 'status', render: function (data) {
                            if (data == 1) {
                                return "<p style='color: green'>Active</p>"
                            } else {
                                return "<p style='color: red'>InActive</p>"
                            }
                        }
                    },
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
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
                var url = "{{ route('resetDataEntryPassword', [':userId',':password']) }}";
                url = url.replace(':password', password);
                url = url.replace(':userId', userId);
                $.ajax({
                    type: "get",
                    url: url,
                    //url: "dataentryController/reset_password/" + userId + "/" + password,
                    success: function (data) {
                        $('#ajax-crud-modal').modal('hide');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>
@endsection
