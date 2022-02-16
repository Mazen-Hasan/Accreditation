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


    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/style.css') }}">

    <script src="{{ URL::asset('js/ag-grid/ag-grid-enterprise.min.js') }}"></script>
    <!-- <script src="{{ URL::asset('js/templates/CustomTooltip.js') }}"></script> -->
    <script src="{{ URL::asset('js/templates/ShowMoreComponent.js') }}"></script>
    <script src="{{ URL::asset('js/templates/PageCountComponent.js') }}"></script>
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
                <input type="hidden" id="accredit" name="accredit" value="{{$accredit}}"/>
                <input type="hidden" id="isChecked" name="isChecked" value="{{$checked}}"/>
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-8">
                                <p class="card-title">
                                    <a class="url-nav" href="{{ route('Selections') }} ">
                                        <span>Fulfillment Selections:</span>
                                    </a>
                                    / Details
                                </p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>

                                <a href="javascript:void(0)" id="generate" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Generate</span>
                                </a>
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
                        <div id="myGrid" class="ag-theme-alpine" style="height: 600px; width:100%;"></div>
                                    @foreach ($dataTableColumns as $dataTableColumn)
                                        <th><?php echo $dataTableColumn ?></th>
                                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorTitle">Generate badges</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="col-sm-12 confirm-text" id="confirmText">Please select at least one participant to generate badges</label>
                    </div>

                    <div class="row">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-yes">Ok
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

        //newlines
        var checkedItems = [];
        var filters;
        var filtercolIds = [];
        var filtercolId = "";
        var allData = "";
        var totalSize = 0;
        var jqueryarray = <?php echo json_encode($dataTableColumns); ?>;
        var expotColumns = [];
        const columnDefs = [];
        i = 0;
        columnDefs.push({field: "id", headerName: "Participant ID", hide: true});
        while (i < jqueryarray.length) {
            filtercolIds.push(jqueryarray[i].replace(/ /g, "_"));
            let  checkboxSelection = false;
            if(i === 0){
               checkboxSelection = true;
            }
            else{
                checkboxSelection = false;
            }
            columnDefs.push(
                {
                    field: jqueryarray[i].replace(/ /g, "_"), checkboxSelection: checkboxSelection, headerCheckboxSelection: checkboxSelection, headerName: jqueryarray[i] , sortable: true, filter: 'agTextColumnFilter', filterParams: {
                        buttons: ['apply', 'cancel', 'reset'],
                        closeOnApply: true
                    },
                    tooltipField:  jqueryarray[i].replace(/ /g, "_") ,
                    tooltipComponentParams: {color: '#ececec'},
                });
            expotColumns.push(i+1);
            i++;
        }
        filtercolIds.push('identifier');
        filtercolIds.push('status');
        columnDefs.push({
            field: "identifier", headerName: "identifier", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                buttons: ['apply', 'cancel', 'reset'],
                closeOnApply: true
            },
            cellRenderer: params => {
                return params.data.identifier;
            },
            valueGetter: params => {
                return params.data.identifier;
            },
        });
        columnDefs.push({
            field: "image", headerName: "image", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                buttons: ['apply', 'cancel', 'reset'],
                closeOnApply: true
            },
            cellRenderer: params => {
                image = '';
                image += "<img src='/badges/" + params.data.Personal_Image + "' alt='Personal' class='pic-img' style='margin-left:40px'>";
                return image;
            },
            valueGetter: params => {
                image = '';
                //image += '<img src="{{ asset("badges/'+params.data.Personal_Image + '")}}" alt="Personal" class="pic-img" style="margin-left:40px">';
                image += "<img src='{{ asset('badges/" + params.data.Personal_Image + "')}}' alt='Personal' class='pic-img' style='margin-left:40px'>";
                return image;
            },
        });
        columnDefs.push({
            field: "status", sortable: true, filter: 'agTextColumnFilter',
            filterParams: {
                buttons: ['apply', 'cancel', 'reset'],
                closeOnApply: true
            },
            cellRenderer: params => {
                switch (params.data.status) {
                    case 0:
                        return "Initiated";
                        break;
                    case 1:
                        return "Waiting Security Officer Approval";
                        break;
                    case 2:
                        return "Waiting Event Admin Approval";
                        break;
                    case 3:
                        return "Approved by security officer";
                        break;
                    case 4:
                        return "Rejected by security officer";
                        break;
                    case 5:
                        return "Rejected by event admin";
                        break;
                    case 6:
                        return "Approved by event admin";
                        break;
                    case 7:
                        return "Needs review and correction by security officer";
                        break;
                    case 8:
                        return "Needs review and correction by event admin";
                        break;
                    case 9:
                        return "Badge generated";
                        break;
                    case 10:
                        return "Badge printed";
                        break;
                }
            },
            valueGetter: params => {
                switch (params.data.status) {
                    case 0:
                        return "Initiated";
                        break;
                    case 1:
                        return "Waiting Security Officer Approval";
                        break;
                    case 2:
                        return "Waiting Event Admin Approval";
                        break;
                    case 3:
                        return "Approved by security officer";
                        break;
                    case 4:
                        return "Rejected by security officer";
                        break;
                    case 5:
                        return "Rejected by event admin";
                        break;
                    case 6:
                        return "Approved by event admin";
                        break;
                    case 7:
                        return "Needs review and correction by security officer";
                        break;
                    case 8:
                        return "Needs review and correction by event admin";
                        break;
                    case 9:
                        return "Badge generated";
                        break;
                    case 10:
                        return "Badge printed";
                        break;
                }
            },
        });
        columnDefs.push({
            field: "Actions",
            pinned:"right",
            cellRenderer: params => {
                var event_status = $('#h_event_status').val();
                const participent_id = params.data.id;
                const event_id = params.data.event_id;
                const company_id = params.data.company_id;
                let button = "";
                var url = "{{route('participantDetails', [':id'])}}";
                url = url.replace(':id', participent_id);
                button += '<a href="' + url + '" data-toggle="tooltip"  id="participant-details" data-id="' + participent_id + '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                button += '&nbsp;&nbsp;';
                if(event_status < 3){
                    switch(params.data.status){
                        case 2:
                            button += '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="' + participent_id + '" data-original-title="Edit" title="Approve"><i class="fas fa-vote-yea"></i></a>';
                            button += '&nbsp;&nbsp;';
                            button += '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' + participent_id + '" data-original-title="Edit" title="Reject"><i class="fas fa-ban"></i></a>';
                            button += '&nbsp;&nbsp;';
                            button += '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' + participent_id + '" data-original-title="Edit" title="Return for correction"><i class="far fa-window-close"></i></a>';
                            break;
                        case 1:
                            url = "{{ route('eventParticipantAdd', [':participent_id',':company_id',':event_id']) }}";
                            url = url.replace(':participent_id', participent_id);
                            url = url.replace(':company_id', company_id);
                            url = url.replace(':event_id', event_id);
                            button += '<a href="' + url + '" data-toggle="tooltip"  id="edit-event" data-id="' + participent_id + '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                            button += '&nbsp;&nbsp;';
                            break;
                        case 7:
                            url = "{{ route('eventParticipantAdd', [':participent_id',':company_id',':event_id']) }}";
                            url = url.replace(':participent_id', participent_id);
                            url = url.replace(':company_id', company_id);
                            url = url.replace(':event_id', event_id);
                            button += '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' + participent_id +  '" data-reason="' + params.data.security_officer_reject_reason + '" title="Reject reason"><i class="far fa-comment-alt"></i></a>';
                            button += '&nbsp;&nbsp;';
                            button += '<a href="' + url + '" data-toggle="tooltip"  id="edit-event" data-id="' + participent_id +  '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                            break;
                        case 8:
                            button += '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' + participent_id +  '" data-reason="' + params.data.event_admin_reject_reason + '" title="Reject reason"><i class="far fa-comment-alt"></i></a>';
                            break;
                        case 6:
                        case 3:
                            if (params.data.print_status == 0) {
                                button += '<a href="javascript:void(0);" id="generate-badge" data-toggle="tooltip" data-original-title="Generate" data-id="' + participent_id +  '" title="Generate"><i class="fas fa-cogs"></i></a>';
                                button += '&nbsp;&nbsp;';
                            } else {
                                printed = params.data.print_status == 2 ? 'printed' : '';
                                button += '<a href="javascript:void(0);" id="preview-badge" data-toggle="tooltip" data-original-title="Preview" data-id="' + participent_id + '" class="preview-badge"' + printed + '" title="Preview"><i class="far fa-eye"></i></a>';
                                button += '&nbsp;&nbsp;';
                            }
                            break;
                    }
                }
                return button;
            }
        });

        // let the grid know which columns and what data to use
        const gridOptions = {
            defaultColDef: {
                resizable: true,
                filterParams: {newRowsAction: 'keep'}
            },
            columnDefs: columnDefs,

            // enables pagination in the grid
            pagination: true,

            // sets 10 rows per page (default is 100)
            paginationPageSize: 2,
            onFirstDataRendered: onFirstDataRendered,
            rowSelection: 'multiple',
            onSelectionChanged: onSelectionChanged,
            suppressRowClickSelection: true,
            tooltipShowDelay: 0,

            // set rowData to null or undefined to show loading panel by default
            rowData: null,
            onGridReady: onGridReady,
            animateRows: true,

            onFilterModified : function(params){
                // note : set filter does not trigger this event if filtering is done using api
                filtercolId = params.column.colId; // save the colID
            },
            components: {
                ShowMoreComponent: ShowMoreComponent,
                PageCountComponent: PageCountComponent,
            },
            statusBar: {
                statusPanels: [
                    {
                        statusPanel: 'ShowMoreComponent',
                    },
                    {
                        statusPanel: 'PageCountComponent',
                        align:'left',
                    },
                ],
            },

        };

        function onFirstDataRendered(params) {
            //params.api.sizeColumnsToFit();
            autoSizeAll();
            params.api.setDomLayout('autoHeight');
            if (filters != null) {
                params.api.setFilterModel(filters);
            }
            gridOptions.api.refreshHeader();
        }

        function onGridReady(params) {
            if (filters != null) {
                params.api.setFilterModel(filters);
            }
        }

        $('.export-to-excel').click(function () {
            gridOptions.api.exportDataAsExcel({
                sheetName: 'Fulfillment Selections',
                columnKeys: filtercolIds,
                fileName: 'Fulfillment Selections.xlsx',
            });
        });

        // setup the grid after the page has finished loading
        document.addEventListener('DOMContentLoaded', () => {
            const gridDiv = document.querySelector('#myGrid');
            new agGrid.Grid(gridDiv, gridOptions);
            data = gridOptions.api.getFilterModel();
            var $eventIdd = $('#h_event_id').val();
            var companyId = $('#company_id').val();
            var eventId = $('#event_id').val();

            var url = "{{ route('getParticipantsData', [":companyId",":eventId",":values"]) }}";
            url = url.replace(':companyId', companyId);
            url = url.replace(':eventId', eventId);
            url = url.replace(":values",'0');
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    $('#total_count').html('Total pages count: ' + data.size);
                    totalSize = data.size;
                    gridOptions.api.setRowData(data.templates);
                    allData = data.templates;
                });
        });

        function autoSizeAll() {
            var allColumnIds = [];
            gridOptions.columnApi.getAllColumns().forEach(function (column) {
                allColumnIds.push(column.colId);
            });
            gridOptions.columnApi.autoSizeColumns(allColumnIds);
        }

        function onSelectionChanged(event) {
            var rowsCount = event.api.getSelectedNodes().length;
            checkedItems = [];
            var rows = event.api.getSelectedNodes();
            if(rowsCount > 0){
                for(var i=0; i< rowsCount; i++){
                    checkedItems.push(rows[i].data.id);
                }
            }
        }

        //////////end new lines



        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            {{--var company_id = $('#company_id').val();--}}
            {{--var jqueryarray = <?php echo json_encode($dataTableColumns); ?>;--}}
            {{--var myColumns = [];--}}
            {{--var i = 0;--}}
        	{{--var exportColumns = [];--}}
            {{--myColumns.push({data: "id", name: "id", 'visible': false});--}}
            {{--myColumns.push({data: "action", name: "action", orderable: false});--}}
            {{--myColumns.push({data: "status", name: "status"});--}}
        	{{--exportColumns.push(2);--}}
            {{--while (i < jqueryarray.length) {--}}
            {{--    myColumns.push({data: jqueryarray[i].replace(/ /g, "_"), name: jqueryarray[i].replace(/ /g, "_")});--}}
            {{--	exportColumns.push(i+3);--}}
            {{--    i++;--}}
            {{--}--}}
            {{--myColumns.push({data: "identifier", name: "identifier"});--}}
            {{--myColumns.push({data: "image", name: "image"});--}}
        	{{--exportColumns.push(i);--}}
            {{--var companyId = $('#company_id').val();--}}
            {{--var eventId = $('#event_id').val();--}}
            {{--var accredit = $('#accredit').val();--}}
            {{--var isChecked = $('#isChecked').val();--}}
            {{--$('#laravel_datatable').DataTable({--}}
            {{--    dom: 'lBfrtip',--}}
            {{--    buttons: [{--}}
            {{--        extend: 'excelHtml5',--}}
            {{--        title: 'Company-Participants',--}}
            {{--        exportOptions: {--}}
            {{--            columns: exportColumns--}}
            {{--        }--}}
            {{--    }],--}}
            {{--    processing: true,--}}
            {{--    serverSide: true,--}}
            {{--    ajax: {--}}
            {{--        url: '../../../../all-participants/' + eventId + '/' + companyId + '/' + accredit + '/' + isChecked,--}}
            {{--        type: 'GET',--}}
            {{--    },--}}
            {{--    columns: myColumns,--}}
            {{--    order: [[2, 'desc']],--}}
            {{--});--}}

            $('body').on('click', '#generate', function () {
                console.log(checkedItems);
                var staff = checkedItems;
                if (staff.length > 0) {
                    $('#loader-modal').modal('show');
                    $.ajax({
                        type: "post",
                        data: {staff: staff},
                        dataType: "json",
                        url: "{{ url('pdf-generate')}}",
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log(data);
                            window.open(data.file, '_blank');
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                        }
                    });
                }
                else{
                    $('#errorModal').modal('show');
                }
            });
        });
    </script>
@endsection
