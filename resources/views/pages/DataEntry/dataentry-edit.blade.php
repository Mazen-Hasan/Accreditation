@extends('main')
@section('subtitle',' Edit Data Entry')
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
                            <img src="{{ asset('images/user_mng.png') }}" alt="Subsidiaries Accounts">
                        </i>
                        <span class="menu-title">Subsidiaries Accounts</span>
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
                        <h4 class="card-title">{{$focalpoint->name.' '.$focalpoint->middle_name.' '.$focalpoint->last_name}}
                            (Data Entry) - Edit</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id" value="{{$focalpoint->id}}">
                            <input type="hidden" name="company_id" id="company_id" value="{{$companyId}}">
                            <input type="hidden" name="event_id" id="event_id" value="{{$eventId}}">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" placeholder="enter name" minlength="1" maxlength="50"
                                                   required="" value="{{$focalpoint->name}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="last_name" name="last_name" minlength="1" maxlength="50"
                                                   placeholder="enter last name" required=""
                                                   value="{{$focalpoint->last_name}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display:none">
                                <div class="col-sm-6">
                                    <div class="form-group col">
                                        <label>Middle Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="middle_name" name="middle_name" minlength="1" maxlength="50"
                                                   placeholder="enter middle name" required=""
                                                   value="{{$focalpoint->middle_name}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Email</label>
                                        <div class="col-sm-12">
                                            <input type="email" id="email" name="email" placeholder="enter email" minlength="1" maxlength="50"
                                                   required="" value="{{$focalpoint->email}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="telephone" name="telephone"
                                                   placeholder="enter telephone" required=""
                                                   value="{{$focalpoint->telephone}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Mobile</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="mobile" name="mobile" placeholder="enter mobile"
                                                   required="" value="{{$focalpoint->mobile}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none;">
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
                                <div class="col-md-6" style="display:none;">
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
                                                            @if ($contactStatus->key == $focalpoint->status)
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

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Contact");
                $('#ajax-crud-modal').modal('show');
            });
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function (form) {
                    var actionType = $('#btn-save').val();
                   	$('#loader-modal').modal('show');
                	$('#btn-save').prop('disabled', true);
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('dataentryController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#loader-modal').modal('hide');
                            $('#btn-save').html('Done');
                            window.location.href = "{{ route('dataentrys',[$companyId,$eventId])}}";
                            // var oTable = $('#laravel_datatable').dataTable();
                            // oTable.fnDraw(false);
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }
    </script>
@endsection
