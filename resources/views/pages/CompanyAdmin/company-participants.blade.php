@extends('main')
@section('subtitle',' Participants')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/print.min.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
    <script src="{{ URL::asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('js/print.min.js') }}"></script>
@endsection
@section('custom_navbar')
            @if($subCompany_nav == 1)
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
                @endif
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <input type="hidden" id="data_values" name="data_values" value=""/>
                <input type="hidden" id="company_id" name="company_id" value="{{$companyId}}"/>
                <input type="hidden" id="event_id" name="event_id" value="{{$eventId}}"/>
                <input type="hidden" id="subCompnay_status" value={{$subCompany_nav}} />
            	<input type="hidden" id="addable_status" value={{$addable}} />
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-7">
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{ route('company-admin') }} ">
                                        <span>My Events:</span>
                                    </a>
                                    {{$event_name}}
                                    / {{$company_name}}
                                    / Participants
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
                                @role('company-admin')
<!--                                 <a href="{{route('templateForm',[0,$companyId,$eventId])}}" id="add-new-post" class="add-hbtn"> -->
                                @if($event_status < 3)
                                <a href="#" id="add-new-post" class="add-hbtn">
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
                        <!-- <input type="hidden" id="action_button"> -->
                        <label class="col-sm-12 confirm-text" id="confirmText-new"></label>
                        <textarea id="reason" style="margin-bottom:10px"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel-new">Ok
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes-new">Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="badge-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="badgeTitle"></h5>
                    <input type="hidden" id="print_staff_id">
                    <div class="col-sm-4">
                        <button type="button" data-dismiss="modal" id="btn-print">Print</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                    </div>
                    <div class="row">
                        <img id="badge" src="" alt="Badge">
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
            var companyId = $('#company_id').val();
            var eventId = $('#event_id').val();
            var subCompany_status = $('#subCompnay_status').val();
            if (subCompany_status == 0) {
                $('#subsidiaries_nav').hide();
            }
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
            $('#laravel_datatable').DataTable({
                dom: 'lBrtip',
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
                    //url: '../../company-participants/' + companyId + '/' + eventId,
                    url: "{{ route('companyParticipants',[$companyId,$eventId]) }}",
                    type: 'GET',
                },
                columns: myColumns,
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

            $('#add-new-post').click(function () {
                var addableStatus = $('#addable_status').val();
                if(addableStatus == 1){
                    var url = "{{route('templateForm',[0,$companyId,$eventId])}}";
                    window.location.href = url;
                }
                if(addableStatus == 2){
                    $('#errorTitle').html('Error: Adding');
                    $('#errorText').html('No accreditation categories size defined yet');
                    $('#error-pop-up-modal').modal('show');
                }
                if(addableStatus == 3){
                    $('#errorTitle').html('Error: Adding');
                    $('#errorText').html('Accreditation size is not approved yet from event admin');
                    $('#error-pop-up-modal').modal('show');
                }
                if(addableStatus == 0){
                    $('#errorTitle').html('Error: Adding');
                    $('#errorText').html('you have reached the max size of participants');
                    $('#error-pop-up-modal').modal('show');
                }
                // $('#btn-save').val("create-post");
                // $('#post_id').val('');
                // $('#postForm').trigger("reset");
                // $('#postCrudModal').html("Add New Post");
            });


            $('body').on('click', '#generate-badge', function () {
                var staff_id = $(this).data("id");
                $('#print_staff_id').val(staff_id);

                var url = "{{ route('badgeGenerate', ":staff_id") }}";
                url = url.replace(':staff_id', staff_id);
                
                $.ajax({
                    type: "get",
                    // url: "badge-generate/" + staff_id,
                    url: url,
                    success: function (data) {
                        $('#badge-modal').modal('show');
                        var imag = data;
                        var image_path = "{{URL::asset('badges/')}}/";

                        $('#badge').attr('src', image_path + imag);
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('body').on('click', '#preview-badge', function () {
                var staff_id = $(this).data("id");
                $('#print_staff_id').val(staff_id);

                var url = "{{ route('badgePreview', ":staff_id") }}";
                url = url.replace(':staff_id', staff_id);
                
                $.ajax({
                    type: "get",
                    // url: "badge-preview/" + staff_id,
                    url: url,
                    success: function (data) {
                        console.log($('#btn-print').attr('class'));
                        $('#badge-modal').modal('show');
                        var imag = data;
                        var image_path = "{{URL::asset('badges/')}}/";

                        $('#badge').attr('src', image_path + imag);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('body').on('click', '#btn-print', function () {
                var staff_id = $('#print_staff_id').val();

                var url = "{{ route('badgePrint', ":staff_id") }}";
                url = url.replace(':staff_id', staff_id);
                
                $.ajax({
                    type: "get",
                    // url: "badge-print/" + staff_id,
                    url: url,
                    success: function (data) {
                        $('#badge-modal').modal('show');
                        printJS($('#badge').attr('src'), 'image');
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                        return false;
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('body').on('click', '#send_request', function () {
                var post_id = $(this).data("id");
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle').html('Send Participation Request');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('sendRequest');
                var confirmText = "Are you sure you want to send event participation?";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });

            $('body').on('click', '#show_reason', function () {
                var post_id = $(this).data("id");
                var reason = $(this).data("reason");
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle-new').html('Reject Reason');
                $('#curr_element_id-new').val(post_id);
                // $('#action_button').val('approve');
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
                        if (action_button == 'sendRequest') {
                            var staffId = $('#curr_element_id').val();
                            var url = "{{ route('companyAdminControllerSendRequest', ":id") }}";
                            url = url.replace(':id', staffId);
                            $.ajax({
                                type: "get",
                                url: url,
                                success: function (data) {
                                    var oTable = $('#laravel_datatable').dataTable();
                                    $('#send-approval-request').hide();
                                    //$('#add-new-post').hide();
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

                        var url = "{{ route('eventAdminControllerRejectToCorrect', [":staffId",":reason"]) }}";
                        url = url.replace(':staffId', staffId);
                        url = url.replace(':reason', reason);
                        
                        $.ajax({
                            type: "get",
                            // url: "../../eventAdminController/RejectToCorrect/" + staffId + "/" + reason,
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
