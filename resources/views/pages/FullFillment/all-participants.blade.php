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
                <input type="hidden" id="data_values" name="data_values" value=""/>
                <input type="hidden" id="company_id" name="company_id" value="{{$company_id}}"/>
                <input type="hidden" id="event_id" name="event_id" value="{{$event_id}}"/>
                <input type="hidden" id="accredit" name="accredit" value="{{$accredit}}"/>
                <input type="hidden" id="isChecked" name="isChecked" value="{{$checked}}"/>
                <div class="card">
                    <div class="card-body">
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-8">
                                <p class="card-title">
                                    <a class="url-nav" href="{{ route('Selections') }} ">
                                        <span>Fulfillment Selections:</span>
                                    </a>
                                    / Details
                                </p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>

                                <a href="javascript:void(0)" id="generate" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Generate</span>
                                </a>
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
                                    <th><input type="checkbox" id="checkAll" style="margin-left: 12px;"/></th>
                                    <th style="color: black">Status</th>
                                    @foreach ($dataTableColumns as $dataTableColumn)
                                        <th><?php echo $dataTableColumn ?></th>
                                    @endforeach
                                    <th>ID</th>
                                    <th>Image</th>
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
        $(document).ready(function () {
            var checkedItems = [];
            var selected = 0;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var company_id = $('#company_id').val();
            var jqueryarray = <?php echo json_encode($dataTableColumns); ?>;
            var myColumns = [];
            var i = 0;
        	var exportColumns = [];
            myColumns.push({data: "id", name: "id", 'visible': false});
            myColumns.push({data: "action", name: "action", orderable: false});
            myColumns.push({data: "status", name: "status"});
        	exportColumns.push(2);
            // if(company_id == 0){
            //     myColumns.push({data: "company",name: "company"});
            // }
            //myColumns.push({data: "company",name: "company"});
            while (i < jqueryarray.length) {
                myColumns.push({data: jqueryarray[i].replace(/ /g, "_"), name: jqueryarray[i].replace(/ /g, "_")});
            	exportColumns.push(i+3);
                i++;
            }
            myColumns.push({data: "identifier", name: "identifier"});
            myColumns.push({data: "image", name: "image"});
        	exportColumns.push(i);
            //alert("val---" + JSON.stringify(myColumns));
            var companyId = $('#company_id').val();
            var eventId = $('#event_id').val();
            var accredit = $('#accredit').val();
            var isChecked = $('#isChecked').val();
            $('#laravel_datatable').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Company-Participants',
                    exportOptions: {
                        columns: exportColumns
                    }
                }],
                //pageLength: 2,
                // stateSavge: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '../../../../all-participants/' + eventId + '/' + companyId + '/' + accredit + '/' + isChecked,
                    type: 'GET',
                },
                columns: myColumns,
                order: [[2, 'desc']],

                "fnInitComplete": function (oSettings, json) {
                    checkedItems = [];
                    selected = 0;
                    $('#checkAll').prop('checked', false);
                    // alert('DataTables has finished its initialisation.');
                }
            }).on('search.dt', function () {
                checkedItems = [];
                selected = 0;
                $('#checkAll').prop('checked', false);
                //alert('i am here');
            }).on('length.dt', function () {
                checkedItems = [];
                selected = 0;
                $('#checkAll').prop('checked', false);
                //alert('data has changed')
            }).on('page.dt', function () {
                checkedItems = [];
                selected = 0;
                $('#checkAll').prop('checked', false);
                //alert('data has changed page')
            });


            $('.export-to-excel').click(function () {
                $('#laravel_datatable').DataTable().button('.buttons-excel').trigger();
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
                //$('#ajax-crud-modal').modal('show');
            });

            $('body').on('click', '#checkAll', function () {
                if (selected == 0) {
                    $('.select').each(function () {
                        //alert(this);
                        checkedItems.push($(this).data("id"));
                        $(this).prop('checked', true);
                    });
                    selected = 1;
                } else {
                    //alert('i am here');
                    $('.select').each(function () {
                        //alert(this);
                        checkedItems.pop();
                        $(this).prop('checked', false);
                    });
                    selected = 0;
                }
                //alert(checkedItems);
            });

            $('body').on('click', '#generate', function () {
                var staff = checkedItems;
                if (staff.length > 0) {
                    $.ajax({
                        type: "post",
                        data: {staff: staff},
                        dataType: "json",
                        url: "{{ url('pdf-generate')}}",
                        success: function (data) {
                            console.log(data);
                            window.open(data.file, '_blank');
                            // window.location.href = data.file;
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });

            $('body').on('click', '.select', function () {
                //alert($(this).data("id"));
                var count = 0;
                var found = false;
                var result = [];
                while (count < checkedItems.length) {
                    if (checkedItems[count] == $(this).data("id")) {
                        found = true;
                        //count = checkedItems.length;
                    } else {
                        result.push(checkedItems[count]);
                    }
                    count++;
                }
                checkedItems = result;
                if (!found) {
                    checkedItems.push($(this).data("id"));
                }
                if (checkedItems.length == 0) {
                    $('#checkAll').prop('checked', false);
                    selected = 0;
                } else {
                    var allChecked = true;
                    $('.select').each(function () {
                        if (!$(this).prop('checked')) {
                            allChecked = false;
                        }
                    });
                    if (allChecked) {
                        $('#checkAll').prop('checked', true);
                        selected = 1;
                    }
                }
                //alert(checkedItems);
            });
        });
    </script>
@endsection
