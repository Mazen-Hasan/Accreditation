@extends('main')
@section('subtitle',' Contacts')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <a href="{{route('companyAdd')}}" class="ha_btn" id="add-new-company">Add New Company</a>
        <br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Contact Table</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Address</th>
                                    <th>Website</th>
                                    <th>Telephone</th>
{{--                                    <th>Category</th>--}}
                                    <th>Focal point</th>
                                    <th>Company admin</th>
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
                    url: "{{ route('companyController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name'},
                    { data: 'category_name', name: 'category' },
                    { data: 'country', name: 'country' },
                    { data: 'city', name: 'city' },
                    { data: 'address', name: 'address'},
                    { data: 'website', name: 'website'},
                    { data: 'telephone', name: 'telephone'},
                    { data: 'focal_point', name: 'focal_point'},
                    { data: 'company_admin', name: 'company_admin'},
                    {data: 'action', name: 'action', orderable: false},
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
