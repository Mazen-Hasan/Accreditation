@extends('main')
@section('subtitle',' Template badge backgrounds')
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
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-10">
                                <p class="card-title">
                                    <a class="url-nav" href="{{route('templateBadge')}}">
                                        <span>Badges:</span>
                                    </a>
                                    / Backgrounds
                                </p>
                            </div>
                            <div class="col-md-1 align-content-md-center">
                                <div class="search-container">
                                    <input class="search expandright" id="search" type="text" placeholder="Search">
                                    <label class="search-button search-button-icon" for="search">
                                        <i class="icon-search"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel" title="Export to excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>Badge ID</th>
                                    <th>Badge BG ID</th>
                                    <th>Accreditation Category</th>
                                    <th>Background Image</th>
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

    <div class="modal fade" id="badge_bg-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="bg_imgForm" name="bg_imgForm" class="form-horizontal  img-upload"
                          enctype="multipart/form-data" action="javascript:void(0)">
                        <div class="row">
                            <div class="col-md-5">
                                <label>Background image</label>
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

                    <form id="badgeBGForm" name="badgeBGForm" class="form-horizontal">
                        <input type="hidden" name="badge_id" id="badge_id" value="{{$badge_id}}">
                        <input type="hidden" name="badge_bg_id" id="badge_bg_id">
                        <div class="row" style="margin-left: 25%; max-height: 100%; max-width: 50%; object-fit: fill">
                        <img id="bg_image_view" src="{{URL::asset('badges/')}}" alt="im">
                        </div>
                        <input style="visibility: hidden" type="text" name="bg_image" id="bg_image">
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

            var badge_id = $('#badge_id').val();

            var url = "{{ route('templateBadgeBGs', ":id") }}";
            url = url.replace(':id', badge_id);

            $('#laravel_datatable').DataTable({

                dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Badge-accreditation-BG',
                    exportOptions: {
                        columns: [ 3, 4]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: 'GET',
                },
                columns: [
                    {data: 'badge_id', name: 'badge_id', 'visible': false},
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {
                        "data": "bg_image",
                        "render": function (val) {
                            // var image_path = "{{URL::asset('storage/badges/')}}/";
                         	var image_path = "{{URL::asset('badges/')}}/";
                            return "<img src= " + image_path + val + "></img>";
                        }
                    },
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

            $('body').on('click', '#edit-bg', function () {
                var badge_bg_id = $(this).data('id');
                $('#loader-modal').modal('show');
                $.get('../templateBadgeBGController/' + badge_bg_id + '/edit', function (data) {
                    $('#loader-modal').modal('hide');
                    $('#modalTitle').html("Edit " + data.name + " Background");
                    $('#file').val('');
                    $('#badge_bg-modal').modal('show');
                    $('#badge_bg_id').val(data.id);

                    $("#file-progress-bar").width('0%');
                    $("#file_type_error").html('');

                    var imag = data.bg_image;
                    // var image_path = "{{URL::asset('storage/badges/')}}/";
                 	var image_path = "{{URL::asset('badges/')}}/";

                    $('#bg_image_view').attr('src', image_path + imag);
                    $('#bg_image_view').show();
                })
            });

        	var oTable = $('#laravel_datatable').DataTable();

            $('#search').on('keyup', function () {
                oTable.search(this.value).draw();
            });

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

        if ($("#badgeBGForm").length > 0) {
            $("#badgeBGForm").validate({

                submitHandler: function (form) {
                    $('#btn-save').html('Sending..');
                    $('#loader-modal').modal('show');
                    $.ajax({
                        data: $('#badgeBGForm').serialize(),
                        url: "{{ route('templateBadgeBGController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#badgeBGForm').trigger("reset");
                            $('#badge_bg-modal').modal('hide');
                            $('#btn-save').html('Save Changes');
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }

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
            formData.append('badge_bg_id', $('#badge_bg_id').val());
            console.log($('#badge_bg_id').val());
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
                url: "{{ url('store-file')}}",
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
                    $("#bg_image").val(data.fileName);

                    var imag = data.fileName;
                    // var image_path = "{{URL::asset('storage/badges/')}}/";
                    var image_path = "{{URL::asset('badges/')}}/";

                    $('#bg_image_view').attr('src', image_path + imag);
                },

                error: function (data) {
                    $("#file_type_error").html('Error uploading file');
                    console.log(data);
                }
            });
        });

    </script>
@endsection
