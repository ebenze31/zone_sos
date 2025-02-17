@extends('layouts.theme_video_call')

@section('content')

    <link href="{{ asset('css/before_video_call.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://kit-pro.fontawesome.com/releases/v6.4.2/css/pro.min.css" rel="stylesheet">

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
                    @if ($type_brand == 'pc')
                        <div class="div-video">
                            <video id="videoDiv" style="background-color: #000000;" class="video_preview" autoplay
                                playsinline></video>
                            <div id="soundTest" class="soundTest">
                                <div class="soundMeter"></div>
                            </div>
                            <div class="buttonDiv d-none">
                                <button id="toggleMicrophoneButton" class="toggleMicrophoneButton btn" onclick="toggleMicrophone()">
                                    <i class="fa-regular fa-microphone"></i>
                                </button>
                                <button id="toggleCameraButton" class="toggleCameraButton mr-3 btn" onclick="toggleCamera()">
                                    <i class="fa-regular fa-camera"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="div-video m-5">
                            <video id="videoDiv" style="background-color: #000000;" class="video_preview" autoplay
                                playsinline></video>
                            <div id="soundTest" class="soundTest">
                                <div class="soundMeter"></div>
                            </div>
                            <div class="buttonDiv d-none">
                                <button id="toggleMicrophoneButton" class="toggleMicrophoneButton btn" onclick="toggleMicrophone()">
                                    <i class="fa-regular fa-microphone"></i>
                                </button>
                                <button id="toggleCameraButton" class="toggleCameraButton mr-3 btn" onclick="toggleCamera()">
                                    <i class="fa-regular fa-camera"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class=" d-nne">
                        @if ($type_brand == 'pc')
                            <div class="selectDivice mt-2 p-2 row justify-content-center">
                                <select id="microphoneList" style="min-width: 150px;"></select>
                                <select id="cameraList" style="min-width: 150px;"></select>
                            </div>
                        @else
                            <div class="selectDivice mt-2 p-2 row d-none justify-content-center">
                                <select id="microphoneList"></select>
                                <select id="cameraList"></select>
                                {{-- <select id="speakerList"></select> --}}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-lg-4  d-flex justify-content-center p-3 align-items-center">
                    <div id="before_join_message" class="text-center w-100">
                        @if ($type_brand == 'pc')
                            @if ($type == 'sos_1669' || $type == 'user_sos_1669')
                                @php
                                    $data_sos_1669 = App\Models\Sos_help_center::where('id', $sos_id)->first();
                                @endphp

                                @if ($user->role == 'partner')
                                    @if (!empty($data_sos_1669->code_for_officer))
                                        <h4 class="w-100">ห้องสนทนา : {{ $data_sos_1669->code_for_officer }}</h4>
                                    @else
                                        <h4 class="w-100">ห้องสนทนา : {{ $data_sos_1669->operating_code }}</h4>
                                    @endif
                                @else
                                    <h4 class="w-100">ห้องสนทนา : {{ $data_sos_1669->operating_code }}</h4>
                                @endif
                            @else
                                <h4 class="w-100">ห้องสนทนา : {ยังไม่มี ID ห้อง}</h4>
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
                            <a id="full_room" class="btn btn-secondary d-none"
                                onclick="AlertPeopleInRoom()">ห้องนี้ถึงจำนวนผู้ใช้สูงสุดแล้ว</a>
                        @else
                            @if ($type == 'sos_1669' || $type == 'user_sos_1669')
                                @php
                                    $data_sos_1669 = App\Models\Sos_help_center::where('id', $sos_id)->first();
                                @endphp

                                @if ($user->role == 'partner')
                                    @if (!empty($data_sos_1669->code_for_officer))
                                        <h1 class="w-100 font-weight-bold">ห้องสนทนา :
                                            {{ $data_sos_1669->code_for_officer }}</h1>
                                    @else
                                        <h1 class="w-100 font-weight-bold">ห้องสนทนา : {{ $data_sos_1669->operating_code }}
                                        </h1>
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

                            <a style="font-size: 40px; border-radius: 10px;" id="btnJoinRoom" class="btn btn-success d-none"
                                href="">
                                เข้าร่วมห้องสนทนา
                            </a>
                            <a style="font-size: 40px; border-radius: 10px;" id="full_room" class="btn btn-secondary d-none"
                                onclick="AlertPeopleInRoom()">ห้องนี้ถึงจำนวนผู้ใช้สูงสุดแล้ว</a>

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="{{ asset('js/for_video_call_4/before_video_call_4.js') }}"></script> --}}

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
    <!-- <script src="{{ asset('partner_new/js/bootstrap.bundle.min.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
        integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
        integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous">
    </script>

    <script>
        var statusCamera = "open";
        var statusMicrophone = "open";
        var useMicrophone = '';
        var useSpeaker = '';
        var useCamera = '';

        var audioTracks = ''; // สำหรับเก็บ tag เสียงแบบ global

        // var appId = localStorage.getItem('appId');
        // var appCertificate = localStorage.getItem('appCertificate');

        var appId = '{{ env('AGORA_APP_ID') }}';
        var appCertificate = '{{ env('AGORA_APP_CERTIFICATE') }}';

        var sos_id = '';

        var selectedMicrophone = null;
        var selectedCamera = null;
        var selectedSpeaker = null;
        var microphoneStream = null;
        var cameraStream = null;
        var speakerStream = null;

        let currentStream = null; // ประกาศตัวแปร global เพื่อเก็บ stream

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            let agoraAppId = appId;
            let agoraAppCertificate = appCertificate;
            if (appId && appCertificate) {
                agoraAppId = '{{ config('agora.app_id') }}';
                agoraAppCertificate = '{{ config('agora.app_certificate') }}';
            }
            console.log(agoraAppId);
            console.log(agoraAppCertificate);

            // await getDevices();
            // เรียกฟังก์ชันเพื่อรับรายการอุปกรณ์
            await getDeviceList();
        });

        // ฟังก์ชันในการขอสิทธิ์การเข้าถึงอุปกรณ์
        async function requestPermissions() {
            try {
                // ขอการเข้าถึงไมโครโฟนและกล้อง
                await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: true
                });
            } catch (error) {
                console.error('Error accessing media devices:', error);
                alert('ไม่สามารถเข้าถึงไมโครโฟนและกล้องได้');
            }
        }

        // รับรายการอุปกรณ์และแสดงใน dropdown
        async function getDeviceList() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const microphones = [];
                const cameras = [];

                document.querySelector('.buttonDiv').classList.remove('d-none');

                devices.forEach((device) => {
                    const option = document.createElement("option");
                    option.value = device.deviceId;

                    if (device.kind === "audioinput") {
                        let labelText = document.createTextNode(
                            `🎙️ ${device.label || `อุปกรณ์ ${device.deviceId}`}`);
                        option.appendChild(labelText);
                        microphones.push(option);
                    } else if (device.kind === "videoinput") {
                        let labelText = document.createTextNode(
                            `📷 ${device.label || `อุปกรณ์ ${device.deviceId}`}`);
                        option.appendChild(labelText);
                        cameras.push(option);
                    }
                });

                 // เช็คถ้าไม่มีอุปกรณ์กล้อง
                if (cameras.length === 0) {
                    handleNoDevice('camera');
                }

                // เช็คถ้าไม่มีอุปกรณ์ไมโครโฟน
                if (microphones.length === 0) {
                    handleNoDevice('microphone');
                }

                // ค้นหาอุปกรณ์ที่กำลังใช้งานอยู่
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: true
                });
                const currentMicrophoneId = stream.getAudioTracks()[0]?.getSettings()?.deviceId;
                const currentCameraId = stream.getVideoTracks()[0]?.getSettings()?.deviceId;
                // ค้นหาอุปกรณ์จาก enumerateDevices() ที่ตรงกับ deviceId
                const activeMicrophone = devices.find(device => device.deviceId === currentMicrophoneId && device
                    .kind === 'audioinput');
                const activeCamera = devices.find(device => device.deviceId === currentCameraId && device.kind ===
                    'videoinput');

                // ตั้งค่าให้เลือกอุปกรณ์ที่ใช้งาน
                microphones.forEach(option => {
                    if (option.value === currentMicrophoneId) {
                        option.selected = true;
                        updateMicrophone(activeMicrophone);
                    }
                    microphoneList.appendChild(option);
                });

                cameras.forEach(option => {
                    if (option.value === currentCameraId) {
                        option.selected = true;
                        updateCamera(activeCamera);
                    }
                    cameraList.appendChild(option);
                });

                // เมื่อเลือกไมโครโฟนใน dropdown
                microphoneList.addEventListener("change", () => {
                    selectedMicrophone = devices.find((device) => device.deviceId === microphoneList.value);
                    updateMicrophone(selectedMicrophone); // เรียกใช้ฟังก์ชันเพื่ออัปเดตไมโครโฟน
                });

                // เมื่อเลือกกล้องใน dropdown
                cameraList.addEventListener("change", () => {
                    selectedCamera = devices.find((device) => device.deviceId === cameraList.value);
                    updateCamera(selectedCamera); // เรียกใช้ฟังก์ชันเพื่ออัปเดตกล้อง
                });

            } catch (error) {
                console.error("เกิดข้อผิดพลาดในการรับรายการอุปกรณ์:", error);
            }
        }

        // ฟังก์ชันสำหรับจัดการกรณีไม่เจออุปกรณ์
        function handleNoDevice(type) {
            if (type === 'camera') {
                console.warn('ไม่พบอุปกรณ์กล้อง');
                document.querySelector('#toggleCameraButton').setAttribute('disabled', true);
                document.querySelector('#toggleCameraButton').classList.add('btn-secondary');
                document.querySelector('#toggleCameraButton').innerHTML = '<i class="fa-regular fa-camera-slash"></i>'
                document.querySelector('#cameraList').setAttribute('disabled', true);
            } else if (type === 'microphone') {
                console.warn('ไม่พบอุปกรณ์ไมโครโฟน');
                document.querySelector('#toggleMicrophoneButton').setAttribute('disabled', true);
                document.querySelector('#toggleMicrophoneButton').classList.add('btn-secondary');
                document.querySelector('#toggleMicrophoneButton').innerHTML = '<i class="fa-regular fa-microphone-slash"></i>'
                document.querySelector('#microphoneList').setAttribute('disabled', true);
            }
        }

        function updateCamera(selectedCamera) {
            if (selectedCamera) {
                useCamera = selectedCamera.deviceId;
                document.querySelector('#btnJoinRoom').setAttribute('href',
                    "{{ url('/' . $type_device . '/' . $type . '/' . $sos_id) }}?videoTrack=" + statusCamera +
                    "&audioTrack=" + statusMicrophone + "&useMicrophone=" + useMicrophone + "&useSpeaker=" +
                    useSpeaker + "&useCamera=" + useCamera);
            } else {
                document.querySelector('#btnJoinRoom').setAttribute('href',
                    "{{ url('/' . $type_device . '/' . $type . '/' . $sos_id) }}?videoTrack=" + statusCamera +
                    "&audioTrack=" + statusMicrophone + "&useMicrophone=" + useMicrophone + "&useSpeaker=" +
                    useSpeaker + "&useCamera=" + useCamera);
            }

            let videoElement = document.getElementById('videoDiv');
            let selectedDeviceId = selectedCamera; // รับค่า ID ของอุปกรณ์ที่เลือกใน dropdown
            let constraints = {
                video: {
                    deviceId: selectedDeviceId
                }
            }; // เลือกอุปกรณ์ที่ถูกเลือก

            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(videoStream) {
                    if (statusCamera == "open") {
                        videoElement.srcObject = videoStream; // กำหนดกล้องใหม่ให้แสดงบนอิลิเมนต์ video
                        // localStorage.setItem('selectedCameraId', selectedDeviceId); // บันทึกอุปกรณ์ที่เลือกลงใน localStorage
                    } else {
                        videoElement.srcObject = videoStream; // กำหนดกล้องใหม่ให้แสดงบนอิลิเมนต์ video

                        let videoTracks = videoElement.srcObject.getVideoTracks();
                        videoTracks[0].stop();

                        statusCamera = "open";
                        document.querySelector('#toggleCameraButton').classList.add('active');
                        document.querySelector('#toggleCameraButton').innerHTML = '<i style="font-size: 25px;" class="fa-regular fa-camera-slash"></i>'

                        // localStorage.setItem('selectedCameraId', selectedDeviceId); // บันทึกอุปกรณ์ที่เลือกลงใน localStorage
                    }
                })
                .catch(function(error) {
                    console.error('เกิดข้อผิดพลาดในการอัปเดตกล้อง:', error);
                });
        }

        // อัปเดตไมโครโฟนที่ใช้งาน
        function updateMicrophone(selectedMicrophone) {

            if (selectedMicrophone) {
                useMicrophone = selectedMicrophone.deviceId;
                document.querySelector('#btnJoinRoom').setAttribute('href',
                    "{{ url('/' . $type_device . '/' . $type . '/' . $sos_id) }}?videoTrack=" + statusCamera +
                    "&audioTrack=" + statusMicrophone + "&useMicrophone=" + useMicrophone + "&useSpeaker=" +
                    useSpeaker + "&useCamera=" + useCamera);
            } else {
                document.querySelector('#btnJoinRoom').setAttribute('href',
                    "{{ url('/' . $type_device . '/' . $type . '/' . $sos_id) }}?videoTrack=" + statusCamera +
                    "&audioTrack=" + statusMicrophone + "&useMicrophone=" + useMicrophone + "&useSpeaker=" +
                    useSpeaker + "&useCamera=" + useCamera);
            }

            // ใช้ getMediaUser() เพื่อกำหนดไมโครโฟนที่ถูกเลือก
            navigator.mediaDevices.getUserMedia({
                    audio: {
                        deviceId: selectedMicrophone.deviceId
                    }
                })
                .then(function(stream) {
                    // ตั้งค่าการใช้งานไมโครโฟนใน element audio
                    audioTracks = stream;
                })
                .catch(function(error) {
                    console.error('เกิดข้อผิดพลาดในการอัปเดตไมโครโฟน:', error);
                });

            if (statusMicrophone == "open") {
                startMicrophone(selectedMicrophone);
            }
        }

        //======================
        //   เปิด - ปิด กล้อง
        //======================
        let toggleCameraButton = document.getElementById('toggleCameraButton');
        function toggleCamera() {

            if (statusCamera == "open") {
                statusCamera = "close"; //เซ็ต statusCamera เป็น close
                document.querySelector('#btnJoinRoom').setAttribute('href',"{{ url('/'. $type_device .'/'. $type . '/' . $sos_id  ) }}?videoTrack="+statusCamera+"&audioTrack="+statusMicrophone+"&useMicrophone="+useMicrophone+"&useSpeaker="+useSpeaker+"&useCamera="+useCamera);

                // ตรวจสอบว่ากล้องถูกเปิดหรือไม่

                let selectedDeviceId = cameraList.value; // รับค่า ID ของอุปกรณ์ที่เลือกใน dropdown
                let constraints = { video: { deviceId: selectedDeviceId } }; // เลือกอุปกรณ์ที่ถูกเลือก

                let videoElement = document.getElementById('videoDiv');
                let stramViddeo = videoElement.srcObject;

                let videoTracks = stramViddeo.getVideoTracks();

                videoTracks[0].stop();

                document.querySelector('#toggleCameraButton').classList.add('active');
                document.querySelector('#toggleCameraButton').innerHTML = '<i class="fa-regular fa-camera-slash"></i>'

            }else{
                statusCamera = "open"; // เซ็ต statusCamera เป็น open
                document.querySelector('#btnJoinRoom').setAttribute('href',"{{ url('/'. $type_device .'/'. $type . '/' . $sos_id  ) }}?videoTrack="+statusCamera+"&audioTrack="+statusMicrophone+"&useMicrophone="+useMicrophone+"&useSpeaker="+useSpeaker+"&useCamera="+useCamera);

                // เปิดกล้อง
                let videoElement = document.getElementById('videoDiv');
                let selectedDeviceId = cameraList.value; // รับค่า ID ของอุปกรณ์ที่เลือกใน dropdown
                let constraints = { video: { deviceId: selectedDeviceId } }; // เลือกอุปกรณ์ที่ถูกเลือก

                navigator.mediaDevices.getUserMedia(constraints)
                .then(function(newVideoStream) {
                    // ได้รับสตรีมวิดีโอใหม่สำเร็จ
                    videoStream = newVideoStream;
                    let videoElement = document.getElementById('videoDiv');
                    videoElement.srcObject = videoStream;

                    document.querySelector('#toggleCameraButton').classList.remove('active');
                    document.querySelector('#toggleCameraButton').innerHTML = '<i class="fa-regular fa-camera"></i>'
                    // console.log('เปิดกล้อง');

                    // console.log(videoStream);
                })
                .catch(function(error) {
                    // ไม่สามารถเข้าถึงกล้องได้ หรือผู้ใช้ไม่อนุญาต
                    console.error('เกิดข้อผิดพลาดในการเข้าถึงกล้อง:', error);
                });
            }
            setTimeout(() => {
                console.log(statusCamera);


            }, 1000);

        }

        //======================
        //   เปิด - ปิด ไมโครโฟน
        //======================
        let toggleMicrophoneButton = document.getElementById('toggleMicrophoneButton');

        function toggleMicrophone() {
            if (statusMicrophone == 'open') {
                statusMicrophone = "close"; // เซ็ต statusMicrophone เป็น close
                document.querySelector('#btnJoinRoom').setAttribute('href',"{{ url('/'. $type_device .'/'. $type . '/' . $sos_id  ) }}?videoTrack="+statusCamera+"&audioTrack="+statusMicrophone+"&useMicrophone="+useMicrophone+"&useSpeaker="+useSpeaker+"&useCamera="+useCamera);

                navigator.mediaDevices.getUserMedia({ audio: true })
                .then(function(audioStream) {

                    // ปิดไมค์
                    audioTracks = audioStream.getAudioTracks();
                    // console.log("audioStream");
                    // console.log(audioStream);

                    // ปิดทุก audio track ใน audioStream
                    for (const track of audioTracks) {
                        track.stop();
                    }

                    document.querySelector('#toggleMicrophoneButton').classList.add('active');
                    document.querySelector('#toggleMicrophoneButton').innerHTML = '<i class="fa-regular fa-microphone-slash"></i>'
                    // console.log('ปิดไมค์');

                })

                //ปิดตัวทดสอบเสียง
                stopMicrophone();
            }else{
                statusMicrophone = "open"; // เซ็ต statusMicrophone เป็น open
                document.querySelector('#btnJoinRoom').setAttribute('href',"{{ url('/'. $type_device .'/'. $type . '/' . $sos_id  ) }}?videoTrack="+statusCamera+"&audioTrack="+statusMicrophone+"&useMicrophone="+useMicrophone+"&useSpeaker="+useSpeaker+"&useCamera="+useCamera);

                let constraints = selectedMicrophone;
                let audioSelect;
                if(constraints){
                    audioSelect = { video: { deviceId: constraints.deviceId } }; // เลือกอุปกรณ์ที่ถูกเลือก
                }else{
                    audioSelect = { video: true, }; // เลือกอุปกรณ์ที่ถูกเลือก
                }

                navigator.mediaDevices.getUserMedia(audioSelect)
                .then(function(newAudioStream) {
                    audioTracks = newAudioStream;
                    document.querySelector('#toggleMicrophoneButton').classList.remove('active');
                    document.querySelector('#toggleMicrophoneButton').innerHTML = '<i class="fa-regular fa-microphone"></i>'
                    console.log('เปิดสตรีมไมโครโฟน');
                    console.log(audioTracks);

                })
                .catch(function(error) {
                    console.error('เกิดข้อผิดพลาดในการเข้าถึงไมโครโฟน:', error);
                });

                //ปิดตัวทดสอบเสียง
                startMicrophone(constraints);
            }
            setTimeout(() => {
                console.log(statusMicrophone);
                document.querySelector('#btnJoinRoom').setAttribute('href',"{{ url('/'. $type_device .'/'. $type . '/' . $sos_id  ) }}?videoTrack="+statusCamera+"&audioTrack="+statusMicrophone+"&useMicrophone="+useMicrophone+"&useSpeaker="+useSpeaker+"&useCamera="+useCamera);


            }, 1000);
        }


    </script>

    <script>
        const soundTest = document.getElementById("soundTest");
        const soundMeter = document.querySelector('.soundMeter');

        let mediaStream;
        let animationFrameId;

        // เริ่มการเข้าถึงไมโครโฟน
        async function startMicrophone(selectedMicrophone) {
            try {
                let constraints;
                if (selectedMicrophone) {
                    constraints = {
                        audio: {
                            deviceId: selectedMicrophone.deviceId
                        },
                    };
                } else {
                    constraints = {
                        audio: true, // ใช้อุปกรณ์เสียงปัจจุบันของผู้ใช้
                    };
                }

                mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
                let audioContext = new (window.AudioContext || window.webkitAudioContext)();
                let microphone = audioContext.createMediaStreamSource(mediaStream);
                let analyser = audioContext.createAnalyser();

                microphone.connect(analyser);

                // ตั้งค่า AnalyserNode เพื่อวัดความดัง
                analyser.fftSize = 256; // ค่าน้อยลง = อัปเดตเร็วขึ้น (เช่น 32, 64, 128, 256)
                let bufferLength = analyser.frequencyBinCount;
                let dataArray = new Uint8Array(bufferLength);

                function updateAnimation() {
                    analyser.getByteFrequencyData(dataArray); // เปลี่ยนเป็น Frequency แทน Time Domain

                    // คำนวณค่าเฉลี่ยของพลังงานในย่านความถี่เสียงทั้งหมด
                    let sum = 0;
                    for (let i = 0; i < bufferLength; i++) {
                        sum += dataArray[i];
                    }
                    let averageVolume = sum / bufferLength;

                    // ขยายค่านี้เพื่อให้ขึ้นง่ายขึ้น
                    let scaledVolume = averageVolume * 2.5; // คูณเพิ่มให้แถบขึ้นง่ายขึ้น

                    updateSoundMeter(scaledVolume);

                    animationFrameId = requestAnimationFrame(updateAnimation);
                }

                updateAnimation();
            } catch (error) {
                console.error('เกิดข้อผิดพลาดในการเริ่มต้นไมโครโฟน:', error);
            }
        }

        // หยุดการเข้าถึงไมโครโฟน
        function stopMicrophone() {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
            }
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            soundMeter.style.width = '0';
        }

        // อัปเดต sound meter
        function updateSoundMeter(volume) {
            const maxWidth = soundTest.clientWidth;
            const newWidth = Math.min((volume / 100) * maxWidth, maxWidth); // จำกัดไม่ให้เกินขอบ
            soundMeter.style.width = `${newWidth}px`;
        }

        // หยุดไมโครโฟนเมื่อปิดหน้า
        window.addEventListener('beforeunload', stopMicrophone);

    </script>

@endsection
