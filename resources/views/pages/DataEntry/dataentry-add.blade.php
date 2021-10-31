@extends('main')
@section('subtitle',' Add Data Entry')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('custom_navbar')
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
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <br>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">
                            Data Entry - New
                        </h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id">
                            <input type="hidden" name="action_type" id="action_type" value="">
                            <input type="hidden" name="account_id" id="account_id" value="">
                            <input type="hidden" name="event_id" id="event_id" value={{$eventId}}>
                            <input type="hidden" name="company_id" id="company_id" value={{$companyId}}>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" placeholder="enter name"
                                                   required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group col">
                                        <label>Middle Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="middle_name" name="middle_name"
                                                   placeholder="enter middle name" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="last_name" name="last_name"
                                                   placeholder="enter last name" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Email</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="email" name="email" placeholder="enter email"
                                                   required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="telephone" name="telephone"
                                                   placeholder="enter telephone" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Mobile</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="mobile" name="mobile" placeholder="enter mobile"
                                                   required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Account Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="account_name" name="account_name"
                                                   placeholder="enter account name" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Account Email</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="account_email" name="account_email"
                                                   placeholder="enter account email" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Account Pasword</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="password" name="password"
                                                   placeholder="enter account password" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Status</label>
                                        <div class="col-sm-12">
                                            <select id="status" name="status" value="" required="">
                                                @foreach ($contactStatuss as $contactStatus)
                                                    <option value="{{ $contactStatus->key }}"
                                                            @if ($contactStatus->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $contactStatus->value }}</option>
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
    <div class="modal fade" id="event-organizer-copy-confirm-modal" aria-hidden="true">
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

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Contact");
                $('#ajax-crud-modal').modal('show');
            });
            $('#btn-cancel').click(function () {
                $('#action_type').val("");
                $('#account_id').val("");
                $('#event-organizer-copy-confirm-modal').modal('hide');
                window.location.href = "{{ route('dataentrys',[$companyId,$eventId])}}";
            });
            $('#btn-yes').click(function () {
                var account_id = $('#account_id').val();
                if(account_id != 0){
                    $('#action_type').val("add_existed");
                    $("#postForm").submit();
                }else{
                    window.location.href = "{{ route('dataentrys',[$companyId,$eventId])}}";
                }
            });
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function (form) {
                    $('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    // alert($('#postForm').serialize());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('dataentryController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#action_type').val("");
                            $('#account_id').val("");
                            $('#btn-cancel').show();
                            if(data.code == 400){
                                $('#account_id').val(data.id);
                                $('#confirmTitle').html('Add new data entry');
                                var confirmText = data.message;
                                $('#confirmText').html(confirmText);
                                $('#btn-cancel').show();
                                $('#event-organizer-copy-confirm-modal').modal('show');
                            }else{
                                if(data.code == 401){
                                    $('#account_id').val(data.id);
                                    $('#confirmTitle').html('Add new data entry');
                                    var confirmText = data.message;
                                    $('#confirmText').html(confirmText);
                                    $('#btn-cancel').hide();
                                    $('#btn-yes').html('OK');
                                    $('#event-organizer-copy-confirm-modal').modal('show');
                                }else{
                                    $('#postForm').trigger("reset");
                                    $('#ajax-crud-modal').modal('hide');
                                    $('#btn-save').html('Add successfully');
                                    window.location.href = "{{ route('dataentrys',[$companyId,$eventId])}}";
                                }
                            }
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
