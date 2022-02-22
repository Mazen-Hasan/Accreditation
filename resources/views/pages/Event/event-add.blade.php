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
                            / Add New Event</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id">
                            <input type="hidden" name="current_date" id="current_date" value="">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" minlength="1" maxlength="100" name="name"
                                                   value="" required=""
                                                   placeholder="enter event name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Accreditation Size</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="size" min="1" max="20000" name="size"
                                                   placeholder="enter accreditation size" required=""/>
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
                                        <label>Event Owner</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="owner" minlength="1" maxlength="100"
                                                   name="owner" value=""
                                                   placeholder="enter owner" required=""/>
{{--                                            <select id="owner" name="owner" required="">--}}
{{--                                                <option value="default">Please select Event Owner</option>--}}
{{--                                                @foreach ($owners as $owner)--}}
{{--                                                    <option value="{{ $owner->key }}"--}}
{{--                                                            @if ($owner->key == -1)--}}
{{--                                                            selected="selected"--}}
{{--                                                        @endif--}}
{{--                                                    >{{ $owner->value }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event Organizer</label>
                                        <div class="col-sm-12">
                                            <select id="organizer" name="organizer" required="">
                                                <option value="default">Please select Event Organizer</option>
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
                                        <label>Location</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="location" minlength="1" maxlength="100"
                                                   name="location" value=""
                                                   placeholder="enter location" required=""/>
                                        </div>
                                    </div>
                                </div>
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
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Event Admin</label>
                                        <div class="col-sm-12">
                                            <select id="event_admins" name="event_admins[]" required="" multiple>
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
                                            <select id="security_officers" name="security_officers[]" required=""
                                                    multiple>
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
                                <div class="col-sm-offset-2 col-sm-2">
                                    <button type="submit" id="btn-save" value="create">Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="event-organizer-copy-confirm-modal" data-backdrop="static" data-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="col-sm-12 confirm-text" id="confirmText"></label>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes">Ok</button>
                        </div>
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
        // var event_admins = [];
        var t = 0;
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var output = d.getFullYear() + '/' +
                (month < 10 ? '0' : '') + month + '/' +
                (day < 10 ? '0' : '') + day;
            //alert(output);
            $('#current_date').val(output);
            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Post");
                $('#ajax-crud-modal').modal('show');
            });

            $(document).on('change', '#approval_option', function () {
                let choose = $('#approval_option').find(":selected").val();
                if (choose == 1) {
                    $("#security_officers").prop('disabled', true);
                }  else{
                    $('#security_officers').prop('disabled', false);
                }
            });

            $('#organizer').on('change', function () {
                let organizer_id = $('#organizer').val();
                let url = "{{ route('eventCheckSameEventOrganizer', ":id") }}";
                url = url.replace(':id', organizer_id);
                $.ajax({
                    type: "get",
                    url: url,
                    success: function (data) {
                        if (data.exist === 1) {
                            $('#confirmTitle').html('Add new event');
                            var confirmText = 'This organizer has another events, all companies, subsidiaries, focal points, data entries will be copied to the new event, Sizes of the companies and subsidiaries must be managed as they set to "0"';
                            $('#confirmText').html(confirmText);
                            $('#event-organizer-copy-confirm-modal').modal('show');
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                rules: {
                    event_start_date: {greaterThanOrEqual: "#current_date"},
                    accreditation_start_date: {greaterThanOrEqual: "#current_date"},
                    event_end_date: {greaterThan: "#event_start_date"},
                    accreditation_start_date: {lessThan: "#event_end_date"},
                    // accreditation_start_date: {greaterThan: "#event_start_date"},
                    accreditation_end_date: {greaterThan: "#accreditation_start_date"},
                    accreditation_end_date: {lessThan: "#event_end_date"},
                    security_officers: {valueNotEquals: "default"},
                    security_categories: {valueNotEquals: "default"},
                    status: {valueNotEquals: "default"},
                    approval_option: {valueNotEquals: "default"},
                    event_type: {valueNotEquals: "default"},
                    owner: {valueNotEquals: "default"},
                    organizer: {valueNotEquals: "default"},
                    event_form: {valueNotEquals: "default"},
                    event_admins: {valueNotEquals: "default"}
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
                    $('#loader-modal').modal('show');
                    $('#btn-save').prop('disabled', true);
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('EventController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#loader-modal').modal('hide');
                            $('#btn-save').html('Done');
                            window.location.href = "{{ route('events')}}";
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                            $('#btn-save').html('Saved');
                        }
                    });
                }
            })
        }

        jQuery.validator.addMethod("greaterThanOrEqual",
            function (value, element, params) {
                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) >= new Date($(params).val());
                }
                return isNaN(value) && isNaN($(params).val())
                    || (Number(value) >= Number($(params).val()));
            }, 'Must be greater than or equal to today.');

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

        // $("#event_admin_add").click(function () {
        //     var event_admin_id = $("#event_admin_multi").val();
        //     var event_admin_val = $("#event_admin_multi").find(":selected").text();
        //
        //     if(event_admin_id !== 'default'){
        //         event_admins.push(event_admin_id);
        //         var html = '';
        //         html += '<div id="eventAdminRow">';
        //         html += '<div class="input-group mb-3">';
        //         html += '<input type="hidden" class="form-control m-input" value="' + event_admin_id + '">';
        //         html += '<label class="form-control m-input">' + event_admin_val + '</label>';
        //         html += '<div class="input-group-append">';
        //         html += '<a href="javascript:void(0)" id="remove"><i class="fas fa-minus-circle"></i></a>';
        //         html += '</div>';
        //         html += '</div>';
        //
        //         $("#event_admin_list").append(html);
        //
        //         console.log(event_admins);
        //     }
        // });
        //
        // $(document).on('click', '#remove', function () {
        //     $(this).closest('#eventAdminRow').remove();
        //     console.log($(this));
        //     // event_admins.remove($(this));
        //     console.log(event_admins);
        // });
    </script>
@endsection
