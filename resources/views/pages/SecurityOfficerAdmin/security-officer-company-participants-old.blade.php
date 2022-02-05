@extends('main')
@section('subtitle',' Participants')
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
                <input type="hidden" id="data_values" name="data_values" value=""/>
                <input type="hidden" id="company_id" name="company_id" value="{{$company_id}}"/>
                <input type="hidden" id="event_id" name="event_id" value="{{$event_id}}"/>
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-8">
                                <p class="card-title">Company / Participants</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                @role('company-admin')
                                <a href="{{route('templateForm',0)}}" id="add-new-post" class="add-hbtn">
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
                                    @foreach ($dataTableColumns as $dataTableColumn)
                                        <th><?php echo $dataTableColumn ?></th>
                                @endforeach
                               	 	<th>ID</th>
                                	<th>Image</th>
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

    <div class="modal fade" id="delete-element-confirm-modal-new" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle-new"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_element_id-new">
                        <label class="col-sm-12 confirm-text" id="confirmText-new"></label>
                        <textarea id="reason" style="margin-bottom:10px"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel-new">Cancel
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes-new">Return</button>
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
            var jqueryarray = <?php echo json_encode($dataTableColumns); ?>;
            var myColumns = [];
            var i = 0;
            myColumns.push({data: "id", name: "id", 'visible': false});
            var expotColumns = [];
        	while (i < jqueryarray.length) {
                myColumns.push({data: jqueryarray[i].replace(/ /g, "_"), name: jqueryarray[i].replace(/ /g, "_")});
                expotColumns.push(i+1);
            	i++;
            }
        	myColumns.push({data: "identifier", name: "identifier"});
            myColumns.push({data: "image", name: "image", orderable: "false"});
            myColumns.push({data: "status", name: "status"});
            myColumns.push({data: "action", name: "action", orderable: "false"});
			expotColumns.push(i+1);
        	expotColumns.push(i+3);
            var companyId = $('#company_id').val();
            var eventId = $('#event_id').val();

            var url = "{{ route('securityOfficerCompanyParticipants', [":companyId",":eventId"]) }}";
            url = url.replace(':companyId', companyId);
            url = url.replace(':eventId', eventId);

            $('#laravel_datatable').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Company-Participants',
                    exportOptions: {
                        columns: expotColumns
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    // url: '../../security-officer-company-participants/' + companyId + '/' + eventId,
                    url: url,
                    type: 'GET',
                },
                columns: myColumns,
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
            });

            $('body').on('click', '#approve', function () {
                var post_id = $(this).data("id");
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle').html('Approve Participation Request');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('approve');
                var confirmText = "Are you sure you want to approve event participation request?";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });

            $('body').on('click', '#reject', function () {
                var post_id = $(this).data("id");
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle').html('Reject Participation Request');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('reject');
                var confirmText = "Are you sure you want to reject event participation request?";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });

            $('body').on('click', '#reject_with_correction', function () {
                var post_id = $(this).data("id");
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle-new').html('Participation Request needs review and correction');
                $('#curr_element_id-new').val(post_id);
                $('#reason').val('');
                var confirmText = "Insert Reason:";
                $('#confirmText-new').html(confirmText);
                $('#delete-element-confirm-modal-new').modal('show');
            });

            $('body').on('click', '#show_reason', function () {
                var post_id = $(this).data("id");
                var reason = $(this).data("reason");
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle-new').html('Reject Reason');
                $('#curr_element_id-new').val(post_id);
                $('#reason').val(reason);
                $('#btn-yes-new').hide();
                var confirmText = "Reason:";
                $('#confirmText-new').html(confirmText);
                $('#delete-element-confirm-modal-new').modal('show');
            });

            $('#delete-element-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var post_id = $('#curr_element_id').val();
                        var action_button = $('#action_button').val();
                        if (action_button == 'approve') {
                            var staffId = $('#curr_element_id').val();

                            var url = "{{ route('securityOfficerAdminControllerApprove', ":staffId") }}";
                            url = url.replace(':staffId', staffId);

                            $.ajax({
                                type: "get",
                                // url: "../../securityOfficerAdminController/Approve/" + staffId,
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
                        if (action_button == 'reject') {

                            var staffId = $('#curr_element_id').val();

                            var url = "{{ route('securityOfficerAdminControllerReject', ":staffId") }}";
                            url = url.replace(':staffId', staffId);

                            $.ajax({
                                type: "get",
                                // url: "../../securityOfficerAdminController/Reject/" + staffId,
                                url: url,
                                success: function (data) {
                                    var oTable = $('#laravel_datatable').dataTable();
                                    $('#send-approval-request').hide();
                                    $('#add-new-post').hide();
                                    oTable.fnDraw(false);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    }
                });
            });
            $('#delete-element-confirm-modal-new button').on('click', function (event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes-new') {
                        var staffId = $('#curr_element_id-new').val();
                        var reason = $('#reason').val();

                        var url = "{{ route('securityOfficerAdminControllerRejectToCorrect', [":staffId",":reason"]) }}";
                        url = url.replace(':staffId', staffId);
                        url = url.replace(':reason', reason);

                        $.ajax({
                            type: "get",
                            // url: "../../securityOfficerAdminController/RejectToCorrect/" + staffId + "/" + reason,
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
