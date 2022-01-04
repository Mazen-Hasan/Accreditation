@extends('main')
@section('subtitle',' Add Focal Point')
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
                            <a class="url-nav" href="{{route('focalpoints')}}">
                                <span>Focal Points:</span>
                            </a>
                             / New
                        </h4>
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
                                            <input type="text" id="name" name="name" placeholder="enter name"
                                                   minlength="1" maxlength="50" required=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group col">
                                        <label>Middle Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="middle_name" name="middle_name"
                                                   placeholder="enter middle name" minlength="1" maxlength="50" required=""/>
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
                            </div>
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
                                                <option value="default">Please select Status</option>
                                                @foreach ($contactStatuss as $contactStatus)
                                                    <option value="{{ $contactStatus->key }}"
                                                            @if ($contactStatus->key == -1)
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
                        </div>
                        <div class="col-sm-4">
                            <button type="button" data-dismiss="modal" id="btn-yes">Ok</button>
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
            $('#btn-yes').click(function () {
                $('#event-organizer-copy-confirm-modal').modal('hide');
                window.location.href = "{{ route('focalpoints')}}";
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

        if ($("#postForm").length > 0) {
            $("#postForm").validate({

                rules: {
                    status: {valueNotEquals: "default"}
                },

                submitHandler: function (form) {
                    $('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('focalpointController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.code == 401){
                                $('#confirmTitle').html('Add new focal point');
                                var confirmText = data.message;
                                $('#confirmText').html(confirmText);
                                $('#event-organizer-copy-confirm-modal').modal('show');
                            }else{
                                $('#postForm').trigger("reset");
                                $('#ajax-crud-modal').modal('hide');
                                $('#btn-save').html('Add successfully');
                                window.location.href = "{{ route('focalpoints')}}";
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
    </script>
@endsection
