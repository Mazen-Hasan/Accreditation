@extends('main')
@section('subtitle','Company Accreditation Size')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
<script src="{{ URL::asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('js/jszip.min.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-7">
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{ route('event-admin') }} ">
                                        <span>My Events:</span>
                                    </a>
                                    <a class="url-nav" href="{{route('eventCompanies',[$eventId])}}">
                                        <span>{{$event_name}}</span>
                                    </a>
                                    <a class="url-nav" href="{{route('eventCompanyParticipants',[$companyId ,$eventId])}}">
                                        <span>/ {{$company_name}}</span>
                                    </a>
                                      / : Size ({{$company_size}}) /
                                    Accreditation Size Management
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
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                @if($event_status < 3)
                                <a href="javascript:void(0)" id="add-new-post" class="add-hbtn">
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
                                    <th>Accreditation Category</th>
                                    <th>Size</th>
                                    @if($status == 0)
                                        <th>Action</th>
                                    @endif
                                    @if($status != 0)
                                        <th>Status</th>
                                @endif
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        @if($status == 1)
                            <a href="javascript:void(0)" class="ha_btn" id="approve" style="width:200px">
                                Approve
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="postCrudModal"></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="company_id" id="company_id" value="{{$companyId}}">
                    <input type="hidden" name="event_id" id="event_id" value="{{$eventId}}">
                    <input type="hidden" name="status" id="status" value="{{$status}}">
                    <input type="hidden" name="remaining_size" id="remaining_size" value="{{$remaining_size}}">
                    <input type="hidden" name="post_id" id="post_id" value="">
                    <input type="hidden" name="prev_size" id="prev_size" value="0">
                    <div class="form-group">
                        <label>Accreditation Category</label>
                        <div class="col-sm-12">
                            <select id="accredit_cat_id" name="accredit_cat_id" value="" required="">
                                @foreach ($accreditationCategorys as $accreditationCategory)
                                    <option value="{{ $accreditationCategory->key }}"
                                            @if ($accreditationCategory->key == 1)
                                            selected="selected"
                                        @endif
                                    >{{ $accreditationCategory->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Size</label>
                        <div class="col-sm-12">
                            <input type="number" min="1" id="size" name="size" value="" required="">
                            <p style="color:red" id=error_message></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12">
                        <button id="edit-size" value="create">Save
                        </button>
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
                        <input type="hidden" id="curr_size" name="curr_size">
                        <input type="hidden" id="action_button">
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
                    <div class="modal-footer">
                        <div class="col-sm-12">
                            <button type="submit" class="btn-cancel" data-dismiss="modal" value="create">OK
                            </button>
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
            var companyId = $('#company_id').val();
            var eventId = $('#event_id').val();
            var status = $('#status').val();
            $('#laravel_datatable').DataTable({
            	dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Accreditation-Categories',
                    exportOptions: {
                        columns: [1, 2]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    //url: '../company-accreditation-size/' + eventId,
                    //url: '../../company-accreditation-size-new/' + companyId + '/' + eventId,
                	url: "{{route('companyAccreditCat',[$companyId,$eventId])}}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'size', name: 'size'},
                    {data: 'action', name: 'action', orderable: false}
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

            $('#add-new-post').click(function () {
                remaining_size = parseInt($('#remaining_size').val());
                if (remaining_size > 0) {
                    $('#error_message').text('');
                    $('#error_message').hide();
                    $('#btn-save').val("create-post");
                    $('#post_id').val('0');
                    $('#size').val('0');
                    $('#prev_size').val('0');
                    $('#postForm').trigger("reset");
                    $('#postCrudModal').html("Add New Accreditation Category");
                    $('#ajax-crud-modal').modal('show');
                    $('#accredit_cat_id').attr('disabled', false);
                } else {
                    $('#errorTitle').html('Error: Max Size');
                    $('#errorText').html('you have reached accreditation categories max size');
                    $('#error-pop-up-modal').modal('show');
                    //alert('you reached the max size');
                }
            });
            $('body').on('click', '#edit-company-accreditation', function () {
                var post_id = $(this).data('id');
                var url = "{{ route('companyControllerEditCompanyAccreditSize', ':id') }}";
                url = url.replace(':id', post_id);
                $.get(url, function (data) {
                    $('#name-error').hide();
                    $('#email-error').hide();
                    $('#postCrudModal').html("Edit Company Accreditation Category");
                    $('#btn-save').val("edit-post");
                    $('#error_message').text('');
                    $('#error_message').hide();
                    $('#ajax-crud-modal').modal('show');
                    $('#post_id').val(data.id);
                    $('#size').val(data.size);
                    $('#prev_size').val(data.size);
                    $('#accredit_cat_id').val(data.accredit_cat_id);
                    $('#accredit_cat_id').attr('disabled', 'disabled');
                })
            });

            $('body').on('click', '#delete-company-accreditation', function () {
                var post_id = $(this).data("id");
                var prev_size = $(this).data("size");
                $('#curr_size').val(prev_size);
                //alert(prev_size);
                $('#confirmTitle').html('Delete Company Accreditation');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('delete');
                var confirmText = 'Are You sure want to delete ?';
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });
            $('body').on('click', '#edit-size', function () {
                $('#error_message').text('');
                $('#error_message').hide();
                var accredit_cat_id = $('#accredit_cat_id').val();
                var size = $('#size').val();
                var post_id = $('#post_id').val();
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                var prevsize = parseInt($('#prev_size').val());
                var remaining_size = parseInt($('#remaining_size').val());
                remaining_size = remaining_size + prevsize;
                if (parseInt(size) > parseInt(remaining_size) || parseInt(size) <= 0) {
                    $('#error_message').text('Size has to be more than 0 and less than ' + remaining_size);
                    $('#error_message').show();
                } else {
                    $('#loader-modal').modal('show');
                    var url = "{{ route('companyControllerStoreCompanyAccrCatSize',[ ':id',':accredit_cat_id',':size',':company_id',':eventId']) }}";
                    url = url.replace(':id', post_id);
                    url = url.replace(':accredit_cat_id', accredit_cat_id);
                    url = url.replace(':size', size);
                    url = url.replace(':company_id', company_id);
                    url = url.replace(':eventId', eventId);
                    $.ajax({
                        type: "get",
                        // url: "../../companyController/storeCompanyAccrCatSize/" + post_id + "/" + accredit_cat_id + "/" + size + "/" + company_id + "/" + eventId,
                    	url:url,
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#ajax-crud-modal').modal('hide');
                            //alert('i am here');
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
                            var remaining_size = parseInt($('#remaining_size').val());
                            var prev_size = parseInt($('#prev_size').val());
                            var inserted_size = data.size;
                            var new_remaining_size = remaining_size + prev_size - inserted_size;
                            $('#remaining_size').val(new_remaining_size);
                            $('#prev_size').val('0');
                            $('#error_message').text('');
                            $('#error_message').hide();
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#ajax-crud-modal').modal('hide');
                            $('#errorTitle').html('Error: Duplicate accrediation category');
                            $('#errorText').html('Cant insert duplicate accreditation category size');
                            $('#error-pop-up-modal').modal('show');
                            //console.log('Error:', data);
                        }
                    });
                }
            });
            $('body').on('click', '#approve', function () {
                var post_id = $('#id').val();
                //alert(post_id);
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle').html('Approve Accreditation Category Sizes');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('approve');
                var confirmText = "Are You sure you want to Approve Accreditation Category sizes?";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });
            $('#delete-element-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var post_id = $('#curr_element_id').val();
                        var action_button = $('#action_button').val();
                        if (action_button == 'delete') {
                            var url = "{{ route('companyControllerDestroyCompanyAccreditCat', ':id') }}";
                            url = url.replace(':id', post_id);
                            $('#loader-modal').modal('show');
                            $.ajax({
                                type: "get",
                                // url: "../../companyController/destroyCompanyAccreditCat/" + post_id,
                            	url:url,
                                success: function (data) {
                                    $('#loader-modal').modal('hide');
                                    var oTable = $('#laravel_datatable').dataTable();
                                    oTable.fnDraw(false);
                                    var remaining_size = parseInt($('#remaining_size').val());
                                    var inserted_size = parseInt($('#curr_size').val());
                                    //alert(inserted_size);
                                    var new_remaining_size = remaining_size + inserted_size;
                                    $('#remaining_size').val(new_remaining_size);
                                    $('#curr_size').val('0');
                                },
                                error: function (data) {
                                    $('#loader-modal').modal('hide');
                                    console.log('Error:', data);
                                }
                            });
                        }
                        if (action_button == 'approve') {
                            var company_id = $('#company_id').val();
                            var eventId = $('#event_id').val();
                            var url = "{{ route('companyControllerApprove', [':company_id',':eventId']) }}";
                            url = url.replace(':company_id', company_id);
                            url = url.replace(':eventId', eventId);
                            $('#loader-modal').modal('show');
                            $.ajax({
                                type: "get",
                                //url: "../../companyController/Approve/" + company_id + "/" + eventId,
                            	url:url,
                                success: function (data) {
                                    $('#loader-modal').modal('hide');
                                    var oTable = $('#laravel_datatable').dataTable();
                                    $('#send-approval-request').hide();
                                    $('#add-new-post').hide();
                                    $('#approve').hide();
                                    oTable.fnDraw(false);
                                },
                                error: function (data) {
                                    $('#loader-modal').modal('hide');
                                    console.log('Error:', data);
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
