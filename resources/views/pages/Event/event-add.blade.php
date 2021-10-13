@extends('main')
@section('subtitle',' Add Event')
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
                            / New</h4>
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
                                            <input type="text" id="name" minlength="1" maxlength="100" name="name" value="" required=""
                                                   placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Size</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="size" min="1" max="20000" name="size"
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
                                                   data-label="Event Start Date" value="" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event End Date</label>
                                        <div class="col-sm-12">
                                            <input type="date" id="event_end_date" name="event_end_date"
                                                   data-label="Event End Date" required=""/>
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
                                                   name="accreditation_start_date" data-label="Accreditation Start Date"
                                                   required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Accreditation End Date</label>
                                        <div class="col-sm-12">
                                            <input type="date" id="accreditation_end_date" name="accreditation_end_date"
                                                   data-label="Accreditation End Date" required=""/>
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
                                                <option value="default">Please select Event Admin</option>
                                                @foreach ($eventAdmins as $eventAdmin)
                                                    <option value="{{ $eventAdmin->key }}"
                                                            @if ($eventAdmin->key == -1)
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
                                                <option value="default">Please select Registration Form Template
                                                </option>
                                                @foreach ($eventForms as $eventForm)
                                                    <option value="{{ $eventForm->key }}"
                                                            @if ($eventForm->key == -1)
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
                                                <option value="default">Please select Owner</option>
                                                @foreach ($owners as $owner)
                                                    <option value="{{ $owner->key }}"
                                                            @if ($owner->key == -1)
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
                                                <option value="default">Please select Organizer</option>
                                                @foreach ($organizers as $organizer)
                                                    <option value="{{ $organizer->key }}"
                                                            @if ($organizer->key == -1)
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
                                            <input type="text" id="location" minlength="1" maxlength="100" name="location" value=""
                                                   placeholder="enter location" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event Type</label>
                                        <div class="col-sm-12">
                                            <select id="event_type" name="event_type" required="">
                                                <option value="default">Please select Event Type</option>
                                                @foreach ($eventTypes as $eventType)
                                                    <option value="{{ $eventType->key }}"
                                                            @if ($eventType->key == -1)
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
                                                <option value="default">Please select Security Option</option>
                                                @foreach ($approvalOptions as $approvalOption)
                                                    <option value="{{ $approvalOption->key }}"
                                                            @if ($approvalOption->key == -1)
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
                                            <select id="security_officer" name="security_officer" required="">
                                                <option value="default">Please select Security Officer</option>
                                                @foreach ($securityOfficers as $securityOfficer)
                                                    <option value="{{ $securityOfficer->key }}"
                                                            @if ($securityOfficer->key == -1)
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
                                                <option value="default">Please select Status</option>
                                                @foreach ($eventStatuss as $eventStatus)
                                                    <option value="{{ $eventStatus->key }}"
                                                            @if ($eventStatus->key == -1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $eventStatus->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Security Group</label>
                                        <div class="col-sm-12">
                                            <select multiple id="security_categories" name="security_categories[]"
                                                    required="">
                                                <option value="default">Please select Security Group</option>
                                                @foreach ($securityCategories as $securityCategory)
                                                    <option value="{{ $securityCategory->key }}"
                                                            @if ($securityCategory->key == -1)
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
        $(document).ready(function () {
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
                rules: {
                    event_end_date: {greaterThan: "#event_start_date"},
                    accreditation_start_date: {lessThan: "#event_end_date"},
                    accreditation_start_date: {greaterThan: "#event_start_date"},
                    accreditation_end_date: {greaterThan: "#accreditation_start_date"},
                    accreditation_end_date: {lessThan: "#event_end_date"},
                    security_officer: {valueNotEquals: "default"},
                    security_categories: {valueNotEquals: "default"},
                    status: {valueNotEquals: "default"},
                    approval_option: {valueNotEquals: "default"},
                    event_type: {valueNotEquals: "default"},
                    owner: {valueNotEquals: "default"},
                    organizer: {valueNotEquals: "default"},
                    event_form: {valueNotEquals: "default"},
                    event_admin: {valueNotEquals: "default"}
                },
                messages: {
                    event_end_date: {greaterThan: "Must be greater than event start date."},
                    accreditation_end_date: {greaterThan: "Must be greater than accreditation start date."},
                    accreditation_start_date: {lessThan: "Must be less than event end date"},
                    accreditation_start_date: {greaterThan: "Must be greater than event start date."},
                    accreditation_end_date: {lessThan: "Must be less than event end date."}
                },

                submitHandler: function (form) {
                    $('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
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
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }
        jQuery.validator.addMethod("greaterThan",
            function (value, element, params) {
                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) > new Date($(params).val());
                }
                return isNaN(value) && isNaN($(params).val())
                    || (Number(value) > Number($(params).val()));
            }, 'Must be greater than {0}.');
        jQuery.validator.addMethod("lessThan",
            function (value, element, params) {
                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) < new Date($(params).val());
                }
                return isNaN(value) && isNaN($(params).val())
                    || (Number(value) < Number($(params).val()));
            }, 'Must be less than {0}.');
        jQuery.validator.addMethod("valueNotEquals",
            function (value, element, params) {
                return params !== value;
            }, " Please select a value");
    </script>
@endsection
