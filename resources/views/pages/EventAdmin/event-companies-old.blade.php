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
                            <input type="hidden" id="h_event_id" value={{$eventid}}>
                            <div class="col-md-8">
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{ route('event-admin') }} ">
                                        <span>My Events:</span>
                                    </a>
                                    / {{$event_name}} / Companies
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
                                @if($event_status < 3)
                                <a href="../company-add/{{$eventid}}" id="add-new-company" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                                @endif
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
                                    <th>City</th><th>Website</th>
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
    <div class="modal fade" id="delete-element-confirm-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_element_id">

                        <label class="col-sm-12 confirm-text" id="confirmText"></label>
                    </div>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">Cancel
                            </button>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var eventId = $('#h_event_id').val();

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
                    url: '',
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

            $('body').on('click', '#invite-company', function () {
                var company_id = $(this).data("id");
                var company_name = $(this).data("name");
                var company_focalpoint = $(this).data("focalpoint");
                $('#confirmTitle').html('Company Invitation');
                $('#curr_element_id').val(company_id);
                var confirmText = 'Are you sure you want to invite Company: ' + company_name + ' to focal point: ' + company_focalpoint + '?';
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });

            $('#delete-element-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var company_id = $('#curr_element_id').val();
                        var eventId = $('#h_event_id').val();

                        var url = "{{ route('eventAdminControllerInvite', [":company_id",":eventId"]) }}";
                        url = url.replace(':company_id', company_id);
                        url = url.replace(':eventId', eventId);

                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                var oTable = $('#laravel_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
