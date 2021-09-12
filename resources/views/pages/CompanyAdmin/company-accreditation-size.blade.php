@extends('main')
@section('subtitle','Company Accreditation Size')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <a href="javascript:void(0)" class="ha_btn" id="add-new-post" style="margin: 10px">Add Accreditation Size</a>
                    <div class="card-body">
                        <h4 class="card-title">Accreditation Size Table</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Accreditation Category</th>
                                    <th>Size</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <a href="javascript:void(0)" class="ha_btn" id="send-approval-request">Send Approval Request</a>
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
{{--                    <form id="postForm" name="postForm" class="form-horizontal">--}}
                        <input type="hidden" name="company_id" id="company_id" value="{{$companyId}}">
                        <input type="hidden" name="post_id" id="post_id" value="">
                        <div class="form-group">
                            <label>Accreditation Category</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="accredit_cat_id" name="accredit_cat_id" value="" required="">
                                    @foreach ($accreditationCategorys as $accreditationCategory)
                                        <option value="{{ $accreditationCategory->key }}"
                                                {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                @if ($accreditationCategory->key == 1)
                                                selected="selected"
                                            @endif
                                        >{{ $accreditationCategory->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">Size</label>
                            <div class="col-sm-12">
                                <input type="text" id="size" name="size" value="" required="">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button id="edit-size" value="create">Save
                            </button>
                        </div>
{{--                    </form>--}}
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
                    {{--url: "{{ route('companyAdminController.companyAccreditCategories') }}",--}}
                    url: '../company-accreditation-size',
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name' },
                    { data: 'size', name: 'size' },
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('0');
                $('#size').val('0');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Accreditation Category");
                $('#ajax-crud-modal').modal('show');
            });


            $('body').on('click', '#edit-company-accreditation', function () {
                var post_id = $(this).data('id');
                //alert(post_id);
                $.get('../companyAdminController/editCompanyAccreditSize/'+post_id, function (data) {
                    $('#name-error').hide();
                    $('#email-error').hide();
                    $('#postCrudModal').html("Edit Company Accreditation Category");
                    $('#btn-save').val("edit-post");
                    $('#ajax-crud-modal').modal('show');
                    $('#post_id').val(data.id);
                    $('#size').val(data.size);
                    $('#accredit_cat_id').val(data.accredit_cat_id);
                    $('#accredit_cat_id').attr('disabled', 'disabled');
                })
            });

            $('body').on('click', '#delete-company-accreditation', function () {
                var post_id = $(this).data("id");
                confirm("Are You sure want to delete Accreditation Category!");
                $.ajax({
                    type: "get",
                    url: "../companyAdminController/destroyCompanyAccreditCat/"+post_id,
                    success: function (data) {
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('body').on('click', '#edit-size', function () {
                var accredit_cat_id = $('#accredit_cat_id').val();
                var size = $('#size').val();
                var post_id = $('#post_id').val();
                var company_id = $('#company_id').val();
                //alert('hey hey');
                //confirm("Are You sure want to deActivate ?!");
                $.ajax({
                    type: "get",
                    url: "../companyAdminController/storeCompanyAccrCatSize/"+post_id+"/"+accredit_cat_id+"/"+size+"/"+company_id,
                    success: function (data) {
                        //alert(data);
                        $('#ajax-crud-modal').modal('hide');
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('body').on('click', '#send-approval-request', function () {
                var post_id = $('#id').val();
                alert(post_id);
                confirm("Are You sure you want to confirm Accreditation Category sizes?");
                $.ajax({
                    type: "get",
                    url: "../companyAdminController/sendApproval/"+post_id,
                    success: function (data) {
                        alert('done');
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>
@endsection
