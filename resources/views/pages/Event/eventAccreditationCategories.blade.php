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
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{route('events')}}">
                                        <span>Events:</span>
                                    </a>
                                    <a class="url-nav" href="{{route('EventController.show',[$event->id])}}">
                                        {{$event->name}}
                                    </a> /
                                    Accreditation Categories
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
                                @role('super-admin')
                                <a href="javascript:void(0)" id="add-event-security-category" class="add-hbtn">
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
                                    <th>Accreditation Category</th>
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

    <!-- add new security officer modal-->
    <div class="modal fade" id="event-security-category-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="eventSecurityCategoryForm" name="eventSecurityCategoryForm" class="form-horizontal">
                        <input style="visibility: hidden" type="text" name="event_id" id="event_id"
                               value="{{$event->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col">
                                    <label>Event Accreditation Category</label>
                                    <div class="col-sm-12">
                                        <select id="accreditation_category_id" name="accreditation_category_id" required="">
                                            <option value="default">Please select Accreditation Category</option>
                                            @foreach ($accreditationCategories as $accreditationCategory)
                                                <option value="{{ $accreditationCategory->id }}"
                                                >{{ $accreditationCategory->name }}</option>
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
    <div class="modal fade" id="delete-event-security-category-confirm-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_security_category_id">
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
                    title: 'Event-Security-Categories',
                    exportOptions: {
                        columns: [ 1]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('eventAccreditationCategories',[$event->id]) }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'accreditation_category_id', name: 'accreditation_category_id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

            $('#add-event-security-category').click(function () {
                $('#btn-save').val("add-event-security-officer");
                $('#event-security-category-modal').trigger("reset");
                $('#modalTitle').html("Add Accrediation Category");
                $('#event-security-category-modal').modal('show');
            });

            $('body').on('click', '#delete-event-accreditation-category', function () {
                var security_category_id = $(this).data("id");
                $('#confirmTitle').html('Remove security category');
                $('#curr_security_category_id').val(security_category_id);
                var confirmText = 'Are you sure you want to remove this event accreditation category?';
                $('#confirmText').html(confirmText);
                $('#delete-event-security-category-confirm-modal').modal('show');
            });

            $('#delete-event-security-category-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);

                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var security_category_id = $('#curr_security_category_id').val();
                        var url = "{{ route('eventAccreditationCategoriesRemove', ":id") }}";
                        url = url.replace(':id', security_category_id);
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

        if ($("#eventSecurityCategoryForm").length > 0) {
            $("#eventSecurityCategoryForm").validate({
                rules: {
                    accreditation_category_id: {valueNotEquals: "default"}
                },

                submitHandler: function (form) {
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#eventSecurityCategoryForm').serialize(),
                        url: "{{ route('eventAccreditationCategoriesAdd') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#eventSecurityCategoryForm').trigger("reset");
                            $('#event-security-category-modal').modal('hide');
                            $('#btn-save').html('Save');
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
                        },
                        error: function (data) {
                            $('#event-security-category-modal').modal('hide');
                            $('#btn-save').html('Save');
                            $('#errorTitle').html('Error: Duplicate accrediation category');
                            $('#errorText').html('Cant insert duplicate accreditation category');
                            $('#error-pop-up-modal').modal('show');
                            console.log('Error:', data);
                            //$('#btn-save').html('Save Changes');
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
