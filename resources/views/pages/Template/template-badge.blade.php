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
                                    <th>Width</th>
                                    <th>High</th>
                                    <th>Background Color</th>
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
                    <form id="badgeForm" name="badgeForm" class="form-horizontal">
                        <input style="visibility: hidden" type="text" name="template_id" id="template_id" value="{{$template_id}}">
                        <input type="hidden" name="badge_id" id="badge_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>Width</label>
                                    <div class="col-sm-12">
                                        <input type="number" id="width" name="width" placeholder="enter width" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col">
                                    <label>High</label>
                                    <div class="col-sm-12">
                                        <input type="number" id="high" name="high" placeholder="enter high" required="">
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
                        columns: [ 1,2,3,4,5,6 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: '../../template-badge/'+ templateId,
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'template_id', name: 'template_id' },
                    { data: 'width', name: 'width' },
                    { data: 'high', name: 'high' },
                    { data: 'bg_color', name: 'bg_color' },
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
                $.get('../templateBadgeController/' + badge_id + '/edit', function (data) {
                    $('#name-error').hide();
                    $('#modalTitle').html("Edit Badge");
                    $('#btn-save').val("edit-badge");
                    $('#field-modal').modal('show');
                    $('#badge_id').val(data.id);
                    $('#width').val(data.width);
                    $('#high').val(data.high);
                    $('#bg_color').val(data.bg_color);
                });
            });
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
    </script>
@endsection
