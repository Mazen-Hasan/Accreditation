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
                                            <input type="number" min="1" max="10000" id="size" name="size" value="{{$event->size}}"
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
                                        <label>Owner</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="owner" name="owner" value="{{$event->owner}}"
                                                   required="" placeholder="enter name"/>
{{--                                            <select id="owner" name="owner" required="">--}}
{{--                                                @foreach ($owners as $owner)--}}
{{--                                                    <option value="{{ $owner->key }}"--}}
{{--                                                            @if ($owner->key == $event->owner)--}}
{{--                                                    selected="selected"--}}
{{--                                                        @endif--}}
{{--                                                    >{{ $owner->value }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
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
                                                    <option value="{{ $eventType->key }}"@if ($eventType->key == $event->event_type)
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
                                                    <option value="{{ $approvalOption->key }}"{{--                                                            @if ($key == old('myselect', $model->option))--}}
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
                                        <label>Status</label>
                                        <div class="col-sm-12">
                                            <select id="status" name="status" required="">
                                                @foreach ($eventStatuss as $eventStatus)
                                                    <option value="{{ $eventStatus->key }}"
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
                            <div class="row">
                                <div class="col-md-6" style="display:none">
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
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function (form) {
                	$("#btn-save").prop("disabled",true);
                	$('#loader-modal').modal('show');
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('EventController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                        	$('#loader-modal').modal('hide');
                            $('#postForm').trigger("reset");
                            $('#btn-save').html('Done');
                            window.location.href = "{{ route('events')}}";
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        	$('#loader-modal').modal('hide');
                            $('#btn-save').html('Save Changes');
                        	window.location.href = "{{ route('events')}}";
                        }
                    });
                }
            })
        }
    </script>
@endsection
