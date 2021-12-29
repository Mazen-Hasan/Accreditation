@extends('main')
@section('subtitle',' Events')
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
        <br> <br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-8">
                                <h4 class="card-title">Event Management</h4>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                @role('super-admin')
                                <a href="{{route('eventAdd')}}" id="add-new-post" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                                @endrole
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Organizer</th>
                                    <th>Template</th>
                                    <th>Type</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Accredit Start</th>
                                    <th>Accredit End</th>
                                    <th style="color: black">Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div>
                        <a href="javascript:void(0)" id="showAll" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="show all">
                                    </i>
                                    <span class="dt-hbtn" id="showAllSpan">Show all</span>
                                </a>
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
            var showStatus = 1;
            var murl = "{{ route('EventController.index') }}";
            $('#laravel_datatable').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Events',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: murl,
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'size', name: 'size'},
                    {data: 'organizer', name: 'organizer'},
                    {data: 'template_name', name: 'template_name'},
                    {data: 'event_type', name: 'event_type'},
                    {data: 'event_start_date', name: 'event_start_date'},
                    {data: 'event_end_date', name: 'event_end_date'},
                    {data: 'accreditation_start_date', name: 'accreditation_start_date'},
                    {data: 'accreditation_end_date', name: 'accreditation_end_date'},
                    {
                        data: 'status', render: function (data) {
                            if (data == 1) {
                                return "<span style='color: green'>Active</span>"
                            } else {
                                if(data == 2){
                                    return "<span style='color: red'>InActive</span>"
                                }else{
                                    return "<span style='color: orange'>Archived</span>"
                                }
                            }
                        }
                    },
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
            });

            $('body').on('click', '#showAll', function () {
                if(showStatus == 1){
                    murl = "{{ route('eventsShowall', [":id"]) }}";
                    murl = murl.replace(':id', showStatus);
                    $('#showAllSpan').html("Hide archived");
                    showStatus = 0;     
                }else{
                    murl = "{{ route('EventController.index') }}";
                    $('#showAllSpan').html("Show all");
                    showStatus = 1;
                }
                //alert(showStatus + "," + murl);
                var mtable = $('#laravel_datatable').DataTable();
                mtable.ajax.url(murl).load(); 
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });
        });
    </script>
@endsection
