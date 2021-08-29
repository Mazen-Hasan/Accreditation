@extends('main')
@section('subtitle',' Events')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
{{--    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>--}}
{{--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
{{--    <style type="text/css">--}}
{{--        tr--}}
{{--        {--}}
{{--            height:80px;--}}
{{--        }--}}
{{--    </style>--}}
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        @role('super-admin')
        <a href="{{route('eventAdd')}}" class="ha_btn" id="add-new-post">Add New Event</a>
{{--        <a href="javascript:void(0)" class="btn btn-info ml-3" id="add-new-post">Add New Evant</a>--}}
        @endrole
        <br><br>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Event Table</h4>
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
                                    <th>Size</th>
                                    <th>Organizer</th>
                                    <th>Owner</th>
                                    <th>Type</th>
                                    <th>period</th>
                                    <th>Accreditation Period</th>
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
                    url: "{{ route('EventController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name'},
                    // { data: 'event_admin', name: 'event_admin', 'visible': false},
                    // { data: 'location', name: 'location' , 'visible': false},
                    { data: 'size', name: 'size' },
                    { data: 'organizer', name: 'organizer' },
                    { data: 'owner', name: 'owner' },
                    { data: 'event_type', name: 'event_type' },
                    { data: 'period', name: 'period'},
                    { data: 'accreditation_period', name: 'accreditation_period' },
                    { data: 'status', render:function (data){ if(data == 1) { return "<span style='color: green'>Active</span>"} else{ return "<span style='color: red'>InActive</span>" }}},
                    // { data: 'approval_option', name: 'approval_option' , 'visible': false},
                    // { data: 'security_officer', name: 'security_officer' , 'visible': false},
                    // { data: 'event_form', name: 'event_form' , 'visible': false},
                    // { data: 'creation_date', name: 'creation_date' , 'visible': false},
                    // { data: 'creator', name: 'creator' , 'visible': false},
                    // { data: 'created_at', name: 'created_at' , 'visible': false},
                    // { data: 'uploaded_at', name: 'uploaded_at' , 'visible': false},
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
