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

        <div id="users_in_sidebar">

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
            {{-- <button class="toggleButton mx-2" id="muteBtn" onclick="toggleMute()"><i
                    class="fa-regular fa-microphone"></i></button>
            <button class="toggleButton mx-2" id="cameraBtn" onclick="toggleCamera()"><i
                    class="fa-regular fa-camera"></i></button> --}}

            <!-- เปลี่ยนไมค์ ให้กดได้แค่ในคอม -->

                <div id="div_for_AudioButton" class="btn btnSpecial" >
                    {{-- <i class="fa-regular fa-microphone"></i> --}}
                    <button class="smallCircle" id="btn_switchMicrophone">
                        <i class="fa-sharp fa-solid fa-angle-up"></i>
                    </button>
                </div>

                <!-- เปลี่ยนกล้อง ให้กดได้แค่ในคอม -->
                <div id="div_for_VideoButton" class="btn btnSpecial " >
                    {{-- <i id="icon_muteVideo" class="fa-solid fa-camera-rotate"></i> --}}
                    <button class="smallCircle" id="btn_switchCamera">
                        <i class="fa-sharp fa-solid fa-angle-up"></i>
                    </button>
                </div>

                @if (Auth::user()->id == 1 || Auth::user()->id == 2 || Auth::user()->id == 64 || Auth::user()->id == 11003429 || Auth::user()->id == 11003473)
                    <button class="btn btnSpecial d-non" id="addButton" onclick="createAndAttachCustomDiv();">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                @endif
                <div class="btn btnSpecial d-non" id="leave">
                    <i class="fa-solid fa-phone-xmark"></i>
                </div>
        </div>
        <div class="right">

        </div>
    </div>

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

        // เกี่ยวกับเวลาในห้อง
        var check_start_timer_video_call = false;
        var check_user_in_video_call = false;
        // var hours = 0;
        // var minutes = 0;
        // var seconds = 0;
        var meet_2_people = 'No' ;

        var remoteVolume = localStorage.getItem('remote_rangeValue') ?? 70; // ค่าสำหรับเลือกระดับเสียงที่ได้ยินจากทุกคน
        var array_remoteVolumeAudio = [];

        var agoraEngine;

        var checkHtml = false; // ใช้เช็คเงื่อนไขตัวปรับเสียงของ remote



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
                    const expirationTimestamp = result['privilegeExpiredTs'];

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
                    checkAndNotifyExpiration(expirationTimestamp); // เรียกใช้ฟังก์ชันนี้ครั้งเดียวหลังจากโหลด Token

                    // ซ่อน Loading เมื่อโหลดเสร็จ
                    if (loadingAnime) {
                        loadingAnime.classList.add('d-none');
                    }
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
            // startBasicCall();
            //หาตำแหน่งของผู้ใช้ --> แสดงข้อมูล sos_map ตามจังหวัด
            if(type_video_call === "sos_map"){
                find_location();
            }

            });

    </script>

    <script>
        async function startBasicCall()
        {
            // Create an instance of the Agora Engine

            agoraEngine = AgoraRTC.createClient({ mode: "rtc", codec: "vp9" });
            // console.log("agoraEngine");
            // console.log(agoraEngine);
            let rtcStats = agoraEngine.getRTCStats();
            // console.log("rtcStats");
            // console.log(rtcStats);

            agoraEngine.enableAudioVolumeIndicator(); // เปิดตัวตรวจับระดับเสียงไมค์

            /////////////////////// ปุ่มสลับ กล้อง /////////////////////
            const btn_switchCamera = document.querySelector('#btn_switchCamera');
            /////////////////////// ปุ่มสลับ ไมค์ /////////////////////
            const btn_switchMicrophone = document.querySelector('#btn_switchMicrophone');

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
                // profile_local = "{{ url('/storage') }}" + "/" + user_data.photo;
                profile_local = "https://www.viicheck.com/" + element.photo;
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

                            // Set a stream fallback option to automatically switch remote video quality when network conditions degrade.
                            // agoraEngine.setStreamFallbackOption(channelParameters.remoteUid, 1);
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

                    // Set a stream fallback option to automatically switch remote video quality when network conditions degrade.
                    agoraEngine.setStreamFallbackOption(channelParameters.remoteUid, 1);

                }

                if (mediaType == "audio")
                {
                    channelParameters.remoteAudioTrack = user.audioTrack;
                    channelParameters.remoteAudioTrack.play();

                    channelParameters.remoteAudioTrack.setVolume(parseInt(array_remoteVolumeAudio[user.uid]));

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

                    // สร้าง function callback ที่จะใช้ในการประกาศตัวแปรเพื่อทำการ unsubscribe
                    // function onVolumeIndicatorCallback(volume) {
                    //     volume.forEach((volume, index) => {
                    //         console.log("volume in published");
                    //         if (channelParameters.remoteUid == volume.uid && volume.level > 50) {
                    //             console.log(`${index} UID ${volume.uid} Level ${volume.level}`);
                    //             document.querySelector('#statusMicrophoneOutput_remote_'+ channelParameters.remoteUid).classList.remove('d-none');
                    //         } else if (channelParameters.remoteUid == volume.uid && volume.level <= 50) {
                    //             console.log(`${index} UID ${volume.uid} Level ${volume.level}`);
                    //             document.querySelector('#statusMicrophoneOutput_remote_'+ channelParameters.remoteUid).classList.add('d-none');
                    //         }
                    //     });
                    // }

                    // // Subscribe การเรียก callback function เมื่อเกิดเหตุการณ์ "volume-indicator"
                    // agoraEngine.off("volume-indicator", onVolumeIndicatorCallback);
                    // agoraEngine.on("volume-indicator", onVolumeIndicatorCallback);
                }

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
                        async function getActiveMicrophoneId() {
                            try {
                                let devices = await navigator.mediaDevices.enumerateDevices();
                                let microphones = devices.filter(device => device.kind === 'audioinput' && device.deviceId !== 'default');

                                return microphones.length > 0 ? microphones[0].deviceId : null;

                            } catch (error) {
                                console.error("❌ ไม่สามารถดึงข้อมูลไมโครโฟนได้:", error);
                                return null;
                            }
                        }

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
                                encoderConfig: "720p_30fps", // ตั้งค่าเป็น 720p @ 30fps
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



                            } catch (error) {
                                console.error("❌ เกิดข้อผิดพลาดระหว่างการ Publish หรือ Update:", error);
                            }
                        }

                        // ✅ จัดการการอัปเดตสมาชิกในห้อง
                        function handleRoomMemberUpdate(result) {
                            setTimeout(() => {
                                if (result.length >= 2) {
                                    if (!check_start_timer_video_call) {
                                        // start_timer_video_call();
                                    }
                                    // if (!check_user_in_video_call) {
                                    //     start_user_in_video_call();
                                    // }
                                } else {
                                    if (check_start_timer_video_call) {
                                        console.log("⚠ สมาชิกในห้องน้อยกว่า 2, หยุด Timer");
                                        // myStop_timer_video_call();
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
                        // เอาหน้าโหลดออก
                        document.querySelector('#lds-ring').remove();


                        // ✅ ตรวจสอบจำนวนสมาชิกในห้อง
                        // handleRoomMemberUpdate(result);

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
                removeVideoDiv(remotePlayerContainer.id);
                removeVideoDiv(localPlayerContainer.id);
                // Leave the channel
                await agoraEngine.leave();
                console.log("You left the channel");

                if (leaveChannel == "false") {
                    // leaveChannel();
                    fetch("{{ url('/') }}/api/left_room" + "?user_id=" + '{{ Auth::user()->id }}' + "&type=" + type_video_call + "&sos_id=" + sos_id +"&meet_2_people=beforeunload"+"&leave=beforeunload")
                        .then(response => response.text())
                        .then(result => {
                            // console.log(result);
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

            var activeVideoDeviceId;
            var activeAudioDeviceId;
            // var activeAudioOutputDeviceId

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

                // if(useSpeaker){
                //     activeAudioOutputDeviceId = useSpeaker;
                // }else{
                //     activeAudioOutputDeviceId = devices.find(device => device.kind === 'audiooutput' && device.deviceId === 'default').deviceId;
                // }

            } catch (error) {
                console.error('เกิดข้อผิดพลาดในการเรียกดูอุปกรณ์:', error);
            }

            // ไมโครโฟน -- Microphone
            var old_activeAudioDeviceId ;

            // เรียกใช้งานเมื่อต้องการเปลี่ยนอุปกรณ์เสียง
            function onChangeAudioDevice() {

                old_activeAudioDeviceId = activeAudioDeviceId;
                // old_activeAudioOutputDeviceId = activeAudioOutputDeviceId;

                const selectedAudioDeviceId = getCurrentAudioDeviceId();
                // const selectedAudioOutputDeviceId = getCurrentAudiooutputDeviceId();
                // console.log('อุปกรณ์เสียงเดิม:', activeAudioDeviceId);
                // console.log('เปลี่ยนอุปกรณ์เสียงเป็น:', selectedAudioDeviceId);

                activeAudioDeviceId = selectedAudioDeviceId ;

                // สร้าง local audio track ใหม่โดยใช้อุปกรณ์ที่คุณต้องการ
                AgoraRTC.createMicrophoneAudioTrack({
                    AEC: true, // การยกเลิกเสียงสะท้อน
                    ANS: true, // การลดเสียงรบกวนอัตโนมัติ
                    encoderConfig: "high_quality", // ระดับคุณภาพเสียง
                    microphoneId: selectedAudioDeviceId
                })
                .then(newAudioTrack => {
                    console.log('newAudioTrack');
                    console.log(newAudioTrack);
                    // หยุดการส่งเสียงจากอุปกรณ์ปัจจุบัน
                    channelParameters.localAudioTrack.setEnabled(false);

                    agoraEngine.unpublish([channelParameters.localAudioTrack]);

                    // ปิดการเล่นเสียงเดิม
                    // channelParameters.localAudioTrack.stop();
                    // channelParameters.localAudioTrack.close();

                    // เปลี่ยน local audio track เป็นอุปกรณ์ใหม่
                    channelParameters.localAudioTrack = newAudioTrack;

                    channelParameters.localAudioTrack.play();

                    if(isAudio == true){
                        // เริ่มส่งเสียงจากอุปกรณ์ใหม่
                        channelParameters.localAudioTrack.setEnabled(true);
                        channelParameters.localAudioTrack.play();

                        agoraEngine.publish([channelParameters.localAudioTrack]);

                        // isAudio = true;
                        console.log('เปลี่ยนอุปกรณ์เสียงสำเร็จ');
                        console.log('เข้า if => isAudio == true');
                        console.log(channelParameters.localAudioTrack);
                        console.log(agoraEngine);

                    }
                    else {
                        channelParameters.localAudioTrack.setEnabled(true);
                        channelParameters.localAudioTrack.play();
                        agoraEngine.publish([channelParameters.localAudioTrack]);

                        channelParameters.localAudioTrack.setEnabled(false);
                        agoraEngine.unpublish([channelParameters.localAudioTrack]);
                        // channelParameters.localAudioTrack.play();
                        // isAudio = false;

                        console.log('เปลี่ยนอุปกรณ์เสียงสำเร็จ');
                        console.log('เข้า else => isAudio == false');
                        console.log(channelParameters.localAudioTrack);
                        console.log(agoraEngine);
                    }

                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาดในการสร้าง local audio track:', error);

                    selectedAudioDeviceId = old_activeAudioDeviceId;
                    selectedAudioOutputDeviceId = old_activeAudioOutputDeviceId;
                });
            }

            // ลำโพง -- Speaker -- ยังหาฟังก์ชันเปลี่ยนไม่ได้
            // var old_activeAudioOutputDeviceId ;
            // function onChangeAudioOutputDevice() {
            //     old_activeAudioOutputDeviceId = activeAudioOutputDeviceId;

            //     const selectedAudioOutputDeviceId = getCurrentAudiooutputDeviceId();
            //     // console.log('อุปกรณ์เสียงเดิม:', activeAudioDeviceId);
            //     // console.log('เปลี่ยนอุปกรณ์เสียงเป็น:', selectedAudioDeviceId);

            //     activeAudioOutputDeviceId = selectedAudioOutputDeviceId;
            //     // สร้าง local audio track ใหม่โดยใช้อุปกรณ์ที่คุณต้องการ
            //     AgoraRTC.createSpeakerAudioTrack({
            //         deviceId: selectedAudioOutputDeviceId,
            //     })
            //     .then(newAudioTrack => {
            //         console.log('newAudioTrack');
            //         console.log(newAudioTrack);
            //         // หยุดการส่งเสียงจากอุปกรณ์ปัจจุบัน
            //         // channelParameters.localAudioTrack.setEnabled(false);

            //         // agoraEngine.unpublish([channelParameters.localAudioTrack]);

            //         // // ปิดการเล่นเสียงเดิม
            //         // // channelParameters.localAudioTrack.stop();
            //         // // channelParameters.localAudioTrack.close();

            //         // // เปลี่ยน local audio track เป็นอุปกรณ์ใหม่
            //         // channelParameters.localAudioTrack = newAudioTrack;

            //         // channelParameters.localAudioTrack.play();

            //         // if(isAudio == true){
            //         //     // เริ่มส่งเสียงจากอุปกรณ์ใหม่
            //         //     channelParameters.localAudioTrack.setEnabled(true);
            //         //     channelParameters.localAudioTrack.play();

            //         //     agoraEngine.publish([channelParameters.localAudioTrack]);

            //         //     // isAudio = true;
            //         //     console.log('เปลี่ยนอุปกรณ์เสียงสำเร็จ');
            //         //     console.log('เข้า if => isAudio == true');
            //         //     console.log(channelParameters.localAudioTrack);
            //         // }
            //         // else {
            //         //     channelParameters.localAudioTrack.setEnabled(false);
            //         //     // channelParameters.localAudioTrack.play();
            //         //     // isAudio = false;
            //         //     console.log('เปลี่ยนอุปกรณ์เสียงสำเร็จ');
            //         //     console.log('เข้า else => isAudio == false');
            //         //     console.log(channelParameters.localAudioTrack);
            //         // }

            //     })
            //     .catch(error => {
            //         console.error('เกิดข้อผิดพลาดในการสร้าง local audio track:', error);

            //         selectedAudioDeviceId = old_activeAudioDeviceId;
            //         selectedAudioOutputDeviceId = old_activeAudioOutputDeviceId;
            //     });
            // }

            var old_activeVideoDeviceId ;

            function onChangeVideoDevice() {

                old_activeVideoDeviceId = activeVideoDeviceId ;

                const selectedVideoDeviceId = getCurrentVideoDeviceId();
                console.log('เปลี่ยนอุปกรณ์กล้องเป็น:', selectedVideoDeviceId);

                activeVideoDeviceId = selectedVideoDeviceId ;

                // สร้าง local video track ใหม่โดยใช้กล้องที่คุณต้องการ
                AgoraRTC.createCameraVideoTrack({ cameraId: selectedVideoDeviceId })
                .then(newVideoTrack => {

                    // console.log('------------ newVideoTrack ------------');
                    // console.log(newVideoTrack);
                    // console.log('------------ channelParameters.localVideoTrack ------------');
                    // console.log(channelParameters.localVideoTrack);
                    // console.log('------------ localPlayerContainer ------------');
                    // console.log(localPlayerContainer);

                    // // หยุดการส่งภาพจากอุปกรณ์ปัจจุบัน
                    // channelParameters.localVideoTrack.setEnabled(false);

                    agoraEngine.unpublish([channelParameters.localVideoTrack]);
                    // console.log('------------unpublish localVideoTrack ------------');

                    // ปิดการเล่นภาพวิดีโอกล้องเดิม
                    channelParameters.localVideoTrack.stop();
                    channelParameters.localVideoTrack.close();

                    // เปลี่ยน local video track เป็นอุปกรณ์ใหม่
                    channelParameters.localVideoTrack = newVideoTrack;

                    if (isVideo == true) {
                        // เริ่มส่งภาพจากอุปกรณ์ใหม่
                        channelParameters.localVideoTrack.setEnabled(true);
                        // แสดงภาพวิดีโอใน <div>
                        channelParameters.localVideoTrack.play(localPlayerContainer);
                        // channelParameters.remoteVideoTrack.play(remotePlayerContainer);
                        agoraEngine.publish([channelParameters.localVideoTrack]);
                        // console.log('เปลี่ยนอุปกรณ์กล้องสำเร็จ');
                    }
                    else {
                        // alert('ปิด');
                        channelParameters.localVideoTrack.setEnabled(false);

                        channelParameters.localVideoTrack.play(localPlayerContainer);
                        agoraEngine.publish([channelParameters.localVideoTrack]);
                    }

                    if (isVideo == false) {
                        setTimeout(() => {
                            console.log("bg_local onChange");
                            changeBgColor(bg_local);
                        }, 50);
                    }

                })
                .catch(error => {
                    // alert('ไม่สามารถเปลี่ยนกล้องได้');
                    // alertNoti('<i class="fa-solid fa-triangle-exclamation fa-shake"></i>', 'ไม่สามารถเปลี่ยนกล้องได้');
                    console.log('ไม่สามารถเปลี่ยนกล้องได้');

                    activeVideoDeviceId = old_activeVideoDeviceId ;

                    // setTimeout(function() {
                    //     document.querySelector('#btn_switchCamera').click();
                    // }, 2000);

                    console.error('เกิดข้อผิดพลาดในการสร้าง local video track:', error);

                    if (isVideo == false) {
                        setTimeout(() => {
                            console.log("bg_local ddddddddddddddddddddddd");
                            changeBgColor(bg_local);
                        }, 50);
                    }
                });

                // document.querySelector('#ปุ่มนี้สำหรับปิด_modal').click();
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

            // function getCurrentAudiooutputDeviceId() {
            //     const audiooutputDevices = document.getElementsByName('audio-device-output');
            //     for (let i = 0; i < audiooutputDevices.length; i++) {
            //         if (audiooutputDevices[i].checked) {
            //             return audiooutputDevices[i].value;
            //         }
            //     }
            //     return null;
            // }

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

            btn_switchCamera.onclick = async function()
            {
                // console.log('btn_switchCamera');
                // console.log('activeVideoDeviceId');
                // console.log(activeVideoDeviceId);

                // เรียกใช้ฟังก์ชันและแสดงผลลัพธ์
                let deviceType = checkDeviceType();
                // console.log("Device Type:", deviceType);

                // ถ้ายังไม่มีข้อมูลอุปกรณ์ที่เก็บไว้
                if (!cachedVideoDevices) {
                    // เรียกดูอุปกรณ์ทั้งหมด
                    let getDevices = await navigator.mediaDevices.enumerateDevices();

                    // แยกอุปกรณ์ตามประเภท
                    let getVideoDevices = getDevices.filter(device => device.kind === 'videoinput');

                    // กำหนดค่าให้กับตัวแปร global เพื่อเก็บไว้
                    cachedVideoDevices = getVideoDevices;
                }

                let videoDevices = cachedVideoDevices; // สามารถใช้ cachedVideoDevices ได้ทุกครั้งที่ต้องการ

                console.log('------- videoDevices -------');
                console.log(videoDevices);
                console.log('length ==>> ' + videoDevices.length);
                console.log('------- ------- -------');

                // สร้างรายการอุปกรณ์ส่งข้อมูลและเพิ่มลงในรายการ
                let videoDeviceList = document.getElementById('video-device-list');
                    videoDeviceList.innerHTML = '';
                let deviceText = document.createElement('li');
                    deviceText.classList.add('text-center','p-1','text-white');
                    deviceText.appendChild(document.createTextNode("กล้อง"));

                    videoDeviceList.appendChild(deviceText);

                let count_i = 1 ;

                videoDevices.forEach(device => {
                    let radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.classList.add('radio_style');
                        radio.id = 'video-device-' + count_i;
                        radio.name = 'video-device';
                        radio.value = device.deviceId;

                    if (deviceType == 'PC'){
                        radio.checked = device.deviceId === activeVideoDeviceId;
                    }

                    let label = document.createElement('li');
                        label.classList.add('ui-list-item');
                        label.appendChild(document.createTextNode(device.label || `อุปกรณ์ส่งข้อมูล ${videoDeviceList.children.length + 1}`));
                        label.appendChild(document.createTextNode("\u00A0")); // เพิ่ม non-breaking space
                        label.appendChild(radio);

                    if (deviceType == 'PC'){
                        // สร้างเหตุการณ์คลิกที่ label เพื่อตรวจสอบ radio2
                        label.addEventListener('click', () => {
                            radio.checked = true;
                            onChangeVideoDevice();
                        });
                    }

                    videoDeviceList.appendChild(label);

                    radio.addEventListener('change', onChangeVideoDevice);

                    count_i = count_i + 1 ;
                });

                // ---------------------------

                if (deviceType !== 'PC'){
                    let check_videoDevices = document.getElementsByName('video-device');

                    if (now_Mobile_Devices == 1){
                        // console.log("now_Mobile_Devices == 1 // ให้คลิก ");
                        // console.log(check_videoDevices[1].id);
                        document.querySelector('#'+check_videoDevices[1].id).click();
                        now_Mobile_Devices = 2 ;
                    }else{
                        // console.log("now_Mobile_Devices == 2 // ให้คลิก ");
                        // console.log(check_videoDevices[0].id);
                        document.querySelector('#'+check_videoDevices[0].id).click();
                        now_Mobile_Devices = 1 ;
                    }
                }

            }
            var cachedAudioDevices = null; // สร้างตัวแปร global เพื่อเก็บข้อมูล microphone
            btn_switchMicrophone.onclick = async function()
            {
                // console.log('btn_switchMicrophone');
                // console.log('activeAudioDeviceId');
                // console.log(activeAudioDeviceId);

                // เรียกใช้ฟังก์ชันและแสดงผลลัพธ์
                let deviceType = checkDeviceType();
                console.log("Device Type:", deviceType);

                // ถ้ายังไม่มีข้อมูลอุปกรณ์ที่เก็บไว้
                if (!cachedAudioDevices) {
                    // เรียกดูอุปกรณ์ทั้งหมด
                    let getDevices = await navigator.mediaDevices.enumerateDevices();
                    // แยกอุปกรณ์ตามประเภท
                    let getAudioDevices = getDevices.filter(device => device.kind === 'audioinput');

                    // กำหนดค่าให้กับตัวแปร global เพื่อเก็บไว้
                    cachedAudioDevices = getAudioDevices;
                }

                let audioDevices = cachedAudioDevices; // สามารถใช้ cachedAudioDevices ได้ทุกครั้งที่ต้องการ
                // แยกอุปกรณ์ตามประเภท --> ลำโพง
                // let audioOutputDevices = devices.filter(device => device.kind === 'audiooutput');

                console.log('------- audioDevices -------');
                console.log(audioDevices);
                console.log('length ==>> ' + audioDevices.length);
                console.log('------- ------- -------');

                // สร้างรายการอุปกรณ์ส่งข้อมูลและเพิ่มลงในรายการ
                let audioDeviceList = document.getElementById('audio-device-list');
                    audioDeviceList.innerHTML = '';
                let deviceText = document.createElement('li');
                    deviceText.classList.add('text-center','p-1','text-white');
                    deviceText.appendChild(document.createTextNode("อุปกรณ์รับข้อมูล"));

                //============================================ ส่วนของ การปรับระดับเสียง(optional)=====================================================================================

                let localVolume = localStorage.getItem('local_sos_1669_rangeValue') ?? 100;

                let localAudioVolumeLabel = `<label class="ui-list-item d-block" for="localAudioVolume" >
                                                <li class="text-center p-1 text-white d-block" style="font-size: 1.1em;">ระดับเสียงไมค์(ตัวเอง)</li>
                                                <input type="range" id="localAudioVolume" min="0" max="1000" value="`+localVolume+`" class="w-100">
                                            </label>`

                audioDeviceList.insertAdjacentHTML('afterbegin', localAudioVolumeLabel); // แทรกบนสุด

                let remoteAudioVolumeLabel = `<label class="ui-list-item d-none" for="remoteAudioVolume" >
                                                <li class="text-center p-1 text-white d-block" style="font-size: 1.1em;">เสียงที่เราได้ยิน</li>
                                                <input type="range" id="remoteAudioVolume" min="0" max="100" value="`+remoteVolume+`" class="w-100">
                                            </label>`

                audioDeviceList.insertAdjacentHTML('afterbegin', remoteAudioVolumeLabel); // แทรกบนสุด

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
                let remote_rangeInput = document.getElementById('remoteAudioVolume');
                remote_rangeInput.addEventListener('input', function() {
                // บันทึกค่าลงใน remoteStorage เมื่อมีการเปลี่ยนแปลง
                    localStorage.setItem('remote_rangeValue', remote_rangeInput.value);
                    remoteVolume = remote_rangeInput.value; // เปลี่ยนค่าระดับเสียงของทางฝั่งตรงข้ามให้เท่ากับตัวปรับ
                });

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
                // let audiooutputDeviceList = document.getElementById('audio-device-output-list');
                //     audiooutputDeviceList.innerHTML = '';

                let count_i = 1 ;
                let count_i_output = 1 ;
                // ----------- Input ----------------
                audioDevices.forEach(device => {
                    const radio2 = document.createElement('input');
                        radio2.type = 'radio';
                        radio2.classList.add('radio_style');
                        radio2.id = 'audio-device-' + count_i;
                        radio2.name = 'audio-device';
                        radio2.value = device.deviceId;

                    if (deviceType == 'PC'){
                        radio2.checked = device.deviceId === activeAudioDeviceId;
                    }

                    let label = document.createElement('li');
                        label.classList.add('ui-list-item');
                        label.appendChild(document.createTextNode(device.label || `อุปกรณ์รับข้อมูล ${audioDeviceList.children.length + 1}`));
                        label.appendChild(document.createTextNode("\u00A0")); // เพิ่ม non-breaking space
                        label.appendChild(radio2);

                        // สร้างเหตุการณ์คลิกที่ label เพื่อตรวจสอบ radio2
                        label.addEventListener('click', () => {
                            radio2.checked = true;
                            onChangeAudioDevice();
                        });


                    audioDeviceList.appendChild(label);
                    radio2.addEventListener('change', onChangeAudioDevice);

                    count_i = count_i + 1 ;
                });

                // let hr = document.createElement('hr');
                // audioDeviceList.appendChild(hr);

                // ----------- Output ----------------
                // audioOutputDevices.forEach(device => {
                // const radio3 = document.createElement('input');
                //     radio3.type = 'radio';
                //     radio3.id = 'audio-device-output-' + count_i_output;
                //     radio3.name = 'audio-device-output';
                //     radio3.value = device.deviceId;

                // if (deviceType == 'PC'){
                //     radio3.checked = device.deviceId === activeAudioOutputDeviceId;
                // }

                // let label_output = document.createElement('label');
                //     label_output.classList.add('dropdown-item');
                //     label_output.appendChild(radio3);
                //     label_output.appendChild(document.createTextNode(device.label || `อุปกรณ์ส่งข้อมูล ${audioDeviceList.children.length + 1}`));

                // audiooutputDeviceList.appendChild(label_output);
                // radio3.addEventListener('change', onChangeAudioOutputDevice);

                // count_i_output = count_i_output + 1 ;
                // });

                // ---------------------------7

                // เพิ่มเหตุการณ์คลิกที่หน้าจอที่ไม่ใช่ตัว audio-device-list ให้ปิด audio-device-list
                // document.addEventListener('click', (event) => {
                //     const target = event.target;

                //     if (!target.closest('#audio-device-list')) {
                //        document.querySelector('.dropcontent').classList.toggle('open');
                //     }
                // });

            }

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



        }
    </script>

    <script>

        let sidebarOpen = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContainer = document.getElementById('mainContainer');

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

        function createAndAttachCustomDiv() {
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

            updateVideoVisibility();
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
