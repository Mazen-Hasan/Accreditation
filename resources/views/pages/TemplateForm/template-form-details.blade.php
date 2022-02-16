@extends('main')
@section('subtitle',' Participants - Details')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('custom_navbar')
            @if($subCompany_nav == 1)
                <li id="subsidiaries_nav" class="nav-item">
                     <a class="nav-link {{ str_contains( Request::route()->getName(),'subCompanies') =="1" ? "active" : "" }}"
                        href="{{ route('subCompanies',[$companyId,$eventId]) }} ">
                         <i class="logout">
                             <img src="{{ asset('images/menu.png') }}" alt="My Sidries">
                         </i>
                         <span class="menu-title">Subsidiaries</span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link {{ str_contains( Request::route()->getName(),'dataentrys') =="1" ? "active" : "" }}"
                        href="{{ route('dataentrys',[$companyId,$eventId]) }}">
                         <i class="logout">
                             <img src="{{ asset('images/menu.png') }}" alt="Data Entry">
                         </i>
                         <span class="menu-title">Data Entry</span>
                     </a>
                 </li>
                 <li class="nav-item">
                    <a class="nav-link {{ str_contains( Request::route()->getName(),'focalpoints') =="1" ? "active" : "" }}"
                    href="{{ route('focalpoints') }}">
                        <i class="logout">
                            <img src="{{ asset('images/user_mng.png') }}" alt="Focal Points">
                        </i>
                        <span class="menu-title">Focal Points</span>
                    </a>
                </li>
                @endif
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
            <input type="hidden" id="subCompnay_status" value={{$subCompany_nav}} />
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">
                            <a class="url-nav" href="{{ route('company-admin') }} ">
                                <span>My Events:</span>
                            </a>
                            <a class="url-nav" href="{{ route('companyParticipants',[$companyId,$eventId]) }} ">
                                <span>{{$event_name}} - {{$company_name}}</span>
                            </a>
                            / Participants - Details
                        </h4>
                        <form class="form-sample" id="templateForm" name="templateForm">
                        <input type="hidden" id="company_id" value={{$companyId}} />
                        <input type="hidden" id="event_id" value={{$eventId}} />
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
                    <div class="row">
                    </div>
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
            var subCompany_status = $('#subCompnay_status').val();
            if (subCompany_status == 0) {
                $('#subsidiaries_nav').hide();
            }
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
        $('body').on('click', '#send_request', function () {
            var post_id = $(this).data("id");
            var company_id = $('#company_id').val();
            var eventId = $('#event_id').val();
            $('#confirmTitle').html('Send Participation Request');
            $('#curr_element_id').val(post_id);
            $('#action_button').val('sendRequest');
            var confirmText = "Are you sure you want to Send event participation?";
            $('#confirmText').html(confirmText);
            $('#delete-element-confirm-modal').modal('show');
        });
        $('#delete-element-confirm-modal button').on('click', function (event) {
            var $button = $(event.target);
            $(this).closest('.modal').one('hidden.bs.modal', function () {
                if ($button[0].id === 'btn-yes') {
                    var post_id = $('#curr_element_id').val();
                    var action_button = $('#action_button').val();
                    if (action_button == 'sendRequest') {
                        $('#loader-modal').modal('show');
                        var staffId = $('#curr_element_id').val();
                        var url = "{{ route('companyAdminControllerSendRequest', ":id") }}";
                        url = url.replace(':id', staffId);

                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (data) {
                                $('#loader-modal').modal('hide');
                                window.location.href = "{{ route('companyParticipants',[$companyId,$eventId])}}";
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
    </script>
@endsection
