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
                    <input type="hidden" id="company_id" value="{{$companyId}}">
                    <input type="hidden" id="event_id" value="{{$eventId}}">
                        <h4 class="card-title">Participant - Detials</h4>
                        <form class="form-sample" id="templateForm" name="templateForm">
                            <?php echo $form ?>
                        </form>
                        <br>
                        <?php echo $attachmentForm ?>
                        <div class="col-sm-offset-2 col-sm-12">
                            <a class="btn btn-reddit" href="{{ URL::previous() }}">Go Back</a>
                            <?php echo $buttons ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="badge-modal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="badgeTitle"></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
<!--                        --><?php //var_dump(gd_info());  ?>
                    </div>
                    <div class="row">
                        <img id="badge" src="" alt="Badge">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-element-confirm-modal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
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
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">Cancel</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-element-confirm-modal-new" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
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
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel-new">Cancel</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes-new">Reject</button>
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
        });

        $('body').on('click', '.preview-badge', function () {
            //alert($(this).data("id"));
                var src = $(this).data("src");
                var label = $(this).data("label")
                $('#badge-modal').modal('show');
                $('#badgeTitle').html(label);
                var image_path = "{{URL::asset('badges/')}}/";
                $('#badge').attr('src', image_path + src );
            });
            $('body').on('click', '#approve', function () {
                var post_id = $(this).data("id");
                //alert(post_id);
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle').html('Approve Participation Request');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('approve');
                var confirmText =  "Are You sure you want to Approve Event participation Request?";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });
            $('body').on('click', '#reject', function () {
                var post_id = $(this).data("id");
                //alert(post_id);
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle').html('Reject Participation Request');
                $('#curr_element_id').val(post_id);
                $('#action_button').val('reject');
                var confirmText =  "Are You sure you want to reject Event participation Request?";
                $('#confirmText').html(confirmText);
                $('#delete-element-confirm-modal').modal('show');
            });
            $('body').on('click', '#reject_with_correction', function () {
                var post_id = $(this).data("id");
                var company_id = $('#company_id').val();
                var eventId = $('#event_id').val();
                $('#confirmTitle-new').html('Reject Participation Request To Correct');
                $('#curr_element_id-new').val(post_id);
                // $('#action_button').val('approve');
                $('#reason').val('');
                var confirmText =  "Insert Reason:";
                $('#confirmText-new').html(confirmText);
                $('#delete-element-confirm-modal-new').modal('show');
            });
            $('#delete-element-confirm-modal button').on('click', function(event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function() {
                    if($button[0].id === 'btn-yes'){
                        var post_id = $('#curr_element_id').val();
                        var action_button = $('#action_button').val();
                        if(action_button == 'approve'){
                            var staffId = $('#curr_element_id').val();
                            var company_id = $('#company_id').val();
                            var event_id = $('#event_id').val();
                            $.ajax({
                                type: "get",
                                url: "../securityOfficerAdminController/Approve/"+staffId,
                                success: function (data) {
                                    // var oTable = $('#laravel_datatable').dataTable();
                                    // oTable.fnDraw(false);
                                    // window.location.href = "{{ route('securityOfficerCompanyParticipants',['"+company_id+"','"+event_id+"'])}}";
                                    window.location.href = "../security-officer-company-participants/"+company_id+"/"+event_id;
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                        if(action_button == 'reject'){
                            var company_id = $('#company_id').val();
                            var event_id = $('#event_id').val();
                            var staffId = $('#curr_element_id').val();
                            $.ajax({
                                type: "get",
                                url: "../securityOfficerAdminController/Reject/"+staffId,
                                success: function (data) {
                                    // var oTable = $('#laravel_datatable').dataTable();
                                    // $('#send-approval-request').hide();
                                    // $('#add-new-post').hide();
                                    // oTable.fnDraw(false);
                                    //window.location.href = "{{ route('securityOfficerCompanyParticipants',['"+company_id+"','"+event_id+"'])}}";
                                    window.location.href = "../security-officer-company-participants/"+company_id+"/"+event_id;
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    }
                });
            });
            $('#delete-element-confirm-modal-new button').on('click', function(event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function() {
                    if($button[0].id === 'btn-yes-new'){
                        var staffId = $('#curr_element_id-new').val();
                        var reason = $('#reason').val();
                        var company_id = $('#company_id').val();
                        var event_id = $('#event_id').val();
                        $.ajax({
                                type: "get",
                                url: "../securityOfficerAdminController/RejectToCorrect/"+staffId+"/"+reason,
                                success: function (data) {
                                    // var oTable = $('#laravel_datatable').dataTable();
                                    // oTable.fnDraw(false);
                                    //window.location.href = "{{ route('securityOfficerCompanyParticipants',['"+company_id+"','"+event_id+"'])}}";
                                    window.location.href = "../security-officer-company-participants/"+company_id+"/"+event_id;
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                        });
                    }
                });
            });


        </script>
@endsection
