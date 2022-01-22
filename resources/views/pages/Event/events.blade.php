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
                            <div class="col-md-7">
                                <h4 class="card-title">Event Management</h4>
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
                                @role('super-admin')
                                <a href="{{route('eventAdd')}}" id="add-new-post" class="add-hbtn">
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
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Organizer</th>
                                    <th>Registration Form</th>
                                    <th>Type</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Accredit Start</th>
                                    <th>Accredit End</th>
                                    <th style="color: black">Status</th>
                                    <th>Logo</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div>
                        <a href="javascript:void(0)" id="showAll" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="show all">
                                    </i>
                                    <span class="dt-hbtn" id="showAllSpan">Show all</span>
                                </a>
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
                        <input  style="visibility: hidden" name="eventId" id="eventId">
                        <div class="form-group">
                            <div class="row" style="margin-left: 25%;justify-content: center; max-height: 100%; max-width: 50%; object-fit: fill">
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var showStatus = 1;
            var murl = "{{ route('EventController.index') }}";

            $('#laravel_datatable').DataTable({
                dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Events',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: murl,
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'size', name: 'size'},
                    {data: 'organizer', name: 'organizer'},
                    {data: 'template_name', name: 'template_name'},
                    {data: 'event_type', name: 'event_type'},
                    {data: 'event_start_date', name: 'event_start_date'},
                    {data: 'event_end_date', name: 'event_end_date'},
                    {data: 'accreditation_start_date', name: 'accreditation_start_date'},
                    {data: 'accreditation_end_date', name: 'accreditation_end_date'},
                    {
                        data: 'status', render: function (data) {
                            if (data == 1) {
                                return "<span style='color: green'>Active</span>"
                            } else {
                                if(data == 2){
                                    return "<span style='color: red'>InActive</span>"
                                }else{
                                    if(data == 3){
                                        return "<span style='color: black'>Finished</span>"
                                    }else{
                                        return "<span style='color: orange'>Archived</span>"
                                    }
                                }
                            }
                        }
                    },
                    {
                        "data": "logo",
                        "render": function (val) {
                            // var image_path = "{{URL::asset('storage/logo/')}}/";
                            var image_path = "{{URL::asset('logo/')}}/";
                            return "<img src= " + image_path + val + "></img>";
                        }
                    },
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
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
                let allowedTypes = ['image/png','image/jpeg'];
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
                if(file=='')
                {
                    $('#file_type_error').removeClass('info').addClass('error');
                    $("#file_type_error").html('Please choose file');
                    return false;
                }

                $('#btn-upload').html('Sending..');
                e.preventDefault();
                var formData = new FormData(this);
                // formData.append('event_id', $('#event-id').val());
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
                        // this.reset();
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
                //alert(eventName);
                $('#confirmTitle').html('Event completion');
                var confirmText = 'Are you sure you want to complete event: ' + eventName + '?';
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });

            $('#delete-element-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var eventName = $('#event_name').val();
                        var eventId = $('#event_id').val();
                        var url = "{{ route('eventComplete', [":eventId"]) }}";
                        url = url.replace(':eventId', eventId);
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

            if ($("#logoForm").length > 0) {
                $("#logoForm").validate({

                    submitHandler: function (form) {
                        $('#btn-save').html('Sending..');
                        $.ajax({
                            data: $('#logoForm').serialize(),
                            url: "{{ route('updateLogo') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function (data) {
                                $('#logoForm').trigger("reset");
                                $('#logo-modal').modal('hide');
                                $('#btn-save').html('Save Changes');
                                var oTable = $('#laravel_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-save').html('Save Changes');
                            }
                        });
                    }
                })
            }

            $('body').on('click', '#showAll', function () {
                if(showStatus == 1){
                    murl = "{{ route('eventsShowall', [":id"]) }}";
                    murl = murl.replace(':id', showStatus);
                    $('#showAllSpan').html("Hide archived");
                    showStatus = 0;
                }else{
                    murl = "{{ route('EventController.index') }}";
                    $('#showAllSpan').html("Show all");
                    showStatus = 1;
                }
                //alert(showStatus + "," + murl);
                var mtable = $('#laravel_datatable').DataTable();
                mtable.ajax.url(murl).load();
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

        	var oTable = $('#laravel_datatable').DataTable();

            $('#search').on('keyup', function () {
                oTable.search(this.value).draw();
            });
        });
    </script>
@endsection
