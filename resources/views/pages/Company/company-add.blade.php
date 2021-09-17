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
                        <h4 class="card-title"> {{$event_name}} / Company - New</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="need_management" id="need_management" value="">
                            <input style="visibility: hidden" name="event_id" id="event_id" value="{{$eventid}}" >
                            <input type="hidden" name="post_id" id="post_id">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" value="" required="" placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Address</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="address" name="address" value="" required="" placeholder="enter address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="telephone" name="telephone" value="" required="" placeholder="enter telephone"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Website</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="website" name="website" value="" required="" placeholder="enter website"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Size</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="size" name="size" value="" required="" placeholder="enter size"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Focal Point</label>
                                        <div class="col-sm-12">
                                            <select id="focal_point" name="focal_point" value="" required="">
                                                @foreach ($focalPoints as $focalPoint)
                                                    <option value="{{ $focalPoint->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($focalPoint->key == 1)
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
                                        <label>Country</label>
                                        <div class="col-sm-12">
{{--                                            <input list="country" name="country">--}}
{{--                                            <datalist id="country">--}}
{{--                                                @foreach ($countrys as $country)--}}
{{--                                                    <option id="{{ $country->key }}"--}}
{{--                                                            @if ($country->key == 1)--}}
{{--                                                            selected="selected"--}}
{{--                                                        @endif--}}
{{--                                                    >{{ $country->value }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </datalist>--}}
                                            <select id="country" name="country" value="" required="">
                                                @foreach ($countrys as $country)
                                                    <option value="{{ $country->key }}"
                                                            @if ($country->key == 1)
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
                                        <div class="col-sm-12">
{{--                                            <input list="city-list" id="city" name="city">--}}
{{--                                            <datalist id="city-list">--}}
{{--                                                @foreach ($citys as $city)--}}
{{--                                                    <option id="{{ $city->key }}" value="{{ $city->value }}"--}}
{{--                                                            @if ($city->key == 1)--}}
{{--                                                            selected="selected"--}}
{{--                                                        @endif--}}
{{--                                                    ></option>--}}
{{--                                                @endforeach--}}
{{--                                            </datalist>--}}

                                            <select id="city" name="city" value="" required="">
                                                @foreach ($citys as $city)
                                                    <option value="{{ $city->key }}"
                                                            @if ($city->key == 1)
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
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($category->key == 1)
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
                                        <label>Accreditation Category</label>
                                        <div class="col-sm-12">
                                            <select id="accreditationCategories" multiple name="accreditationCategories[]" value="" required="" style="height:150px">
                                                @foreach ($accreditationCategorys as $accreditationCategory)
                                                    <option value="{{ $accreditationCategory->key }}"
{{--@if ($key == old('myselect', $model->option))--}}
                                                            @if ($accreditationCategory->key == 1)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $accreditationCategory->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col">
                                        <div class="col-sm-12">
                                            <label for="needManagmentCheckbox"  style="word-wrap:break-word;font-size:20px">
                                                <input type="checkbox" id="needManagmentCheckbox" name="needManagmentCheckbox" value="0" style="width:20px;display: inline-block;vertical-align: middle" />    Need Company Admin Accreditation Category Zise Management
                                            </label>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $('#needManagmentCheckbox').click(function () {
            //     if($('#needManagmentCheckbox').is(':checked')){
            //         $('#need_managment').val('1');
            //     }else{
            //         $('#need_managment').val('0');
            //     }
            // });
        });
        $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add New Contact");
                $('#ajax-crud-modal').modal('show');
            });


        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function (form) {
                    $('#post_id').val('');
                    var $eventid = $('#event_id').val();
                    var actionType = $('#btn-save').val();
                    if($('#needManagmentCheckbox').is(':checked')){
                        $('#need_management').val('1');
                    }else{
                        $('#need_management').val('0');
                    }
                    $('#btn-save').html('Sending..');
                    alert($('#postForm').serialize());
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
                            //window.location.href = "{{ route('companies')}}";
                            window.location.href = "../event-companies/"+$eventid;
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
