@extends('main')
@section('subtitle',' Event Company')
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
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{ route('event-admin') }} ">
                                        <span>My Events:</span>
                                    </a>
                                    {{$event_name}} / Companies
                                </h4>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Event ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Website</th>
                                    <th>Telephone</th>
                                    <th>Focal point</th>
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
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Event-Companies',
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('securityOfficerCompanies', $eventid) }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'event_id', name: 'event_id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
                    {data: 'country', name: 'country'},
                    {data: 'city', name: 'city'},
                    {data: 'website', name: 'website'},
                    {data: 'telephone', name: 'telephone'},
                    {data: 'focal_point', name: 'focal_point'},
                    {
                        data: 'status', render: function (data) {
                            if (data == 1) {
                                return "<p style='color: green'>Active</p>"
                            } else {
                                if (data == 0) {
                                    return "<p style='color: red'>InActive</p>"
                                } else {
                                    return "<p style='color: orange'>Invited</p>"
                                }
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

            $('#add-new-company').click(function () {
                $('#btn-save').val("create-company");
                $('#company_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Company");
            });
        });
    </script>
@endsection
