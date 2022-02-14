@extends('main')
@section('subtitle',' Registration Forms')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/style.css') }}">

    <script src="{{ URL::asset('js/ag-grid/ag-grid-enterprise.min.js') }}"></script>
    <script src="{{ URL::asset('js/templates/CustomTooltip.js') }}"></script>
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
                            <div class="col-md-8">
                                <p class="card-title">Registration Forms</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                <a href="javascript:void(0)" id="add-new-template" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                            </div>
                        </div>

                        <div id="myGrid" class="ag-theme-alpine" style="height: 600px; width:100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="template-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="templateForm" name="templateForm" class="form-horizontal">
                        <input type="hidden" name="template_id" id="template_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Registration Form Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" minlength="5" maxlength="50"
                                       placeholder="enter Registration Form Name" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-12">
                                <select id="status" name="status" required="">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
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

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_template_id">
                        <input type="hidden" id="mode_id">
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
        // specify the columns
        var filters;
        var allData = "";
        var totalSize = 0;
        const columnDefs = [
            {field: "id", headerName: "Template ID", hide: true},
            {
                field: "name", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'name',
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
                    return params.value == 'Active' ? {color: 'green'} : {color: 'red'};
                },
                valueGetter: params => {
                    return params.data.status == 1 ? "Active" : "InActive";
                },
            },
            {
                field: "Actions",
                cellRenderer: params => {
                    const template_id = params.data.id;
                    let button = '<a href="javascript:void(0)" id="edit-template" data-id="' + template_id + '"title="Edit"><i class="fas fa-edit"></i></a>';
                    button += '&nbsp;&nbsp;';

                    var url = "{{ route('templateFields', [':template_id']) }}";
                    url = url.replace(':template_id', template_id);

                    button += '<a href="' + url + '" id="template-fields" data-id="' + template_id + '" title="Fields"><i class="far fa-list-alt"></i></a>';
                    button += '&nbsp;&nbsp;';

                    if (params.data.is_locked == 1) {
                        if (params.data.can_unlock == 1) {
                            button += '<a href="javascript:void(0);" id="unLock-template" data-toggle="tooltip" data-original-title="Unlock" data-id="' + template_id + '" title="Un-Lock"><i class="fas fa-unlock"></i></a>';
                        }
                    } else {
                        button += '<a href="javascript:void(0);" id="lock-template" data-toggle="tooltip" data-original-title="Lock" data-id="' + template_id + '" title="Lock"><i class="fas fa-lock"></i></a>';
                    }
                    button += '&nbsp;&nbsp;';
                    if (params.data.status == 1) {
                        button += '<a href="javascript:void(0);" id="deActivate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' + template_id + '" title="Deactivate"><i class="fas fa-ban"></i></a>';
                    } else {
                        button += '<a href="javascript:void(0);" id="activate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' + template_id + '" title="Activate"><i class="fas fa-check-circle"></i></a>';
                    }
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

            // enables pagination in the grid
            pagination: true,

            enableCellTextSelection: true,

            // sets 10 rows per page (default is 100)
            paginationPageSize: 2,
            onFirstDataRendered: onFirstDataRendered,
            rowSelection: 'single',
            tooltipShowDelay: 0,

            // set rowData to null or undefined to show loading panel by default
            rowData: null,
            onGridReady: onGridReady,
            animateRows: true,

            components: {
                customTooltip: CustomTooltip,
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
            params.api.sizeColumnsToFit();
            params.api.setDomLayout('autoHeight');
            if (filters != null) {
                params.api.setFilterModel(filters);
            }
        }

        function onGridReady(params) {
            if (filters != null) {
                params.api.setFilterModel(filters);
            }
        }

        $('.export-to-excel').click(function () {
            gridOptions.api.exportDataAsExcel({
                sheetName: 'templates',
                columnKeys: ['name', 'status'],
                fileName: 'templates.xlsx',
            });
        });

        // setup the grid after the page has finished loading
        document.addEventListener('DOMContentLoaded', () => {
            const gridDiv = document.querySelector('#myGrid');
            new agGrid.Grid(gridDiv, gridOptions);
            data = gridOptions.api.getFilterModel();
            fetch('{{ route('templatesData1',"0") }}')
                .then(response => response.json())
                .then(data => {
                    $('#total_count').html('Total pages count: ' + data.size);
                    totalSize = data.size;
                    gridOptions.api.setRowData(data.templates);
                    allData = data.templates;
                });
        });

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $('#add-new-template').click(function () {
            $('#btn-save').val("create-template");
            $('#template_id').val('');
            $('#templateForm').trigger("reset");
            $('#modalTitle').html("New Registration Form");
            $('#template-modal').modal('show');
        });

        $('body').on('click', '#edit-template', function () {
            var template_id = $(this).data('id');

            $.get('templateController/' + template_id + '/edit', function (data) {
                $('#name-error').hide();
                $('#modalTitle').html("Edit Registration Form");
                $('#btn-save').val("edit-template");
                $('#template-modal').modal('show');
                $('#template_id').val(data.id);
                $('#name').val(data.name);
                $('#status').val(data.status);
            })
        });

        $('body').on('click', '#activate-template', function () {
            var template_id = $(this).data("id");
            $('#confirmTitle').html('Activate Registration Form');
            $('#curr_template_id').val(template_id);
            $('#mode_id').val('1');
            var confirmText = 'Are you sure you want to activate this registration form?';
            $('#confirmText').html(confirmText);
            $('#confirmModal').modal('show');
        });

        $('body').on('click', '#deActivate-template', function () {
            var template_id = $(this).data("id");
            $('#confirmTitle').html('Deactivate Registration Form');
            $('#curr_template_id').val(template_id);
            $('#mode_id').val('0');
            var confirmText = 'Are you sure you want to deactivate this registration form?';
            $('#confirmText').html(confirmText);
            $('#confirmModal').modal('show');
        });

        $('body').on('click', '#lock-template', function () {
            var template_id = $(this).data("id");
            $('#confirmTitle').html('Lock Registration Form');
            $('#curr_template_id').val(template_id);
            $('#mode_id').val('3');
            var confirmText = 'Are you sure you want to lock this registration form?';
            $('#confirmText').html(confirmText);
            $('#confirmModal').modal('show');
        });

        $('body').on('click', '#unLock-template', function () {
            var template_id = $(this).data("id");
            $('#confirmTitle').html('Un-Lock Registration Form');
            $('#curr_template_id').val(template_id);
            $('#mode_id').val('2');
            var confirmText = 'Are you sure you want to unLock this registration form?';
            $('#confirmText').html(confirmText);
            $('#confirmModal').modal('show');
        });

        $(document).on('click', '.ag-standard-button', function () {
            var value = $(this).html();
            value = value.replace(/\s/g, '');
            if(value == "Apply"){
                $('#filtersButton').show();
            }else{
                $('#filtersButton').click();
                $('#filtersButton').hide();
            }
        });

        $('body').on('click', '.ag-icon-next', function () {
            var value = $('.ag-paging-number').html();
            var size = 0;
            if(value % 5 == 0){
                var size = value / 5;
                filters = gridOptions.api.getFilterModel();
                nameFilter = size;
                if (filters.name != null) {
                    if (filters.name.operator != null) {
                        nameFilter = nameFilter + ",";
                        nameFilter = nameFilter +  getCondition(filters.name.condition1.type);
                        nameFilter = nameFilter + ",";
                        nameFilter = nameFilter + filters.name.condition1.filter;
                        nameFilter = nameFilter + ",";
                        nameFilter = nameFilter + filters.name.operator;
                        nameFilter = nameFilter + ",";
                        nameFilter = nameFilter + getCondition(filters.name.condition2.type);
                        nameFilter = nameFilter + ",";
                        nameFilter = nameFilter + filters.name.condition2.filter;
                    } else {
                        nameFilter = nameFilter + ",";
                        nameFilter = nameFilter + getCondition(filters.name.type);
                        nameFilter = nameFilter + ",";
                        nameFilter = nameFilter + filters.name.filter;
                    }
                }
                var url = '{{ route('templatesData1',":id") }}';
                url = url.replace(":id",nameFilter);
                fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    var newdata = allData.concat(data.templates);
                                    gridOptions.api.setRowData(newdata);
                                    allData = newdata;
                                    var page = parseInt(value);
                                });

                            gridOptions.api.refreshCells({force: true});
            }else{
                //alert('i am here too' + value);
            }
        });


        $('body').on('click', '#filtersButton', function () {
            filters = gridOptions.api.getFilterModel();
            var nameFilter = 0;
            if (filters.name != null) {
                nameFilter = "0"
                if (filters.name.operator != null) {
                    nameFilter = nameFilter + ",";
                    nameFilter = nameFilter + getCondition(filters.name.condition1.type);
                    nameFilter = nameFilter + ",";
                    nameFilter = nameFilter + filters.name.condition1.filter;
                    nameFilter = nameFilter + ",";
                    nameFilter = nameFilter + filters.name.operator;
                    nameFilter = nameFilter + ",";
                    nameFilter = nameFilter + getCondition(filters.name.condition2.type);
                    nameFilter = nameFilter + ",";
                    nameFilter = nameFilter + filters.name.condition2.filter;
                } else {
                    nameFilter = nameFilter + ",";
                    nameFilter = nameFilter + getCondition(filters.name.type);
                    nameFilter = nameFilter + ",";
                    nameFilter = nameFilter + filters.name.filter;
                    alert(nameFilter);
                }
            }
            data = nameFilter;
            var url = "{{ route('templatesData1', ":id") }}";
            url = url.replace(':id', data);
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

        $('#confirmModal button').on('click', function (event) {
            var $button = $(event.target);

            $(this).closest('.modal').one('hidden.bs.modal', function () {
                if ($button[0].id === 'btn-yes') {
                    var template_id = $('#curr_template_id').val();
                    var mode_id = $('#mode_id').val();

                    var url = "{{ route('templateControllerChangeStatus', [':template_id',':mode_id']) }}";
                    url = url.replace(':template_id', template_id);
                    url = url.replace(':mode_id', mode_id);

                    if (mode_id == 0 || mode_id == 1) {
                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                data = gridOptions.api.getFilterModel();
                                fetch('{{ route('templatesData1',"0") }}')
                                    .then(response => response.json())
                                    .then(data => {
                                        //alert(data);
                                        gridOptions.api.setRowData(data.templates);
                                    });
                                gridOptions.api.refreshCells({force: true});
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    } else {
                        var url = "{{ route('templateControllerChangeLock', [':template_id',':mode_id']) }}";
                        url = url.replace(':template_id', template_id);
                        url = url.replace(':mode_id', mode_id);

                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                data = gridOptions.api.getFilterModel();
                                fetch('{{ route('templatesData1',"0") }}')
                                    .then(response => response.json())
                                    .then(data => {
                                        //alert(data);
                                        gridOptions.api.setRowData(data.templates);
                                    });
                                gridOptions.api.refreshCells({force: true});
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }

                }
            });
        });

        if ($("#templateForm").length > 0) {
            console.log('Sending...');
            $("#templateForm").validate({
                submitHandler: function (form) {
                    $('#btn-save').html('Sending..');

                    $.ajax({
                        data: $('#templateForm').serialize(),
                        url: "{{ route('templateController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#templateForm').trigger("reset");
                            $('#template-modal').modal('hide');
                            $('#btn-save').html('Save Changes');

                            fetch('{{ route('templatesData1',"0") }}')
                                .then(response => response.json())
                                .then(data => {
                                    //alert(data);
                                    gridOptions.api.setRowData(data.templates);
                                    totalSize = data.size;
                                    $('#total_count').html('Total pages count: ' + data.size);
                                    allData = data.templates;
                                    $('.ag-icon-first').click();
                                });
                            gridOptions.api.refreshCells({force: true});
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }
    </script>
@endsection
