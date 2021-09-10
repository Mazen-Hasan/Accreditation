@extends('main')
@section('subtitle',' Edit Company')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTable.css') }}">

    <script src="{{ URL::asset('js/dataTable.js') }}"></script>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">Company Management - Edit</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="event_id" id="event_id" value="{{$eventid}}">
                            <input type="hidden" name="company_Id" id="company_Id" value="{{$company->id}}">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" name="name" value="{{$company->name}}" required="" placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Address</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="address" name="address" value="{{$company->address}}" required="" placeholder="enter address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="telephone" name="telephone" value="{{$company->telephone}}" required="" placeholder="enter telephone"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Website</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="website" name="website" value="{{$company->website}}" required="" placeholder="enter website"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Size</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="size" name="size" value="{{$company->size}}" required="" placeholder="enter size"/>
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
                                                            @if ($focalPoint->key == $company->focal_point_id)
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
                                            <select id="country" name="country" value="" required="">
                                                @foreach ($countrys as $country)
                                                    <option value="{{ $country->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($country->key == $company->country_id)
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
                                            <select id="city" name="city" value="" required="">
                                                @foreach ($citys as $city)
                                                    <option value="{{ $city->key }}"
                                                            {{--                                                            @if ($key == old('myselect', $model->option))--}}
                                                            @if ($city->key == $company->city_id)
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
                                                            @if ($category->key == $company->category_id)
                                                            selected="selected"
                                                        @endif
                                                    >{{ $category->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-2">
                                <button type="submit" id="btn-save" value="create">Edit
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
                    //$('#post_id').val('');
                    var actionType = $('#btn-save').val();
                    var $eventid = $('#event_id').val();
                    $('#btn-save').html('Sending..');
                    //alert($('#postForm').serialize());
                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('companyController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Edited successfully');
                            window.location.href = "../../event-companies/"+$eventid;
                            {{--window.location.href = "{{ route('companies')}}";--}}
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
