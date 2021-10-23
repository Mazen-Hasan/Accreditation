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
                        <h4 class="card-title">Fulfillment Selections</h4>
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
                                                <select id="company" name="company" required="">
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
                                            <div class="col-sm-12">
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
                                                    <label id="lbl_select" class="card_event_text">0</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="card_event_label">Total generated</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <label id="lbl_generate" class="card_event_text">0</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="card_event_label">Total printed</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <label id="lbl_print" class="card_event_text">0</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <a id="btn-generate" title="Generate" href='javascript:void(0)' class="ha_icon_btn  disabled">
                                                <i class="fas fa-cogs" style="font-size: 25px; color: white"></i>Generate
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <a id="btn-Details" title="Details" href='javascript:void(0)' class="ha_icon_btn disabled">
                                                <i class="fa fa-list" style="font-size: 25px"></i>Details
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="col-md-12">
                                            <a id="btn-mark-printed" title="Mark as printed" href='javascript:void(0)' class="ha_icon_btn disabled">
                                                <i class="fas fa-tasks" style="font-size: 25px"></i>Mark as printed
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
            // $('#lbl_select').html('0');
            // $('#btn-filter').html('Filter');
            resetAll();
        });

        $('#category').on('change', function () {
            // $('#lbl_select').html('0');
            // $('#btn-filter').html('Filter');
            resetAll();
        });

        $('#event').on('change', function () {
            // $('#lbl_select').html('');
            // $('#btn-filter').html('Filter');
            resetAll();

            var url = "{{ route('getCompanies', ":id") }}";
            url = url.replace(':id', this.value);

            $.ajax({
                type: "get",
                // url: "fullFillmentController/getCompanies/" + this.value,
                url: url,
                success: function (data) {
                    var companySelectOptions = data;
                    $('#container').html('');
                    var html = '<select id="company" name="company" required="">';
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
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

        $('#btn-filter').click(function () {
            // var labelValue = $('#btn-filter').html();
            // if (labelValue.toLowerCase().indexOf('filter') >= 0) {
                var selectedEvent = $('#event option:selected').val();
                var selectedCompany = $('#company option:selected').val();
                var selectedAccredit = $('#category option:selected').text();

                var url = "{{ route('getParticipants', [":selectedEvent",":selectedCompany",":selectedAccredit"]) }}";
                url = url.replace(':selectedEvent', selectedEvent);
                url = url.replace(':selectedCompany', selectedCompany);
                url = url.replace(':selectedAccredit', selectedAccredit);

                $.ajax({
                    type: "get",
                    // url: "fullFillmentController/getParticipants/" + selectedEvent + "/" + selectedCompany + "/" + selectedAccredit,
                    url: url,
                    success: function (data) {
                        companySelectOptions = data;
                        $('#lbl_select').html(companySelectOptions.length);
                        if(companySelectOptions.length>0){
                            $('#lbl_select').css("color", "#54af36");
                            $('#btn-generate').removeClass('disabled');
                            $('#btn-generate').removeClass('disabled');
                            $('#btn-Details').removeClass('disabled');
                        }
                        // $('#btn-filter').html('Generate');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            // }

            {{--if (labelValue.toLowerCase().indexOf('generate') >= 0) {--}}
            {{--    var staff = companySelectOptions;--}}
            {{--    if (staff.length > 0) {--}}
            {{--        $.ajax({--}}
            {{--            type: "post",--}}
            {{--            data: {staff: staff},--}}
            {{--            dataType: "json",--}}
            {{--            url: "{{ url('pdf-generate')}}",--}}
            {{--            success: function (data) {--}}
            {{--                console.log(data);--}}
            {{--                window.open(data.file, '_blank');--}}
            {{--                $('#btn-filter').html('Fullfillment');--}}
            {{--                $('#lbl_select').html('Total generated count: ' + companySelectOptions.length);--}}
            {{--            },--}}
            {{--            error: function (data) {--}}
            {{--                console.log('Error:', data);--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }--}}
            {{--}--}}

            {{--if (labelValue.toLowerCase().indexOf('fullfillment') >= 0) {--}}
            {{--    var staff = companySelectOptions;--}}
            {{--    if (staff.length > 0) {--}}
            {{--        $.ajax({--}}
            {{--            type: "post",--}}
            {{--            data: {staff: staff},--}}
            {{--            dataType: "json",--}}
            {{--            url: "{{ url('fullFillment')}}",--}}
            {{--            success: function (data) {--}}
            {{--                $('#btn-filter').html('Reset');--}}
            {{--                $('#lbl_select').html('Total fullfillment count: ' + companySelectOptions.length);--}}
            {{--            },--}}
            {{--            error: function (data) {--}}
            {{--                console.log('Error:', data);--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }--}}
            {{--}--}}

            {{--if (labelValue.toLowerCase().indexOf('reset') >= 0) {--}}
            {{--    $('#lbl_select').html('');--}}
            {{--    $('#btn-filter').html('Filter');--}}
            {{--}--}}
        });

        $('#btn-generate').click(function () {
            var staff = companySelectOptions;
            if (staff.length > 0) {
                $.ajax({
                    type: "post",
                    data: {staff: staff},
                    dataType: "json",
                    url: "{{ route('pdf-generate') }}",
                    {{--url: "{{ url('pdf-generate')}}",--}}
                    success: function (data) {
                        window.open(data.file, '_blank');
                        $('#lbl_generate').html(companySelectOptions.length);
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
                        {{--url: "{{ url('fullFillment')}}",--}}
                        url: "{{ route('fullFillment')}}",
                        success: function (data) {
                            $('#btn-filter').html('Reset');
                            $('#lbl_print').html(companySelectOptions.length);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });

        $('#btn-details').click(function () {
            var selectedEvent = $('#event option:selected').val();
            var selectedCompany = $('#company option:selected').val();
            var selectedAccredit = $('#category option:selected').text();

            var url = "{{ route('allParticipants', [":selectedEvent",":selectedCompany",":selectedAccredit","0"]) }}";
            url = url.replace(':selectedEvent', selectedEvent);
            url = url.replace(':selectedCompany', selectedCompany);
            url = url.replace(':selectedAccredit', selectedAccredit);

            // window.location.href = "all-participants/" + selectedEvent + "/" + selectedCompany + "/" + selectedAccredit + "/0";
            window.location.href = url;
        });

        function  resetAll(){
            $('#lbl_select').html('0');
            $('#lbl_generate').html('0');
            $('#lbl_print').html('0');

            $('#btn-filter').removeClass('disabled');

            $('#btn-generate').addClass('disabled');
            $('#btn-Details').addClass('disabled');
            $('#btn-mark-printed').addClass('disabled');

            $('#lbl_select').css("color", "#b8b5b5");
        }
    </script>
@endsection
