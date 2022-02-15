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

    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/style.css') }}">

    <script src="{{ URL::asset('js/ag-grid/ag-grid-enterprise.min.js') }}"></script>
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
                <input type="hidden" id="subCompnay_status" value={{$subCompany_nav}} />
                <input type="hidden" id="event_id" value={{$eventId}} />
                <input type="hidden" id="company_id" value={{$companyId}} />
                <input type="hidden" id="addable_status" value={{$addable}} />
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-11">
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{ route('dataentrys', [$companyId, $eventId]) }} ">
                                        <span>My Events:</span>
                                    </a>
                                    {{$event_name}}
                                    / {{$company_name}}
                                    / Participants
                                </h4>
                            </div>
                            <div class="col-md-1 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel" title="Export to excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                </a>
                                @role('data-entry')
                                @if($event_status < 3)
                                    <a href="#" id="add-new-post" class="add-hbtn" title="Add">
                                        <i>
                                            <img src="{{ asset('images/add.png') }}" alt="Add">
                                        </i>
                                    </a>
                                @endif
                                @endrole
                            </div>
                        </div>
                        <div id="myGrid" class="ag-theme-alpine" style="height: 600px; width:100%;"></div>
                        @foreach ($dataTableColumns as $dataTableColumn)
                            <?php echo $dataTableColumn ?>
                        @endforeach
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
        //newlines
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
            columnDefs.push(
                {
                    field: jqueryarray[i].replace(/ /g, "_"),
                    headerName: jqueryarray[i],
                    sortable: true,
                    filter: 'agTextColumnFilter',
                    filterParams: {
                        buttons: ['apply', 'cancel', 'reset'],
                        closeOnApply: true
                    },
                    tooltipField: jqueryarray[i].replace(/ /g, "_"),
                    tooltipComponentParams: {color: '#ececec'},
                });
            expotColumns.push(i + 1);
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
            pinned: "right",
            cellRenderer: params => {
                var event_status = $('#h_event_status').val();
                const participent_id = params.data.id;
                const event_id = params.data.event_id;
                const company_id = params.data.company_id;
                let button = "";
                if (event_status < 3) {
                    switch (params.data.status) {
                        case 0:
                            url = "{{ route('participantAdd', [':participent_id',':company_id',':event_id']) }}";
                            url = url.replace(':participent_id', participent_id);
                            url = url.replace(':company_id', company_id);
                            url = url.replace(':event_id', event_id);
                            button += '<a href="' + url + '" data-toggle="tooltip"  id="edit-event" data-id="' + participent_id + '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                            button += '&nbsp;&nbsp;';
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
                tooltipComponent: 'customTooltip',
                filterParams: {newRowsAction: 'keep'}
            },
            columnDefs: columnDefs,

            debug: true,

            // enables pagination in the grid
            pagination: true,

            // sets 10 rows per page (default is 100)
            paginationPageSize: 2,
            onFirstDataRendered: onFirstDataRendered,
            rowSelection: 'single',
            tooltipShowDelay: 0,

            // set rowData to null or undefined to show loading panel by default
            rowData: null,
            onGridReady: onGridReady,
            animateRows: true,

            onFilterModified: function (params) {
                // note : set filter does not trigger this event if filtering is done using api
                filtercolId = params.column.colId; // save the colID
            },
            components: {
                //customTooltip: CustomTooltip,
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
                        align: 'left',
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
            //params.api.filter.onFilterChanged();
        }

        var statusValueGetter = function (params) {
            console.log('params');
            return params.getValue('status') == 1 ? "Active" : "InActive";
        };

        $('.export-to-excel').click(function () {
            gridOptions.api.exportDataAsExcel({
                sheetName: 'Paticipants',
                columnKeys: filtercolIds,
                fileName: 'participants.xlsx',
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

            var url = "{{ route('dataEntryParticipantsData', [":companyId",":eventId",":values"]) }}";
            url = url.replace(':companyId', companyId);
            url = url.replace(':eventId', eventId);
            url = url.replace(":values", '0');
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

        //////////end new lines
        $(document).ready(function () {
            var subCompany_status = $('#subCompnay_status').val();
            if (subCompany_status == 0) {
                $('#subsidiaries_nav').hide();
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //var jqueryarray = <?php echo json_encode($dataTableColumns); ?>;
            // var myColumns = [];
            // var i = 0;
            // myColumns.push({data: "id", name: "id", 'visible': false});
            // var expotColumns = [];
            // while (i < jqueryarray.length) {
            //     myColumns.push({data: jqueryarray[i].replace(/ /g, "_"), name: jqueryarray[i].replace(/ /g, "_")});
            //     expotColumns.push(i+1);
            // 	i++;
            // }
            // myColumns.push({data: "identifier", name: "identifier"});
            // myColumns.push({data: "image", name: "image", orderable: "false"});
            // myColumns.push({data: "status", name: "status"});
            // myColumns.push({data: "action", name: "action", orderable: "false"});
            // expotColumns.push(i+1);
            // expotColumns.push(i+3);
            // $('#laravel_datatable').DataTable({
            //     dom: 'lBfrtip',
            //     buttons: [{
            //         extend: 'excelHtml5',
            //         title: 'DataEntry-Participants',
            //         exportOptions: {
            //             columns: expotColumns
            //         }
            //     }],

            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         //url: 'dataentry-participants',
            //         url: "{{ route('dataEntryParticipants',[$companyId,$eventId]) }}",
            //         type: 'GET',
            //     },
            //     columns: myColumns,
            //     order: [[0, 'desc']]
            // });

            // $('.export-to-excel').click(function () {
            //     $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            // });

            $('#add-new-post').click(function () {
                var addableStatus = $('#addable_status').val();
                if (addableStatus == 1) {
                    var url = "{{route('participantAdd',[0,$companyId,$eventId])}}";
                    window.location.href = url;
                }
                if (addableStatus == 2) {
                    $('#errorTitle').html('Error: Adding');
                    $('#errorText').html('No accreditation categories size defined yet');
                    $('#error-pop-up-modal').modal('show');
                }
                if (addableStatus == 3) {
                    $('#errorTitle').html('Error: Adding');
                    $('#errorText').html('Accreditation size is not approved yet from event admin');
                    $('#error-pop-up-modal').modal('show');
                }
                if (addableStatus == 0) {
                    $('#errorTitle').html('Error: Adding');
                    $('#errorText').html('you have reached the max size of participants');
                    $('#error-pop-up-modal').modal('show');
                }
                // $('#btn-save').val("create-post");
                // $('#post_id').val('');
                // $('#postForm').trigger("reset");
                // $('#postCrudModal').html("Add New Post");
                //$('#ajax-crud-modal').modal('show');
            });
            ///new lines
            $('body').on('click', '.ag-icon-previous', function () {
                var value = $('.ag-paging-number').html();
            });
            $(document).on('click', '.ag-standard-button', function () {
                var value = $(this).html();
                value = value.replace(/\s/g, '');
                if (value == "Apply") {
                    $('#filtersButton').show();
                } else {
                    if (value == "Reset") {
                        $('#filtersButton').click();
                        $('#filtersButton').hide();
                    }
                }
            });

            $('body').on('click', '.ag-icon-next', function () {
                var value = $('.ag-paging-number').html();
                var size = 0;
                if (value % 5 == 0) {
                    if (value == (allData.length / 2)) {
                        var size = value / 5;
                        filters = gridOptions.api.getFilterModel();
                        nameFilter = size;
                        nameFilter = nameFilter + buildFilters(filters);
                        var $eventIdd = $('#h_event_id').val();
                        var companyId = $('#company_id').val();
                        var eventId = $('#event_id').val();
                        var url = "{{ route('dataEntryParticipantsData', [":companyId",":eventId",":values"]) }}";
                        url = url.replace(':companyId', companyId);
                        url = url.replace(':eventId', eventId);
                        url = url.replace(":values", nameFilter);
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                var newdata = allData.concat(data.templates);
                                gridOptions.api.setRowData(newdata);
                                allData = newdata;
                                var page = parseInt(value);
                            });

                        gridOptions.api.refreshCells({force: true});
                    }
                }
            });
            $('body').on('click', '#filtersButton', function () {
                var hi = "";
                filters = gridOptions.api.getFilterModel();
                var nameFilter = 0;
                nameFilter = nameFilter + buildFilters(filters);
                data = nameFilter;
                var $eventIdd = $('#h_event_id').val();
                var companyId = $('#company_id').val();
                var eventId = $('#event_id').val();
                var url = "{{ route('dataEntryParticipantsData', [":companyId",":eventId",":values"]) }}";
                url = url.replace(':companyId', companyId);
                url = url.replace(':eventId', eventId);
                url = url.replace(":values", data);
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        gridOptions.api.setRowData(data.templates);
                        totalSize = data.size;
                        $('#total_count').html('Total pages count: ' + data.size);
                        allData = data.templates;
                        $('.ag-icon-first').click();
                    });

                gridOptions.api.refreshCells({force: true});
                if (filters != null) {
                    gridOptions.api.setFilterModel(filters);
                }
                $('#filtersButton').hide();
            });

            function getCondition($condition) {
                var result = "0";
                switch ($condition) {
                    case "contains":
                        result = "1";
                        return result;
                        break;
                    case "notContains":
                        result = "2";
                        return result;
                        break;
                    case "equals":
                        result = "3";
                        return result;
                        break;
                    case "notEqual":
                        result = "4";
                        return result;
                        break;
                    case "startsWith":
                        return result;
                        result = "5";
                        break;
                    case "endsWith":
                        return result;
                        result = "6";
                        break;
                }
                return result;
            }

            function buildFilters(mfilters) {
                var returnFilters = "";
                var nameFilter = "";
                var i = 0;
                while (i < filtercolIds.length) {
                    if (mfilters[filtercolIds[i]] != null) {
                        if (mfilters[filtercolIds[i]].operator != null) {
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + filtercolIds[i];
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + "C";
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + getCondition(mfilters[filtercolIds[i]].condition1.type);
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + mfilters[filtercolIds[i]].condition1.filter;
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + mfilters[filtercolIds[i]].operator;
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + getCondition(mfilters[filtercolIds[i]].condition2.type);
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + mfilters[filtercolIds[i]].condition2.filter;
                        } else {
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + filtercolIds[i];
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + "N";
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + getCondition(mfilters[filtercolIds[i]].type);
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter + mfilters[filtercolIds[i]].filter;
                        }
                    }

                    i++;
                }
                returnFilters = nameFilter;
                return returnFilters;
            }

            //// end new lines
        });
    </script>
@endsection
