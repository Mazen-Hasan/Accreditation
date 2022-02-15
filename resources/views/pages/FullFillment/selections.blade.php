@extends('main')
@section('subtitle',' FullFillment')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <!--                             <a class="url-nav" href="{{ route('event-admin') }} ">
                               	<span>My Events:</span>
                            </a> -->
                            Fulfillment Selections</h4>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group col">
                                            <label>Events</label>
                                            <div class="col-sm-12">
                                                <select id="event" name="event" required="">
                                                    @foreach ($eventsSelectOptions as $eventsSelectOption)
                                                        <option value="{{ $eventsSelectOption->key }}"
                                                                @if ($eventsSelectOption->key == 1)
                                                                selected="selected"
                                                            @endif
                                                        >{{ $eventsSelectOption->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group col">
                                            <label>Company</label>
                                            <div id="container" class="col-sm-12">
                                                <select id="company" name="company" required="" class="hi">
                                                    @foreach ($companySelectOptions as $companySelectOption)
                                                        <option value="{{ $companySelectOption->key }}"
                                                                @if ($companySelectOption->key == 0)
                                                                selected="selected"
                                                            @endif
                                                        >{{ $companySelectOption->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group col">
                                            <label>Accreditation Category</label>
                                            <div id="ac_container" class="col-sm-12">
                                                <select id="category" name="category" required="">
                                                    @foreach ($accrediationCategorySelectOptions as $accrediationCategorySelectOption)
                                                        <option value="{{ $accrediationCategorySelectOption->key }}"
                                                                @if ($accrediationCategorySelectOption->key == 0)
                                                                selected="selected"
                                                            @endif
                                                        >{{ $accrediationCategorySelectOption->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-sm-12">
                                            <a id="btn-filter" href='javascript:void(0)' class="ha_icon_btn">
                                                <i class="fas fa-filter" style="font-size: 25px"></i>&nbsp;
                                                Filter
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <br>
                                    <br>
                                    <div class="card" style="padding: 0px; border-radius: 0px">
                                        <label class="card-header">Summary</label>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="card_event_label">Total selected</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <label id="lbl_select" class="fulfillment_text">0</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="card_event_label">Total generated</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <label id="lbl_generate" class="fulfillment_text">0</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="card_event_label">Total printed</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <label id="lbl_print" class="fulfillment_text">0</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <a id="btn-generate" title="Generate" href='javascript:void(0)'
                                               class="ha_icon_btn  disabled">
                                                <i class="fas fa-cogs" style="font-size: 25px; color: white"></i><p style="color:white">Generate</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <a id="btn-Details" title="Details" href='javascript:void(0)'
                                               class="ha_icon_btn disabled">
                                                <i class="fa fa-list" style="font-size: 25px"></i><p style="color:white">Details</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="col-md-12">
                                            <a id="btn-mark-printed" title="Mark as printed" href='javascript:void(0)'
                                               class="ha_icon_btn disabled">
                                                <i class="fas fa-tasks" style="font-size: 25px"></i><p style="color:white">Mark as printed</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loader-modal" tabindex="-1" data-backdrop="static" data-keyboard="false"
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
            var companySelectOptions = [];
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $('#company').on('change', function () {
            resetAll();
        });

        $('#category').on('change', function () {
            resetAll();
        });

        $(document).on('change' , '.hi', function () {
            //$('#loader-modal').modal('show');
            //alert('hi');
            resetAll();
            var selectedEvent = $('#event option:selected').val();
            var url = "{{ route('getEventCompanyACs', [":id",":companyidd"]) }}";
            url = url.replace(':id', selectedEvent);
            url = url.replace(':companyidd', this.value);
            $.ajax({
                type: "get",
                url: url,
                success: function (data) {
                    $('#loader-modal').modal('hide');
                    console.log('success: hide');
                    var acSelectOptions = data;
                    $('#ac_container').html('');
                    var html = '<select id="category" name="category" required="">';
                            var count = 0;
                            while (count < acSelectOptions.length) {
                                if (count == 0) {
                                    html = html + "<option selected='selected' value=" + acSelectOptions[count].key + ">" + acSelectOptions[count].value + "</option>";
                                } else {
                                    html = html + "<option value=" + acSelectOptions[count].key + ">" + acSelectOptions[count].value + "</option>";
                                }
                                count++;
                            }
                            html = html + '<select/>';
                            $('#ac_container').append(html);
                },
                error: function (data) {
                    $('#loader-modal').modal('hide');
                    console.log('Error:', data);
                }
            });
        });

        $('#event').on('change', function () {
            //$('#loader-modal').modal('show');
            resetAll();

            var url = "{{ route('getCompanies', ":id") }}";
            url = url.replace(':id', this.value);
            var eventIdd = this.value;
            $.ajax({
                type: "get",
                url: url,
                success: function (data) {
                    $('#loader-modal').modal('hide');
                    console.log('success: hide');
                    var companySelectOptions = data;
                    $('#container').html('');
                    var html = "<select id='company' name='company' required='' class='hi'>";
                    var count = 0;
                    while (count < companySelectOptions.length) {
                        if (count == 0) {
                            html = html + "<option selected='selected' value=" + companySelectOptions[count].key + ">" + companySelectOptions[count].value + "</option>";
                        } else {
                            html = html + "<option value=" + companySelectOptions[count].key + ">" + companySelectOptions[count].value + "</option>";
                        }
                        count++;
                    }
                    html = html + '<select/>';
                    $('#container').append(html);
                    url = "{{ route('getEventACs', ":id") }}";
                    url = url.replace(':id', eventIdd);
                    $.ajax({
                        type: "get",
                        url: url,
                        success: function (data) {
                            $('#loader-modal').modal('hide');
                            var acSelectOptions = data;
                            $('#ac_container').html('');
                            var html = '<select id="category" name="category" required="">';
                            var count = 0;
                            while (count < acSelectOptions.length) {
                                if (count == 0) {
                                    html = html + "<option selected='selected' value=" + acSelectOptions[count].key + ">" + acSelectOptions[count].value + "</option>";
                                } else {
                                    html = html + "<option value=" + acSelectOptions[count].key + ">" + acSelectOptions[count].value + "</option>";
                                }
                                count++;
                            }
                            html = html + '<select/>';
                            $('#ac_container').append(html);
                        },
                        error: function (data) {
                            $('#loader-modal').modal('hide');
                            console.log('Error:', data);
                        }
                    });
                },
                error: function (data) {
                    $('#loader-modal').modal('hide');
                    console.log('Error:', data);
                }
            });
        });

        $('#btn-filter').click(function () {
            if ($('#btn-filter').html() == 'Reset') {
                resetAll();
            } else {
                //$('#loader-modal').modal('show');
                var selectedEvent = $('#event option:selected').val();
                var selectedCompany = $('#company option:selected').val();
                var selectedAccredit = $('#category option:selected').text();

                var url = "{{ route('getParticipants', [":selectedEvent",":selectedCompany",":selectedAccredit"]) }}";
                url = url.replace(':selectedEvent', selectedEvent);
                url = url.replace(':selectedCompany', selectedCompany);
                url = url.replace(':selectedAccredit', selectedAccredit);

                $.ajax({
                    type: "get",
                    url: url,
                    success: function (data) {
                        $('#loader-modal').modal('hide');
                        companySelectOptions = data['selected'];
                        $('#lbl_select').html(companySelectOptions.length);
                        $('#lbl_print').html(data['printed']);
                        if (companySelectOptions.length > 0) {
                            $('#lbl_select').css("color", "#54af36");
                            $('#btn-generate').removeClass('disabled');
                            $('#btn-Details').removeClass('disabled');
                        }
                        if (data['printed'] > 0) {
                            $('#lbl_print').css("color", "#54af36");
                        }
                    },
                    error: function (data) {
                        $('#loader-modal').modal('hide');
                        console.log('Error:', data);
                    }
                });
            }
        });

        $('#btn-generate').click(function () {
            var staff = companySelectOptions;
            if (staff.length > 0) {
                $.ajax({
                    type: "post",
                    data: {staff: staff},
                    dataType: "json",
                    url: "{{ route('pdf-generate') }}",
                    success: function (data) {
                        window.open(data.file, '_blank');
                        $('#lbl_generate').html(companySelectOptions.length);
                        $('#lbl_generate').css("color", "#54af36");
                        $('#btn-mark-printed').removeClass('disabled')
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

        $('#btn-mark-printed').click(function () {
            var staff = companySelectOptions;
            if (staff.length > 0) {
                $.ajax({
                    type: "post",
                    data: {staff: staff},
                    dataType: "json",
                    url: "{{ route('fullFillment')}}",
                    success: function (data) {
                        $('#btn-filter').html('Reset');
                        $('#lbl_print').html(companySelectOptions.length + Number($('#lbl_print').html()));
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

        $('#btn-Details').click(function () {
            console.log('kkk');
            var selectedEvent = $('#event option:selected').val();
            var selectedCompany = $('#company option:selected').val();
            var selectedAccredit = $('#category option:selected').text();

            var url = "{{ route('allParticipants', [":selectedEvent",":selectedCompany",":selectedAccredit","0"]) }}";
            url = url.replace(':selectedEvent', selectedEvent);
            url = url.replace(':selectedCompany', selectedCompany);
            url = url.replace(':selectedAccredit', selectedAccredit);

            window.location.href = url;
        });

        function resetAll() {
            $('#btn-filter').html('Filter');
            $('#lbl_select').html('0');
            $('#lbl_generate').html('0');
            $('#lbl_print').html('0');

            $('#btn-filter').removeClass('disabled');

            $('#btn-generate').addClass('disabled');
            $('#btn-Details').addClass('disabled');
            $('#btn-mark-printed').addClass('disabled');

            $('#lbl_select').css("color", "#b8b5b5");
            $('#lbl_generate').css("color", "#b8b5b5");
            $('#lbl_print').css("color", "#b8b5b5");
        }
    </script>
@endsection
