@section('style')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/feather/feather.css') }}" rel="stylesheet">
@endsection
<div class="content-wrapper">
    <br> <br>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row align-content-md-center" style="height: 80px">
                        <div class="col-md-8">
                            <h4 class="card-title">
                                <span>You had been assigned as event admin for  the event:  {{$template->name}}</span>
                            </h4>
                            <div class="card-body">
                                <span>To check your event, kindly follow below link</span>
                                <a class="url-nav" href="{{route('events')}}">
                                    <span>Events:</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{--<table style="height: 400px; width: 100%; border-collapse: collapse; background-color: #f3f2ef;" border="0">--}}
{{--    <tbody>--}}
{{--    <tr style="height: 15px;">--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--        <td style="width: 5%; background-color: white;">&nbsp;</td>--}}
{{--        <td style="width: 50%; background-color: white;">&nbsp;</td>--}}
{{--        <td style="width: 5%; background-color: white;">&nbsp;</td>--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--    </tr>--}}
{{--    <tr style="height: 340px;">--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--        <td style="width: 5%; background-color: white;">--}}
{{--            <p>&nbsp;</p>--}}
{{--        </td>--}}
{{--        <td style="width: 50%; background-color: white;">--}}
{{--            <p>Dear ,</p>--}}
{{--            <span>You had been assigned as event admin for the event:  {{$template->name}}</span>--}}
{{--            <p>&nbsp;</p>--}}
{{--            <span>To check your event, kindly follow below link</span>--}}
{{--            <a class="url-nav" href="{{route('EventController.show', $template->id)}}">--}}
{{--                <span>Events:</span>--}}
{{--            </a>--}}
{{--            <p>&nbsp;</p>--}}
{{--        </td>--}}
{{--        <td style="width: 5%; background-color: white;">--}}
{{--            <p style="text-align: justify;">&nbsp;</p>--}}
{{--        </td>--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--        <td style="width: 5%; background-color: white;">&nbsp;</td>--}}
{{--        <td>&copy; 2021 Accrediation</td>--}}
{{--        <td style="width: 5%; background-color: white;">&nbsp;</td>--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--    </tr>--}}
{{--    <tr style="height: 30px;">--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--        <td style="width: 5%; background-color: white;">&nbsp;</td>--}}
{{--        <td style="width: 50%; background-color: white;">&nbsp;</td>--}}
{{--        <td style="width: 5%; background-color: white;">&nbsp;</td>--}}
{{--        <td style="width: 20%;">&nbsp;</td>--}}
{{--    </tr>--}}
{{--    </tbody>--}}
{{--</table>--}}
