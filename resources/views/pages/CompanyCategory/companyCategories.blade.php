@extends('main')
@section('subtitle',' Company categories')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <a href="javascript:void(0)" class="ha_btn" id="add-new-category">Add New Category</a>
        <br><br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Title Table</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>name</th>
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
    <div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="postCrudModal"></h4>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" name="categoryForm" class="form-horizontal">
                        <input type="hidden" name="category_id" id="category_id">
                        <div class="form-group">
                            <label for="name" >company category</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" value="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label >Status</label>
                            <div class="col-sm-12">
                                <select  id="status" name="status" required="">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="submit"  id="btn-save" value="create">Save
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('companyCategoryController.index') }}",
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

            $('#add-new-category').click(function () {
                $('#btn-save').val("create-category");
                $('#category_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Category");
                $('#ajax-crud-modal').modal('show');
            });


            $('body').on('click', '.edit-category', function () {
                var category_id = $(this).data('id');
                $.get('companyCategoryController/'+category_id+'/edit', function (data) {
                    $('#name-error').hide();
                    $('#postCrudModal').html("Edit Category Name");
                    $('#btn-save').val("edit-category");
                    $('#ajax-crud-modal').modal('show');
                    $('#category_id').val(data.id);
                    $('#name').val(data.name);
                    $('#status').val(data.status);
                    // alert($('#name').val(data.name).val());
                })
            });

            $('body').on('click', '#delete-category', function () {
                var category_id = $(this).data("id");
                confirm("Are You sure want to delete !");
                $.ajax({
                    type: "get",
                    url: "companyCategoryController/destroy/"+ category_id,
                    success: function (data) {
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('body').on('click', '#activate-category', function () {
                var category_id = $(this).data("id");
                confirm("Are You sure want to activate ?!");
                $.ajax({
                    type: "get",
                    url: "companyCategoryController/changeStatus/"+category_id+"/1",
                    success: function (data) {
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('body').on('click', '#deActivate-category', function () {
                var category_id = $(this).data("id");
                confirm("Are You sure want to deActivate ?!");
                $.ajax({
                    type: "get",
                    url: "companyCategoryController/changeStatus/"+category_id+"/0",
                    success: function (data) {
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });

        if ($("#categoryForm").length > 0) {
            $("#categoryForm").validate({
                submitHandler: function(form) {
                    $('#btn-save').html('Sending..');

                    $.ajax({
                        data: $('#categoryForm').serialize(),
                        url: "{{ route('companyCategoryController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
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
