@extends('main')
@section('subtitle',' Events')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ag-grid/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/events/style.css') }}">

    <script src="{{ URL::asset('js/ag-grid/ag-grid-enterprise.min.js') }}"></script>
    <script src="{{ URL::asset('js/events/CustomTooltip.js') }}"></script>
    <script src="{{ URL::asset('js/events/ShowMoreComponent.js') }}"></script>
    <script src="{{ URL::asset('js/events/PageCountComponent.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br> <br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-6">
                                <h4 class="card-title">Event Management</h4>
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
                                <a href="{{route('eventAdd')}}" id="add-new-post" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                                @endrole
                            </div>
                            <div class="col-md-2 align-content-md-center">
                                <label class="switch">
                                    <input type="checkbox" id="show-all">
                                    <span class="slider round"></span>
                                    <label class="customShowAll"> Show all</label>
                                </label>

                            </div>
                        </div>

                        <div id="myGrid" class="ag-theme-alpine" style="height: 600px;"></div>
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
                        <input type="hidden" id="event_id">
                        <input type="hidden" id="event_name">
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

    <div class="modal fade" id="logo-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">

                    <form id="logoUploadForm" name="logoUploadForm" class="form-horizontal  img-upload"
                          enctype="multipart/form-data" action="javascript:void(0)">
                        <div class="row">
                            <div class="col-md-5">
                                <label>New Logo</label>
                            </div>

                            <div class="col-md-4">
                                <div class="col-sm-12">
                                    <input type="file" id="file" name="file">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <button type="submit" id="btn-upload" value="Upload">Upload
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col">
                                    <label id="file_type_error"></label>
                                    <div style="background-color: #ffffff00!important;" class="progress">
                                        <div id="file-progress-bar" class="progress-bar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <form id="logoForm" name="logoForm" class="form-horizontal">
                        <input style="visibility: hidden" name="eventId" id="eventId">
                        <div class="form-group">
                            <div class="row"
                                 style="margin-left: 25%;justify-content: center; max-height: 100%; max-width: 50%; object-fit: fill">
                                <img id="logo" name="logo" src="" alt="Logo"
                                     style="width:200px;height:200px;">
                            </div>
                        </div>
                        <input style="visibility: hidden" type="text" name="logoName" id="logoName">
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

