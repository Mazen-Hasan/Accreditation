@extends('main')
@section('subtitle',' Registration Forms')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/style.css') }}">

    <script src="{{ URL::asset('js/ag-grid/ag-grid-enterprise.min.js') }}"></script>
    <script src="{{ URL::asset('js/ag-grid/CustomTooltip.js') }}"></script>

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
                            <div class="col-md-7">
                                <p class="card-title">Registration Forms</p>
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
                                <a href="javascript:void(0)" id="add-new-template" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                            </div>
                        </div>

                        <div id="myGrid" class="ag-theme-alpine" style="height: 600px; width:100%;"></div>
                        <div>
                                <a href="javascript:void(0)" id="filtersButton" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">See more</span>
                                </a>
                        </div>
                        <script type="text/javascript" charset="utf-8">

                        </script>
                        {{--                        <div class="table-responsive">--}}
                        {{--                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">--}}
                        {{--                                <thead>--}}
                        {{--                                <tr>--}}
                        {{--                                    <th>ID</th>--}}
                        {{--                                    <th>Registration Form Name</th>--}}
                        {{--                                    <th>Locked</th>--}}
                        {{--                                    <th style="color: black">Status</th>--}}
                        {{--                                    <th>Action</th>--}}
                        {{--                                </tr>--}}
                        {{--                                </thead>--}}
                        {{--                                <tbody>--}}
                        {{--                                </tbody>--}}
                        {{--                            </table>--}}
                        {{--                        </div>--}}
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
        const columnDefs = [
            {field: "id", headerName: "Template ID",hide: true },
            {field: "name", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel','reset'],
                    closeOnApply: true},
                tooltipField: 'name',
                tooltipComponentParams: { color: '#ececec' },
            },
            {field: "status", sortable: true, filter: 'agTextColumnFilter',
                filterParams: {
                    buttons: ['apply', 'cancel','reset'],
                    closeOnApply: true
                },
                // cellRenderer: params => {
                //     return params.value == 1 ? "Active" : "InActive";
                // },
                cellStyle: params => {
                    return params.value == 'Active' ? {color: 'green'} : {color: 'red'};
                },
                valueGetter:  params => {
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
                        if(params.data.can_unlock == 1){
                            button += '<a href="javascript:void(0);" id="unLock-template" data-toggle="tooltip" data-original-title="Unlock" data-id="' + template_id + '" title="Un-Lock"><i class="fas fa-unlock"></i></a>';
                        }
                    }
                    else {
                        button += '<a href="javascript:void(0);" id="lock-template" data-toggle="tooltip" data-original-title="Lock" data-id="' + template_id + '" title="Lock"><i class="fas fa-lock"></i></a>';
                    }
                    button += '&nbsp;&nbsp;';
                    if (params.data.status == 1) {
                        button += '<a href="javascript:void(0);" id="deActivate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' + template_id + '" title="Deactivate"><i class="fas fa-ban"></i></a>';
                    } else {
                        button += '<a href="javascript:void(0);" id="activate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' + template_id + '" title="Activate"><i class="fas fa-check-circle"></i></a>';
                    }
                    return  button ;
                }
            },
        ];


        // let the grid know which columns and what data to use
        const gridOptions = {
            defaultColDef: {
                resizable: true,
                tooltipComponent: 'customTooltip',
                filterParams: { newRowsAction: 'keep'}
            },
            columnDefs: columnDefs,

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

            components: {
                customTooltip: CustomTooltip,
            },

        };

        function onFirstDataRendered(params) {
            params.api.sizeColumnsToFit();
            params.api.setDomLayout('autoHeight');
            if(filters != null){
                params.api.setFilterModel(filters);
            }
        }

        function onGridReady(params){
            if(filters != null){
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
                sheetName: 'templates',
                columnKeys: ['name','status'],
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
                    gridOptions.api.setRowData(data);
                });
        });

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $('#laravel_datatable thead tr')
            //     .clone(true)
            //     .addClass('filters')
            //     .appendTo('#laravel_datatable thead');

            {{--$('#laravel_datatable').DataTable({--}}

            {{--    fixedHeader: true,--}}
            {{--    initComplete: function () {--}}
            {{--        var api = this.api();--}}
            {{--        // For each column--}}
            {{--        api--}}
            {{--            .columns([1, 2, 3])--}}
            {{--            .eq(0)--}}
            {{--            .each(function (colIdx) {--}}
            {{--                // Set the header cell to contain the input element--}}
            {{--                var cell = $('.filters th').eq(--}}
            {{--                    $(api.column(colIdx).header()).index()--}}
            {{--                );--}}
            {{--                var title = $(cell).text();--}}
            {{--                $(cell).html('<input type="text" placeholder="' + title + '" />');--}}

            {{--                // On every keypress in this input--}}
            {{--                $(--}}
            {{--                    'input',--}}
            {{--                    $('.filters th').eq($(api.column(colIdx).header()).index())--}}
            {{--                )--}}
            {{--                    .off('keyup change')--}}
            {{--                    .on('keyup change', function (e) {--}}
            {{--                        e.stopPropagation();--}}

            {{--                        // Get the search value--}}
            {{--                        $(this).attr('title', $(this).val());--}}
            {{--                        var regexr = '({search})'; //$(this).parents('th').find('select').val();--}}

            {{--                        var cursorPosition = this.selectionStart;--}}
            {{--                        // Search the column for that value--}}
            {{--                        api--}}
            {{--                            .column(colIdx)--}}
            {{--                            .search(--}}
            {{--                                this.value != ''--}}
            {{--                                    ? regexr.replace('{search}', '(((' + this.value + ')))')--}}
            {{--                                    : '',--}}
            {{--                                this.value != '',--}}
            {{--                                this.value == ''--}}
            {{--                            )--}}
            {{--                            .draw();--}}

            {{--                        $(this)--}}
            {{--                            .focus()[0]--}}
            {{--                            .setSelectionRange(cursorPosition, cursorPosition);--}}
            {{--                    });--}}
            {{--            });--}}
            {{--    },--}}
            {{--    dom: 'lBrtip',--}}
            {{--    buttons: [{--}}
            {{--        extend: 'excelHtml5',--}}
            {{--        title: 'Registration-Forms',--}}
            {{--        exportOptions: {--}}
            {{--            columns: [1, 2, 3]--}}
            {{--        }--}}
            {{--    }],--}}

            {{--    processing: true,--}}
            {{--    serverSide: true,--}}
            {{--    ajax: {--}}
            {{--        url: "{{ route('templateController.index') }}",--}}
            {{--        type: 'GET',--}}
            {{--    },--}}
            {{--    columns: [--}}
            {{--        {data: 'id', name: 'id', 'visible': false},--}}
            {{--        {data: 'name', name: 'name'},--}}
            {{--        {--}}
            {{--            "data": "is_locked",--}}
            {{--            "render": function (val) {--}}
            {{--                return val == 1 ? "Yes" : "No";--}}
            {{--            }--}}
            {{--        },--}}
            {{--        {--}}
            {{--            data: 'status', render: function (data) {--}}
            {{--                if (data == 1) {--}}
            {{--                    return "<p style='color: green'>Active</p>"--}}
            {{--                } else {--}}
            {{--                    return "<p style='color: red'>InActive</p>"--}}
            {{--                }--}}
            {{--            }--}}
            {{--        },--}}
            {{--        {data: 'action', name: 'action', orderable: false}--}}
            {{--    ],--}}
            {{--    order: [[0, 'desc']]--}}
        });

        // $('.export-to-excel').click(function () {
        //     $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
        // });

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

        $('body').on('click', '#filtersButton', function () {
            var hi = "";
            //var soso = gridOptions.api.getFilterModel();
            //var soso1 = soso[0];
            //alert(soso);
            filters = gridOptions.api.getFilterModel();
            //alert(data);
            if(filters.name != null){
                if(filters.name.operator != null){
                    alert(filters.name.operator);
                }else{
                    alert(filters.name.filterType); 
                }
            }
            data = 8;
            var url = "{{ route('templatesData1', ":id") }}";
                url = url.replace(':id', data);
            //fetch('{{ route('templatesData1',"") }}')
            //alert(url);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                      gridOptions.api.setRowData(data);
                });
            gridOptions.api.refreshCells({force : true});
            if(filters != null){
                gridOptions.api.setFilterModel(filters);
            }
        });

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
                                        gridOptions.api.setRowData(data);
                                    });
                                gridOptions.api.refreshCells({force : true});
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
                                        gridOptions.api.setRowData(data);
                                    });
                                gridOptions.api.refreshCells({force : true});
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
                                    gridOptions.api.setRowData(data);
                                });
                            gridOptions.api.refreshCells({force : true});
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
