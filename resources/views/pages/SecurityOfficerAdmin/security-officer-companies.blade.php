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
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                        <input type="hidden" id="h_event_id" value={{$eventid}}>
                            <div class="col-md-11">
                                <h4 class="card-title">
                                    <a class="url-nav" href="{{ route('event-admin') }} ">
                                        <span>My Events:</span>
                                    </a>
                                    {{$event_name}} / Companies
                                </h4>
                            </div>
                            <div class="col-md-1 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel" title="Export to excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                </a>
                            </div>
                        </div>
                        <div id="myGrid" class="ag-theme-alpine" style="height: 600px; width:100%;"></div>
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
        var filtercolIds = ['name', 'category','country','city','webiste','telephone','focal_point','status'];
        var filtercolId = "";
        var allData = "";
        var totalSize = 0;
        const columnDefs = [
            {field: "id", headerName: "Company ID", hide: true},
            {field: "event_id", headerName: "Event ID", hide: true},
            {
                field: "name", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'name',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "category", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'category',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "country", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'country',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "city", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'city',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "website", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'website',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "telephone", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'telephone',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "focal_point", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'focal_point',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "status", sortable: true, filter: 'agTextColumnFilter',
                filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                // cellRenderer: params => {
                //     return params.value == 1 ? "Active" : "InActive";
                // },
                cellStyle: params => {
                    if(params.value == "Active"){
                        return  {color: 'green'};
                    }else{
                        if(params.value == "InActive"){
                            return  {color: 'red'} ;
                        }else{
                            return {color: 'orange'}
                        }
                    }
                    //return params.value == 'Active' ? {color: 'green'} : {color: 'red'};
                },
                valueGetter: params => {
                    if(params.data.status == 1){
                        return "Active";
                    }else{
                        if(params.data.status == 0){
                            return "InActive"
                        }else{
                            return "Invited"
                        }
                    }
                    //return params.data.status == 1 ? "Active" : "InActive";
                },
            },
            {
                field: "Actions",
                pinned:"right",
                cellRenderer: params => {
                    //var event_status = $('#h_event_status').val();
                    const company_id = params.data.id;
                    const event_id = params.data.event_id;
                    let button = "";
                    var url = "";
                    url = "{{ route('securityOfficerCompanyParticipants', [':company_id',':event_id']) }}";
                    url = url.replace(':company_id', company_id);
                    url = url.replace(':event_id', event_id);
                    button += '<a href="' + url + '" id="company-participant" data-toggle="tooltip" data-original-title="Delete" data-id="' + company_id +  '" title="Participants"><i class="fas fa-users"></i></a>';

                    return button;
                }
            },
        ];

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

            onFilterModified : function(params){
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
            var makeCol = gridOptions.columnApi.getColumn("focal_point");
            makeCol.colDef.headerName="Focal Point";
            var makeCol = gridOptions.columnApi.getColumn("name");
            makeCol.colDef.headerName="Company Name";
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
                sheetName: 'Companies',
                columnKeys: ['name', 'category','country','city','webiste','telephone','focal_point','status'],
                fileName: 'companies.xlsx',
            });
        });

        // setup the grid after the page has finished loading
        document.addEventListener('DOMContentLoaded', () => {
            const gridDiv = document.querySelector('#myGrid');
            new agGrid.Grid(gridDiv, gridOptions);
            data = gridOptions.api.getFilterModel();
            var $eventIdd = $('#h_event_id').val();
            var url = '{{ route('securityOfficerCompaniesData',[':id',':values']) }}';
            url = url.replace(":id",$eventIdd);
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

        //////////end new lines

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $('#laravel_datatable').DataTable({
            //     dom: 'lBfrtip',
            //     buttons: [{
            //         extend: 'excelHtml5',
            //         title: 'Event-Companies',
            //         exportOptions: {
            //             columns: [2, 3, 4, 5, 6, 7, 8, 9]
            //         }
            //     }],

            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         url: "{{ route('securityOfficerCompanies', $eventid) }}",
            //         type: 'GET',
            //     },
            //     columns: [
            //         {data: 'id', name: 'id', 'visible': false},
            //         {data: 'event_id', name: 'event_id', 'visible': false},
            //         {data: 'name', name: 'name'},
            //         {data: 'category', name: 'category'},
            //         {data: 'country', name: 'country'},
            //         {data: 'city', name: 'city'},
            //         {data: 'website', name: 'website'},
            //         {data: 'telephone', name: 'telephone'},
            //         {data: 'focal_point', name: 'focal_point'},
            //         {
            //             data: 'status', render: function (data) {
            //                 if (data == 1) {
            //                     return "<p style='color: green'>Active</p>"
            //                 } else {
            //                     if (data == 0) {
            //                         return "<p style='color: red'>InActive</p>"
            //                     } else {
            //                         return "<p style='color: orange'>Invited</p>"
            //                     }
            //                 }
            //             }
            //         },
            //         {data: 'action', name: 'action', orderable: false},
            //     ],
            //     order: [[0, 'desc']]
            // });

            // $('.export-to-excel').click(function () {
            //     $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            // });

            $('#add-new-company').click(function () {
                $('#btn-save').val("create-company");
                $('#company_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Company");
            });

        //////new lines
        $('body').on('click', '.ag-icon-previous', function () {
            var value = $('.ag-paging-number').html();
        });
        $(document).on('click', '.ag-standard-button', function () {
            var value = $(this).html();
            value = value.replace(/\s/g, '');
            if(value == "Apply"){
                $('#filtersButton').show();
            }else{
                if(value == "Reset"){
                    $('#filtersButton').click();
                    $('#filtersButton').hide();
                }
            }
        });

        $('body').on('click', '.ag-icon-next', function () {
            var value = $('.ag-paging-number').html();
            var size = 0;
            if(value % 5 == 0){
                if(value == (allData.length/2)){
                    var size = value / 5;
                    filters = gridOptions.api.getFilterModel();
                    nameFilter = size;
                    nameFilter = nameFilter + buildFilters(filters);
                    var $eventIdd = $('#h_event_id').val();
                    var url = '{{ route('securityOfficerCompaniesData',[':id',':values']) }}';
                    url = url.replace(":id",$eventIdd);
                    url = url.replace(":values",nameFilter);
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
            var url = '{{ route('securityOfficerCompaniesData',[':id',':values']) }}';
            url = url.replace(":id",$eventIdd);
            url = url.replace(":values",data);
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
        function buildFilters(mfilters){
            var returnFilters = "";
            var nameFilter = "";
            var i =0;
            while(i < filtercolIds.length){
                    if (mfilters[filtercolIds[i]] != null) {
                        if (mfilters[filtercolIds[i]].operator != null) {
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter +  filtercolIds[i];
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter +  "C";
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter +  getCondition(mfilters[filtercolIds[i]].condition1.type);
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
                            nameFilter = nameFilter +  filtercolIds[i];
                            nameFilter = nameFilter + ",";
                            nameFilter = nameFilter +  "N";
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
                // function addFilterCol(colName){
        //     var i =0;
        //     var exist = false;
        //     while(i <filtercolIds.length){
        //         if(filtercolIds[i] == colName){
        //             exist = true;
        //             i = filtercolId.size;
        //         }
        //         i++;
        //     }
        //     if(!exist){
        //         filtercolIds.push(colName);
        //     }
        // }
        // function removeFilterCol(colName){
        //     if(filtercolIds.length > 0){
        //         const index = filtercolIds.indexOf(colName);
        //         if (index > -1) {
        //             filtercolIds.splice(index, 1); // 2nd parameter means remove one item only
        //         }
        //     }
        // }

            ////end new lines
        });
    </script>
@endsection
