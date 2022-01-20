@extends('main')
@section('subtitle',' Data Entry')
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
                    <input type="hidden" id="subCompnay_status" value={{$subCompany_nav}} />
                    <div class="card-body">
                        <h4 class="card-title">
                            My events</h4>
                        <div class="row">
                            @foreach($events as $event)
                                <div class="col-sm-4">
                                    <div class="card"
                                    @if($event->status > 2)
                                        style="background-color: #d7d4d4;"
                                    @endif
                                    >
                                        <div>
                                            <input type="hidden" id="logoName" value="{{ $event->logo }}">
                                            <a href="{{route('dataEntryParticipants',[$event->company_id,$event->id])}}">
                                                <img id="logo" class="card-img-top"
                                                     style="width: 374px; height:374px; border-top-left-radius: 20px; border-top-right-radius: 20px"
                                                     src="{{ asset('images/event.png') }}" alt="Event">
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="card_event_title">
                                                        {{ $event->name }} / {{ $event->company_name }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="card_event_label">Size:</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="card_event_text">{{ $event->size}}</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="card_event_label">Location:</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="card_event_text">{{ $event->location}}</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="card_event_label">Type:</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="card_event_text">{{ $event->event_type}}</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="card_event_label">Period:</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="card_event_text">{{ $event->period}} days</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="card_event_label">Accreditation period:</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="card_event_text">{{ $event->accreditation_period}} days</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="card_event_label">Template:</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="card_event_text">{{ $event->template_name}}</p>
                                                </div>
                                            </div>
                                            <!-- <div class="row"
                                                 @if ($event->need_management == 0)
                                                 style="display:none"
                                                @endif
                                            >
                                                <div class="col-12">
                                                    <a href='company-accreditation-size/{{$event->id}}/{{$event->company_id}}' class="ha_btn">company
                                                        categories</a>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            @endforeach
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
            var subCompany_status = $('#subCompnay_status').val();
            //alert(subCompany_status);
            if (subCompany_status == 0) {
                $('#subsidiaries_nav').hide();
            }

            let imag = $('#logoName').val();
            if (imag) {
                var image_path = "{{URL::asset('logo/')}}/";
                $('#logo').attr('src', image_path + imag);
            }
        });
    </script>
@endsection
