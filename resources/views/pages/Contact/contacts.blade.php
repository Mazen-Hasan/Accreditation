@extends('main')
@section('subtitle',' Contacts')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>--}}
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>--}}
    {{--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
    <style type="text/css">
        tr
        {
            height:80px;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <a href="{{route('contactAdd')}}" class="btn btn-info ml-3" id="add-new-post">Add New Contact</a>
{{--        <a href="javascript:void(0)" class="btn btn-info ml-3" id="add-new-post">Add New Evant</a>--}}
        <br><br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Contact Table</h4>
{{--                        <p class="card-description">--}}
{{--                            Add class <code>.table-hover</code>--}}
{{--                        </p>--}}
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
{{--                                    <th>Location</th>--}}
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Mobile</th>
                                    <th>Titles</th>
                                    <th>Status</th>
{{--                                    <th style="color: black">Status</th>--}}
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--<tr>
                                    <td>Jacob</td>
                                    <td>Photoshop</td>
                                    <td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td>
                                    <td><label class="badge badge-danger">Pending</label></td>
                                </tr>
                                <tr>
                                    <td>Messsy</td>
                                    <td>Flash</td>
                                    <td class="text-danger"> 21.06% <i class="ti-arrow-down"></i></td>
                                    <td><label class="badge badge-warning">In progress</label></td>
                                </tr>
                                <tr>
                                    <td>John</td>
                                    <td>Premier</td>
                                    <td class="text-danger"> 35.00% <i class="ti-arrow-down"></i></td>
                                    <td><label class="badge badge-info">Fixed</label></td>
                                </tr>
                                <tr>
                                    <td>Peter</td>
                                    <td>After effects</td>
                                    <td class="text-success"> 82.00% <i class="ti-arrow-up"></i></td>
                                    <td><label class="badge badge-success">Completed</label></td>
                                </tr>
                                <tr>
                                    <td>Dave</td>
                                    <td>53275535</td>
                                    <td class="text-success"> 98.05% <i class="ti-arrow-up"></i></td>
                                    <td><label class="badge badge-warning">In progress</label></td>
                                </tr>--}}
                                </tbody>
                            </table>
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

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('contactController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email' },
                    { data: 'telephone', name: 'telephone' },
                    { data: 'mobile', name: 'mobile'},
                    { data: 'titleNames', name: 'titleNames'},
                    { data: 'status', render:function (data){ if(data == 1) { return "<p style='color: green'>Active</p>"} else{ return "<p style='color: red'>InActive</p>" }}},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
                //$('#ajax-crud-modal').modal('show');

            });


            {{--$('body').on('click', '.edit-post', function () {--}}
            {{--    var post_id = $(this).data('id');--}}
            {{--    // $.get('dtable-posts/'+post_id+'/edit', function (data) {--}}
            {{--    //     $('#name-error').hide();--}}
            {{--    //     $('#email-error').hide();--}}
            {{--    //     $('#postCrudModal').html("Edit Post");--}}
            {{--    //     $('#btn-save').val("edit-post");--}}
            {{--    //     $('#ajax-crud-modal').modal('show');--}}
            {{--    //     $('#post_id').val(data.id);--}}
            {{--    //     $('#title').val(data.title);--}}
            {{--    //     $('#body').val(data.body);--}}
            {{--    // })--}}
            {{--    //var url = '{{ route("event-edit", ":id") }}';--}}
            {{--    var url = '{{ route("event-edit") }}';--}}
            {{--    //rl = url.replace(':id', post_id);--}}
            {{--    window.location.href = url;--}}
            {{--});--}}
            //
            // $('body').on('click', '#edit-event', function () {
            //     var post_id = $(this).data("id");
            //     confirm("Are You sure want to delete !");
            //     $.ajax({
            //         type: "get",
            //         url: "dtable-posts/edit/"+ post_id,
            //         success: function (data) {
            //             var oTable = $('#laravel_datatable').dataTable();
            //             oTable.fnDraw(false);
            //         },
            //         error: function (data) {
            //             console.log('Error:', data);
            //         }
            //     });
            // });
        });

        {{--if ($("#postForm").length > 0) {--}}
        {{--    $("#postForm").validate({--}}
        {{--        submitHandler: function(form) {--}}
        {{--            $('#post_id').val('');--}}
        {{--            var actionType = $('#btn-save').val();--}}
        {{--            $('#btn-save').html('Sending..');--}}
        {{--            alert($('#postForm').serialize());--}}
        {{--            $.ajax({--}}
        {{--                data: $('#postForm').serialize(),--}}
        {{--                url: "{{ route('dtable-posts.store') }}",--}}
        {{--                type: "POST",--}}
        {{--                dataType: 'json',--}}
        {{--                success: function (data) {--}}
        {{--                    $('#postForm').trigger("reset");--}}
        {{--                    $('#ajax-crud-modal').modal('hide');--}}
        {{--                    $('#btn-save').html('Add successfully');--}}
        {{--                    window.location.href = "{{ route('home')}}";--}}
        {{--                    // var oTable = $('#laravel_datatable').dataTable();--}}
        {{--                    // oTable.fnDraw(false);--}}
        {{--                },--}}
        {{--                error: function (data) {--}}
        {{--                    console.log('Error:', data);--}}
        {{--                    $('#btn-save').html('Save Changes');--}}
        {{--                }--}}
        {{--            });--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}
    </script>
@endsection
