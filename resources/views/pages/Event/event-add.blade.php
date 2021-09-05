@extends('main')
@section('subtitle',' Add Event')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card"  style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">Event Management - New</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" value="" required="" placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Period</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="period" name="period" value="" required=""placeholder="enter period"/>
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
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventAdmin->key == 1)
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
                                        <label>Accreditation Period</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="accreditation_period" name="accreditation_period" value="" placeholder="enter accreditation period" required=""/>
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
                                                                @if ($owner->key == 1)
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
                                        <label class=" col-form-label">Organizer</label>
                                        <div class="col-sm-12">
                                           <select id="organizer" name="organizer" required="">
                                                @foreach ($organizers as $organizer)
                                                    <option value="{{ $organizer->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($organizer->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $organizer->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
{{--                            <p class="card-description">--}}
{{--                                Address--}}
{{--                            </p>--}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Location</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="location" name="location" value="" placeholder="enter location"  required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Size</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="size" name="size" placeholder="enter size" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Security Officer</label>
                                        <div class="col-sm-12">
                                           <select id="security_officer" name="security_officer" required="">
                                                @foreach ($securityOfficers as $securityOfficer)
                                                    <option value="{{ $securityOfficer->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($securityOfficer->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $securityOfficer->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Security Option</label>
                                        <div class="col-sm-12">
                                           <select id="approval_option" name="approval_option" required="">
                                                @foreach ($approvalOptions as $approvalOption)
                                                    <option value="{{ $approvalOption->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($approvalOption->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $approvalOption->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Event Type</label>
                                        <div class="col-sm-12">
                                           <select id="event_type" name="event_type" required="">
                                                @foreach ($eventTypes as $eventType)
                                                    <option value="{{ $eventType->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventType->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventType->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Status</label>
                                        <div class="col-sm-12">
                                           <select id="status" name="status" required="">
                                                @foreach ($eventStatuss as $eventStatus)
                                                    <option value="{{ $eventStatus->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventStatus->key == 1)
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
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label >Registration Form Template</label>
                                        <div class="col-sm-12">
                                           <select id="event_form" name="event_form" required="">
                                                @foreach ($eventForms as $eventForm)
                                                    <option value="{{ $eventForm->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($eventForm->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventForm->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Security Group</label>
                                        <div class="col-sm-12">
                                            <select  multiple id="security_categories" name="security_categories[]" required="" style="height: 150px">
                                                @foreach ($securityCategories as $securityCategory)
                                                    <option value="{{ $securityCategory->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($securityCategory->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $securityCategory->value }}</option>
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
@endsection
@section('script')
    <script>
        $(document).ready( function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
                $('#ajax-crud-modal').modal('show');
            });
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function(form) {
                    $('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    //alert($('#postForm').serialize());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('EventController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Add successfully');
                            window.location.href = "{{ route('events')}}";
                            // var oTable = $('#laravel_datatable').dataTable();
                            // oTable.fnDraw(false);
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
