@extends('main')
@section('subtitle',' Event Admin')
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
                        <h4 class="card-title">My events</h4>
                        <div class="row">
                            @foreach($events as $event)
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div>
                                            <a href="{{route('securityOfficerCompanies',[$event->id])}}">
                                                <img class="card-img-top"
                                                     style="border-top-left-radius: 20px; border-top-right-radius: 20px"
                                                     src="{{ asset('images/event.png') }}" alt="Event">
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="card_event_title">{{ $event->name }}</h5>
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
                                                    <p class="card_event_text">{{ $event->period}}</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="card_event_label">Accreditation period:</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="card_event_text">{{ $event->accreditation_period}}</p>
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
                                            <div class="row">
                                                <div class="col-2"></div>
                                                <div class="col-8">
                                                    <a href="{{route('securityOfficerCompanyParticipants',[0,$event->id])}}"
                                                       class="ha_icon_btn">
                                                        <i class="fa fa-users" style="font-size: 25px; color: white"></i>&nbsp;
                                                        Participants
                                                    </a>
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
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
