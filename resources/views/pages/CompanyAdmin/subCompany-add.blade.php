@extends('main')
@section('subtitle',' Add Company')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
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
                            <img src="{{ asset('images/user_mng.png') }}" alt="Subsidiaries Accounts">
                        </i>
                        <span class="menu-title">Subsidiaries Accounts</span>
                    </a>
                </li>
                @endif
@endsection
@section('content')
    <div class="content-wrapper">
        <br>
        <br>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title"> {{$event_name}} / {{$company_name}} / Subsidiary - New</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="need_management" id="need_management" value="0">
                            <input type="hidden" name="parent_id" id="parent_id" value="{{$companyId}}">
                            <input style="visibility: hidden" name="event_id" id="event_id" value="{{$eventId}}">
                            <input type="hidden" name="post_id" id="post_id">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="company_name" name="company_name" minlength="1" maxlength="100" value="" required=""
                                                   placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Focal Point</label>
                                        <div class="col-sm-12">
                                            <select id="focal_point" name="focal_point" value="" required="">
                                                <option value="default">Please select focal point</option>
                                                <option value="-2" id="instant_add">add new focal point</option>
                                                @foreach ($focalPoints as $focalPoint)
                                                    <option value="{{ $focalPoint->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($focalPoint->key == -1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $focalPoint->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="telephone" name="telephone" value="" required=""
                                                   placeholder="enter telephone"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Website</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="website" name="website" value=""
                                                   placeholder="enter website"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Size</label>
                                        <div class="col-sm-12">
                                            <input type="number" id="size" name="size" min="1" max="{{$allowedSize}}" value="" required=""
                                                   placeholder="enter size"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Address</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="address" name="address" minlength="1" maxlength="150" value="" required=""
                                                   placeholder="enter address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Country</label>
                                        <div class="col-sm-12">
                                            <select id="country" name="country" value="" required="">
                                            <option value="default">Please select country</option>
                                                @foreach ($countrys as $country)
                                                    <option value="{{ $country->key }}"
                                                            @if ($country->key == -1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $country->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>City</label>
                                        <div class="col-sm-12" id="container">
                                            <select id="city" name="city" value="" required="">
                                            <option value="default">Please select city</option>
                                                @foreach ($citys as $city)
                                                    <option value="{{ $city->key }}"
                                                            @if ($city->key == -1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $city->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Company Category</label>
                                        <div class="col-sm-12">
                                            <select id="category" name="category" value="" required="">
                                            <option value="default">Please select category</option>
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($category->key == -1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $category->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Status</label>
                                        <div class="col-sm-12">
                                            <select id="company_status" name="company_status" value="" required="">
                                            <option value="default">Please select status</option>
                                                @foreach ($statuss as $status)
                                                    <option value="{{ $status->key }}"
{{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($status->key == -1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $status->value }}</option>
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
                        <input type="hidden" id="curr_size" name="curr_size">
                        <input type="hidden" id="action_button">
                        <label class="col-sm-12 confirm-text" id="confirmText"></label>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">No, Manage
                                them myself
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
    <div class="modal fade" id="add-focal-point-modal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Add New Focal Point</h5>
                </div>
                <div class="modal-body">
                    <form class="form-sample" id="focaPointForm" name="focaPointForm">
                        <input type="hidden" id="entry_type" name="entry_type" value="instant" />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" placeholder="enter name"
                                                   minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="last_name" name="last_name"
                                                   placeholder="enter last name" minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!--                             <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="last_name" name="last_name"
                                                   placeholder="enter last name" minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Email</label>
                                        <div class="col-sm-12">
                                            <input type="email" id="email" name="email" placeholder="enter email"
                                                   required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="telephone" name="telephone"
                                                   placeholder="enter telephone" minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Mobile</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="mobile" name="mobile" placeholder="enter mobile"
                                                   minlength="1" maxlength="50" required=""/>
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
                                                   placeholder="enter account name" minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Account Email</label>
                                        <div class="col-sm-12">
                                            <input type="email" id="account_email" name="account_email"
                                                   placeholder="enter account email" minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Account Password</label>
                                        <div class="row">
                                            <div class="col-sm-11">
                                                <input style="margin-left: 16px; width: 103%" type="password"
                                                       id="password" name="password" placeholder="enter password"
                                                       required=""/>
                                            </div>
                                            <div class="col-sm-1" id="eye">
                                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Status</label>
                                        <div class="col-sm-12">
                                            <select id="status" name="status" required="">
                                                <option value="default">Please select status</option>
                                                <option value="0">InActive</option>
                                                <option value="1">Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <button type="button" style="width:200px" class="btn-cancel" data-dismiss="modal" id="btn-cancel">Cancel
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" id="btn-add-focal">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="event-organizer-copy-confirm-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="focalentryconfirmTitle"></h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="col-sm-12 confirm-text" id="focalentryconfirmText"></label>
                        <input type="hidden" id="focal_point_name" value=""/>
                        <input type="hidden" id="focal_point_id" value=""/>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-gone">Cancel
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-confirm">Yes</button>
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
            $('#focal_point').on('change', function () {
                //alert('i am here');
                var selectedFocal = $('#focal_point option:selected').val();
                if(selectedFocal == -2){
                    //window.location.href = "{{ route('focalpointAdd')}}";
                    $('#name').val('');
                    $('#last_name').val('');
                    $('#middle_name').val('');
                    $('#telephone').val('');
                    $('#mobile').val('');
                	$('#email').val('');
                    $('#account_name').val('');
                    $('#account_email').val('');
                    $('#password').val('');
                    $('#status').val('default');
                    $('#btn-add-focal').html('Save');
                    $('#add-focal-point-modal').modal('show');
                }
            });
        $('#togglePassword').click(function () {
            var type = $('#password').attr('type') === 'password' ? 'text' : 'password';
            $('#password').attr('type', type);
            if (type === 'text') {
                $('#togglePassword').removeClass('fa fa-eye-slash');
                $('#togglePassword').addClass('fa fa-eye');
            } else {
                $('#togglePassword').removeClass('fa fa-eye');
                $('#togglePassword').addClass('fa fa-eye-slash');
            }
        });
        $('#country').on('change', function () {
            // $('#lbl_select').html('');
            // $('#btn-filter').html('Filter');
            //resetAll();
            $('#loader-modal').modal('show');
            var url = "{{ route('getSubCompnayCities', ":id") }}";
            url = url.replace(':id', this.value);

            $.ajax({
                type: "get",
                // url: "fullFillmentController/getCompanies/" + this.value,
                url: url,
                success: function (data) {
                    $('#loader-modal').modal('hide');
                    var citySelectOptions = data;
                    $('#container').html('');
                    var html = '<select id="city" name="city" required="">';
                    html = html + "<option selected='selected' value='default'>Please select city</option>";
                    var count = 0;
                    while (count < citySelectOptions.length) {
                        // if (count == 0) {
                        //     html = html + "<option selected='selected' value='default'>Please select city</option>";
                        // } else {
                            html = html + "<option value=" + citySelectOptions[count].key + ">" + citySelectOptions[count].value + "</option>";
                        //}
                        count++;
                    }
                    html = html + '<select/>';
                    $('#container').append(html);
                },
                error: function (data) {
                    $('#loader-modal').modal('hide');
                    console.log('Error:', data);
                }
            });
        });
        $('#btn-confirm').click(function () {
            var name = $('#focal_point_name').val();
            var id = $('#focal_point_id').val();
            var found = false;
            if(id != ''){
                $("#focal_point").find("option").each(function() {
                    if($(this).val() == id){
                        found = true;
                    }
                });
                if(found){
                    $("#focal_point").val(id);
                }else{
                    $("#focal_point").append(new Option(name, id));
                    $("#focal_point").val(id);
                }
                $('#event-organizer-copy-confirm-modal').modal('hide');
                $('#focal_point_id').val('');
                $('#focal_point_name').val('');
            }else{
                $('#focal_point').val('default');
            }
        });
        $('#btn-gone').click(function () {
            $('#focal_point').val('default');
        });
        if ($("#postForm").length > 0) {
            $("#postForm").validate({                
                rules: {
                    company_status: {valueNotEquals: "default"},
                    category: {valueNotEquals: "default"},
                    city: {valueNotEquals: "default"},
                    country: {valueNotEquals: "default"},
                    focal_point: {valueNotEquals: "default"}
                },
                submitHandler: function (form) {
                    $('#post_id').val('');
                    var eventid = $('#event_id').val();
                    var actionType = $('#btn-save').val();
                    $(":input,:hidden").serialize();
                    $('#loader-modal').modal('show');
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('storeSubCompnay') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Add successfully');
                            var url = "{{ route('subCompanyAccreditCategories', [':id',$eventId]) }}";
                            url = url.replace(':id', data.id);
                            window.location.href = url;
                            //window.location.href = "../../subCompany-accreditation-size/" + data.id + "/" + eventid
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
        if ($("#focaPointForm").length > 0) {
            $("#focaPointForm").validate({
                rules: {
                    status: {valueNotEquals: "default"}
                },
                submitHandler: function (form) {
                    $('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-add-focal').html('Sending..');
                    $('#loader-modal').modal('show');
                    $.ajax({
                        data: $('#focaPointForm').serialize(),
                        url: "{{ route('focalpointController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            if(data.code == 401){
                                //alert('I am here');
                                $('#add-focal-point-modal').modal('hide');
                                $('#focalentryconfirmTitle').html('Add new focal point');
                                $('#focal_point_id').val(data.id);
                                $('#focal_point_name').val(data.name);
                                var confirmText = data.message;
                                $('#btn-gone').show();
                                $('#btn-confirm').html('yes');
                                $('#focalentryconfirmText').html(confirmText);
                                $('#event-organizer-copy-confirm-modal').modal('show');
                            }else{
                                if(data.code == 402){
                                    $('#add-focal-point-modal').modal('hide');
                                    $('#focalentryconfirmTitle').html('Add new focal point');
                                    var confirmText = data.message;
                                    $('#focalentryconfirmText').html(confirmText);
                                    $('#btn-gone').hide();
                                    $('#btn-confirm').html('OK');
                                    $('#event-organizer-copy-confirm-modal').modal('show');
                                }else{
                                    var name = data.name + ' ' + data.last_name;
                                    $('#focaPointForm').trigger("reset");
                                    $('#btn-add-focal').html('Add successfully');
                                    $('#add-focal-point-modal').modal('hide');
                                    $("#focal_point").append(new Option(name, data.id));
                                    $("#focal_point").val(data.id);
                                }
                            }
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
    });
    jQuery.validator.addMethod("valueNotEquals",
            function (value, element, params) {
                return params !== value;
            }, " Please select a value");
    </script>
@endsection
