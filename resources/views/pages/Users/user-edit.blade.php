@extends('main')
@section('subtitle',' Add User')
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
                <div class="card"  style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">User - Edit</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id" value="{{$user->user_id}}">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" placeholder="enter name" required="" value="{{$user->user_name}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Email</label>
                                        <div class="col-sm-12">
                                            <input type="text"  id="email" name="email" placeholder="enter email" required="" value="{{$user->email}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col">
                                        <label>Email</label>
                                        <div class="col-sm-6">
                                            <input type="text"  id="email" name="email" placeholder="enter email" required="" value="{{$user->email}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row" style="display:none">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Password</label>
                                        <div class="col-sm-12">
                                            <input type="password" id="password" name="password" placeholder="enter password" required="" value="{{$user->password}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Confirm Password</label>
                                        <div class="col-sm-12">
                                            <input type="password" id="confirm_password" name="confirm_password" placeholder="confirm password" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row" style="display:none">
                                <div class="col-md-12">
                                    <div class="form-group col">
                                        <label>Confirm Password</label>
                                        <div class="col-sm-6">
                                            <input type="password" id="confirm_password" name="confirm_password" placeholder="confirm password" required=""/>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col">
                                        <label>Role</label>
                                        <div class="col-sm-6">
                                            <select id="role" name="role" value="" required="">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->key }}"
                                                            @if ($role->key == $user->role_id)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $role->value }}</option>
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
        $(document).ready( function () {
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
                submitHandler: function(form) {
                    //$('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    // alert($('#postForm').serialize());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('userController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Add successfully');
                            window.location.href = "{{ route('users')}}";
                            // var oTable = $('#laravel_datatable').dataTable();
                            // oTable.fnDraw(false);
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
