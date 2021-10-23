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
                                            <input type="text" id="name" name="name" value="" required=""
                                                   placeholder="enter name"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Address</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="address" name="address" value="" required=""
                                                   placeholder="enter address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Telephone</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="telephone" name="telephone" value="" required=""
                                                   placeholder="enter telephone"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Website</label>
                                        <div class="col-sm-12">
                                            <input type="url" id="website" name="website" value="" required=""
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
                                            <input type="number" id="size" name="size" value="" required=""
                                                   placeholder="enter size"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Focal Point</label>
                                        <div class="col-sm-12">
                                            <select id="focal_point" name="focal_point" value="" required="">
                                                @foreach ($focalPoints as $focalPoint)
                                                    <option value="{{ $focalPoint->key }}"@if ($focalPoint->key == 1)
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
                                        <div class="col-sm-12"><select id="country" name="country" value="" required="">
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
                                            <select id="city" name="city" required="">
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
                                            <select id="category" name="category" required="">
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->key }}"
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
                                        <label>Status</label>
                                        <div class="col-sm-12">
                                            <select id="status" name="status" required="">
                                                @foreach ($statuss as $status)
                                                    <option value="{{ $status->key }}"
                                                            @if ($status->key == 1)
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

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function (form) {
                    $('#post_id').val('');
                    var $eventid = $('#event_id').val();
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
                                window.location.href = "../event-companies/" + $eventid;
                            }
                            if (data.need_management == '0') {
                                window.location.href = "../company-accreditation-size-new/" + data.id + "/" + data.event_id
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
