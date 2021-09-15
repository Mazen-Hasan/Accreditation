@extends('main')
@section('subtitle',' Users')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <a href="{{route('userAdd')}}" class="ha_btn" id="add-new-company">Add User</a>
        <br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Users</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role_ID</th>
                                    <th>Account</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <!-- <th>Status</th> -->
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
                    url: "{{ route('userController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'user_id', name: 'user_id', 'visible': false},
                    { data: 'role_id', name: 'role_id', 'visible': false},
                    { data: 'user_name', name: 'user_name'},
                    { data: 'email', name: 'email' },
                    { data: 'role_name', name: 'role_name' },
                    { data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-company').click(function () {
                $('#btn-save').val("create-company");
                $('#company_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Company");
                //$('#ajax-crud-modal').modal('show');
            });
        });
    </script>
@endsection
