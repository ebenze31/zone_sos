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
                <div class="col-12 col-sm-12 col-lg-9 p-2 d-flex flex-column align-items-center justify-content-center">
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

                    <div class="w-100 d-nne">
                        @if ($type_brand == 'pc')
                            <div class="selectDivice mt-2 p-2 row justify-content-center">
                                <select id="microphoneList" style="min-width: 150px; max-width: 200px;"></select>
                                <select id="cameraList" style="min-width: 150px; max-width: 200px;"></select>
                                <select id="speakerList" style="min-width: 150px; max-width: 200px;"></select>
                            </div>
                        @else
                            <div class="selectDivice mt-2 p-2 row d-none justify-content-center">
                                <select id="microphoneList"></select>
                                <select id="cameraList"></select>
                                <select id="speakerList"></select>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-lg-3  d-flex justify-content-center p-3 align-items-center">
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

        var sos_id = '{{ $sos_id }}';
        var type_sos = '{{ $type }}';

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

            // await getDevices();
            // เรียกฟังก์ชันเพื่อรับรายการอุปกรณ์
            await getDeviceList();

            Check_video_call_room(); // เรียกฟังก์ชันทันทีเมื่อหน้าโหลด
            setInterval(() => {
                Check_video_call_room();
            }, 5000); // เรียกซ้ำทุก ๆ 5 วินาที

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
                const speakers = [];

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
                    } else if (device.kind === "audiooutput") { // เพิ่มการตรวจจับลำโพง
                        let labelText = document.createTextNode(
                            `🔊 ${device.label || `อุปกรณ์ ${device.deviceId}`}`);
                        option.appendChild(labelText);
                        speakers.push(option);
                    }
                });

                // เช็คถ้าไม่มีอุปกรณ์กล้อง
                if (cameras.length === 0) {
                    await requestPermission('camera');
                }

                // เช็คถ้าไม่มีอุปกรณ์ไมโครโฟน
                if (microphones.length === 0) {
                    await requestPermission('microphone');
                }

                // เช็คถ้าไม่มีอุปกรณ์ลำโพง
                if (speakers.length === 0) {
                    await requestPermission('speaker');
                }

                // ค้นหาข้อมูลไมโครโฟน, กล้อง และลำโพงที่ใช้งาน
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: true
                });

                // ดึงข้อมูลไมโครโฟนที่ใช้งาน
                const currentMicrophoneId = stream.getAudioTracks()[0]?.getSettings()?.deviceId;
                // ดึงข้อมูลกล้องที่ใช้งาน
                const currentCameraId = stream.getVideoTracks()[0]?.getSettings()?.deviceId;

                // ใช้ enumerateDevices() เพื่อตรวจสอบอุปกรณ์ลำโพงที่ใช้งาน
                const activeSpeakers = devices.filter(device => device.kind === 'audiooutput');

                // เปรียบเทียบ deviceId เพื่อหาลำโพงที่ใช้งาน
                const currentSpeakerId = activeSpeakers.length > 0 ? activeSpeakers[0].deviceId : null;

                // ค้นหาอุปกรณ์จาก enumerateDevices() ที่ตรงกับ deviceId
                const activeMicrophone = devices.find(device => device.deviceId === currentMicrophoneId && device.kind === 'audioinput');
                const activeCamera = devices.find(device => device.deviceId === currentCameraId && device.kind === 'videoinput');
                const activeSpeaker = devices.find(device => device.deviceId === currentSpeakerId && device.kind === 'audiooutput');


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

                speakers.forEach(option => {
                    if (option.value === currentSpeakerId) {
                        option.selected = true;
                        updateSpeaker(activeSpeaker); // ฟังก์ชันเพื่ออัปเดตลำโพง
                    }
                    speakerList.appendChild(option);
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

                // เมื่อเลือกลำโพงใน dropdown
                speakerList.addEventListener("change", () => {
                    selectedSpeaker = devices.find((device) => device.deviceId === speakerList.value);
                    updateSpeaker(selectedSpeaker); // เรียกใช้ฟังก์ชันเพื่ออัปเดตลำโพง
                });


            } catch (error) {
                console.error("เกิดข้อผิดพลาดในการรับรายการอุปกรณ์:", error);
            }
        }

        // ฟังก์ชันขออนุญาตการเข้าถึงอุปกรณ์
        async function requestPermission(deviceType) {
            try {
                let constraints = {};

                if (deviceType === 'camera') {
                    constraints = { video: true };
                } else if (deviceType === 'microphone') {
                    constraints = { audio: true };
                } else if (deviceType === 'speaker') {
                    // ขออนุญาตลำโพง
                    constraints = { audio: { deviceId: 'default' } };
                }

                // ขออนุญาตให้เข้าถึงอุปกรณ์
                const stream = await navigator.mediaDevices.getUserMedia(constraints);

                // ตรวจสอบว่ามีอุปกรณ์หลังจากการขออนุญาต
                const devices = await navigator.mediaDevices.enumerateDevices();
                updateDeviceList(devices); // เรียกใช้ฟังก์ชันอัปเดตรายการอุปกรณ์

            } catch (error) {
                console.error(`ไม่สามารถขออนุญาตเข้าถึง ${deviceType}:`, error);
                handleNoDevice(deviceType); // เรียกฟังก์ชันเมื่อไม่สามารถขออนุญาตได้
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
            }else if (type === 'speaker'){
                console.warn('ไม่พบอุปกรณ์ไมโครโฟน');
                document.querySelector('#speakerList').setAttribute('disabled', true);
            }else{
                console.warn('ไม่พบประเภทอุปกรณ์');
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

        // อัปเดตลำโพงที่ใช้งาน
        function updateSpeaker(selectedSpeaker) {
            if (selectedSpeaker) {
                useSpeaker = selectedSpeaker.deviceId;
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

            // ตั้งค่าการเล่นเสียงผ่านลำโพงที่เลือก
            const audio = new Audio(); // สร้างออบเจกต์ Audio
            audio.setSinkId(selectedSpeaker.deviceId) // ตั้งค่าลำโพงที่เลือก
                .then(() => {
                    console.log(`ใช้งานลำโพง: ${selectedSpeaker.label}`);
                })
                .catch((error) => {
                    console.error('เกิดข้อผิดพลาดในการตั้งค่าลำโพง:', error);
                });

            // if (statusSpeaker == "open") {
            //     startSpeaker(selectedSpeaker);
            // }
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


        function Check_video_call_room(){

            fetch("{{ url('/') }}/api/check_user_in_room" + "?sos_id=" + sos_id + "&type=" + type_sos)
            .then(response => response.json())
            .then(result => {

                if(result['status'] === "ok"){
                    // console.log("user_in_room");
                    // console.log(result);

                    document.querySelector('#btnJoinRoom').classList.remove('d-none');
                    document.querySelector('#full_room').classList.add('d-none');

                    if (result['data'] != "ไม่มีข้อมูล") {
                        let avatar_div = document.querySelector('#avatars');
                        avatar_div.innerHTML = '';
                        result['data'].forEach(element => {
                            let profile_user;
                            if (element.photo) {
                                // profile_user = "{{ url('/storage') }}" + "/" + element.photo;
                                profile_user = "https://www.viicheck.com" + "/" + element.photo;
                            } else if (!element.photo && element.avatar) {
                                profile_user = element.avatar;
                            } else {
                                profile_user = "https://www.viicheck.com/Medilab/img/icon.png";
                            }
                            console.log(profile_user);
                            let html = `<span class="avatar">
                                            <img src="` + profile_user + `">
                                        </span>`;

                            avatar_div.insertAdjacentHTML('beforeend', html);
                        });

                        let html_2;
                        if (result['data'].length > 1) {
                            html_2 = `<h6 class="w-100 text_for_device">${result['data'][0].name} และอีก ${result['data'].length - 1} คนในห้องสนทนา</h6>`;
                        } else if (result['data'].length === 1) {
                            html_2 = `<h6 class="w-100 text_for_device">${result['data'][0].name} อยู่ในห้องสนทนา</h6>`;
                        } else {
                            // Handle the case where there are no users
                            html_2 = `<h6 class="w-100 text_for_device">ไม่มีคนอยู่ในห้องสนทนา</h6>`;
                        }

                        let text_user_in_room = document.querySelector('#text_user_in_room');
                        text_user_in_room.innerHTML = '';
                        // Handle the case where there are no users
                        text_user_in_room.insertAdjacentHTML('beforeend', html_2);

                    }else{
                        let avatar_div = document.querySelector('#avatars');
                        avatar_div.innerHTML = '';

                        let text_user_in_room = document.querySelector('#text_user_in_room');
                        text_user_in_room.innerHTML = '';
                        // Handle the case where there are no users
                        let html_2 = `<h6 class="w-100 text_for_device">ไม่มีคนอยู่ในห้องสนทนา</h6>`;
                        text_user_in_room.insertAdjacentHTML('beforeend', html_2);
                    }

                }else{

                    document.querySelector('#btnJoinRoom').classList.add('d-none');
                    document.querySelector('#full_room').classList.remove('d-none');

                    if (result['data'] != "ไม่มีข้อมูล") {
                        let avatar_div = document.querySelector('#avatars');
                        avatar_div.innerHTML = '';
                        result['data'].forEach(element => {
                            let profile_user;
                            if (element.photo) {
                                // profile_user = "{{ url('/storage') }}" + "/" + element.photo;
                                profile_user = "https://www.viicheck.com" + "/" + element.photo;
                            } else if (!element.photo && element.avatar) {
                                profile_user = element.avatar;
                            } else {
                                profile_user = "https://www.viicheck.com/Medilab/img/icon.png";
                            }

                            let html = `<span class="avatar">
                                            <img src="` + profile_user + `">
                                        </span>`;

                            avatar_div.insertAdjacentHTML('beforeend', html);
                        });

                        let html_2;
                        if (result['data'].length > 1) {
                            html_2 = `<span class="w-100 text_for_device">${result['data'][0].name} และอีก ${result['data'].length - 1} คนในห้องสนทนา</span>`;
                        } else if (result['data'].length === 1) {
                            html_2 = `<span class="w-100 text_for_device">${result['data'][0].name} อยู่ในห้องสนทนา</span>`;
                        } else {
                            // Handle the case where there are no users
                            html_2 = `<span class="w-100 text_for_device">ไม่มีคนอยู่ในห้องสนทนา</span>`;
                        }

                        let text_user_in_room = document.querySelector('#text_user_in_room');
                        text_user_in_room.innerHTML = '';
                        // Handle the case where there are no users
                        text_user_in_room.insertAdjacentHTML('beforeend', html_2);

                    }else{
                        let avatar_div = document.querySelector('#avatars');
                        avatar_div.innerHTML = '';

                        let text_user_in_room = document.querySelector('#text_user_in_room');
                        text_user_in_room.innerHTML = '';
                        // Handle the case where there are no users
                        let html_2 = `<span class="w-100 text_for_device">ไม่มีคนอยู่ในห้องสนทนา</span>`;
                        text_user_in_room.insertAdjacentHTML('beforeend', html_2);
                    }

                }

            });
        }

    </script>

@endsection
