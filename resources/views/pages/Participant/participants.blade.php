@extends('main')
@section('subtitle',' Events')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
    <script src="{{ URL::asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('js/pdfmake.min.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-start" style="height: 80px">
                            <div class="col-md-8">
                                <h4 class="card-title">Participant Table</h4>
                            </div>
                            <div class="col-md-4 align-self-end">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                @role('super-admin')
                                <a href="{{route('participantAdd')}}" id="add-new-post" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Add</span>
                                </a>
                                @endrole
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="laravel_datatable" style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
{{--                                    <th>Location</th>--}}
                                    <th>Nationality</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Position</th>
                                    <th>Company</th>
                                    <th>Accreditation Category</th>
                                    <th>Class</th>
{{--                                    <th style="color: black">Status</th>--}}
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

                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Participants',
                    exportOptions: {
                        columns: [ 1,2,3,4,5,6,7,8 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('participantController.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false},
                    { data: 'name', name: 'name'},
                    // { data: 'event_admin', name: 'event_admin', 'visible': false},
                    // { data: 'location', name: 'location' , 'visible': false},
                    { data: 'nationality', name: 'nationality' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'position', name: 'position' },
                    { data: 'company', name: 'company'},
                    { data: 'accreditation_category', name: 'accreditation_category' },
                    { data: 'class', name: 'class' },
                    // { data: 'status', render:function (data){ if(data == 1) { return "<span style='color: green'>Active</span>"} else{ return "<span style='color: red'>InActive</span>" }}},
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

            $('.export-to-excel').click( function() {
                $('#laravel_datatable').DataTable().button( '.buttons-excel' ).trigger();
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
                //$('#ajax-crud-modal').modal('show');
            });
        });
    </script>
@endsection
