@extends('layouts.theme_video_call')

@section('content')
    <link href="{{ asset('css/video_call_pc.css') }}?v={{ time() }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://kit-pro.fontawesome.com/releases/v6.4.2/css/pro.min.css" rel="stylesheet">

    <!-- Animation Loading -->
    <div class="d-flex justify-content-center align-items-center">
        <div id="lds-ring" class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button id="join" class="btn btn-success d-none" >เข้าร่วม</button> <!-- ปุ่ม join สำหรับเริ่ม video call ของตัวเอง --ซ่อนไว้ -->

        <div id="users_in_sidebar" class="users_in_sidebar">

        </div>
    </div>

    <div class="container" id="mainContainer">

        <div class="video-wrapper">
            <!-- Video Container -->
            <div class="video-container" id="video-container" ></div>

            <!-- ปุ่มซ่อน/แสดง Video Bar -->
            <button class="toggle-video-bar-btn d-none" id="toggleVideoBarBtn" onclick="toggleVideoBar()">
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <!-- Video Bar (Bottom) -->
            <div class="video-bar" id="video-bar">

            </div>
        </div>

    </div>

    <!----------------- list รายการอุปกรณ์ ตอนกดเปลี่ยนที่ปุ่มไมโครโฟน หรือ กล้อง  ------------------>
    {{-- <div class="d-flex overflow_auto_video_call row py-3" style="background-color: #2b2d31;">
        <div class="align-self-end w-100">

            <div class="dropcontent">
                <ul id="audio-device-list" class="ui-list">
                    <!-- Created list-audio from Javascript Here -->
                </ul>
            </div>
            <div class="dropcontent2">
                <ul id="video-device-list" class="ui-list">
                    <!-- Created list-video from Javascript Here -->
                </ul>
            </div>

        </div>
    </div> --}}
    <!----------------- จบ list รายการอุปกรณ์  ------------------>

    <style>
        .controls-bar .left {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 5px;  /* ช่องว่างระหว่างปุ่ม */
        }

        .controls-bar .center {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;  /* ให้ปุ่มในกลางจัดเรียงหลายแถวได้ */
            gap: 5px;
        }

        .controls-bar .right {
            display: flex;
            justify-content: flex-start;
            overflow-x: auto;  /* เลื่อนเมื่อปุ่มเกิน */
            flex-wrap: wrap;  /* เรียงปุ่มใหม่ในแถวถ้าพื้นที่ไม่พอ */
            gap: 5px;
        }
    </style>
    <!-- Controls Bar -->
    <div class="controls-bar">
        <div class="left">
            <button class="btn btnSpecial" id="sidebarBtn" onclick="toggleSidebar()">
                <i class="fa-solid fa-sidebar"></i>
            </button>
            {{-- <button class="toggle-video-bar-btn d-none mx-2" id="toggleVideoBarBtn" onclick="toggleVideoBar()">
                <i class="fa-solid fa-chevron-down"></i>
            </button> --}}
        </div>
        <div class="center">
            <!-- เปลี่ยนไมค์ ให้กดได้แค่ในคอม -->

                <div id="div_for_AudioButton" class=" btnSpecial" >
                    {{-- <i class="fa-regular fa-microphone"></i> --}}
                    <button class="smallCircle" id="btn_switchMicrophone">
                        <i class="fa-sharp fa-solid fa-angle-up"></i>
                    </button>
                    <div class="dropcontent" id="audio-dropcontent">
                        <ul id="audio-device-list" class="ui-list">
                            <!-- Created list-audio from Javascript Here -->
                        </ul>
                    </div>
                </div>

                <!-- เปลี่ยนกล้อง ให้กดได้แค่ในคอม -->
                <div id="div_for_VideoButton" class=" btnSpecial " >
                    {{-- <i id="icon_muteVideo" class="fa-solid fa-camera-rotate"></i> --}}
                    <button class="smallCircle" id="btn_switchCamera">
                        <i class="fa-sharp fa-solid fa-angle-up"></i>
                    </button>
                    <div class="dropcontent2" id="video-dropcontent">
                        <ul id="video-device-list" class="ui-list">
                            <!-- Created list-video from Javascript Here -->
                        </ul>
                    </div>
                </div>

                @if (Auth::user()->id == 1 || Auth::user()->id == 64 || Auth::user()->id == 11003429 || Auth::user()->id == 11003473)
                    <button class="btn btnSpecial d-non" id="addButton" onclick="createVideoCard();">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                @endif
                <div class="btn btnSpecial d-non" id="leave">
                    <i class="fa-solid fa-phone-xmark"></i>
                </div>
        </div>
        <div class="right">
            <div class="p-1 " style="color: #ffffff;" id="time_of_room"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('Agora_Web_SDK_FULL/AgoraRTC_N-4.23.0.js') }}"></script>
    <script src="{{ asset('js/video_call/video_call_pc.js') }}" defer></script>

    <script>
        const agoraAppId = '{{ $agoraAppId }}';
        const agoraAppCertificate = '{{ $agoraAppCertificate }}';
        const sos_id = '{{ $sos_id }}';
        const type_video_call = '{{ $type }}';

        var agoraEngine;
        // เรียกสองอันเพราะไม่อยากไปยุ่งกับโค้ดเก่า
        var user_id = '{{ Auth::user()->id }}';
        var user_data = @json(Auth::user());
        var options;
        // ใช้สำหรับ เช็คสถานะของปุ่มเปิด-ปิด วิดีโอและเสียง
        var isVideo = true;
        var isAudio = true;

        // ใช้สำหรับ เช็คสถานะของปุ่มเปิด-ปิด วิดีโอและเสียง ตอนเริ่มเข้าวิดีโอคอล
        var videoTrack = '{{$videoTrack}}';
        var audioTrack = '{{$audioTrack}}';

        // ID device ที่ส่งมาจากหน้าทางเข้า
        var useSpeaker = '{{$useSpeaker}}'; //ลำโพง
        var useMicrophone = '{{$useMicrophone}}'; //ไมโครโฟน
        var useCamera = '{{$useCamera}}'; //กล้อง

        // เก็บ id device ที่ active อยู่ปัจจุบันเพื่อใช้ในฟังก์ชัน เปลี่ยนอุปกรณ์
        var activeVideoDeviceId;
        var activeAudioDeviceId;
        var activeAudioOutputDeviceId;

        //สำหรับกำหนดสี background localPlayerContainer
        var bg_local;
        var name_local;
        var type_local;
        var profile_local;
        var type_user_sos;
        // เกี่ยวกับเวลาในห้อง
        var check_start_timer_video_call = false;
        var check_user_in_video_call = false;

        // ใช้สำหรับ เช็ค icon
        var isRemoteIconSound = false;

        // ใช้สำหรับ เช็คไม่ให้ฟังก์ชันออกห้องทำงานซ้ำ
        var leaveChannel = "false";

        // var hours = 0;
        // var minutes = 0;
        // var seconds = 0;
        var meet_2_people = 'No' ;

        var remoteVolume = localStorage.getItem('remote_rangeValue') ?? 70; // ค่าสำหรับเลือกระดับเสียงที่ได้ยินจากทุกคน
        var array_remoteVolumeAudio = [];

        var checkHtml = false; // ใช้เช็คเงื่อนไขตัวปรับเสียงของ remote

        //ใช้เช็คระดับเสียง
        var check_start_volume_indicator = [];
        var status_remote_volume = [];

        let channelParameters =
        {
            localAudioTrack: null,
            localVideoTrack: null,
            remoteAudioTrack: null,
            remoteVideoTrack: null,
            remoteUid: null,
        };

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {

            // if (type_video_call == "sos_personal_assistant") {
            //     initMap();
            // }
            console.log(user_data);
            options =
            {
                'appId': agoraAppId,
                'channel': type_video_call+sos_id,
                'token': '',
                'uid': user_data['id'],
                'role': '',
            };

            async function LoadingVideoCall() {
                const loadingAnime = document.getElementById('lds-ring');

                try {
                    if (loadingAnime) {
                        loadingAnime.classList.remove('d-none'); // แสดง loading
                    }

                    const response = await fetch("{{ url('/') }}/api/video_call_token" +
                        "?user_id=" + user_data['id'] +
                        '&appCertificate=' + agoraAppCertificate +
                        '&appId=' + agoraAppId +
                        '&type=' + type_video_call +
                        '&sos_id=' + sos_id
                    );

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const result = await response.json();
                    // console.log("GET Token success");
                    // console.log(result);

                    options['token'] = result['token'];
                    // console.log( options['token']);

                    // ตั้งค่าเวลาหมดอายุของห้อง
                    // const expirationTimestamp = result['privilegeExpiredTs'];

                     // ฟังก์ชันตรวจสอบเวลาหมดอายุแบบประหยัดทรัพยากร
                    function checkAndNotifyExpiration(expirationTimestamp) {
                        const currentTimestamp = Math.floor(Date.now() / 1000);
                        const timeRemaining = expirationTimestamp - currentTimestamp;

                        if (timeRemaining <= 0) {
                            // ถ้าหมดอายุแล้ว ให้กดปุ่ม leave ทันที
                            document.getElementById('leave').click();
                        } else {
                            setTimeout(() => {
                                document.getElementById('leave').click();
                            }, timeRemaining * 1000); // แปลงวินาทีเป็นมิลลิวินาที
                        }
                    }
                    checkAndNotifyExpiration(600); // เรียกใช้ฟังก์ชันนี้ เมื่อครบ 10 นาที --> กดออกห้อง

                    //เริ่มทำการสร้าง channel Video_call
                    startBasicCall();
                    setTimeout(() => {
                        document.getElementById("join").click();
                    }, 1000);

                } catch (error) {
                    console.error("Error fetching video call data:", error);

                    if (loadingAnime) {
                        loadingAnime.classList.add('d-none'); // ซ่อน loading เมื่อเกิด error
                    }

                    // ลองโหลดใหม่อีกครั้งหลังจาก 2 วินาที
                    setTimeout(() => {
                        LoadingVideoCall();
                    }, 2000);
                }
            }


            //แสดง animation โหลด
            LoadingVideoCall();

            //หาตำแหน่งของผู้ใช้ --> แสดงข้อมูล sos_map ตามจังหวัด
            // if(type_video_call === "sos_map"){
            //     find_location();
            // }


            const btn_switchCamera = document.querySelector('#btn_switchCamera');
            const btn_switchMicrophone = document.querySelector('#btn_switchMicrophone');
            // ฟังก์ชันเปิด-ปิดรายการไมโครโฟน
            btn_switchMicrophone.addEventListener("click", function(event) {
                event.stopPropagation(); // หยุดการกระจายเหตุการณ์คลิกไปยัง document

                // ซ่อน dropcontent ของกล้องหากเปิดอยู่
                if (document.querySelector(".open_dropcontent2")) {
                    document.querySelector(".dropcontent2").classList.remove("open_dropcontent2");
                }

                // สลับแสดง hide/show ของ dropcontent ไมโครโฟน
                document.querySelector(".dropcontent").classList.toggle("open_dropcontent");
            });

            // ฟังก์ชันเปิด-ปิดรายการกล้อง
            btn_switchCamera.addEventListener("click", function(event) {
                event.stopPropagation(); // หยุดการกระจายเหตุการณ์คลิกไปยัง document

                // ซ่อน dropcontent ของไมโครโฟนหากเปิดอยู่
                if (document.querySelector(".open_dropcontent")) {
                    document.querySelector(".dropcontent").classList.remove("open_dropcontent");
                }

                // สลับแสดง hide/show ของ dropcontent2 กล้อง
                document.querySelector(".dropcontent2").classList.toggle("open_dropcontent2");
            });

            // ปิด dropcontent เมื่อคลิกที่นอกตัว dropcontent
            document.addEventListener("click", function(event) {
                if (!event.target.closest(".dropcontent")) {
                    document.querySelector(".dropcontent").classList.remove("open_dropcontent");
                }
                if (!event.target.closest(".dropcontent2")) {
                    document.querySelector(".dropcontent2").classList.remove("open_dropcontent2");
                }
            });



        });

    </script>

    <script>
        async function startBasicCall()
        {
            // Create an instance of the Agora Engine

            agoraEngine = AgoraRTC.createClient({
                mode: "rtc",
                codec: "vp9",
                connectTimeout: 10000 // เพิ่ม Timeout เป็น 10 วินาที
            });
            // console.log("agoraEngine");
            // console.log(agoraEngine);
            let rtcStats = agoraEngine.getRTCStats();
            // console.log("rtcStats");
            // console.log(rtcStats);

            agoraEngine.enableAudioVolumeIndicator(); // เปิดตัวตรวจับระดับเสียงไมค์

            let remotePlayerContainer = []; // ไว้เก็บข้อมูล ผู้ใช้คนอื่น

            let localPlayerContainer = document.createElement('div');
                localPlayerContainer.id = options.uid;
                localPlayerContainer.style.backgroundColor = "gray";
                localPlayerContainer.style.width = "100%";
                localPlayerContainer.style.height = "100%";
                localPlayerContainer.style.position = "absolute";
                localPlayerContainer.style.left = "0";
                localPlayerContainer.style.top = "0";
                localPlayerContainer.classList.add('agora_create_local');

            if(user_data.photo){
                profile_local = "{{ url('/storage') }}" + "/" + user_data.photo;
                // profile_local = "https://www.viicheck.com/" + element.photo;
            }else if(!user_data.photo && user_data.avatar){
                profile_local = user_data.avatar;
            }else{
                profile_local = "https://www.viicheck.com/Medilab/img/icon.png";
            }

            //===== สุ่มสีพื้นหลังของ localPlayerContainer=====

            fetch("{{ url('/') }}/api/get_local_data" + "?user_id=" + options.uid + "&type=" + type_video_call + "&sos_id=" + sos_id)
                .then(response => response.json())
                .then(result => {
                    console.log("result get_local_data");
                    console.log(result);
                    bg_local = result.hexcolor;
                    name_local = result.name_user;
                    type_local = result.user_type;

                    type_user_sos = type_local; //เก็บ ประเภทผู้ใช้ไว้ใน array

                    // changeBgColor(bg_local);
            })
            .catch(error => {
                console.log("โหลดข้อมูล LocalUser ล้มเหลว ใน get_local_data");
            });
            //===== จบส่วน สุ่มสีพื้นหลังของ localPlayerContainer =====

            // Listen for the "user-published" event to retrieve a AgoraRTCRemoteUser object.
            agoraEngine.on("user-published", async (user, mediaType) =>
            {
                await agoraEngine.subscribe(user, mediaType);
                console.log("subscribe success");
                // console.log("user");
                // console.log(user);

                // สำหรับปรับลดคุณภาพสตรีมวิดีโอของผู้ใช้ uid เมื่อเครือข่ายไม่เสถียร หรือมีปัญหาด้านแบนด์วิดท์
                    // 0 → ไม่ลดคุณภาพ (รับวิดีโอเต็มคุณภาพเสมอ ไม่ว่าเน็ตจะแย่แค่ไหน
                    // 1 → ลดวิดีโอเป็นเสียง (ถ้าเน็ตแย่ ลดคุณภาพจนเหลือแค่เสียง)
                    // 2 → ลดคุณภาพวิดีโอแต่ไม่ปิดเสียง (ลดจาก HD → SD → ต่ำสุด แต่ยังมีวิดีโอ)
                agoraEngine.setStreamFallbackOption(channelParameters.remoteUid, 2);

                // setTimeout(() => {
                //     StatsVideoUpdate();
                // }, 2500);

                // Set the remote video container size.
                remotePlayerContainer[user.uid] = document.createElement("div");
                remotePlayerContainer[user.uid].style.backgroundColor = "black";
                remotePlayerContainer[user.uid].style.width = "100%";
                remotePlayerContainer[user.uid].style.height = "100%";
                remotePlayerContainer[user.uid].style.position = "absolute";
                remotePlayerContainer[user.uid].style.left = "0";
                remotePlayerContainer[user.uid].style.top = "0";

                // ตรวจสอบว่า user.uid เป็นไอดีของ remote user ที่คุณเลือก
                // if (mediaType == "video" && user.videoTrack)
                if (mediaType == "video")
                {
                    channelParameters.remoteVideoTrack = user.videoTrack;
                    channelParameters.remoteAudioTrack = user.audioTrack;

                    console.log("============== channelParameters.remoteVideoTrack ใน published  ==================");
                    console.log(channelParameters.remoteVideoTrack);

                    channelParameters.remoteUid = user.uid.toString();
                    remotePlayerContainer[user.uid].id = user.uid.toString();

                    //======= สำหรับสร้าง div ที่ใส่ video tag พร้อม id_tag สำหรับลบแท็ก ========//
                    let name_remote;
                    let type_remote;

                    fetch("{{ url('/') }}/api/get_remote_data" + "?user_id=" + user.uid + "&type=" + type_video_call + "&sos_id=" + sos_id)
                        .then(response => response.json())
                        .then(result => {
                            // console.log("result published");
                            // console.log(result);

                            bg_remote = result.hexcolor;
                            name_remote = result.name_user;
                            type_remote = result.user_type;

                            // bg_remote = "#2b2d31";
                            // name_remote = "guest";
                            // type_remote = "guest";

                            console.log("โหลดข้อมูล RemoteUser สำเร็จ published");

                            // สำหรับ สร้าง divVideo ตอนผู้ใช้เปิดกล้อง
                            create_element_remotevideo_call(remotePlayerContainer[user.uid], name_remote, type_remote , bg_remote ,user);

                            channelParameters.remoteVideoTrack.play(remotePlayerContainer[user.uid]);

                    })
                    .catch(error => {
                        console.log("โหลดข้อมูล RemoteUser ล้มเหลว published");
                    });

                    if(user.hasVideo == false){
                        // เปลี่ยน ไอคอนวิดีโอเป็น ปิด
                        document.querySelector('#camera_remote_' + user.uid).innerHTML = '<i class="fa-duotone fa-video-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
                    }else{
                        // เปลี่ยน ไอคอนวิดีโอเป็น เปิด
                        document.querySelector('#camera_remote_' + user.uid).innerHTML = '<i class="fa-solid fa-video"></i>';
                    }

                    if(user.hasAudio == false){
                        // เปลี่ยน ไอคอนไมโครโฟนเป็น ปิด
                        document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
                    }else{
                        // เปลี่ยน ไอคอนไมโครโฟนเป็น เปิด
                        document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-solid fa-microphone"></i>';
                    }

                    // channelParameters.remoteVideoTrack.play(remotePlayerContainer);

                }

                if (mediaType == "audio")
                {
                    channelParameters.remoteAudioTrack = user.audioTrack;
                    channelParameters.remoteAudioTrack.play();

                    channelParameters.remoteAudioTrack.setVolume(parseInt(array_remoteVolumeAudio[user.uid]));

                    onChangeAudioOutputDevice();

                    if(user.hasAudio == false){
                        // เปลี่ยน ไอคอนไมโครโฟนเป็น ปิด
                        if (document.querySelector('#mic_remote_' + user.uid)) {
                            document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
                        }else{
                            console.log("========================= ");
                            console.log("ไมค์ตาย");
                            console.log("=========================");
                        }
                    }else{
                        // เปลี่ยน ไอคอนไมโครโฟนเป็น เปิด
                        if (document.querySelector('#mic_remote_' + user.uid)) {
                            document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-solid fa-microphone"></i>';
                        }else{
                            console.log("========================= ");
                            console.log("ไมค์ตาย");
                            console.log("=========================");
                        }
                    }

                    let type_of_microphone = "open";
                    waitForElement_in_sidebar(type_of_microphone,user.uid); // รอจนกว่าจะมี icon ของไอดีนี้ใน sidebar และ เปลี่ยนไอคอน

                    //ตรวจจับเสียงพูดแล้ว สร้าง animation บนขอบ div
                    // agoraEngine.on("volume-indicator", volumes => {
                    //     volumes.forEach((volume, index) => {
                    //         console.log("volume in published");
                    //         if (user.uid == volume.uid && volume.level > 50) {
                    //             console.log(`${index} UID ${volume.uid} Level ${volume.level}`);
                    //             document.querySelector('#statusMicrophoneOutput_remote_'+ user.uid).classList.remove('d-none');
                    //         } else if (user.uid == volume.uid && volume.level <= 50) {
                    //             console.log(`${index} UID ${volume.uid} Level ${volume.level}`);
                    //             document.querySelector('#statusMicrophoneOutput_remote_'+ user.uid).classList.add('d-none');
                    //         }
                    //     });
                    // })

                    status_remote_volume[user.uid] = "yes";
                    if (check_start_volume_indicator[user.uid] == "no") {
                        volume_indicator_remote(user.uid);
                    }

                }

            });

            // Listen for the "user-unpublished" event.
            agoraEngine.on("user-unpublished", async (user, mediaType) =>
            {
                // console.log("เข้าสู่ user-unpublished");
                // console.log("agoraEngine");
                // console.log(agoraEngine);

                if(mediaType == "video"){
                    if (user.hasVideo == false) {

                        // console.log("สร้าง Div_Dummy ของ" + user.uid);
                        // console.log(user);

                        let name_remote_user_unpublished;
                        let type_remote_user_unpublished;
                        let profile_remote_user_unpublished;
                        let hexcolor;
                        fetch("{{ url('/') }}/api/get_remote_data" + "?user_id=" + user.uid + "&type=" + type_video_call + "&sos_id=" + sos_id)
                            .then(response => response.json())
                            .then(result => {
                                // console.log("result");
                                // console.log(result);

                                hexcolor = result.hexcolor;
                                name_remote_user_unpublished = result.name_user;
                                type_remote_user_unpublished = result.user_type;

                                if(result.photo){
                                    profile_remote_user_unpublished = "{{ url('/storage') }}" + "/" + result.photo;
                                }else if(!result.photo && result.avatar){
                                    profile_remote_user_unpublished = result.avatar;
                                }else{
                                    profile_remote_user_unpublished = "https://www.viicheck.com/Medilab/img/icon.png";
                                }
                                // สำหรับ สร้าง div_dummy ตอนผู้ใช้ไม่ได้เปิดกล้อง
                                create_dummy_videoTrack(user ,name_remote_user_unpublished ,type_remote_user_unpublished ,profile_remote_user_unpublished, hexcolor);

                                // เปลี่ยน ไอคอนวิดีโอเป็น ปิด
                                if(user.hasVideo == false){
                                    // เปลี่ยน ไอคอนวิดีโอเป็น ปิด
                                    document.querySelector('#camera_remote_' + user.uid).innerHTML = '<i class="fa-duotone fa-video-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
                                }else{
                                    // เปลี่ยน ไอคอนวิดีโอเป็น เปิด
                                    document.querySelector('#camera_remote_' + user.uid).innerHTML = '<i class="fa-solid fa-video"></i>';
                                }

                                if(user.hasAudio == false){
                                    // เปลี่ยน ไอคอนไมโครโฟนเป็น ปิด
                                    document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
                                }else{
                                    // เปลี่ยน ไอคอนไมโครโฟนเป็น เปิด
                                    document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-solid fa-microphone"></i>';
                                }

                        })
                        .catch(error => {
                            console.log("โหลดข้อมูล RemoteUser ล้มเหลว");
                        });

                    }
                }

                if(mediaType == "audio"){
                    // ตรวจจับเสียงพูดแล้ว สร้าง animation บนขอบ div
                    console.log('unpublished AudioTrack:');
                    console.log(channelParameters.localAudioTrack);

                    status_remote_volume[user.uid] = "no";

                    let type_of_microphone = "close";
                    waitForElement_in_sidebar(type_of_microphone,user.uid); // รอจนกว่าจะมี icon ของไอดีนี้ใน sidebar และ เปลี่ยนไอคอน

                    if(user.hasAudio == false){
                        console.log("if unpublished");
                        // เปลี่ยน ไอคอนไมโครโฟนเป็น ปิด
                        document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
                    }else{
                        console.log("else unpublished");
                        // เปลี่ยน ไอคอนไมโครโฟนเป็น เปิด
                        document.querySelector('#mic_remote_' + user.uid).innerHTML = '<i class="fa-solid fa-microphone"></i>';
                    }

                    // agoraEngine.on("volume-indicator", volumes => {
                    //     volumes.forEach((volume, index) => {
                    //         console.log("volume in unpublished");
                    //         if (user.uid == volume.uid && volume.level > 50) {
                    //             console.log(`${index} UID ${volume.uid} Level ${volume.level}`);
                    //             document.querySelector('#statusMicrophoneOutput_remote_'+ user.uid).classList.remove('d-none');
                    //         } else if (user.uid == volume.uid && volume.level <= 50) {
                    //             console.log(`${index} UID ${volume.uid} Level ${volume.level}`);
                    //             document.querySelector('#statusMicrophoneOutput_remote_'+ user.uid).classList.add('d-none');
                    //         }
                    //     });
                    // })

                }


            });

            // เมื่อมีคนเข้าห้อง
            agoraEngine.on("user-joined", function (evt)
            {
                check_start_volume_indicator[evt.uid] = "no";

                console.log("agoraEngine มีคนเข้าห้องมา");
                console.log(agoraEngine);

                // เสียงแจ้งเตือน เวลาคนเข้า
                let audio_ringtone_join = new Audio("{{ asset('sound/join_room_1.mp3') }}");
                    audio_ringtone_join.play();

                // หยุดการเล่นเสียงหลังจาก 1 วินาที
                setTimeout(function() {
                    audio_ringtone_join.pause();
                    audio_ringtone_join.currentTime = 0; // เริ่มเสียงใหม่เมื่อต้องการเล่นอีกครั้ง
                }, 1000);

                if(agoraEngine['remoteUsers'][0]){
                    if( agoraEngine['remoteUsers']['length'] != 0 ){
                        for(let c_uid = 0; c_uid < agoraEngine['remoteUsers']['length']; c_uid++){

                            const dummy_remote = agoraEngine['remoteUsers'][c_uid];
                            console.log(dummy_remote);

                            if(dummy_remote['hasVideo'] == false){ //ถ้า remote คนนี้ ไม่ได้เปิดกล้องไว้ --> ไปสร้าง div_dummy
                                let name_remote_user_joined;
                                let type_remote_user_joined;
                                let profile_remote_user_joined;
                                let hexcolor;
                                fetch("{{ url('/') }}/api/get_remote_data" + "?user_id=" + dummy_remote.uid + "&type=" + type_video_call + "&sos_id=" + sos_id)
                                    .then(response => response.json())
                                    .then(result => {
                                        // console.log("result");
                                        // console.log(result);
                                        name_remote_user_joined = result.name_user;
                                        type_remote_user_joined = result.user_type
                                        hexcolor = result.hexcolor;
                                        // hexcolor = "#2b2d26";
                                        // name_remote_user_unpublished = "guest";
                                        // type_remote_user_unpublished = "guest";
                                        if(result.photo){
                                            profile_remote_user_joined = "{{ url('/storage') }}" + "/" + result.photo;
                                        }else if(!result.photo && result.avatar){
                                            profile_remote_user_joined = result.avatar;
                                        }else{
                                            profile_remote_user_joined = "https://www.viicheck.com/Medilab/img/icon.png";
                                        }

                                        create_dummy_videoTrack(dummy_remote ,name_remote_user_joined ,type_remote_user_joined ,profile_remote_user_joined ,hexcolor);
                                        console.log("Dummy Created !!!");

                                        // สร้าง โปรไฟล์ใน sidebar =========== อยู่จนกว่าจะออกจากห้อง ======================

                                        let create_profile_remote = document.createElement("div");
                                            create_profile_remote.id = "profile_"+dummy_remote.uid;
                                            create_profile_remote.classList.add('row');

                                        let html_profile_user = create_profile_in_sidebar(dummy_remote ,name_remote_user_joined ,type_remote_user_joined ,profile_remote_user_joined,array_remoteVolumeAudio[dummy_remote.uid]);


                                        create_profile_remote.innerHTML = html_profile_user;

                                        // ตรวจสอบว่าเจอ div เดิมหรือไม่
                                        let oldDiv = document.getElementById("profile_"+ dummy_remote.uid);
                                        if (oldDiv) {
                                            // ใช้ parentNode.replaceChild() เพื่อแทนที่ div เดิมด้วย div ใหม่
                                            oldDiv.parentNode.replaceChild(create_profile_remote, oldDiv);
                                        } else {
                                            document.querySelector('#users_in_sidebar').appendChild(create_profile_remote);
                                        }

                                        // จบส่วน สร้าง โปรไฟล์ใน sidebar ===============================================

                                        // เปลี่ยน ไอคอนวิดีโอเป็น ปิด
                                        document.querySelector('#camera_remote_' + dummy_remote.uid).innerHTML = '<i class="fa-duotone fa-video-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';

                                        //เช็คว่าไมค์ของเขาเปิดหรือไม่
                                        if(dummy_remote['hasAudio'] == false){ //ถ้า remote คนนี้ ไม่ได้เปิดไมไว้ --> ไปสร้าง div_dummy
                                            status_remote_volume[dummy_remote.uid] = "no";
                                            // เปลี่ยน ไอคอนไมโครโฟนเป็น ปิด
                                            document.querySelector('#mic_remote_' + dummy_remote.uid).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
                                        }else{
                                            // เปลี่ยน ไอคอนไมโครโฟนเป็น เปิด
                                            document.querySelector('#mic_remote_' + dummy_remote.uid).innerHTML = '<i class="fa-solid fa-microphone"></i>';

                                            status_remote_volume[dummy_remote.uid] = "yes";
                                            if (check_start_volume_indicator[dummy_remote.uid] == "no") {
                                                volume_indicator_remote(dummy_remote.uid);
                                            }

                                        }

                                        let type_of_microphone;
                                        if (dummy_remote['hasAudio'] == false) {
                                            type_of_microphone = "close";
                                        } else {
                                            type_of_microphone = "open";
                                        }

                                        waitForElement_in_sidebar(type_of_microphone,dummy_remote.uid); // รอจนกว่าจะมี icon ของไอดีนี้ใน sidebar
                                        updateVideoVisibility(); // นับจำนวน video div ไม่ให้เกิน 9

                                })
                                .catch(error => {
                                    console.log("โหลด เมื่อมีคนเข้าห้อง ล้มเหลว");
                                });

                            }

                        }
                    }
                }

                setTimeout(() => {

                    fetch("{{ url('/') }}/api/check_status_room" + "?sos_id="+ sos_id + "&type=" + type_video_call)
                        .then(response => response.json())
                        .then(result => {
                        // console.log("check_status_room user_join");
                        // console.log(result);

                        let member_in_room = JSON.parse(result['member_in_room']);
                        // console.log(member_in_room.length);

                        if(member_in_room.length >= 2){
                            if(check_start_timer_video_call == false){
                                start_timer_video_call();
                            }else{
                                clearInterval(loop_timer_video_call);
                                document.getElementById("time_of_room").innerHTML = "";
                                start_timer_video_call();
                            }

                            // if (check_user_in_video_call == false) {
                            //     start_user_in_video_call(); // ทำฟังก์ชันเช็คคนที่ออกจากห้องไปแล้ว
                            // }
                        }
                    });
                }, 2000);

            });

            // ออกจากห้อง
            agoraEngine.on("user-left", function (evt)
            {

                console.log("ไอดี : " + evt.uid + " ออกจากห้อง");

                // ลบ videoDiv_ ที่อยู่ใน ห้องสนทนาออก
                if(document.getElementById('videoDiv_' + evt.uid)) {
                    document.getElementById('videoDiv_' + evt.uid).remove();
                }

                // ลบ โปรไฟล์ที่อยู่ใน sidebar เมื่อ ออก
                if(document.getElementById('profile_' + evt.uid)) {
                    document.getElementById('profile_' + evt.uid).remove();
                }

                updateVideoVisibility(); // นับจำนวน video div ไม่ให้เกิน 9

                // เช็คว่ามี div .custom-div อยู่ใน div container_user_video_call
                let container = document.getElementById("video-container");
                let customDivs = container.querySelectorAll(".video-card");
                //ถ้าไม่มีให้ ย้าย div ใน bar ข้างล่าง ขึ้นมาทั้งหมด
                if (customDivs.length == 0) {
                    moveAllDivsToContainer();
                }

                // เสียงแจ้งเตือน เวลาคนเข้า
                let audio_ringtone_left = new Audio("{{ asset('sound/left_room_1.mp3') }}");
                audio_ringtone_left.play();

                // หยุดการเล่นเสียงหลังจาก 1 วินาที
                setTimeout(function() {
                    audio_ringtone_left.pause();
                    audio_ringtone_left.currentTime = 0; // เริ่มเสียงใหม่เมื่อต้องการเล่นอีกครั้ง
                }, 1000);

                //=======================  Check Delete Member =========================

                fetch("{{ url('/') }}/api/left_room" + "?user_id=" + evt.uid + "&type=" + type_video_call + "&sos_id=" + sos_id +"&meet_2_people=beforeunload"+"&leave=beforeunload")
                    .then(response => response.text())
                    .then(result => {
                        console.log("result left_room :" + result);
                        // OK
                });

                //=======================  Check Member And Stop Count Time =========================
                setTimeout(() => {
                    fetch("{{ url('/') }}/api/check_status_room" + "?sos_id="+ sos_id + "&type=" + type_video_call)
                        .then(response => response.json())
                        .then(result => {
                            console.log("result check_status_room");
                            console.log(typeof result['member_in_room']);
                            console.log(result['member_in_room']);

                        let member_in_room = JSON.parse(result['member_in_room']);
                        console.log(typeof member_in_room);

                        // ถ้าผู้ใช้ เหลือ น้อยกว่า 2 คน ให้หยุดนับเวลา
                        if(member_in_room.length < 2){
                            console.log("member_in_room น้อยกว่า 2 --> user-left");
                            if(check_start_timer_video_call == true){
                                myStop_timer_video_call();
                            }

                            // if (check_user_in_video_call == true) {
                            //     Stop_check_user_in_video_call();
                            // }
                        }
                        // ถ้าผู้ใช้ เหลือ 0 คน ให้ทำลายห้องทิ้ง
                        if(member_in_room.length < 1){
                            setTimeout(() => {
                                agoraEngine.destroy();
                            }, 7000);
                        }
                    });
                }, 3000);


                console.log("agoraEngine ของ user-left");
                console.log(agoraEngine);

            });

            document.getElementById("join").onclick = async function (user_id)
            {
                try {
                    let response = await fetch("{{ url('/') }}/api/check_user_in_room" + "?sos_id=" + sos_id + "&type=" + type_video_call);
                    let result = await response.json();

                    if (result['status'] == "ok") {

                        // Enable dual-stream mode.
                        // agoraEngine.enableDualStream();
                        // Join a channel.
                        await agoraEngine.join(options.appId, options.channel, options.token, options.uid);
                        // Create a local audio track from the audio sampled by a microphone.

                        // ปิดกล้องเดิม (หากมีการสร้างไว้ก่อนหน้านี้)
                        if (channelParameters.localVideoTrack) {
                            channelParameters.localVideoTrack.close();
                            channelParameters.localVideoTrack = null;
                        }

                        // ปิดไมโครโฟนเดิม (หากมีการสร้างไว้ก่อนหน้านี้)
                        if (channelParameters.localAudioTrack) {
                            channelParameters.localAudioTrack.close();
                            channelParameters.localAudioTrack = null;
                        }

                        //หาไมโครโฟน
                        try {
                            let microphoneId = useMicrophone || await getActiveMicrophoneId();

                            if (!microphoneId) {
                                console.error("ไม่พบไมโครโฟนที่ active");
                                return null;
                            }

                            // สร้าง local audio track พร้อมตัวเลือกที่เหมาะสม
                            channelParameters.localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack({
                                AEC: true, // Acoustic Echo Cancellation (ยกเลิกเสียงสะท้อน)
                                ANS: true, // Automatic Noise Suppression (ลดเสียงรบกวนอัตโนมัติ)
                                AGC: true, // Automatic Gain Control (ปรับระดับเสียงอัตโนมัติ)
                                encoderConfig: "high_quality", // คุณภาพเสียงสูง
                                microphoneId: microphoneId
                            });

                            console.log("🎤 ไมโครโฟนพร้อมใช้งาน:", microphoneId);
                        } catch (error) {
                            console.error("❌ เกิดข้อผิดพลาดในการสร้างไมโครโฟน:", error);
                        }

                        // 🔍 ฟังก์ชันช่วยในการหาไมโครโฟนที่ใช้งานได้
                        // async function getActiveMicrophoneId() {
                        //     try {
                        //         let devices = await navigator.mediaDevices.enumerateDevices();
                        //         let microphones = devices.filter(device => device.kind === 'audioinput' && device.deviceId !== 'default');

                        //         return microphones.length > 0 ? microphones[0].deviceId : null;

                        //     } catch (error) {
                        //         console.error("❌ ไม่สามารถดึงข้อมูลไมโครโฟนได้:", error);
                        //         return null;
                        //     }
                        // }

                        // // ฟังก์ชันเปลี่ยนอุปกรณ์ลำโพง
                        // async function updateSpeaker(selectedSpeakerId) {
                        //     try {
                        //         // ตั้งค่าอุปกรณ์ลำโพงที่เลือก
                        //         await AgoraRTC.setAudioOutputDevice(selectedSpeakerId);
                        //         console.log("🔊 ลำโพงพร้อมใช้งาน:", selectedSpeakerId);
                        //     } catch (error) {
                        //         console.error("❌ เกิดข้อผิดพลาดในการตั้งค่าลำโพง:", error);
                        //     }
                        // }

                        // // ฟังก์ชันช่วยในการหาลำโพงที่ใช้งานได้
                        // async function getActiveSpeakerId() {
                        //     try {
                        //         let devices = await navigator.mediaDevices.enumerateDevices();
                        //         let speakers = devices.filter(device => device.kind === 'audiooutput' && device.deviceId !== 'default');

                        //         return speakers.length > 0 ? speakers[0].deviceId : null;

                        //     } catch (error) {
                        //         console.error("❌ ไม่สามารถดึงข้อมูลลำโพงได้:", error);
                        //         return null;
                        //     }
                        // }

                        // หากล้อง
                        try {
                            let cameraId = useCamera || await getActiveCameraId();

                            if (!cameraId) {
                                console.error("❌ ไม่พบกล้องที่ active");
                                return null;
                            }

                            // สร้าง local video track พร้อมตัวเลือกที่เหมาะสม
                            channelParameters.localVideoTrack = await AgoraRTC.createCameraVideoTrack({
                                cameraId: cameraId,
                                encoderConfig: "720p_30fps",
                                optimizationMode: "detail", // โหมดปรับคุณภาพที่เน้นรายละเอียด
                            });

                            console.log("📷 กล้องพร้อมใช้งาน:", cameraId);
                        } catch (error) {
                            console.error("❌ เกิดข้อผิดพลาดในการสร้างกล้อง:", error);
                        }

                        // 🔍 ฟังก์ชันช่วยในการหากล้องที่ใช้งานได้
                        async function getActiveCameraId() {
                            try {
                                let devices = await navigator.mediaDevices.enumerateDevices();
                                let cameras = devices.filter(device => device.kind === 'videoinput');

                                return cameras.length > 0 ? cameras[0].deviceId : null;

                            } catch (error) {
                                console.error("❌ ไม่สามารถดึงข้อมูลกล้องได้:", error);
                                return null;
                            }
                        }

                        async function publishAndJoin() {
                            try {
                                // ✅ Publish วิดีโอและเสียงไปที่ Agora
                                await agoraEngine.publish([channelParameters.localVideoTrack, channelParameters.localAudioTrack]);
                                console.log("✅ AgoraEngine Published Successfully");

                                // ✅ เมื่อ publish สำเร็จ ให้ส่งข้อมูลไปเก็บใน Database
                                console.log("publishAndJoin");
                                console.log(type_video_call);
                                console.log(sos_id);

                                const response = await fetch("{{ url('/') }}/api/join_room" +
                                    "?user_id=" + '{{ Auth::user()->id }}' +
                                    "&type=" + type_video_call +
                                    "&sos_id=" + sos_id);

                                if (!response.ok) throw new Error("❌ ไม่สามารถส่งข้อมูลไปยัง Database ได้");

                                const result = await response.json();
                                console.log("✅ Result from join_room:", result);

                                // ✅ ตรวจสอบจำนวนสมาชิกในห้อง
                                handleRoomMemberUpdate(result);

                            } catch (error) {
                                console.error("❌ เกิดข้อผิดพลาดระหว่างการ Publish หรือ Update:", error);
                            }
                        }

                        // ✅ จัดการการอัปเดตสมาชิกในห้อง
                        function handleRoomMemberUpdate(result) {
                            setTimeout(() => {
                                if (result.length >= 2) {
                                    if (check_start_timer_video_call == false) {
                                        start_timer_video_call();
                                    }
                                    // if (!check_user_in_video_call) {
                                    //     start_user_in_video_call();
                                    // }
                                } else {
                                    if (check_start_timer_video_call == true) {
                                        // console.log("⚠ สมาชิกในห้องน้อยกว่า 2, หยุด Timer");
                                        myStop_timer_video_call();
                                    }
                                    // if (check_user_in_video_call) {
                                    //     Stop_check_user_in_video_call();
                                    // }
                                }
                            }, 800);
                        }
                        // ✅ เรียกใช้งานฟังก์ชันหลัก
                        publishAndJoin();

                        //======= สำหรับสร้าง div ที่ใส่ video tag พร้อม id_tag สำหรับลบแท็ก ========//
                        console.log("localPlayerContainer");
                        console.log(localPlayerContainer);
                        console.log(name_local);
                        console.log(type_local);
                        console.log(profile_local);
                        console.log(bg_local);

                        create_element_localvideo_call(localPlayerContainer, name_local, type_local, profile_local, bg_local);
                        // Play the local video track.
                        channelParameters.localVideoTrack.play(localPlayerContainer);

                        //======= สำหรับ สร้างปุ่มที่ใช้ เปิด-ปิด กล้องและไมโครโฟน ==========//
                        btn_toggle_mic_camera(videoTrack,audioTrack,bg_local);
                        console.log("btn_toggle_mic_camera");

                        // สร้าง โปรไฟล์ใน sidebar =========== อยู่จนกว่าจะออกจากห้อง ======================
                        let create_profile_local = document.createElement("div");
                            create_profile_local.id = "profile_"+localPlayerContainer.id;
                            create_profile_local.classList.add('row');

                        let html_profile_user = create_profile_in_sidebar_local_only(localPlayerContainer ,name_local ,type_local ,profile_local);

                        create_profile_local.innerHTML = html_profile_user;

                        // ตรวจสอบว่าเจอ div เดิมหรือไม่
                        let oldDiv = document.getElementById("profile_"+ localPlayerContainer.id);
                        if (oldDiv) {
                            // ใช้ parentNode.replaceChild() เพื่อแทนที่ div เดิมด้วย div ใหม่
                            oldDiv.parentNode.replaceChild(create_profile_local, oldDiv);
                        } else {
                            document.querySelector('#users_in_sidebar').appendChild(create_profile_local);
                        }
                        // จบส่วน สร้าง โปรไฟล์ใน sidebar ===============================================

                        //ถ้ากดปุ่ม muteVideo แล้วกล้องอยู่ในสถานะปิด ให้เปลี่ยนสี bg ของ local
                        document.querySelector('#muteVideo').addEventListener("click", function(e) {
                            if (isVideo == false) {
                                console.log(bg_local);
                                changeBgColor(bg_local);
                            }
                        });

                        //ถ้ากดปุ่ม muteVideo แล้วกล้องอยู่ในสถานะปิด ให้เปลี่ยนสี bg ของ local
                        document.querySelector('#muteAudio').addEventListener("click", function(e) {
                            if (isAudio == true) {
                                // SoundTest();
                            }
                        });

                        if(isAudio == true){
                            agoraEngine.publish([channelParameters.localAudioTrack]);
                        }

                        try { // เช็คสถานะจากห้องทางเข้า แล้วเลือกกดเปิด-ปิด ตามสถานะ
                            if(videoTrack == "open"){
                                // เข้าห้องด้วย->สถานะเปิดกล้อง
                                isVideo = false;
                                document.querySelector('#muteVideo').click();
                                console.log("Click open video ===================");
                            }else{
                                // เข้าห้องด้วย->สถานะปิดกล้อง
                                isVideo = true;
                                document.querySelector('#muteVideo').click();
                                console.log("Click close video ===================");
                            }

                            if(audioTrack == "open"){
                                // เข้าห้องด้วย->สถานะเปิดไมค์
                                isAudio = false;
                                document.querySelector('#muteAudio').click();
                                console.log("Click open audio ===================");
                            }else{
                                // เข้าห้องด้วย->สถานะปิดไมค์
                                isAudio = true;
                                document.querySelector('#muteAudio').click();
                                console.log("Click close audio ===================");
                            }
                        }
                        catch (error) {
                            console.log('ส่งตัวแปร videoTrack audioTrack ไม่สำเร็จ');
                        }

                        // console.log('AudioTrack:');
                        // console.log(channelParameters.localAudioTrack);

                        // เอาหน้าโหลดออก
                        document.querySelector('#lds-ring').remove();

                    }else{
                        alert("จำนวนผู้ใช้ในห้องสนทนาสูงสุดแล้ว");
                        window.history.back();
                    }

                } catch (error) {
                    console.log("โหลดหน้าล้มเหลว :" + error);
                    // alert("ไม่สามารถเข้าร่วมได้ ");
                    // window.location.reload(); // รีเฟรชหน้าเว็บ
                }
            }
            // Listen to the Leave button click event.
            document.getElementById('leave').onclick = async function ()
            {

                // Destroy the local audio and video tracks.
                if(channelParameters.localAudioTrack){
                    channelParameters.localAudioTrack.close();
                }
                if(channelParameters.localVideoTrack){
                    channelParameters.localVideoTrack.close();
                }

                // Remove the containers you created for the local video and remote video.
                // removeVideoDiv(remotePlayerContainer.id);
                console.log("localPlayerContainer : "+localPlayerContainer);

                removeVideoDiv(localPlayerContainer.id);
                // Leave the channel
                await agoraEngine.leave();
                console.log("You left the channel");

                if (leaveChannel == "false") {
                    // leaveChannel();
                    console.log("sos_id : "+sos_id);
                    console.log("type_video_call : "+type_video_call);
                    console.log("user_id : "+user_id);

                    fetch("{{ url('/') }}/api/left_room" + "?user_id=" + user_id + "&type=" + type_video_call + "&sos_id=" + sos_id +"&meet_2_people=beforeunload"+"&leave=beforeunload")
                        .then(response => response.text())
                        .then(result => {
                            console.log(result);
                            console.log("left_and_update สำเร็จ");
                            leaveChannel = "true";

                            let type_url;
                            switch (type_video_call) {
                                case 'zone_sos':
                                        if (type_user_sos == "ศูนย์อำนวยการ") {
                                            window.history.back();
                                        } else if(type_user_sos == "หน่วยแพทย์ฉุกเฉิน"){
                                             // "ศูนย์อำนวยการ" , "หน่วยแพทย์ฉุกเฉิน" , "--"
                                            type_url = "{{ url('/sos_help_center')}}"+ '/' + "{{ $sos_id }}" + '/show_case';
                                            window.location.href = type_url;
                                        }else if(type_user_sos == "เจ้าหน้าที่ห้อง ER"){
                                            window.history.back();
                                        }else{
                                            window.history.back();
                                        }
                                    break;

                                default:
                                    window.history.back();
                                    break;
                            }
                    })
                    .catch(error => {
                        console.log("บันทึกข้อมูล left_and_update ล้มเหลว :" + error);
                    });
                }

            }

            //=============================================================================//
            //                               สลับอุปกรณ์                                     //
            //=============================================================================//

            try {
                // เรียกดูอุปกรณ์ทั้งหมด
                const devices = await navigator.mediaDevices.enumerateDevices();
                // เรียกดูอุปกรณ์ที่ใช้อยู่
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: true
                });

                if(useMicrophone){
                    activeAudioDeviceId = useMicrophone;
                }else{
                    activeAudioDeviceId = stream.getAudioTracks()[0].getSettings().deviceId;
                }

                if(useCamera){
                    activeVideoDeviceId = useCamera;
                }else{
                    activeVideoDeviceId = stream.getVideoTracks()[0].getSettings().deviceId;
                }

                if(useSpeaker){
                    activeAudioOutputDeviceId = useSpeaker;
                }else{
                    activeAudioOutputDeviceId = devices.find(device => device.kind === 'audiooutput' && device.deviceId === 'default').deviceId;
                }

            } catch (error) {
                console.error('เกิดข้อผิดพลาดในการเรียกดูอุปกรณ์:', error);
            }

            // เรียกใช้งานเมื่อต้องการเปลี่ยนอุปกรณ์เสียง
            var old_activeAudioDeviceId;

            // เรียกใช้งานเมื่อต้องการเปลี่ยนอุปกรณ์เสียง
            async function onChangeAudioDevice() {
                old_activeAudioDeviceId = activeAudioDeviceId;

                const selectedAudioDeviceId = getCurrentAudioDeviceId();
                activeAudioDeviceId = selectedAudioDeviceId;

                try {
                    // ถ้ามี localAudioTrack เดิม ให้หยุดและ unpublish ก่อนสร้างใหม่
                    if (channelParameters.localAudioTrack) {
                        await channelParameters.localAudioTrack.setEnabled(false);
                        await agoraEngine.unpublish([channelParameters.localAudioTrack]);
                        channelParameters.localAudioTrack.stop();
                        channelParameters.localAudioTrack.close();
                    }

                    // สร้าง local audio track ใหม่
                    const newAudioTrack = await AgoraRTC.createMicrophoneAudioTrack({
                        AEC: true,
                        ANS: true,
                        AGC: true,
                        encoderConfig: "high_quality",
                        microphoneId: selectedAudioDeviceId
                    });

                    console.log('เปลี่ยนอุปกรณ์เสียงสำเร็จ:', newAudioTrack);

                    // ตั้งค่า track ใหม่ให้กับ channel
                    channelParameters.localAudioTrack = newAudioTrack;

                    if (isAudio) {
                        await newAudioTrack.setEnabled(true);
                        await agoraEngine.publish([newAudioTrack]);
                    } else {
                        await newAudioTrack.setEnabled(false);
                    }

                } catch (error) {
                    console.error('เกิดข้อผิดพลาดในการสร้าง local audio track:', error);
                    activeAudioDeviceId = old_activeAudioDeviceId; // กลับไปใช้อุปกรณ์เดิม
                }
            }

            // --------------------------  เปลี่ยนลำโพง ---------------------------------------------

            async function onChangeAudioOutputDevice() {
                old_activeAudioOutputDeviceId = activeAudioOutputDeviceId;
                const selectedAudioOutputDeviceId = getCurrentAudiooutputDeviceId();
                activeAudioOutputDeviceId = selectedAudioOutputDeviceId;

                try {
                    // วนลูปทุก Remote User และเปลี่ยน audio track
                    Object.keys(remotePlayerContainer).forEach(async (uid) => {
                        let user = agoraEngine.remoteUsers.find(u => u.uid == uid);
                        if (user && user.audioTrack) {
                            await changeAudioOutputForRemoteUser(uid, user.audioTrack, selectedAudioOutputDeviceId);
                        }
                    });

                    console.log("🔊 เปลี่ยนลำโพงสำเร็จสำหรับทุก Remote Users เป็น:", selectedAudioOutputDeviceId);
                } catch (error) {
                    console.error("❌ ไม่สามารถเปลี่ยนลำโพงของ Remote Users:", error);
                    activeAudioOutputDeviceId = old_activeAudioOutputDeviceId; // กลับไปใช้อุปกรณ์เดิมถ้าเกิดข้อผิดพลาด
                }
            }

            // 🎧 ฟังก์ชันเปลี่ยนลำโพงโดยใช้ createCustomAudioTrack
            async function changeAudioOutputForRemoteUser(remoteUid, originalAudioTrack, speakerDeviceId) {
                try {
                    if (!originalAudioTrack) {
                        console.warn(`❌ ไม่มี audio track จาก remote user: ${remoteUid}`);
                        return;
                    }

                    // 🔄 สร้าง custom audio track จาก track ของ Remote User
                    const processedAudioTrack = await AgoraRTC.createCustomAudioTrack({
                        mediaStreamTrack: originalAudioTrack.getMediaStreamTrack()
                    });

                    // ค้นหา <audio> element เดิม หรือสร้างใหม่
                    let audioElement = document.getElementById(`audio-${remoteUid}`);
                    if (!audioElement) {
                        audioElement = document.createElement("audio");
                        audioElement.id = `audio-${remoteUid}`;
                        audioElement.autoplay = true;
                        document.body.appendChild(audioElement);
                    }

                    // ตั้งค่าให้เล่นเสียงจาก Remote User
                    processedAudioTrack.play(audioElement);

                    // เปลี่ยนอุปกรณ์ลำโพง
                    if (typeof audioElement.setSinkId === "function") {
                        await audioElement.setSinkId(speakerDeviceId);
                        console.log(`🔊 เปลี่ยนลำโพงสำเร็จให้กับ UID: ${remoteUid}, Speaker: ${speakerDeviceId}`);
                    } else {
                        console.warn(`❌ Browser ไม่รองรับ setSinkId() สำหรับ UID: ${remoteUid}`);
                    }
                } catch (error) {
                    console.error(`❌ ไม่สามารถเปลี่ยนลำโพงของ Remote User ${remoteUid}:`, error);
                }
            }


            // --------------------------จบส่วน เปลี่ยนลำโพง ---------------------------------------------

            var old_activeVideoDeviceId;

            // เรียกใช้งานเมื่อต้องการเปลี่ยนอุปกรณ์กล้อง
            async function onChangeVideoDevice() {
                old_activeVideoDeviceId = activeVideoDeviceId;

                const selectedVideoDeviceId = getCurrentVideoDeviceId();
                console.log('เปลี่ยนอุปกรณ์กล้องเป็น:', selectedVideoDeviceId);

                activeVideoDeviceId = selectedVideoDeviceId;

                try {
                    // สร้าง local video track ใหม่โดยใช้กล้องที่คุณต้องการ
                    const newVideoTrack = await AgoraRTC.createCameraVideoTrack({
                        cameraId: selectedVideoDeviceId,
                        encoderConfig: "720p_30fps",
                        optimizationMode: "detail", // โหมดปรับคุณภาพที่เน้นรายละเอียด
                    });

                    // หยุดการส่งภาพจากกล้องปัจจุบัน
                    if (channelParameters.localVideoTrack) {
                        channelParameters.localVideoTrack.stop();
                        channelParameters.localVideoTrack.close();
                        await agoraEngine.unpublish([channelParameters.localVideoTrack]);
                    }

                    // เปลี่ยน local video track เป็นอุปกรณ์ใหม่
                    channelParameters.localVideoTrack = newVideoTrack;

                    if (isVideo) {
                        // เริ่มส่งภาพจากอุปกรณ์ใหม่
                        channelParameters.localVideoTrack.setEnabled(true);
                        // แสดงภาพวิดีโอใน <div>
                        channelParameters.localVideoTrack.play(localPlayerContainer);
                        await agoraEngine.publish([channelParameters.localVideoTrack]);
                    } else {
                        // ปิดการแสดงภาพวิดีโอ
                        channelParameters.localVideoTrack.setEnabled(false);
                        channelParameters.localVideoTrack.play(localPlayerContainer);
                        await agoraEngine.publish([channelParameters.localVideoTrack]);
                    }

                    // การเปลี่ยนพื้นหลัง (bg_local) เมื่อ video ปิด
                    if (!isVideo) {
                        setTimeout(() => {
                            console.log("bg_local onChange");
                            // changeBgColor(bg_local);
                        }, 50);
                    }

                } catch (error) {
                    console.error('ไม่สามารถเปลี่ยนกล้องได้:', error);
                    activeVideoDeviceId = old_activeVideoDeviceId;

                    if (!isVideo) {
                        setTimeout(() => {
                            console.log("bg_local ddddddddddddddddddddddd");
                            // changeBgColor(bg_local);
                        }, 50);
                    }
                }
            }

            function getCurrentAudioDeviceId() {
                const audioDevices = document.getElementsByName('audio-device');
                for (let i = 0; i < audioDevices.length; i++) {
                    if (audioDevices[i].checked) {
                        return audioDevices[i].value;
                    }
                }
                return null;
            }

            function getCurrentAudiooutputDeviceId() {
                const audiooutputDevices = document.getElementsByName('audio-output-device');
                for (let i = 0; i < audiooutputDevices.length; i++) {
                    if (audiooutputDevices[i].checked) {
                        return audiooutputDevices[i].value;
                    }
                }
                return null;
            }

            function getCurrentVideoDeviceId() {
                const videoDevices = document.getElementsByName('video-device');
                for (let i = 0; i < videoDevices.length; i++) {
                    if (videoDevices[i].checked) {
                        return videoDevices[i].value;
                    }
                }
                return null;
            }

            var now_Mobile_Devices = 1;
            var cachedVideoDevices = null; // สร้างตัวแปร global เพื่อเก็บข้อมูล camera

            btn_switchCamera.onclick = async function() {
                // เรียกใช้ฟังก์ชันและแสดงผลลัพธ์
                let deviceType = checkDeviceType();

                // ถ้ายังไม่มีข้อมูลอุปกรณ์ที่เก็บไว้
                if (!cachedVideoDevices) {
                    try {
                        // เรียกดูอุปกรณ์ทั้งหมด
                        let getDevices = await navigator.mediaDevices.enumerateDevices();

                        // แยกอุปกรณ์ตามประเภท
                        let getVideoDevices = getDevices.filter(device => device.kind === 'videoinput');

                        // กำหนดค่าให้กับตัวแปร global เพื่อเก็บไว้
                        cachedVideoDevices = getVideoDevices;
                    } catch (error) {
                        console.error('ไม่สามารถดึงข้อมูลอุปกรณ์ได้:', error);
                        return;
                    }
                }

                let videoDevices = cachedVideoDevices; // ใช้ cachedVideoDevices ได้ทุกครั้งที่ต้องการ

                console.log('------- videoDevices -------');
                console.log(videoDevices);
                console.log('length ==>> ' + videoDevices.length);
                console.log('------- ------- -------');

                // สร้างรายการอุปกรณ์ส่งข้อมูลและเพิ่มลงในรายการ
                let videoDeviceList = document.getElementById('video-device-list');
                videoDeviceList.innerHTML = ''; // เคลียร์รายการเก่า

                let deviceText = document.createElement('li');
                deviceText.classList.add('text-center','p-1','text-white');
                deviceText.appendChild(document.createTextNode("กล้อง"));

                videoDeviceList.appendChild(deviceText);

                let count_i = 1;

                videoDevices.forEach(device => {
                    let radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.classList.add('radio_style');
                    radio.id = 'video-device-' + count_i;
                    radio.name = 'video-device';
                    radio.value = device.deviceId;

                    if (deviceType === 'PC') {
                        radio.checked = device.deviceId === activeVideoDeviceId;
                    }

                    let label = document.createElement('li');
                    label.classList.add('ui-list-item');
                    label.appendChild(document.createTextNode(device.label || `อุปกรณ์ส่งข้อมูล ${videoDeviceList.children.length + 1}`));
                    label.appendChild(document.createTextNode("\u00A0")); // เพิ่ม non-breaking space
                    label.appendChild(radio);

                    // เพิ่ม event listener เมื่อคลิกที่ label
                    label.addEventListener('click', () => {
                        radio.checked = true;
                        onChangeVideoDevice();
                    });

                    videoDeviceList.appendChild(label);

                    radio.addEventListener('change', onChangeVideoDevice);

                    count_i++;
                });

                // --------------------------- สำหรับอุปกรณ์มือถือ
                if (deviceType !== 'PC') {
                    let check_videoDevices = document.getElementsByName('video-device');

                    if (now_Mobile_Devices === 1) {
                        // สลับกล้องมือถือ
                        document.querySelector('#' + check_videoDevices[1].id).click();
                        now_Mobile_Devices = 2;
                    } else {
                        document.querySelector('#' + check_videoDevices[0].id).click();
                        now_Mobile_Devices = 1;
                    }
                }
            };


            var cachedAudioDevices = null; // ตัวแปร global สำหรับเก็บข้อมูลไมโครโฟน
            var cachedAudioOutputDevices = null; // ตัวแปร global สำหรับเก็บข้อมูลลำโพง

            btn_switchMicrophone.onclick = async function() {
                // console.log('btn_switchMicrophone');
                // console.log('activeAudioDeviceId');
                // console.log(activeAudioDeviceId);

                // เรียกใช้ฟังก์ชันและแสดงผลลัพธ์
                let deviceType = checkDeviceType();
                console.log("Device Type:", deviceType);

                // ถ้ายังไม่มีข้อมูลอุปกรณ์ที่เก็บไว้
                if (!cachedAudioDevices || !cachedAudioOutputDevices) {
                    // เรียกดูอุปกรณ์ทั้งหมด
                    let getDevices = await navigator.mediaDevices.enumerateDevices();

                    // แยกอุปกรณ์ตามประเภท
                    let getAudioDevices = getDevices.filter(device => device.kind === 'audioinput');
                    let getAudioOutputDevices = getDevices.filter(device => device.kind === 'audiooutput');

                    // เก็บข้อมูลอุปกรณ์ไว้
                    cachedAudioDevices = getAudioDevices;
                    cachedAudioOutputDevices = getAudioOutputDevices;
                }

                let audioDevices = cachedAudioDevices; // ไมโครโฟน
                let audioOutputDevices = cachedAudioOutputDevices; // ลำโพง

                console.log('------- audioDevices (ไมโครโฟน) -------');
                console.log(audioDevices);
                console.log('length ==>> ' + audioDevices.length);
                console.log('------- ------- -------');

                console.log('------- audioOutputDevices (ลำโพง) -------');
                console.log(audioOutputDevices);
                console.log('length ==>> ' + audioOutputDevices.length);
                console.log('------- ------- -------');

                // สร้างรายการอุปกรณ์ส่งข้อมูลและเพิ่มลงในรายการ
                let audioDeviceList = document.getElementById('audio-device-list');
                audioDeviceList.innerHTML = ''; // เคลียร์รายการเก่า

                // แสดงรายการไมโครโฟน
                let deviceText = document.createElement('li');
                deviceText.classList.add('text-center', 'p-1', 'text-white');
                deviceText.appendChild(document.createTextNode("ไมโครโฟน"));

                //============================================ ส่วนของ การปรับระดับเสียง(optional)=====================================================================================

                let localVolume = localStorage.getItem('local_sos_1669_rangeValue') ?? 100;

                let localAudioVolumeLabel = `<label class="ui-list-item d-block" for="localAudioVolume" >
                                                <li class="text-center p-1 text-white d-block" style="font-size: 1.1em;">ระดับเสียงไมค์(ตัวเอง)</li>
                                                <input type="range" id="localAudioVolume" min="0" max="1000" value="`+localVolume+`" class="w-100">
                                            </label>
                                            <hr style="border-top:1px solid #ccc; margin:10px 0;">
                                            `

                audioDeviceList.insertAdjacentHTML('afterbegin', localAudioVolumeLabel); // แทรกบนสุด

                // let remoteAudioVolumeLabel = `<label class="ui-list-item d-none" for="remoteAudioVolume" >
                //                                 <li class="text-center p-1 text-white d-block" style="font-size: 1.1em;">เสียงที่เราได้ยิน</li>
                //                                 <input type="range" id="remoteAudioVolume" min="0" max="100" value="`+remoteVolume+`" class="w-100">
                //                             </label>`

                // audioDeviceList.insertAdjacentHTML('afterbegin', remoteAudioVolumeLabel); // แทรกบนสุด

                // เข้าถึงตัวปรับ input =============== localVolume ==========================
                let local_rangeInput = document.getElementById('localAudioVolume');
                local_rangeInput.addEventListener('input', function() {
                // บันทึกค่าลงใน localStorage เมื่อมีการเปลี่ยนแปลง
                    localStorage.setItem('local_sos_1669_rangeValue', local_rangeInput.value);
                    localVolume = local_rangeInput.value; // เปลี่ยนค่าระดับเสียงของทางเราให้เท่ากับตัวปรับ

                    if (local_rangeInput.value == 0) { // ถ้า value ตัวปรับเสียง ของ remote คนนี้ เป็น 0
                        document.querySelector('#icon_microphone_in_sidebar').innerHTML = `<i title="คุณปิดไมโครโฟนผู้ใช้ท่านนี้ไว้" class="fa-duotone fa-volume-xmark"
                        style="--fa-primary-color: #000000; --fa-secondary-color: #ff0000; --fa-secondary-opacity: 1; display: inline-block; z-index: 6; "></i>`;
                        // document.querySelector('#icon_microphone_in_sidebar').innerHTML = `<i title="คุณปิดไมโครโฟนผู้ใช้ท่านนี้ไว้" class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #1319b9; --fa-secondary-color: #000000; --fa-secondary-opacity: 1; display: inline-block; z-index: 6;"></i>`;
                    } else {
                        if (isAudio == true) {
                            document.querySelector('#icon_microphone_in_sidebar').innerHTML = `<i class="fa-solid fa-microphone" style="display: inline-block; z-index: 6;" ></i>`;
                        } else {
                            document.querySelector('#icon_microphone_in_sidebar').innerHTML = `<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #e60000; --fa-secondary-color: #000000; --fa-secondary-opacity: 1; display: inline-block; z-index: 6;"></i>`;
                        }
                    }
                });

                // เข้าถึงตัวปรับ input =============== remoteVolume ==========================
                // let remote_rangeInput = document.getElementById('remoteAudioVolume');
                // remote_rangeInput.addEventListener('input', function() {
                // // บันทึกค่าลงใน remoteStorage เมื่อมีการเปลี่ยนแปลง
                //     localStorage.setItem('remote_rangeValue', remote_rangeInput.value);
                //     remoteVolume = remote_rangeInput.value; // เปลี่ยนค่าระดับเสียงของทางฝั่งตรงข้ามให้เท่ากับตัวปรับ
                // });

                let localVolumeFromStorage = localStorage.getItem('local_sos_1669_rangeValue');
                // let remoteVolumeFromStorage = localStorage.getItem('remote_rangeValue');

                // ตั้งค่าเสียงในตอนที่เริ่มต้น
                if (localVolumeFromStorage !== null) {
                    // ตั้งค่าเสียง local audio
                    console.log("Volume of local audio at start :" + localVolumeFromStorage);
                    channelParameters.localAudioTrack.setVolume(parseInt(localVolumeFromStorage));
                }else{
                    channelParameters.localAudioTrack.setVolume(parseInt(100));
                }

                // เพิ่ม event listener สำหรับ local audio volume slider
                document.getElementById("localAudioVolume").addEventListener("change", function (evt) {
                    console.log("Volume of local audio :" + evt.target.value);
                    // Set the local audio volume.
                    channelParameters.localAudioTrack.setVolume(parseInt(evt.target.value));
                    // บันทึกค่าลงใน localStorage เพื่อให้ค่าเสียงเป็นค่าเริ่มต้นต่อครั้งถัดไป
                });

                // let remoteAudioTracksArray = [];

                // document.getElementById("remoteAudioVolume").addEventListener("change", function (evt) {
                //     // Set the remote audio volume.
                //     // ในตัวอย่างนี้, เราให้ remoteAudioTracksArray เป็น array ที่เก็บ remoteAudioTrack ของทุกคน
                //     remoteAudioTracksArray.forEach(remoteAudioTrack => {
                //         remoteAudioTrack.setVolume(parseInt(evt.target.value));
                //         console.log("Volume of remote audio for All User");
                //     });

                //     // บันทึกค่าลงใน localStorage เพื่อให้ค่าเสียงเป็นค่าเริ่มต้นต่อครั้งถัดไป
                // });

                //=================================================================================================================================

                audioDeviceList.appendChild(deviceText);

                let count_i = 1;

                // เพิ่มรายการไมโครโฟน
                audioDevices.forEach(device => {
                    const radio2 = document.createElement('input');
                    radio2.type = 'radio';
                    radio2.classList.add('radio_style');
                    radio2.id = 'audio-device-' + count_i;
                    radio2.name = 'audio-device';
                    radio2.value = device.deviceId;

                    if (deviceType === 'PC') {
                        radio2.checked = device.deviceId === activeAudioDeviceId;
                    }

                    let label = document.createElement('li');
                    label.classList.add('ui-list-item');
                    label.appendChild(document.createTextNode(device.label || `ไมโครโฟน ${count_i}`));
                    label.appendChild(document.createTextNode("\u00A0")); // เพิ่ม non-breaking space
                    label.appendChild(radio2);

                    // สร้างเหตุการณ์คลิกที่ label เพื่อตรวจสอบ radio2
                    label.addEventListener('click', () => {
                        radio2.checked = true;
                        onChangeAudioDevice();
                    });

                    audioDeviceList.appendChild(label);
                    radio2.addEventListener('change', onChangeAudioDevice);

                    count_i++;
                });

                // เพิ่มเส้นแบ่งก่อนหัวข้อ "ลำโพง"
                let divider_2 = document.createElement('hr');
                divider_2.style.borderTop = '1px solid #ccc';
                divider_2.style.margin = '10px 0';

                audioDeviceList.appendChild(divider_2);

                // เพิ่มรายการลำโพง
                let outputDeviceText = document.createElement('li');
                outputDeviceText.classList.add('text-center', 'p-1', 'text-white');
                outputDeviceText.appendChild(document.createTextNode("ลำโพง"));
                audioDeviceList.appendChild(outputDeviceText);

                let count_i_output = 1;

                // เพิ่มรายการลำโพง
                audioOutputDevices.forEach(device => {
                    const radio3 = document.createElement('input');
                    radio3.type = 'radio';
                    radio3.classList.add('radio_style');
                    radio3.id = 'audio-output-device-' + count_i_output;
                    radio3.name = 'audio-output-device';
                    radio3.value = device.deviceId;

                    if (deviceType === 'PC') {
                        radio3.checked = device.deviceId === activeAudioOutputDeviceId;
                    }

                    let label_output = document.createElement('li');
                    label_output.classList.add('ui-list-item');
                    label_output.appendChild(document.createTextNode(device.label || `ลำโพง ${count_i_output}`));
                    label_output.appendChild(document.createTextNode("\u00A0")); // เพิ่ม non-breaking space
                    label_output.appendChild(radio3);

                    // สร้างเหตุการณ์คลิกที่ label เพื่อตรวจสอบ radio3
                    label_output.addEventListener('click', () => {
                        radio3.checked = true;
                        onChangeAudioOutputDevice();
                    });

                    audioDeviceList.appendChild(label_output);
                    radio3.addEventListener('change', onChangeAudioOutputDevice);

                    count_i_output++;
                });

                // เพิ่มเหตุการณ์คลิกที่หน้าจอที่ไม่ใช่ตัว audio-device-list เพื่อปิด audio-device-list
                // document.addEventListener('click', (event) => {
                //     const target = event.target;
                //     if (!target.closest('#audio-device-list')) {
                //         document.querySelector('.dropcontent').classList.toggle('open');
                //     }
                // });
            };

            // ตรวจสอบอุปกรณ์ที่ใช้งาน
            function checkDeviceType() {
                const userAgent = navigator.userAgent || navigator.vendor || window.opera;

                // ตรวจสอบชนิดของอุปกรณ์
                if (/android/i.test(userAgent)) {
                    return "Mobile (Android)";
                }

                if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                    return "Mobile (iOS)";
                }

                return "PC";
            }

            //=============================================================================//
            //                              จบ -- สลับอุปกรณ์                                //
            //=============================================================================//


            //=================================== ฟังก์ชันเทสเสียง ==========================================//

            let isIconVisible;
            let volumeIndicatorHandler = volumes => {
                volumes.forEach((volume) => {

                    let localAudioTrackCheck = channelParameters.localAudioTrack;
                    if (volume.level >= 50) {
                        //แสดงชื่ออุปกรณ์ที่ใช้และระดับเสียง

                        if (user_id == volume.uid && volume.level > 50) {
                            console.log('Enabled Device: ' + localAudioTrackCheck['_deviceName']);
                            console.log(`UID ${volume.uid} Level ${volume.level}`);

                            // แสดงปุ่มเสียงพูด"
                            if (!isIconVisible) {
                                document.querySelector('#statusMicrophoneOutput_local').classList.remove('d-none');
                                isIconVisible = true;
                            }
                        }

                    } else {

                        if (user_id == volume.uid && volume.level <= 50) {
                            console.log('Enabled Device: ' + localAudioTrackCheck['_deviceName']);
                            console.log(`UID ${volume.uid} Level ${volume.level}`);

                            // ซ่อนปุ่มเสียงพูด"
                            if (isIconVisible) {
                                document.querySelector('#statusMicrophoneOutput_local').classList.add('d-none');
                                isIconVisible = false;
                            }

                        }

                    }
                });
            };


            function SoundTest() {
                // ตรวจจับเสียงพูดแล้ว สร้าง animation บนขอบ div
                agoraEngine.off("volume-indicator", volumeIndicatorHandler);
                agoraEngine.on("volume-indicator", volumeIndicatorHandler);
            }

            function volume_indicator_remote(remote_id){
                console.log("ทำแล้วน้าาา volume_indicator_remote");
                check_start_volume_indicator[remote_id] = "yes";

                agoraEngine.on("volume-indicator", volumes => {
                        volumes.forEach((volume, index) => {
                            console.log("เข้า foreach");
                            console.log(volume.uid);
                            if (remote_id == volume.uid && status_remote_volume[remote_id] == "yes") {
                                if (volume.level > 50) {
                                    console.log(`Dummy_UID ${volume.uid} Level ${volume.level}`);
                                    document.querySelector('#statusMicrophoneOutput_remote_'+remote_id).classList.remove('d-none');
                                } else if (volume.level <= 50) {
                                    console.log(`Dummy_UID ${volume.uid} Level ${volume.level}`);
                                    document.querySelector('#statusMicrophoneOutput_remote_'+remote_id).classList.add('d-none');
                                }
                            }else if(remote_id == volume.uid && status_remote_volume[remote_id] == "no") {
                                console.log("else ล่างสุด");
                                document.querySelector('#statusMicrophoneOutput_remote_'+remote_id).classList.add('d-none');
                            }

                            // if (remote_id == volume.uid && volume.level > 50) {
                            //     console.log(`Dummy_UID ${volume.uid} Level ${volume.level}`);
                            //     document.querySelector('#statusMicrophoneOutput_remote_'+remote_id).classList.remove('d-none');
                            // } else if (remote_id == volume.uid && volume.level <= 50) {
                            //     console.log(`Dummy_UID ${volume.uid} Level ${volume.level}`);
                            //     document.querySelector('#statusMicrophoneOutput_remote_'+remote_id).classList.add('d-none');
                            // }

                        });
                    })
            }



        }
    </script>

    <script>

        let sidebarOpen = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContainer = document.getElementById('mainContainer');
            const videoWrapper = document.querySelector('.video-wrapper');  // เรียกใช้ video-wrapper

            sidebarOpen = !sidebarOpen;

            if (sidebarOpen) {
                sidebar.classList.add('open');
                mainContainer.classList.add('shifted');
            } else {
                sidebar.classList.remove('open');
                mainContainer.classList.remove('shifted');
            }
        }

        // ฟังก์ชันสุ่มสี HEX
        function getRandomColor() {
            const letters = "0123456789ABCDEF";
            let color = "#";
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        //======================================= โยกย้าย Div   ==================================================//

        // ตรวจสอบว่า div อยู่ใน .video-bar หรือไม่
        function isInUserVideoCallBar(div) {
            return div.parentElement === document.querySelector(".video-bar");
        }

        // ย้าย div ไปยัง .video-bar หากไม่อยู่ในนั้นและสลับ div
        function moveDivsToUserVideoCallBar(clickedDiv) {
            let container = document.getElementById("video-container");
            let videoCard = container.querySelectorAll(".video-card");
            let userVideoCallBar = document.querySelector(".video-bar");

            if (videoCard.length > 1) {
                document.querySelector("#toggleVideoBarBtn").classList.remove("d-none");

                videoCard.forEach(function(div) {
                    if (div !== clickedDiv) { //ถ้า div ไม่ใช่ div ที่ถูกคลิก
                        if (!isInUserVideoCallBar(div)) { //ถ้า div ไม่ได้อยู่ใน div .video-bar
                            userVideoCallBar.appendChild(div);
                        }
                    }
                });

                // ย้าย div ที่ถูกคลิกไปยังตำแหน่งที่ถูกคลิก
                if (!isInUserVideoCallBar(clickedDiv)) {
                    container.appendChild(clickedDiv);
                }
            }

        }

        // ย้ายทุก div ใน video-bar ไปยัง #video-container
        function moveAllDivsToContainer() {
            let container = document.getElementById("video-container");
            let userVideoCallBar = document.querySelector(".video-bar");
            let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".video-card");

            document.querySelector("#toggleVideoBarBtn").classList.add("d-none");


            customDivsInUserVideoCallBar.forEach(function(div) {
                container.appendChild(div);
            });

        }

        function handleClick(clickedDiv) {
            let userVideoCallBar = document.querySelector(".video-bar");
            let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".video-card");
            // console.log("handleClick OG : "+ checkHtml);

            if (customDivsInUserVideoCallBar.length > 0) { // กรณีมี div video ใน video-bar
                moveAllDivsToContainer();
            } else {
                moveDivsToUserVideoCallBar(clickedDiv);
            }

        }

        // เพิ่ม event listener บน .user-video-call-bar สำหรับสลับ div
        document.querySelector(".video-bar").addEventListener("click", function(e) {
            if (e.target.classList.contains("video-card")) {
                handleClick(e.target);
            }
        });

        function createVideoCard() {
            let randomColor = "##4d4d4d";
                randomColor = getRandomColor();

            let newDiv = document.createElement("div");
            newDiv.className = "video-card";
            newDiv.style.backgroundColor = randomColor;


            // เพิ่ม event listener สำหรับการคลิก
            newDiv.addEventListener("click", function() {
                handleClick(newDiv);
            });

            let userVideoCallBar = document.querySelector(".video-bar");
            let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".video-card");

            if (customDivsInUserVideoCallBar.length > 0) {
                userVideoCallBar.appendChild(newDiv);
            } else {
                document.getElementById("video-container").appendChild(newDiv);
            }

            updateVideoVisibility(); // นับจำนวน video div ไม่ให้เกิน 9
        }

        function updateVideoVisibility() { // นับจำนวน video div ไม่ให้เกิน 9
            const videoContainer = document.getElementById("video-container");
            const videoBar = document.querySelector(".video-bar");

            const allVideos = [
                ...videoContainer.querySelectorAll(".video-card"),
                ...videoBar.querySelectorAll(".video-card"),
            ];

            allVideos.forEach((video, index) => {
                if (index < 9) {
                    video.style.display = "";
                } else {
                    video.style.display = "none";
                }
            });
        }

        let isInitialPositionSet = false; // ตัวแปรเช็คว่า ตั้งค่าตอนแรกไปแล้วหรือยัง

        function positionToggleButton() {
            const videoBar = document.getElementById('video-bar');
            const toggleBtn = document.getElementById('toggleVideoBarBtn');
            const videoCards = videoBar.querySelectorAll('.video-card');

            if (!isInitialPositionSet) {
                // ตั้งค่าครั้งแรกที่ 807.188px
                toggleBtn.style.top = '790px';
                toggleBtn.style.bottom = 'auto';
                isInitialPositionSet = true; // ตั้งค่าเสร็จแล้ว
                return;
            }

            if (toggleBtn.classList.contains('toggle-video-bar-btn')) {
                if (videoCards.length === 0) {
                    toggleBtn.style.top = '790px'; // หากไม่มีวิดีโออยู่ ให้กลับมาอยู่ตำแหน่งเริ่ม
                    toggleBtn.style.bottom = 'auto';
                } else {
                    const barRect = videoBar.getBoundingClientRect();
                    const wrapperRect = document.querySelector('.video-wrapper').getBoundingClientRect();
                    const barTop = barRect.top - wrapperRect.top;

                    toggleBtn.style.top = `${barTop - toggleBtn.offsetHeight / 2}px`;
                    toggleBtn.style.bottom = 'auto';
                }
            } else if (toggleBtn.classList.contains('toggle-video-bar-btn-close')) {
                toggleBtn.style.top = 'auto';
                toggleBtn.style.bottom = '-0.5rem';
            }
        }
        window.addEventListener('DOMContentLoaded', positionToggleButton);
        window.addEventListener('resize', positionToggleButton);

        function toggleVideoBar() {
            const videoBar = document.getElementById('video-bar');
            const toggleBtn = document.getElementById('toggleVideoBarBtn');

            videoBar.classList.toggle('hidden');

            if (toggleBtn.classList.contains('toggle-video-bar-btn')) {
                toggleBtn.classList.remove('toggle-video-bar-btn');
                toggleBtn.classList.add('toggle-video-bar-btn-close');
            } else {
                toggleBtn.classList.remove('toggle-video-bar-btn-close');
                toggleBtn.classList.add('toggle-video-bar-btn');
            }

            positionToggleButton();
        }

        function removeVideoDiv(elementId)
        {
            console.log("Removing "+ elementId+"Div");
            let Div = document.getElementById(elementId);
            if (Div)
            {
                Div.remove();
            }
        };

        function changeBgColor(bg_local){
            // เซ็ท bg-local เป็นสีที่ดูด
            console.log("ทำงาน "+bg_local)

            let agoraCreateLocalDiv = document.querySelector("#videoDiv_"+user_id);

            let divsInsideAgoraCreateLocal = agoraCreateLocalDiv.querySelector(".agora_create_local");
                let sub_div = divsInsideAgoraCreateLocal.querySelector("div");
                    sub_div.style.backgroundColor = bg_local;

                if(isVideo == false){
                    let video_tag = divsInsideAgoraCreateLocal.querySelector("video");
                        video_tag.remove();
                }
        }

        //======================================= จบโยกย้าย Div   ==================================================//

        function myStop_timer_video_call() {
            console.log("เข้ามาหยุด myStop_timer_video_call");
            setTimeout(() => {
                clearInterval(loop_timer_video_call);
                check_start_timer_video_call = false;

                document.getElementById("time_of_room").innerHTML = "";
                // document.getElementById("time_of_room").classList.add('d-none');
            }, 3000);

        }
        var audio_in_room = new Audio("{{ asset('sound/แจ้งเตือนก่อนหมดเวลาวิดีโอคอล.mp3') }}");

        function start_timer_video_call(){

            console.log('start_timer_video_call');
            // console.log(time_start);

            check_start_timer_video_call = true ;

            setTimeout(() => {

                // let time_of_room = document.getElementById("time_of_room");
                //     time_of_room.classList.remove('d-none');

                fetch("{{ url('/') }}/api/check_status_room" + "?sos_id="+ sos_id + "&type=" + type_video_call)
                    .then(response => response.json())
                    .then(result => {

                        // วันที่และเวลาที่กำหนด
                        let targetDate = '';
                        if (result['than_2_people_time_start']) {
                            targetDate = new Date(result['than_2_people_time_start']);
                        } else {
                            // targetDate = new Date(result['than_2_people_time_start']);
                            targetDate = new Date();
                        }

                        let targetTime = targetDate.getTime();

                        loop_timer_video_call = setInterval(function() {

                            // วันที่และเวลาปัจจุบัน
                            let currentDate = new Date();
                            let currentTime = currentDate.getTime();

                            // คำนวณเวลาที่ผ่านไปในมิลลิวินาที
                            let elapsedTime = currentTime - targetTime;
                            let elapsedMinutes = Math.floor(elapsedTime / (1000 * 60));

                            // แปลงเวลาที่ผ่านไปให้เป็นรูปแบบชั่วโมง:นาที:วินาที
                            let hours = Math.floor(elapsedMinutes / 60);
                            let minutes = elapsedMinutes % 60;
                            let seconds = Math.floor((elapsedTime / 1000) % 60);

                            let minsec = minutes + '.' + seconds;
                            let showTimeCountVideo;
                            // แสดงผลลัพธ์
                            let max_minute_time = 8;

                            // let remain_time = max_minute_time - 1;
                            // let time_warning = "";
                            // if (max_minute_time > 1) {
                            //     time_warning = (max_minute_time - remain_time);
                            // }else{
                            //     time_warning = "น้อยกว่า 1";
                            // }

                            if (hours > 0) {
                                if (minutes < 10) {  // ใส่ 0 ข้างหน้า นาที กรณีเลขยังไม่ถึง 10
                                    showTimeCountVideo = hours + ':' + '0' + minutes + ':' + seconds + `&nbsp;/ `+max_minute_time+` นาที`;
                                }else{
                                    showTimeCountVideo = hours + ':' + minutes + ':' + seconds + `&nbsp;/ `+max_minute_time+` นาที`;
                                }
                            } else {
                                if(seconds < 10){  // ใส่ 0 ข้างหน้า วินาที กรณีเลขยังไม่ถึง 10
                                    showTimeCountVideo =  minutes + ':' + '0' + seconds + `&nbsp;/ `+max_minute_time+` นาที`;
                                }else{
                                    showTimeCountVideo = minutes + ':' + seconds + `&nbsp;/ `+max_minute_time+` นาที`;
                                }
                            }

                            // // อัปเดตข้อความใน div ที่มี id เป็น timeCountVideo
                            time_of_room.innerHTML = '<i class="fa-regular fa-clock fa-fade" style="color: #11b06b; font-size: 16px;"></i>&nbsp;' + ": " + showTimeCountVideo;

                            if (minsec == 5.00) {
                                audio_in_room.play();
                                let alert_warning = document.querySelector('#alert_warning')
                                alert_warning.style.display = 'block'; // แสดง .div_alert

                                // document.querySelector('#alert_text').innerHTML = `เหลือเวลา `+ time_warning +` นาที`;
                                document.querySelector('#alert_text').innerHTML = `เหลือเวลา 3 นาที`;
                                alert_warning.classList.add('up_down');

                                const animated = document.querySelector('.up_down');
                                animated.onanimationend = () => {
                                    document.querySelector('#alert_warning').classList.remove('up_down');
                                    let alert_warning = document.querySelector('#alert_warning')
                                    alert_warning.style.display = 'none'; // แสดง .div_alert
                                };
                            }

                            if (minsec == 7.00) {
                                audio_in_room.play();
                                let alert_warning = document.querySelector('#alert_warning')
                                alert_warning.style.display = 'block'; // แสดง .div_alert

                                // document.querySelector('#alert_text').innerHTML = `เหลือเวลา `+ time_warning +` นาที`;
                                document.querySelector('#alert_text').innerHTML = `เหลือเวลา 1 นาที`;
                                alert_warning.classList.add('up_down');

                                const animated = document.querySelector('.up_down');
                                animated.onanimationend = () => {
                                    document.querySelector('#alert_warning').classList.remove('up_down');
                                    let alert_warning = document.querySelector('#alert_warning')
                                    alert_warning.style.display = 'none'; // แสดง .div_alert
                                };
                            }

                            if (elapsedMinutes == max_minute_time) {
                                document.querySelector('#leave').click();
                            }

                        }, 1000);


                    });
            }, 2000);

        }


        //============================== บันทึกข้อมูลเมื่อออกแบบรวดเร็ว ==================================

        window.addEventListener('beforeunload', function(event) {
            if (leaveChannel == "false") {
                // leaveChannel();
                fetch("{{ url('/') }}/api/left_room" + "?user_id=" + '{{ Auth::user()->id }}' + "&type=" + type_video_call + "&sos_id=" + sos_id +"&meet_2_people=beforeunload"+"&leave=beforeunload")
                    .then(response => response.text())
                    .then(result => {
                        // console.log(result);
                        console.log("left_and_update สำเร็จ");
                        leaveChannel = "true";
                })
                .catch(error => {
                    console.log("บันทึกข้อมูล left_and_update ล้มเหลว :" + error);
                });
            }
        });

    </script>

@endsection
