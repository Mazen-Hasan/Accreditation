@extends('main')
@section('subtitle',' Email Templates')
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
                                <p class="card-title">Email Templates</p>
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
                                    <th>ID</th>
                                    <th>Email Template Name</th>
                                    <th>Subject</th>
                                    <th>Full Content</th>
                                    <th>Content</th>
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
    <div class="modal fade" id="email_template-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="emailTemplateForm" name="emailTemplateForm" class="form-horizontal">
                        <input type="hidden" name="email_template_id" id="email_template_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="col-sm-2 control-label">Subject</label>
                            <div class="col-sm-12">
                                <input type="text" id="subject" name="subject">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Content</label>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <textarea id="email_template_content" name="email_template_content"></textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <select id="option_add_list" name="option_add_list" required="">
                                            <option value="@event_name">Event name</option>
                                            <option value="@company_name">Company name</option>
                                            <option value="@link">link</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1" id="add">
                                        <a href="javascript:void(0)" id="option_add">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
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

            $('#laravel_datatable').DataTable({

                dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Email-Templates',
                    exportOptions: {
                        columns: [1, 2, 3, 5]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('emailTemplateController.index') }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'email_template_name', name: 'email_template_name'},
                    {data: 'email_template_subject', name: 'email_template_subject'},
                    {data: 'content', name: 'content','visible': false},
                    {
                        "data": "content",
                        "render": function (val) {
                            return val.substring(0, 50) + '  ...';
                        }
                    },
                    {data: 'email_template_type', name: 'email_template_type'},
                    {data: 'action', name: 'action', orderable: false}
                ]
            });

            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

        	var oTable = $('#laravel_datatable').DataTable();

            $('#search').on('keyup', function () {
                oTable.search(this.value).draw();
            });

            $('body').on('click', '#edit-email-template', function () {
                var email_template_id = $(this).data('id');

                $.get('emailTemplateController/' + email_template_id + '/edit', function (data) {
                    $('#name-error').hide();
                    $('#modalTitle').html("Edit Email Template");
                    $('#email_template-modal').modal('show');
                    $('#email_template_id').val(data.id);
                    $('#name').val(data.name);
                    $('#subject').val(data.subject);
                    $('#email_template_content').val(data.content);
                })
            });
        });

        if ($("#emailTemplateForm").length > 0) {
            console.log('Sending...');
            $("#emailTemplateForm").validate({
                submitHandler: function (form) {
                    $('#btn-save').html('Sending..');
                    $('#loader-modal').modal('show');
                    $.ajax({
                        data: $('#emailTemplateForm').serialize(),
                        url: "{{ route('emailTemplateController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#emailTemplateForm').trigger("reset");
                            $('#email_template-modal').modal('hide');
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

        $("#option_add").click(function () {
            $("#email_template_content").val($("#email_template_content").val() +  ' ' + $("#option_add_list").val());
        });
    </script>
@endsection
