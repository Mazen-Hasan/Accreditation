@extends('main')
@section('subtitle',' Participants')
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
                <input type="hidden" id="data_values" name ="data_values" value=""/>
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-8">
                                <p class="card-title">Company / Participants</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                @role('company-admin')
                                <a href="{{route('templateForm',0)}}" id="add-new-post" class="add-hbtn">
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
                                    @foreach ($dataTableColumns as $dataTableColumn)
                                        <th><?php echo $dataTableColumn ?></th>
                                    @endforeach
                                    <!-- <th>ID</th>
                                    <th>Name</th>
                                    {{--                                    <th>Location</th>--}}
                                    <th>Nationality</th>
                                    <th>Class</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Position</th>
                                    <th>Accreditation Category</th>
                                    <th>Religion</th>
                                    {{--                                    <th style="color: black">Status</th>--}} -->
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
            var jqueryarray = <?php echo json_encode($dataTableColumns); ?>;
            var myColumns = [];
            var i =0;
            myColumns.push({data: "id",name: "id", 'visible': false});
            while(i< jqueryarray.length){
                myColumns.push({data: jqueryarray[i].replace(/ /g,"_") ,name: jqueryarray[i].replace(/ /g,"_")});
                i++;
            }
            myColumns.push({data: "action",name: "action" , orderable: "false"});
            //alert("val---" + JSON.stringify(myColumns));
            $('#laravel_datatable').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Company-Participants',
                    exportOptions: {
                        columns: [ 1,2,3,4,5,6,7,8 ]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    {{--url: "{{ route('participantController.index') }}",--}}
                    url: '../company-participants',
                    type: 'GET',
                },
                // columns: [myColumns
                //     { data: 'id', name: 'id', 'visible': false},
                //     { data: 'name', name: 'name'},
                //     { data: 'nationality', name: 'nationality' },
                //     { data: 'class_name', name: 'class' },
                //     { data: 'email', name: 'email' },
                //     { data: 'mobile', name: 'mobile' },
                //     { data: 'position', name: 'position' },
                //     { data: 'accreditation_category_name', name: 'accreditation_category' },
                //     { data: 'religion_name', name: 'religion' },
                //     { data: 'action', name: 'action', orderable: false},
                // ],
                columns: myColumns,
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
