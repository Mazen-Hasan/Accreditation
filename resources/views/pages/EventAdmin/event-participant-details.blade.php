@extends('main')
@section('subtitle',' Templates')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">
                            <a class="url-nav" href="{{ route('event-admin') }} ">
                                <span>My Events:</span>
                            </a>
                            /
                            <a class="url-nav" href="{{route('eventCompanies',[$eventId])}}">
                                <span>{{$event_name}}</span>
                            </a>
                            /
                            <a class="url-nav" href="{{ route('eventCompanyParticipants',[$companyId,$eventId]) }}">
                                <span> {{$company_name}} - Participants</span>
                            </a>
                            / Details
                        </h4>
                        <input type="hidden" id="company_id" value="{{$companyId}}">
                        <input type="hidden" id="event_id" value="{{$eventId}}">
                        <form class="form-sample" id="templateForm" name="templateForm">
                            <?php echo $form ?>
                        </form>
                        <br>
                        <?php echo $attachmentForm ?>
                        <div class="col-sm-offset-2 col-sm-12" style="margin: 20px;">
                            <a class="btn btn-reddit" href="{{ URL::previous() }}">Go Back</a>
                            <?php echo $buttons ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="badge-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="badgeTitle"></h5>
                </div>
                <div class="modal-body">
                    <div class="row"></div>
                    <div class="row">
                        <img id="badge" src="" alt="Badge">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-element-confirm-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_element_id">
                        <input type="hidden" id="action_button">
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
    <div class="modal fade" id="delete-element-confirm-modal-new" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle-new"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="curr_element_id-new">
                        <!-- <input type="hidden" id="action_button"> -->
                        <label class="col-sm-12 confirm-text" id="confirmText-new"></label>
                        <textarea id="reason" style="margin-bottom:10px"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel-new">Cancel
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes-new">Return</button>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });


        $('body').on('click', '.preview-badge', function () {
            var src = $(this).data("src");
            var label = $(this).data("label")
            $('#badge-modal').modal('show');
            $('#badgeTitle').html(label);
            var image_path = "{{URL::asset('badges/')}}/";
            $('#badge').attr('src', image_path + src);
        });
        $('body').on('click', '#approve', function () {
            var post_id = $(this).data("id");
            var company_id = $('#company_id').val();
            var eventId = $('#event_id').val();
            $('#confirmTitle').html('Approve Participation Request');
            $('#curr_element_id').val(post_id);
            $('#action_button').val('approve');
            var confirmText = "Are you sure you want to approve event participation request?";
            $('#confirmText').html(confirmText);
            $('#delete-element-confirm-modal').modal('show');
        });
        $('body').on('click', '#reject', function () {
            var post_id = $(this).data("id");
            var company_id = $('#company_id').val();
            var eventId = $('#event_id').val();
            $('#confirmTitle').html('Reject Participation Request');
            $('#curr_element_id').val(post_id);
            $('#action_button').val('reject');
            var confirmText = "Are you sure you want to reject event participation request?";
            $('#confirmText').html(confirmText);
            $('#delete-element-confirm-modal').modal('show');
        });

        $('body').on('click', '#reject_with_correction', function () {
            var post_id = $(this).data("id");
            var company_id = $('#company_id').val();
            var eventId = $('#event_id').val();
            $('#confirmTitle-new').html('Participation Request needs review and correction');
            $('#curr_element_id-new').val(post_id);
            $('#reason').val('');
            var confirmText = "Insert reason:";
            $('#confirmText-new').html(confirmText);
            $('#delete-element-confirm-modal-new').modal('show');
        });

        $('#delete-element-confirm-modal button').on('click', function (event) {
            var $button = $(event.target);
            $(this).closest('.modal').one('hidden.bs.modal', function () {
                if ($button[0].id === 'btn-yes') {
                    var post_id = $('#curr_element_id').val();
                    var action_button = $('#action_button').val();
                    if (action_button == 'approve') {
                        // var company_id = $('#company_id').val();
                        // var event_id = $('#event_id').val();
                        var staffId = $('#curr_element_id').val();
                        var url = "{{ route('eventAdminControllerApprove', ":id") }}";
                        url = url.replace(':id', staffId);
                        $('#loader-modal').modal('show');
                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                $('#loader-modal').modal('hide');
                                // window.location.href = "../event-company-participants/" + company_id + "/" + event_id;
                                window.location.href = "{{route('eventCompanyParticipants',[$companyId, $eventId])}}";
                            },
                            error: function (data) {
                                $('#loader-modal').modal('hide');
                                console.log('Error:', data);
                            }
                        });
                    }

                    if (action_button == 'reject') {
                        // var company_id = $('#company_id').val();
                        // var event_id = $('#event_id').val();
                        var staffId = $('#curr_element_id').val();
                        var url = "{{ route('eventAdminControllerReject', ":id") }}";
                        url = url.replace(':id', staffId);
                        $('#loader-modal').modal('show');
                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                $('#loader-modal').modal('hide');
                                // window.location.href = "../event-company-participants/" + company_id + "/" + event_id;
                                window.location.href = "{{route('eventCompanyParticipants',[$companyId, $eventId])}}";
                            },
                            error: function (data) {
                                $('#loader-modal').modal('hide');
                                console.log('Error:', data);
                            }
                        });
                    }
                }
            });
        });

        $('#delete-element-confirm-modal-new button').on('click', function (event) {
            var $button = $(event.target);
            $(this).closest('.modal').one('hidden.bs.modal', function () {
                if ($button[0].id === 'btn-yes-new') {
                    var staffId = $('#curr_element_id-new').val();
                    var reason = $('#reason').val();
                    // var company_id = $('#company_id').val();
                    // var event_id = $('#event_id').val();

                    var url = "{{ route('eventAdminControllerRejectToCorrect', [":id",":res"]) }}";
                    url = url.replace(':id', staffId);
                    url = url.replace(':res', reason);
                    $('#loader-modal').modal('show');
                    $.ajax({
                        type: "get",
                        // url: "../eventAdminController/RejectToCorrect/" + staffId + "/" + reason,
                        url: url,
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            // window.location.href = "../event-company-participants/" + company_id + "/" + event_id;
                            window.location.href = "{{route('eventCompanyParticipants',[$companyId, $eventId])}}";
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });
    </script>
@endsection
