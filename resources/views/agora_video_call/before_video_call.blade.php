
@extends('layouts.theme_video_call')

@section('content')

<link href="{{ asset('css/before_video_call.css') }}" rel="stylesheet">

<div class="container-before-video-call">
    <div class="nav-bar-video-call">
        <img src="https://www.viicheck.com/img/logo/logo-viicheck-outline.png" alt="ViiCheck Logo">
        <div id="myAlert" class="alert alert-warning alert-dismissible fade" role="alert" style="display: none;">
            <strong>จำนวนคนในห้องสูงสุดแล้ว
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="main-content-video-call">
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-8 p-2 d-flex flex-column align-items-center justify-content-center">
                @if ($type_brand == "pc")
                    <div class="div-video">
                        <video id="videoDiv" style="background-color: #000000;" class="video_preview" autoplay playsinline></video>
                        <div id="soundTest" class="soundTest">
                            <div class="soundMeter"></div>
                        </div>
                        <div class="buttonDiv d-none">
                            <button id="toggleCameraButton" class="toggleCameraButton mr-3 btn"></button>
                            <button id="toggleMicrophoneButton" class="toggleMicrophoneButton btn"></button>
                        </div>
                    </div>
                @else
                    <div class="div-video m-5">
                        <video id="videoDiv" style="background-color: #000000;" class="video_preview" autoplay playsinline></video>
                        <div id="soundTest" class="soundTest">
                            <div class="soundMeter"></div>
                        </div>
                        <div class="buttonDiv d-none">
                            <button id="toggleCameraButton" class="toggleCameraButton mr-3 btn"></button>
                            <button id="toggleMicrophoneButton" class="toggleMicrophoneButton btn"></button>
                        </div>
                    </div>
                @endif

                <div class=" d-nne">
                    @if ($type_brand == "pc")
                        <div class="selectDivice mt-2 p-2 row">
                            <select disabled id="microphoneList" style="min-width: 150px;"></select>
                            <select disabled id="cameraList" style="min-width: 150px;"></select>
                        </div>
                    @else
                        <div class="selectDivice mt-2 p-2 row d-none">
                            <select id="microphoneList"></select>
                            <select id="cameraList"></select>
                            {{-- <select id="speakerList"></select> --}}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-sm-12 col-lg-4  d-flex justify-content-center p-3 align-items-center">
                <div id="before_join_message" class="text-center w-100">
                    @if ($type_brand == "pc")
                        @if($type == "sos_1669" || $type == "user_sos_1669")
                            @php
                                $data_sos_1669 = App\Models\Sos_help_center::where('id' , $sos_id)->first();
                            @endphp

                            @if ($user->role == "partner")
                                @if (!empty($data_sos_1669->code_for_officer))
                                    <h4 class="w-100">ห้องสนทนา : {{ $data_sos_1669->code_for_officer }}</h4>
                                @else
                                    <h4 class="w-100">ห้องสนทนา : {{ $data_sos_1669->operating_code }}</h4>
                                @endif
                            @else
                                <h4 class="w-100">ห้องสนทนา : {{ $data_sos_1669->operating_code }}</h4>
                            @endif
                        @else
                            <h4 class="w-100">ห้องสนทนา : </h4>
                        @endif
                        <div id="avatars" class="avatars">
                            {{-- <span class="avatar">
                                <img src="https://picsum.photos/70">
                            </span>
                            <span class="avatar">
                                <img src="https://picsum.photos/80">
                            </span>
                            <span class="avatar">
                                <img src="https://picsum.photos/90">
                            </span> --}}
                        </div>
                        <div id="text_user_in_room" class="mt-2">
                            <!-- สำหรับใส่ text ที่บอกคนในห้อง-->
                        </div>

                        <a id="btnJoinRoom" class="btn btn-success d-none" href="">
                            เข้าร่วมห้องสนทนา
                        </a>
                        <a id="full_room" class="btn btn-secondary d-none" onclick="AlertPeopleInRoom()">ห้องนี้ถึงจำนวนผู้ใช้สูงสุดแล้ว</a>
                    @else
                        @if($type == "sos_1669" || $type == "user_sos_1669")
                            @php
                                $data_sos_1669 = App\Models\Sos_help_center::where('id' , $sos_id)->first();
                            @endphp

                            @if ($user->role == "partner")
                                @if (!empty($data_sos_1669->code_for_officer))
                                    <h1 class="w-100 font-weight-bold">ห้องสนทนา : {{ $data_sos_1669->code_for_officer }}</h1>
                                @else
                                    <h1 class="w-100 font-weight-bold">ห้องสนทนา : {{ $data_sos_1669->operating_code }}</h1>
                                @endif
                            @else
                                <h1 class="w-100 font-weight-bold">ห้องสนทนา : {{ $data_sos_1669->operating_code }}</h1>
                            @endif
                        @else
                            <h1 class="w-100 font-weight-bold">ห้องสนทนา : </h1>
                        @endif
                        <div id="avatars" class="avatars">
                            {{-- <span class="avatar">
                                <img src="https://picsum.photos/70">
                            </span>
                            <span class="avatar">
                                <img src="https://picsum.photos/80">
                            </span>
                            <span class="avatar">
                                <img src="https://picsum.photos/90">
                            </span> --}}
                        </div>
                        <div id="text_user_in_room" class="mt-2">
                            <!-- สำหรับใส่ text ที่บอกคนในห้อง-->
                        </div>

                        <a style="font-size: 40px; border-radius: 10px;" id="btnJoinRoom" class="btn btn-success d-none" href="">
                            เข้าร่วมห้องสนทนา
                        </a>
                        <a style="font-size: 40px; border-radius: 10px;" id="full_room" class="btn btn-secondary d-none" onclick="AlertPeopleInRoom()">ห้องนี้ถึงจำนวนผู้ใช้สูงสุดแล้ว</a>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script src="{{ asset('js/for_video_call_4/before_video_call_4.js') }}"></script> --}}

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
<!-- <script src="{{ asset('partner_new/js/bootstrap.bundle.min.js') }}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<script>
    var statusCamera = "open";
    var statusMicrophone = "open";
    var useMicrophone = '';
    var useSpeaker = '';
    var useCamera = '';

    var audioTracks = ''; // สำหรับเก็บ tag เสียงแบบ global

    // var appId = localStorage.getItem('appId');
    // var appCertificate = localStorage.getItem('appCertificate');

    var appId = '{{ env("AGORA_APP_ID") }}';
    var appCertificate = '{{ env("AGORA_APP_CERTIFICATE") }}';

    var selectedMicrophone = null;
    var selectedCamera = null;
    var selectedSpeaker = null;
    var microphoneStream = null;
    var cameraStream = null;
    var speakerStream = null;
</script>

<script>

    document.addEventListener("DOMContentLoaded", async () => {
        let agoraAppId = appId;
        let agoraAppCertificate = appCertificate;
        if (appId && appCertificate) {
            agoraAppId = '{{ config("agora.app_id") }}';
            agoraAppCertificate = '{{ config("agora.app_certificate") }}';
        }
        console.log(agoraAppId);
        console.log(agoraAppCertificate);

    });

</script>

@endsection
