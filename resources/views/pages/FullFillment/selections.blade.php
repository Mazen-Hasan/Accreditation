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
                        <div class="col-sm-offset-2 col-sm-2">
                                <button id="btn-go" value="create">Go
                                </button>
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
        $('#event').on('change', function() {
            //alert( this.value );
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
        $('#btn-go').click(function () {
            var selectedEvent = $('#event option:selected').val();
            var selectedCompany = $('#company option:selected').val();
            var selectedAccredit = $('#category option:selected').text();
            //alert('i ma here' +selectedEvent +',' + selectedCompany + ',' + selectedAccredit);
            window.location.href = "all-participants/"+selectedEvent+"/"+selectedCompany+"/"+selectedAccredit+"/0";
            });
    </script>
@endsection
