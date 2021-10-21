@extends('main')
@section('subtitle',' Edit Event')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">
                            <a class="url-nav" href="{{route('events')}}">
                                <span>Events:</span>
                            </a>
                            <a class="url-nav" href="{{route('EventController.show',[$event->id])}}">
                                {{$event->name}}
                            </a> / Edit
                        </h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id" value="{{$event->id}}">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" value="{{$event->name}}"
                                                   required="" placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Size</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="size" name="size" value="{{$event->size}}"
                                                   placeholder="enter size" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event Start Date</label>
                                        <div class="col-sm-12">
                                            <input type="date" id="event_start_date" name="event_start_date"
                                                   value="{{$event->event_start_date}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event End Date</label>
                                        <div class="col-sm-12">
                                            <input type="date" id="event_end_date" name="event_end_date"
                                                   value="{{$event->event_end_date}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Accreditation Start Date</label>
                                        <div class="col-sm-12">
                                            <input type="date" id="accreditation_start_date"
                                                   name="accreditation_start_date"
                                                   value="{{$event->accreditation_start_date}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Accreditation End Date</label>
                                        <div class="col-sm-12">
                                            <input type="date" id="accreditation_end_date" name="accreditation_end_date"
                                                   value="{{$event->accreditation_end_date}}" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event Admin</label>
                                        <div class="col-sm-12">
                                            <select id="event_admin" name="event_admin" required="">
                                                @foreach ($eventAdmins as $eventAdmin)
                                                    <option value="{{ $eventAdmin->key }}"
                                                            @if ($eventAdmin->key == $event->event_admin)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventAdmin->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Registration Form Template</label>
                                        <div class="col-sm-12">
                                            <select id="event_form" name="event_form" required="">
                                                @foreach ($eventForms as $eventForm)
                                                    <option value="{{ $eventForm->key }}"
                                                           @if ($eventForm->key == $event->event_form)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventForm->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Owner</label>
                                        <div class="col-sm-12">
                                            <select id="owner" name="owner" required="">
                                                @foreach ($owners as $owner)
                                                    <option value="{{ $owner->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($owner->key == $event->owner)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $owner->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Organizer</label>
                                        <div class="col-sm-12">
                                            <select id="organizer" name="organizer" required="">
                                                @foreach ($organizers as $organizer)
                                                    <option value="{{ $organizer->key }}"
                                                            @if ($organizer->key == $event->organizer)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $organizer->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Location</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="location" name="location"
                                                   value="{{$event->location}}" placeholder="enter location"
                                                   required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event Type</label>
                                        <div class="col-sm-12">
                                            <select id="event_type" name="event_type" required="">
                                                @foreach ($eventTypes as $eventType)
                                                    <option value="{{ $eventType->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventType->key == $event->event_type)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventType->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Security Option</label>
                                        <div class="col-sm-12">
                                            <select id="approval_option" name="approval_option" required="">
                                                @foreach ($approvalOptions as $approvalOption)
                                                    <option value="{{ $approvalOption->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($approvalOption->key == $event->approval_option)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $approvalOption->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Security Officer</label>
                                        <div class="col-sm-12">
                                            <select id="security_officer"
                                                    @if ($event->approval_option == 1)
                                                    disabled=true
                                                    @endif
                                                    name="security_officer" required="">
                                                @foreach ($securityOfficers as $securityOfficer)
                                                    <option value="{{ $securityOfficer->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($securityOfficer->key == $event->security_officer)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $securityOfficer->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Status</label>
                                        <div class="col-sm-12">
                                            <select id="status" name="status" required="">
                                                @foreach ($eventStatuss as $eventStatus)
                                                    <option value="{{ $eventStatus->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventStatus->key == $event->status)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventStatus->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-2">
                                <button type="submit" id="btn-save" value="create">Save
                                </button>
                            </div>
                        </form>
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
                    <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}">
                    <div class="form-group">
                        <label>Security Category</label>
                        <div class="col-sm-12">
                            <select id="eventSecurityCategory" name="eventSecurityCategory" value="" required="">
                                @foreach ($securityCategories as $securityCategory)
                                    <option value="{{ $securityCategory->key }}"
                                            @if ($securityCategory->key == 1)
                                            selected="selected"
                                        @endif
                                    >{{ $securityCategory->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="btn-event-security-category-save" value="create">Save
                        </button>
                    </div>
                </div>
                <div class="modal-footer">

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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('eventEdit',[$event->id]) }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false}
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Event Security Category");
                $('#ajax-crud-modal').modal('show');
            });

            $('body').on('click', '#remove-event-security-category', function () {
                var post_id = $(this).data("id");
                confirm("Are You sure want to remove event security category ?!");
                $.ajax({
                    type: "get",
                    url: "../EventController/remove/" + post_id,
                    success: function (data) {
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('body').on('click', '#btn-event-security-category-save', function () {
                var event_id = $('#event_id').val();
                var eventSecurityCategory = $('#eventSecurityCategory').val();
                $.ajax({
                    type: "get",
                    url: "../EventController/storeEventSecurityCategory/" + event_id + "/" + eventSecurityCategory,
                    success: function (data) {
                        $('#ajax-crud-modal').modal('hide');
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $(document).on('change', '#approval_option', function () {
                var choosed = $('#approval_option').find(":selected").val();
                if (choosed == 1) {
                    $("#security_officer").prop('disabled', true);
                } else {
                    $('#security_officer').prop('disabled', false);
                }
            });
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function (form) {
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('EventController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Edited successfully');
                            window.location.href = "{{ route('events')}}";
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
