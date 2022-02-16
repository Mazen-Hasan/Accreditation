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
                            <div class="col-md-10">
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{route('events')}}">
                                        <span>Events:</span>
                                    </a>
                                    <a class="url-nav" href="{{route('EventController.show',[$event->id])}}">
                                        {{$event->name}}
                                    </a> /
                                    Admins
                                </h4>
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
                                @role('super-admin')
                                @if($event->status < 3)
                                <a href="javascript:void(0)" id="add-event-admin" class="add-hbtn" title="Add">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                                @endif
                                @endrole
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
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

    <!-- add new admin modal-->
    <div class="modal fade" id="event-admin-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="eventAdminForm" name="eventAdminForm" class="form-horizontal">
                        <input style="visibility: hidden" type="text" name="event_id" id="event_id"
                               value="{{$event->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col">
                                    <label>Event admin</label>
                                    <div class="col-sm-12">
                                        <select id="admin_id" name="admin_id" required="">
                                            <option value="default">Please select Event Admin</option>
                                            @foreach ($eventAdmins as $eventAdmin)
                                                <option value="{{ $eventAdmin->user_id }}"
                                                >{{ $eventAdmin->user_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-12">
                                <button type="submit" id="btn-save" value="create">Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- delete confirm modal -->
    <div class="modal fade" id="delete-event-admin-confirm-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_event_admin_id">
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
	<div class="modal fade" id="error-pop-up-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="col-sm-12 confirm-text" id="errorText"></label>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-ok">OK
                            </button>
                        </div>
                        <div class="col-sm-4">
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


            $('#laravel_datatable').DataTable({
                dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Event-Admins',
                    exportOptions: {
                        columns: [ 1]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('eventAdmins',[$event->id]) }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'admin_id', name: 'admin_id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

        	var oTable = $('#laravel_datatable').DataTable();

            $('#search').on('keyup', function () {
                oTable.search(this.value).draw();
            });

            $('#add-event-admin').click(function () {
                $('#btn-save').val("add-event-admin");
                $('#event-admin-modal').trigger("reset");
                $('#modalTitle').html("Add Event Admin");
                $('#event-admin-modal').modal('show');
            });

            $('body').on('click', '#delete-event-admin', function () {
                var event_admin_id = $(this).data("id");
                $('#confirmTitle').html('Remove event admin');
                $('#curr_event_admin_id').val(event_admin_id);
                var confirmText = 'Are you sure you want to remove this event admin?';
                $('#confirmText').html(confirmText);
                $('#delete-event-admin-confirm-modal').modal('show');
            });

            $('#delete-event-admin-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);

                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var event_admin_id = $('#curr_event_admin_id').val();
                        var url = "{{ route('eventAdminsRemove', ":id") }}";
                        url = url.replace(':id', event_admin_id);
                        $('#loader-modal').modal('show');
                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                $('#loader-modal').modal('hide');
                                var oTable = $('#laravel_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                $('#loader-modal').modal('hide');
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });
        });

        if ($("#eventAdminForm").length > 0) {
            $("#eventAdminForm").validate({
                rules: {
                    admin_id: {valueNotEquals: "default"}
                },

                submitHandler: function (form) {
                    $('#loader-modal').modal('show');
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#eventAdminForm').serialize(),
                        url: "{{ route('eventAdminsAdd') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#eventAdminForm').trigger("reset");
                            $('#event-admin-modal').modal('hide');
                            $('#btn-save').html('Save');
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
                        },
                        error: function (data) {
                            //console.log('Error:', data);
                            $('#loader-modal').modal('hide');
                        	$('#event-admin-modal').modal('hide');
                            $('#errorTitle').html('Error: Duplicate Event admins');
                            $('#errorText').html('Cant insert duplicate aevent admin');
                            $('#error-pop-up-modal').modal('show');
                            $('#btn-save').html('Save');
                        }
                    });
                }
            })
        }

        jQuery.validator.addMethod("valueNotEquals",
            function (value, element, params) {
                return params !== value;
            }, " Please select a value");
    </script>
@endsection
