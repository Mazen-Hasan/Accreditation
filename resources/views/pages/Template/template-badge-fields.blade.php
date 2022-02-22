@extends('main')
@section('subtitle',' Template badge fields')
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
                            <div class="col-md-9">
                                <p class="card-title">
                                    <a class="url-nav" href="{{route('templateBadge')}}">
                                        <span>Badges:</span>
                                    </a>
                                    / Fields
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
                            <div class="col-md-2">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel" title="Export to excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                </a>
                                @if($badge->is_locked == 0)
                                    <a href="javascript:void(0)" id="add-new-field" class="add-hbtn" title="Add">
                                        <i>
                                            <img src="{{ asset('images/add.png') }}" alt="Add">
                                        </i>
                                    </a>
                                @endif
                                <a href="javascript:void(0)" id="preview-badge" class="add-hbtn" title="Preview">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Field ID</th>
                                    <th>Field Name</th>
                                    <th>Position - X (mm)</th>
                                    <th>Position - Y (mm)</th>
                                    <th>Size (mm)</th>
                                    <th>Text color</th>
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

    <!-- add new field modal-->
    <div class="modal fade" id="field-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="fieldForm" name="fieldForm" class="form-horizontal">
                        <input style="visibility: hidden" type="text" name="badge_id" id="badge_id"
                               value="{{$badge->id}}">
                        <input type="hidden" name="field_id" id="field_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Field</label>
                                    <div class="col-sm-12">
                                        <select id="template_field_id" name="template_field_id" required="">
                                            @foreach ($templateFields as $templateField)
                                                <option value="{{ $templateField->id }}"
                                                        data-slug="{{$templateField->field_type_id}}"
                                                        @if ($templateField->id == 1)
                                                        selected="selected"
                                                    @endif
                                                >{{ $templateField->label_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Size (mm)</label>
                                    <div class="col-sm-12">
                                        <input type="number" id="size" name="size" placeholder="enter size (pixel)"
                                               required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Position - X (mm)</label>
                                    <div class="col-sm-12">
                                        <input type="number" id="position_x" name="position_x"
                                               placeholder="enter position x (pixel)" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Position - Y (mm)</label>
                                    <div class="col-sm-12">
                                        <input type="number" id="position_y" name="position_y"
                                               placeholder="enter position y (pixel)" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="div-txt-color" class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Text Color</label>
                                    <div class="col-sm-12">
                                        <input type="color" id="text_color" name="text_color" value="#ffffff">
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
    <!-- delete confirm modal -->
    <div class="modal fade" id="delete-field-confirm-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_field_id">
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

     <div class="modal fade" id="badge-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="badgeTitle">Badge preview</h5>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-left: 25%; max-height: 100%; max-width: 50%; object-fit: fill">
                        <img id="badge" src="" alt="Badge">
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let badge_id = $('#badge_id').val();
            let url = "{{ route('templateBadgeFields', [":badge_id"]) }}";
            url = url.replace(':badge_id', badge_id);

            $('#laravel_datatable').DataTable({

                dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Badge-fields',
                    exportOptions: {
                        columns: [2, 3, 4, 5]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'template_field_id', name: 'template_field_id', 'visible': false},
                    {data: 'template_field_name', name: 'template_field_name'},
                    // {data: 'position_x', name: 'position_x'},
                    {
                        "data": "position_x",
                        "render": function (val) {
                            return Math.trunc(val * 0.2645833333);
                        }
                    },
                    // {data: 'position_y', name: 'position_y'},
                    {
                        "data": "position_y",
                        "render": function (val) {
                            return Math.trunc(val * 0.2645833333);
                        }
                    },
                    // {data: 'size', name: 'size'},
                    {
                        "data": "size",
                        "render": function (val) {
                            return Math.trunc(val * 0.2645833333);
                        }
                    },
                    {
                        "data": "text_color",
                        "render": function (val) {
                            if(val !== ""){
                                return "<div class='div-color' style='background-color: " + val + "'></div>";
                            }
                            else{
                                return "";
                            }
                        }
                    },
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

        	$('#search').on('keyup', function () {
                oTable.search(this.value).draw();
            });

            $('#add-new-field').click(function () {
                $('#btn-save').val("create-field");
                $('#field_id').val('');
                $('#fieldForm').trigger("reset");
                $('#modalTitle').html("New Field");
                $('#field-modal').modal('show');
            });

            $('body').on('click', '#edit-field', function () {
                let field_id = $(this).data('id');

                $.get('../templateBadgeFieldController/' + field_id + '/edit', function (data) {
                    $('#name-error').hide();
                    $('#modalTitle').html("Edit Field");
                    $('#btn-save').val("edit-field");
                    $('#field-modal').modal('show');
                    $('#field_id').val(data.id);
                    $('#position_x').val(Math.trunc(data.position_x * 0.2645833333));
                    $('#position_y').val(Math.trunc(data.position_y * 0.2645833333));
                    $('#size').val(Math.round(data.size * 0.2645833333));
                    $('#text_color').val(data.text_color);
                    // $('#bg_color').val(data.bg_color);
                    $('#template_field_id').val(data.template_field_id);
                });
            });

            $('body').on('click', '#delete-field', function () {
                var field_id = $(this).data("id");
                $('#confirmTitle').html('Delete field');
                $('#curr_field_id').val(field_id);
                $('#mode_id').val('1');
                var confirmText = 'Are you sure you want to delete this field?';
                $('#confirmText').html(confirmText);
                $('#delete-field-confirm-modal').modal('show');
            });

            $('#delete-field-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);

                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        $('#loader-modal').modal('show');
                        var field_id = $('#curr_field_id').val();
                        let url = "{{ route('TemplateBadgeFieldControllerDestroy', [":field_id"]) }}";
                        url = url.replace(':field_id', field_id);

                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                $('#loader-modal').modal('hide');
                                var oTable = $('#laravel_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                $('#loader-modal').modal('hide');
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });
        });

        $('#preview-badge').click(function () {
            var badge_id = $('#badge_id').val();
            let url = "{{ route('badgeDesignGenerate', [":badge_id"]) }}";
            url = url.replace(':badge_id', badge_id);

            $('#loader-modal').modal('show');
            $.ajax({
                type: "get",
                url: url,
                success: function (data) {
                    $('#loader-modal').modal('hide');
                    $('#badge-modal').modal('show');

                    var imag = data;
                    var image_path = "{{URL::asset('preview/')}}/";

                    $('#badge').attr('src', image_path + imag + '?verion=' + (new Date().getTime()));
                },
                error: function (data) {
                    $('#loader-modal').modal('hide');
                    console.log('Error:', data);
                }
            });
        });


        if ($("#fieldForm").length > 0) {
            $("#fieldForm").validate({
                submitHandler: function (form) {
                    $('#loader-modal').modal('show');
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#fieldForm').serialize(),
                        url: "{{ route('templateBadgeFieldController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#fieldForm').trigger("reset");
                            $('#field-modal').modal('hide');
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

        $('select').on('change', function () {
            var selected = $(this).find('option:selected');
            var slug = selected.data('slug');
            if (slug === 14) {
                $('#div-txt-color').hide();
            } else {
                $('#div-txt-color').show();
            }
        });

    </script>
@endsection
