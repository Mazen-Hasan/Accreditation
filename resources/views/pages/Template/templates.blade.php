@extends('main')
@section('subtitle',' Templates')
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
                                <p class="card-title">Templates</p>
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
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th style="color: black">Status</th>
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
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" placeholder="enter name" required="">
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
    <div class="modal fade" id="confirmModal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
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

            $('#laravel_datatable').DataTable({

                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Templates',
                    exportOptions: {
                        columns: [ 1,2 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('templateController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name' },
                    { data: 'status', render:function (data){ if(data == 1) { return "<p style='color: green'>Active</p>"} else{ return "<p style='color: red'>InActive</p>" }}},
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('.export-to-excel').click( function() {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

            $('#add-new-template').click(function () {
                $('#btn-save').val("create-template");
                $('#template_id').val('');
                $('#templateForm').trigger("reset");
                $('#modalTitle').html("New template");
                $('#template-modal').modal('show');
            });

            $('body').on('click', '.edit-template', function () {
                var template_id = $(this).data('id');
                $.get('templateController/'+template_id+'/edit', function (data) {
                    $('#name-error').hide();
                    $('#modalTitle').html("Edit Template");
                    $('#btn-save').val("edit-template");
                    $('#template-modal').modal('show');
                    $('#template_id').val(data.id);
                    $('#name').val(data.name);
                    $('#status').val(data.status);
                })
            });


            $('body').on('click', '#activate-template', function () {
                var template_id = $(this).data("id");
                $('#confirmTitle').html('Activate template');
                $('#curr_template_id').val(template_id);
                $('#mode_id').val('1');
                var confirmText =  'Are you sure you want to activate this template?';
                $('#confirmText').html(confirmText);
                $('#confirmModal').modal('show');
            });

            $('#confirmModal button').on('click', function(event) {
                var $button = $(event.target);

                $(this).closest('.modal').one('hidden.bs.modal', function() {
                    if($button[0].id === 'btn-yes'){
                        var template_id = $('#curr_template_id').val();
                        var mode_id = $('#mode_id').val();
                        $.ajax({
                            type: "get",
                            url: "templateController/changeStatus/" + template_id+"/" + mode_id,
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

            $('body').on('click', '#deActivate-template', function () {
                var template_id = $(this).data("id");
                $('#confirmTitle').html('Deactivate template');
                $('#curr_template_id').val(template_id);
                $('#mode_id').val('0');
                var confirmText =  'Are you sure you want to deactivate this template?';
                $('#confirmText').html(confirmText);
                $('#confirmModal').modal('show');
            });
        });

        if ($("#templateForm").length > 0) {
            console.log('Sending...');
            $("#templateForm").validate({
                submitHandler: function(form) {
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
    </script>
@endsection
