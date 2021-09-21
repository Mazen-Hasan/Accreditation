@extends('main')
@section('subtitle',' Edit Participant')
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
                        <h4 class="card-title">Company / Participants - Edit</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id" value="{{$post->id}}">
                            <p class="card-description">
                                Participant Form
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">First Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="first_name" name="first_name" value="{{$post->first_name}}" required="" placeholder="enter first name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Last Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="last_name" name="last_name" value="{{$post->last_name}}" required=""placeholder="enter last name"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">First Name Arabic</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="first_name_ar" name="first_name_ar" value="{{$post->first_name_ar}}" required="" placeholder="enter first name arabic"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Last Name Arabic</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="last_name_ar" name="last_name_ar" value="{{$post->last_name_ar}}" required=""placeholder="enter last name arabic"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Nationality</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="nationality" name="nationality" value="{{$post->nationality}}" required="" placeholder="enter nationality"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Email</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="email" name="email" value="{{$post->email}}" required=""placeholder="enter email"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Mobile</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="mobile" name="mobile" value="{{$post->mobile}}" required="" placeholder="enter mobile"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Position</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="position" name="position" value="{{$post->position}}" required=""placeholder="enter position"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Religion</label>
                                        <div class="col-sm-12">
                                            <select class="input100 minimal" id="religion" name="religion" required="">
                                                @foreach ($religionsSelectOption as $religionSelectOption)
                                                    <option value="{{ $religionSelectOption->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($religionSelectOption->key == $post->religion)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $religionSelectOption->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Address</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="address" name="address" value="{{$post->address}}" required=""placeholder="enter address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Birthdate</label>
                                        <div class="col-sm-12">
                                            <input type="date" class="input100" id="birthdate" name="birthdate" value="{{$post->birthdate}}" required="" placeholder="enter birthdate"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Gender</label>
                                        <div class="col-sm-12">
                                            <select class="input100 minimal" id="gender" name="gender" required="">
                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($gender->key == $post->gender)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $gender->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class="col-form-label">Passport Number</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="passport_number" name="passport_number" value="{{$post->passport_number}}" required="" placeholder="enter passport number"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">ID Number</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="input100" id="id_number" name="id_number" value="{{$post->id_number}}" required=""placeholder="enter ID number"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Class</label>
                                        <div class="col-sm-12">
                                            <select class="input100 minimal" id="class" name="class" required="">
                                                @foreach ($classess as $class)
                                                    <option value="{{ $class->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($class->key == $post->class)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $class->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label class=" col-form-label">Accreditation Category</label>
                                        <div class="col-sm-12">
                                            <select class="input100 minimal" id="accreditation_category" name="accreditation_category" required="">
                                                @foreach ($accreditationCategoriesSelectOption as $accreditationCategorySelectOption)
                                                    <option value="{{ $accreditationCategorySelectOption->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($accreditationCategorySelectOption->key == $post->accreditation_category)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $accreditationCategorySelectOption->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-2">
                                <button type="submit" class="login100-form-btn" id="btn-save" value="create">Save
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
    {{--    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>--}}
    {{--    <script src="vendors/select2/select2.min.js"></script>--}}
    {{--    <script src="js/file-upload.js"></script>--}}
    {{--    <script src="js/typeahead.js"></script>--}}
    {{--    <script src="js/select2.js"></script>--}}
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
                $('#postCrudModal').html("Add New Post");
                $('#ajax-crud-modal').modal('show');
            });


            // $('body').on('click', '.edit-post', function () {
            //     var post_id = $(this).data('id');
            //     $.get('dtable-posts/'+post_id+'/edit', function (data) {
            //         $('#name-error').hide();
            //         $('#email-error').hide();
            //         $('#postCrudModal').html("Edit Post");
            //         $('#btn-save').val("edit-post");
            //         $('#ajax-crud-modal').modal('show');
            //         $('#post_id').val(data.id);
            //         $('#title').val(data.title);
            //         $('#body').val(data.body);
            //     })
            // });
            //
            // $('body').on('click', '#delete-post', function () {
            //     var post_id = $(this).data("id");
            //     confirm("Are You sure want to delete !");
            //     $.ajax({
            //         type: "get",
            //         url: "dtable-posts/destroy/"+post_id,
            //         success: function (data) {
            //             var oTable = $('#laravel_datatable').dataTable();
            //             oTable.fnDraw(false);
            //         },
            //         error: function (data) {
            //             console.log('Error:', data);
            //         }
            //     });
            // });
        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function(form) {
                    //$('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    //alert($('#postForm').serialize());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('companyAdminController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Edited successfully');
                            window.location.href = "{{ route('companyParticipants')}}";
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