@endsection
@section('script')
    <script>
        // specify the columns
        var filtercolIds = ['name', 'size', 'organizer', 'template_name', 'event_type', 'event_start_date',
                    'event_end_date', 'accreditation_start_date', 'accreditation_end_date'];
        var filters;
        var allData = "";
        var totalSize = 0;
        const columnDefs = [
            {field: "id", headerName: "Event ID", hide: true},
            {
                field: "name", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'name',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: "logo", headerName: "Logo", sortable: false,
                cellRenderer: params => {
                    var image_path = "{{URL::asset('logo/')}}/";
                    return '<img class="event-logo-grid" src= ' + image_path + params.data.logo + '></img>';
                },
            },
            {field: 'size', headerName: "Size", sortable: true},
            {
                field: 'organizer', headerName: "Organizer", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'organizer',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: 'template_name', headerName: "Template", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'template_name',
                tooltipComponentParams: {color: '#ececec'},
            },
            {
                field: 'event_type', headerName: "Type", sortable: true, filter: 'agTextColumnFilter', filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                tooltipField: 'event_type',
                tooltipComponentParams: {color: '#ececec'},
            },
            {field: 'event_start_date', headerName: "Start", sortable: true},
            {field: 'event_end_date', headerName: "End", sortable: true},
            {
                field: 'accreditation_start_date',
                headerName: "Accreditation Start",
                sortable: true
            },
            {
                field: 'accreditation_end_date',
                headerName: "Accreditation End",
                sortable: true
            },
            {
                field: "status", headerName: "Status", sortable: true, filter: 'agTextColumnFilter',
                filterParams: {
                    buttons: ['apply', 'cancel', 'reset'],
                    closeOnApply: true
                },
                cellStyle: params => {
                    return params.data.status == '1' ? {color: 'green'} : {color: 'red'};
                },
                valueGetter: params => {
                    return params.data.status == 1 ? "Active" : "InActive";
                },
            },
            {
                field: "Actions",
                pinned: "right",
                cellRenderer: params => {
                    const event_id = params.data.id;
                    const logo = params.data.logo;
                    const status = params.data.status;
                    const approval_option = params.data.approval_option;
                    const name = params.data.name;

                    var url = "{{ route('EventController.show', [':event_id']) }}";
                    url = url.replace(':event_id', event_id);

                    let $button = '<a href="' + url + '" data-toggle="tooltip"  id="event-details" data-id="' + event_id + '" data-original-title="Details" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button += '&nbsp;&nbsp;';
                    if (status < 3) {
                        url = "{{ route('eventEdit', [':event_id']) }}";
                        url = url.replace(':event_id', event_id);

                        $button += '<a href="' + url + '" data-toggle="tooltip"  id="edit-event" data-id="' + event_id + '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $button += '&nbsp;&nbsp;';
                        $button += '<a href="javascript:void(0);" id="edit-logo" data-toggle="tooltip" data-id="' + event_id + '" data-name="' + name + '" data-l="' + logo + '" data-original-title="Edit" title="Edit Logo"><i class="far fa-image"></i></a>';
                        $button += '&nbsp;&nbsp;';
                    }

                    url = "{{ route('eventSecurityCategories', [':event_id']) }}";
                    url = url.replace(':event_id', event_id);

                    $button += '<a href="' + url + '" data-toggle="tooltip"  id="event-security-categories" data-id="' + event_id + '" data-original-title="Edit" title="Event security categories"><i class="fas fa-users-cog"></i></a>';
                    $button += '&nbsp;&nbsp;';

                    url = "{{ route('eventAdmins', [':event_id']) }}";
                    url = url.replace(':event_id', event_id);

                    $button += '<a href="' + url + '" data-toggle="tooltip"  id="event-admins" data-id="' + event_id + '" data-original-title="Edit" title="Event admins"><i class="fas fa-user-cog"></i></a>';
                    if (approval_option != 1) {
                        $button += '&nbsp;&nbsp;';

                        url = "{{ route('eventSecurityOfficers', [':event_id']) }}";
                        url = url.replace(':event_id', event_id);

                        $button += '<a href="' + url + '" data-toggle="tooltip"  id="event-security-officers" data-id="' + event_id + '" data-original-title="Edit" title="Event security officers"><i class="fas fa-user-shield"></i></a>';
                    }
                    $button += '&nbsp;&nbsp;';

                    url = "{{ route('eventAccreditationCategories', [':event_id']) }}";
                    url = url.replace(':event_id', event_id);

                    $button += '<a href="' + url + '" data-toggle="tooltip"  id="event-accreditation-categories" data-id="' + event_id + '" data-original-title="Edit" title="Event accreditation categories"><i class="fas fa-users"></i></a>';
                    $button += '&nbsp;&nbsp;';
                    if (status == 3) {
                        $button += '<a href="javascript:void(0);" id="complete-event" data-toggle="tooltip" data-id="' + event_id + '" data-name="' + name + '" data-original-title="Edit" title="Complete Events"><i class="far fa-calendar-check"></i></a>';
                        $button += '&nbsp;&nbsp;';
                    }
                    return $button;
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
                        align: 'left',
                    },
                ],
            },
        };

        function onFirstDataRendered(params) {
            autoSizeAll();
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
                sheetName: 'events',
                columnKeys: ['name', 'size', 'organizer', 'template_name', 'event_type', 'event_start_date',
                    'event_end_date', 'accreditation_start_date', 'accreditation_end_date'],
                fileName: 'events.xlsx',
            });
        });

        // setup the grid after the page has finished loading
        document.addEventListener('DOMContentLoaded', () => {
            console.log($('#show-all').prop("checked"));
            const gridDiv = document.querySelector('#myGrid');
            new agGrid.Grid(gridDiv, gridOptions);
            data = gridOptions.api.getFilterModel();
            fetch('{{ route('eventsData',["0","0"]) }}')
                .then(response => response.json())
                .then(data => {
                    $('#total_count').html('Total pages count: ' + data.size);
                    totalSize = data.size;
                    gridOptions.api.setRowData(data.events);
                    allData = data.events;
                });
        });

        function autoSizeAll() {
            var allColumnIds = [];
            gridOptions.columnApi.getAllColumns().forEach(function (column) {
                allColumnIds.push(column.colId);
            });

            gridOptions.columnApi.autoSizeColumns(allColumnIds);
        }

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
                    var url = '{{ route('eventsData',[':showAll',':values']) }}';
                    url = url.replace(":values", nameFilter);
                    var showAll = $('#show-all').prop("checked") == true ? '1' : '0';
                    url = url.replace(":showAll", showAll);
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            var newdata = allData.concat(data.events);
                            gridOptions.api.setRowData(newdata);
                            allData = newdata;
                            var page = parseInt(value);
                        });

                    gridOptions.api.refreshCells({force: true});
                }
            }
        });

        $('body').on('click', '#filtersButton', function () {
            filters = gridOptions.api.getFilterModel();
            var nameFilter = 0;
            nameFilter = nameFilter + buildFilters(filters);
            data = nameFilter;
            var url = '{{ route('eventsData',[':showAll',':values']) }}';
            url = url.replace(":values", data);
            var showAll = $('#show-all').prop("checked") == true ? '1' : '0';
            url = url.replace(":showAll", showAll);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    gridOptions.api.setRowData(data.events);
                    totalSize = data.size;
                    $('#total_count').html('Total pages count: ' + data.size);
                    allData = data.events;
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

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
            });

            $('body').on('click', '#edit-logo', function () {
                $('#file').val('');
                $("#file-progress-bar").width('0%');
                $("#file_type_error").html('');
                $("#logoName").val('');
                $('#eventId').val($(this).data("id"));
                let eventName = $(this).data("name");
                $('#modalTitle').html("Edit " + eventName + " Logo");
                let imag = $(this).data("l");
                // server
                {{--let image_path = "{{URL::asset('storage/badges/')}}/";--}}
                // local
                let image_path = "{{URL::asset('logo/')}}/";
                $('#logo').attr('src', image_path + imag);
                $('#logo-modal').modal('show');
            });

            $("#file").change(function () {
                let allowedTypes = ['image/png', 'image/jpeg'];
                let file = this.files[0];
                let fileType = file.type;
                if (!allowedTypes.includes(fileType)) {
                    $("#file-progress-bar").width('0%');
                    $('#file_type_error').removeClass('info').addClass('error');
                    $("#file_type_error").html('Please choose a valid file (jpeg, png)');
                    $("#file").val('');
                    $("#btn-upload").attr('disabled', true);
                    return false;
                } else {
                    $("button").removeAttr('disabled');
                    $("#file_type_error").html('');
                    $("#file-progress-bar").width('0%');
                }
            });

            $('.img-upload').submit(function (e) {

                var file = $('#file').val();
                if (file == '') {
                    $('#file_type_error').removeClass('info').addClass('error');
                    $("#file_type_error").html('Please choose file');
                    return false;
                }

                $('#btn-upload').html('Sending..');
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    xhr: function () {
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (element) {
                            if (element.lengthComputable) {
                                var percentComplete = ((element.loaded / element.total) * 100);
                                $("#file-progress-bar").width(percentComplete + '%');
                                $("#file-progress-bar").html(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },

                    type: 'POST',
                    url: "{{ route('uploadLogo')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    beforeSend: function () {
                        $("#file-progress-bar").width('0%');
                    },

                    success: (data) => {
                        $('#file_type_error').removeClass('error').addClass('info');
                        $("#file_type_error").html('File uploaded successfully');
                        $('#btn-upload').html('Upload');
                        $("#logoName").val(data.data.fileName);
                        console.log(data.data.fileName);
                    },

                    error: function (data) {
                        $("#file_type_error").html('Error uploading file');
                        console.log(data);
                    }
                });
            });

            $('body').on('click', '#complete-event', function () {
                $('#event_id').val($(this).data("id"));
                $('#event_name').val($(this).data("name"));
                var eventName = $('#event_name').val();
                $('#confirmTitle').html('Event completion');
                var confirmText = 'Are you sure you want to complete event: ' + eventName + '?';
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });

            $('#delete-element-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var eventId = $('#event_id').val();
                        var url = "{{ route('eventComplete', [":eventId"]) }}";
                        url = url.replace(':eventId', eventId);
                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                $('#filtersButton').click();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

            if ($("#logoForm").length > 0) {
                $("#logoForm").validate({

                    submitHandler: function (form) {
                        $('#btn-save').html('Sending..');
                        var url = '{{ route('eventsData',[':showAll','0']) }}';
                        url = url.replace(":showAll", $('#show-all').prop("checked"));

                        $.ajax({
                            data: $('#logoForm').serialize(),
                            url: "{{ route('updateLogo') }}",
                            type: "POST",
                            dataType: 'json',

                            success: function (data) {
                                $('#logoForm').trigger("reset");
                                $('#logo-modal').modal('hide');
                                $('#btn-save').html('Save Changes');
                                $('#filtersButton').click();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-save').html('Save Changes');
                            }
                        });
                    }
                })
            }

            $('#show-all').on('change', function() {
                $('#filtersButton').click();
            });
        });
    </script>
@endsection
