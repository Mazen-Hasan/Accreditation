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
                        <h4 class="card-title"> FullFillment Selections</h4>
                        <form class="form-sample" id="postForm" name="postForm">
                            <input type="hidden" name="creation_date" id="creation_date" value="">
                            <input type="hidden" name="creator" id="creator" value="">
                            <input type="hidden" name="post_id" id="post_id">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Events</label>
                                        <div class="col-sm-12">
                                            <select id="event" name="event" value="" required="">
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
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Company</label>
                                        <div id="container" class="col-sm-12">
                                            <select id="company" name="company" value="" required="">
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
                                <div class="col-md-6">
                                    <div class="form-group col">
                                        <label>Accreditation Category</label>
                                        <div class="col-sm-12">
                                            <select id="category" name="category" value="" required="">
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
                        </form>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="col-sm-12">
                                        <button id="btn-filter" value="create">filter
                                        </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-sm-12">
                                        <label id="label-count" style="color:green;padding-top:5px"> </label>
                                </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="col-sm-12">
                                        <button id="btn-mark-printed" value="create">Mark as printed
                                        </button>
                                </div>
                            </div> -->
                            <div class="col-md-3">
                                <div class="col-sm-12">
                                        <button id="btn-details" value="create">Datails
                                        </button>
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
        $('#company').on('change', function() {
            $('#label-count').html('');
            $('#btn-filter').html('Filter');
        });
        $('#category').on('change', function() {
            $('#label-count').html('');
            $('#btn-filter').html('Filter');
        }); 
        $('#event').on('change', function() {
            $('#label-count').html('');
            $('#btn-filter').html('Filter');
            $.ajax({
                type: "get",
                url: "fullFillmentController/getCompanies/"+this.value,
                success: function (data) {
                    var companySelectOptions = data;
                    $('#container').html('');
                    var html = '<select id="company" name="company" value="" required="">';
                    var count = 0;
                    while (count < companySelectOptions.length){
                        if(count == 0){
                            html = html + "<option selected='selected' value="+companySelectOptions[count].key+">"+companySelectOptions[count].value+"</option>";
                        }else{
                            html = html + "<option value="+companySelectOptions[count].key+">"+companySelectOptions[count].value+"</option>";
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
            
            //alert($('#btn-filter').html());
            //alert( this.value );
            var labelValue = $('#btn-filter').html();
            //alert(labelValue);
            if(labelValue.toLowerCase().indexOf('filter') >= 0){
                //alert('first');
                var selectedEvent = $('#event option:selected').val();
                var selectedCompany = $('#company option:selected').val();
                var selectedAccredit = $('#category option:selected').text();
                $.ajax({
                    type: "get",
                    url: "fullFillmentController/getParticipants/"+selectedEvent+"/"+selectedCompany+"/"+selectedAccredit,
                    success: function (data) {
                        companySelectOptions = data;
                        //alert(companySelectOptions);
                        $('#label-count').html('Total filtered Count: '+companySelectOptions.length);
                        $('#btn-filter').html('Generate');

                    },
                        error: function (data) {
                            console.log('Error:', data);
                    }
                });
            }
            if(labelValue.toLowerCase().indexOf('generate') >=0){
                //alert('second');        
                var staff = companySelectOptions;
                //alert(companySelectOptions);
                if(staff.length > 0){
                    $.ajax({
                        type: "post",
                        data:  {staff: staff} ,
                        dataType: "json",
                        url: "{{ url('pdf-generate')}}",
                        success: function (data) {
                            console.log(data);
                            //window.open(data.file, '_blank');
                            window.location.href = data.file;
                            $('#btn-filter').html('Fullfillment');
                            ('#label-count').html('Total generated count: '+companySelectOptions.length);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            }
            if(labelValue.toLowerCase().indexOf('fullfillment') >=0){
                //alert('third');
                //alert(companySelectOptions);
                var staff = companySelectOptions;
                if(staff.length > 0){
                    $.ajax({
                        type: "post",
                        data:  {staff: staff} ,
                        dataType: "json",
                        url: "{{ url('fullFillment')}}",
                        success: function (data) {
                            $('#btn-filter').html('Reset');
                            $('#label-count').html('Total fullfillment count: '+companySelectOptions.length);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            }
            if(labelValue.toLowerCase().indexOf('reset') >=0){
                //alert('third');
                //alert(companySelectOptions);
                $('#label-count').html('');
                $('#btn-filter').html('Filter');
            }
        });
        $('#btn-details').click(function () {
            var selectedEvent = $('#event option:selected').val();
            var selectedCompany = $('#company option:selected').val();
            var selectedAccredit = $('#category option:selected').text();
            //alert('i ma here' +selectedEvent +',' + selectedCompany + ',' + selectedAccredit);
            window.location.href = "all-participants/"+selectedEvent+"/"+selectedCompany+"/"+selectedAccredit+"/0";
            });
    </script>
@endsection
