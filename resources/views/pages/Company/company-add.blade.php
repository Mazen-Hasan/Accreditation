@extends('main')
@section('subtitle',' Add Company')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
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
                            <a class="url-nav" href="{{ route('event-admin') }} ">
                                <span>My Events:</span>
                            </a>
                            <a class="url-nav" href="{{route('eventCompanies',[$eventid])}}">
                                <span>{{$event_name}}</span>
                            </a>
                             / Companies / New
                        </h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="need_management" id="need_management" value="">
                            <input style="visibility: hidden" name="event_id" id="event_id" value="{{$eventid}}">
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
                                            <input type="number" id="size" name="size"  min="1" max="{{$allowedSize}}" value="" required=""
                                                   placeholder="enter size"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Address</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="address" minlength="1" maxlength="150" name="address" value="" required=""
                                                   placeholder="enter address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Country</label>
                                        <div class="col-sm-12"><select id="country" name="country" value="" required="">
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
                                            <select id="city" name="city" required="">
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
                                            <select id="category" name="category" required="">
                                            <option value="default">Please select category</option>
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->key }}"
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
                                            <select id="company_status" name="company_status" required="">
                                            <option value="default">Please select status</option>
                                                @foreach ($statuss as $status)
                                                    <option value="{{ $status->key }}"
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
                        </form>
                        <div class="col-sm-offset-2 col-sm-2">
                            <button id="btn-save" value="create">Save
                            </button>
                        </div>
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
    <div class="modal fade" id="add-focal-point-modal" tabindex="-1" data-bs-backdrop="static"
         data-bs-keyboard="false" role="dialog" aria-hidden="true">
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
                                <div class="col-sm-6">
                                    <div class="form-group col">
                                        <label>Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="last_name" name="last_name"
                                                   placeholder="enter last name" minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row" style="visibility:hidden">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="last_name" name="last_name"
                                                   placeholder="enter last name" minlength="1" maxlength="50"  value=" "/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Email</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="email" name="email" placeholder="enter email"
                                                   value=" "/>
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
                                                <option value="1">Acticve</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <!-- <div class="col-sm-8">
                                <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">No, Manage
                                    them myself
                                </button>
                            </div> -->
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
        $('#btn-save').click(function () {
            $('#confirmTitle').html('Add Company');
            $('#curr_element_id').val(post_id);
            $('#action_button').val('approve');
            var confirmText = "Give permission to company admin to manage accreditation categories sizes?";
            $('#confirmText').html(confirmText);
            $('#delete-element-confirm-modal').modal('show');
        });

        $('#delete-element-confirm-modal button').on('click', function (event) {
            var $button = $(event.target);
            $(this).closest('.modal').one('hidden.bs.modal', function () {
                $('#need_management').val('0');
                if ($button[0].id === 'btn-yes') {
                    $('#need_management').val('1');
                }
                $("#postForm").submit();
            });
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
        $('#country').on('change', function () {
            // $('#lbl_select').html('');
            // $('#btn-filter').html('Filter');
            //resetAll();

            var url = "{{ route('getCities', ":id") }}";
            url = url.replace(':id', this.value);

            $.ajax({
                type: "get",
                // url: "fullFillmentController/getCompanies/" + this.value,
                url: url,
                success: function (data) {
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
                    console.log('Error:', data);
                }
            });
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
                    $('#account_name').val('');
                    $('#account_email').val('');
                    $('#password').val('');
                    $('#status').val('default');
                    $('#btn-add-focal').html('Save');
                    $('#add-focal-point-modal').modal('show');
                }
            });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                rules: {
                    company_status: {valueNotEquals: "default"},
                    category: {valueNotEquals: "default"},
                    city: {valueNotEquals: "default"},
                    country: {valueNotEquals: "default"},
                    focal_point: {valueNotEquals: "default"}
                    //website: {urlValid: ""}
                },
                submitHandler: function (form) {
                    $('#post_id').val('');
                    //var $eventid = $('#event_id').val();
                    var actionType = $('#btn-save').val();
                    $(":input,:hidden").serialize();
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('companyController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Add successfully');

                            if (data.need_management == '1') {
                                window.location.href = "{{ route('eventCompanies',$eventid)}}";
                            }
                            if (data.need_management == '0') {
                                var url = "{{ route('companyAccreditCat', [':id',$eventid]) }}";
                                url = url.replace(':id', data.id);
                                window.location.href = url;
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
        if ($("#focaPointForm").length > 0) {
            $("#focaPointForm").validate({
                rules: {
                    status: {valueNotEquals: "default"}
                },
                submitHandler: function (form) {
                    $('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-add-focal').html('Sending..');
                    alert($('#focaPointForm').serialize());
                    $.ajax({
                        data: $('#focaPointForm').serialize(),
                        url: "{{ route('focalpointController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.code == 401){
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
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }
        jQuery.validator.addMethod("valueNotEquals",
            function (value, element, params) {
                return params !== value;
            }, " Please select a value");

        jQuery.validator.addMethod("urlValid",
            function (value, element, params) {
                console.log('urlValid');
                var res = value.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
                return res != null;
            }, " Please enter a valid URL");

    </script>
@endsection
