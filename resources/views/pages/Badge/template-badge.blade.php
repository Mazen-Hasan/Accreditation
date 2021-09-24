@extends('main')
@section('subtitle',' Template badge')
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
                            <div class="col-md-8">
                                <p class="card-title">Template / Badge</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                <a href="javascript:void(0)" id="add-new-badge" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Template ID</th>
                                    <th>Template Name</th>
                                    <th>Width</th>
                                    <th>High</th>
                                    <th>Background Color</th>
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

    <!-- add new badge modal-->
    <div class="modal fade" id="field-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="bg_imgForm" name="badgeForm" class="form-horizontal  img-upload" enctype="multipart/form-data" action="javascript:void(0)">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Background image</label>
                                    <div class="col-sm-12">
                                        <input type="file" id="file" name="file">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col">
                                    <button type="submit" id="btn-upload" value="Upload">Upload
                                    </button>
                                </div>
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
                    <form id="badgeForm" name="badgeForm" class="form-horizontal">
{{--                        <input style="visibility: hidden" type="text" name="bg_image" id="bg_image">--}}
                        <input type="text" name="bg_image" id="bg_image">
                        <img src="{{asset('storage/badges/2021-09-24_09:18:00.png')}}" alt="im" style="width:200px;height:200px;">
                        <input type="hidden" name="badge_id" id="badge_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Width</label>
                                    <div class="col-sm-12">
                                        <input type="number" id="width" min="300" name="width" placeholder="enter width" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>High</label>
                                    <div class="col-sm-12">
                                        <input type="number" id="high" name="high" min="300" placeholder="enter high" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Background Color</label>
                                    <div class="col-sm-12">
                                        <input type="color" id="bg_color" name="bg_color" value="#ffffff">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Type</label>
                                    <div class="col-sm-12">
                                        <select id="template_id" name="template_id" required="">
                                            @foreach ($templates as $template)
                                                <option value="{{ $template->id }}" data-slug="{{$template->name}}"
{{--                                                        @if ($template->key == 1)--}}
{{--                                                        selected="selected"--}}
{{--                                                    @endif--}}
                                                >{{ $template->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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

    <!-- add badge image modal -->
    <div class="modal fade" id="fileErrorModal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle">
                        Badge background Image
                    </h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="col-sm-12 confirm-text" id="confirmText">
                            Please choose a valid file (png)!
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">

                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready( function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var templateId = $('#template_id').val();

            $('#laravel_datatable').DataTable({

                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Badge',
                    exportOptions: {
                        columns: [ 2,3,4,5 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: '/template-badge',
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id','visible': false},
                    { data: 'template_id', name: 'template_id', 'visible': false},
                    { data: 'name', name: 'name' },
                    { data: 'width', name: 'width' },
                    { data: 'high', name: 'high' },
                    { data: 'bg_color', name: 'bg_color' },
                    { data: 'bg_image', name: 'bg_image' },
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click( function() {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

            $('#add-new-badge').click(function () {
                $('#btn-save').val("create-field");
                $('#badge_id').val('');
                $('#badgeForm').trigger("reset");
                $('#modalTitle').html("New Badge");
                $('#field-modal').modal('show');
            });

            $('body').on('click', '.edit-badge', function () {
                var badge_id = $(this).data('id');
                console.log(badge_id);
                $.get('../templateBadgeController/' + badge_id + '/edit', function (data) {
                    $('#name-error').hide();
                    $('#modalTitle').html("Edit Badge");
                    $('#btn-save').val("edit-badge");
                    $('#badge_id').val(data.id);
                    $('#width').val(data.width);
                    $('#high').val(data.high);
                    $('#bg_color').val(data.bg_color);
                    $('#field-modal').modal('show');
                    $('#template_id').val(data.template_id);
                    $("#file-progress-bar").width('0%');
                    $("#file_type_error").html('');
                    // if(data){
                    //     $('#template_id').attr('disabled', 'disabled');
                    // }
                    // else {
                    //     $('#template_id').removeAttr('disabled');
                    // }

                });
            });
        });

        $("#file").change(function () {
            let allowedTypes = ['image/png'];
            let file = this.files[0];
            let fileType = file.type;
            if (!allowedTypes.includes(fileType)) {
                // $('#fileErrorModal').modal('show');
                $("#file-progress-bar").width('0%');
                $("#file_type_error").html('Please choose a valid file (png)');
                $("#file").val('');
                $("#btn-upload").attr('disabled',true);
                return false;
            } else {
                $("button").removeAttr('disabled');
                $("#file_type_error").html('');
                $("#file-progress-bar").width('0%');
            }
        });

        if ($("#badgeForm").length > 0) {
            $("#badgeForm").validate({
                submitHandler: function(form) {
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#badgeForm').serialize(),
                        url: "{{ route('templateBadgeController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#badgeForm').trigger("reset");
                            $('#field-modal').modal('hide');
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
        };


        $('.img-upload').submit(function(e) {
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

                type:'POST',
                url: "{{ url('store-file')}}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,

                beforeSend: function () {
                    $("#file-progress-bar").width('0%');
                },

                success: (data) => {
                    this.reset();
                    $("#file_type_error").html('File uploaded successfully');
                    $('#btn-upload').html('Upload');
                    $("#bg_image").val(data.fileName);
                    console.log(data);
                },

                error: function(data){
                    $("#file_type_error").html('Error uploading file');
                    console.log(data);
                }
            });
        });
    </script>
@endsection
