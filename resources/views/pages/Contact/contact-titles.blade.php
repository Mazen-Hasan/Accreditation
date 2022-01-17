@extends('main')
@section('subtitle',' Contacts titles')
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
                            <div class="col-md-7">
                                <p class="card-title">
                                    <a class="url-nav" href="{{route('contacts')}}">
                                        <span>Contacts:</span>
                                    </a>
                                    {{$contact->name}} / Titles
                                </p>
                            </div>
                            <div class="col-md-1 align-content-md-center">
                                <div class="search-container">
                                    <input class="search expandright" id="search" type="text" placeholder="Search">
                                    <label class="search-button search-button-icon" for="search">
                                        <i class="icon-search"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <a href="javascript:void(0)" class="add-hbtn export-to-excel">
                                    <i>
                                        <img src="{{ asset('images/excel.png') }}" alt="Export to excel">
                                    </i>
                                    <span class="dt-hbtn">Export to excel</span>
                                </a>
                                <span class="dt-hbtn"></span>
                                <a href="javascript:void(0)" id="add-new-title" class="add-hbtn">
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
                                    <th>Title</th>
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

    <div class="modal fade" id="title-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="titleForm" name="titleForm" class="form-horizontal">
                        <input style="visibility: hidden" type="text" name="contact_id" id="contact_id"
                               value="{{$contact->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col">
                                    <label>Title</label>
                                    <div class="col-sm-12">
                                        <select id="title_id" name="title_id" required="">
                                            @foreach ($titles as $title)
                                                <option value="{{ $title->id }}"
                                                        @if ($title->key == 1)
                                                        selected="selected"
                                                    @endif
                                                >{{ $title->title_label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label id="lbl_error" class="error"></label>
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
    <div class="modal fade" id="delete-title-confirm-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_title_id">
                        <label class="col-sm-12 confirm-text" id="confirmText"></label>
                    </div>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">Cancel
                            </button>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var contactId = $('#contact_id').val();

            var url = "{{ route('contactTitles', ":id") }}";
            url = url.replace(':id', contactId);

            $('#laravel_datatable').DataTable({
                dom: 'lBrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Contact-titles',
                    exportOptions: {
                        columns: [1]
                    }
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'title', name: 'title'},
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-title').click(function () {
                $('#lbl_error').html('');
                $('#titleForm').trigger("reset");
                $('#btn-save').html('Save');
                $('#modalTitle').html("Add Title");
                $('#title-modal').modal('show');
            });

            $('body').on('click', '#delete-title', function () {
                var title_id = $(this).data("id");
                $('#confirmTitle').html('Delete title');
                $('#curr_title_id').val(title_id);
                var confirmText = 'Are you sure you want to delete this title?';
                $('#confirmText').html(confirmText);
                $('#delete-title-confirm-modal').modal('show');
            });

            $('#delete-title-confirm-modal button').on('click', function (event) {
                var $button = $(event.target);

                $(this).closest('.modal').one('hidden.bs.modal', function () {
                    if ($button[0].id === 'btn-yes') {
                        var title_id = $('#curr_title_id').val();

                        var url = "{{ route('removeContactTitle', ":id") }}";
                        url = url.replace(':id', title_id);

                        $.ajax({
                            type: "get",
                            url: url,
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
        });

        if ($("#titleForm").length > 0) {
            $("#titleForm").validate({
                submitHandler: function (form) {
                    $('#btn-save').html('Sending..');

                    $.ajax({
                        data: $('#titleForm').serialize(),
                        url: "{{ route('contactTitlesController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#titleForm').trigger("reset");
                            $('#title-modal').modal('hide');
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
                        },
                        error: function (data) {
                            $('#btn-save').html('Save');
                            console.log('Error:', data.responseJSON.message);
                            $('#lbl_error').show();
                            $('#lbl_error').html(data.responseJSON.message);
                        }
                    });
                }
            })
        }
    </script>
@endsection
