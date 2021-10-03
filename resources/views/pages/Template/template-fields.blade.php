@extends('main')
@section('subtitle',' Template fields')
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
                                <p class="card-title">Template / Fields</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                <a href="javascript:void(0)" id="add-new-field" class="add-hbtn">
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
                                    <th>Label (Arabic)</th>
                                    <th>Label (English)</th>
                                    <th>Order</th>
                                    <th>Mandatory</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th>Type</th>
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
                        <input style="visibility: hidden" type="text" name="template_id" id="template_id" value="{{$template_id}}">
                        <input type="hidden" name="field_id" id="field_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Label (Arabic)</label>
                                    <div class="col-sm-12">
                                        <input type="text" id="label_ar" name="label_ar" placeholder="enter arabic label" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Label (English)</label>
                                    <div class="col-sm-12">
                                        <input type="text" id="label_en" name="label_en" placeholder="enter english label" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col">
                                    <label>Type</label>
                                    <div class="col-sm-12">
                                        <select id="field_type" name="field_type" required="">
                                            @foreach ($fieldTypes as $fieldType)
                                                <option value="{{ $fieldType->id }}" data-slug="{{$fieldType->slug}}"
                                                        @if ($fieldType->key == 1)
                                                        selected="selected"
                                                    @endif
                                                >{{ $fieldType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="option">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Min</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="min_char" min="1" max="500" name="min_char" placeholder="enter min">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Max</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="max_char" min="1" max="500" name="max_char" placeholder="enter max">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Order</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="field_order" name="field_order" min="1" max="500" name="max_char" placeholder="enter field order">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Mandatory</label>
                                        <div class="col-sm-12">
                                            <input type="checkbox" id="mandatory" name="mandatory">
                                        </div>
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
    <div class="modal fade" id="delete-field-confirm-modal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
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
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">Cancel</button>
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
                    title: 'Templates',
                    exportOptions: {
                        columns: [ 1,2,3,4,5,6,7 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: '../template-fields/'+ templateId,
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'label_ar', name: 'label_ar' },
                    { data: 'label_en', name: 'label_en' },
                    { data: 'field_order', name: 'field_order' },
                    // { data: 'mandatory', name: 'mandatory' },
                    {
                        "data": "mandatory",
                        "render": function (val, type, row) {
                            return val == 1 ? "Yes" : "No";
                        }
                    },
                    { data: 'min_char', name: 'min_char' },
                    { data: 'max_char', name: 'max_char' },
                    { data: 'name', name: 'name' },
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click( function() {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

            $('#add-new-field').click(function () {
                $('#btn-save').val("create-field");
                $('#field_id').val('');
                $('#fieldForm').trigger("reset");
                $('#modalTitle').html("New Field");
                $('#field-modal').modal('show');
                // $('#field_type').removeAttr('disabled');
            });

            $('body').on('click', '#edit-field', function () {
                var field_id = $(this).data('id');
                $.get('../templateFieldController/' + field_id + '/edit', function (data) {
                    $('#name-error').hide();
                    $('#modalTitle').html("Edit Field");
                    $('#btn-save').val("edit-field");
                    $('#field-modal').modal('show');
                    $('#field_id').val(data.id);
                    $('#label_ar').val(data.label_ar);
                    $('#label_en').val(data.label_en);
                    $('#min_char').val(data.min_char);
                    $('#max_char').val(data.max_char);
                    $('#field_order').val(data.field_order);
                    console.log(data.mandatory)
                    if(data.mandatory === 1){
                        $('#mandatory').attr('checked','checked');
                    }
                    else {
                        $('#mandatory').removeAttr('checked');
                    }

                    $('#field_type').val(data.field_type_id);
                });
            });

            $('body').on('click', '#delete-field', function () {
                var field_id = $(this).data("id");
                $('#confirmTitle').html('Delete field');
                $('#curr_field_id').val(field_id);
                $('#mode_id').val('1');
                var confirmText =  'Are you sure you want to delete this field?';
                $('#confirmText').html(confirmText);
                $('#delete-field-confirm-modal').modal('show');
            });

            $('#delete-field-confirm-modal button').on('click', function(event) {
                var $button = $(event.target);

                $(this).closest('.modal').one('hidden.bs.modal', function() {
                    if($button[0].id === 'btn-yes'){
                        var field_id = $('#curr_field_id').val();
                        $.ajax({
                            type: "get",
                            url: "../templateFieldController/destroy/" + field_id,
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
        });

        if ($("#fieldForm").length > 0) {
            $("#fieldForm").validate({
                submitHandler: function(form) {
                    $('#btn-save').html('Sending..');

                    $.ajax({
                        data: $('#fieldForm').serialize(),
                        url: "{{ route('templateFieldController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#fieldForm').trigger("reset");
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

        $('select').on('change', function() {
            var selected = $(this).find('option:selected');
            var slug = selected.data('slug');
            console.log(slug);
            if(slug === 'text' || slug === 'number' || slug === 'textarea') {
                $('#option').show();
                $('#min_char').prop('disabled', false);
                $('#max_char').prop('disabled', false);
            }
           else{
                $('#option').hide();
                $('#min_char').prop('disabled', true);
                $('#max_char').prop('disabled', true);
            }
        });
    </script>
@endsection
