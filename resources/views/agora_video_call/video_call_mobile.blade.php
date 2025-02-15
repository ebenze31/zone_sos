<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Zone_SOS') }}</title>
    <link rel="shortcut icon" href="{{ asset('/img/logo/logo_x-icon.png') }}" type="image/x-icon" />
    <link href="https://kit-pro.fontawesome.com/releases/v6.4.2/css/pro.min.css" rel="stylesheet">
    <link href="{{ asset('video_call_theme/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            background: rgba(56, 55, 55, 0.5);
            overflow: hidden;
        }

        .container {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: rgba(56, 55, 55, 0.5);
            padding-top: 60px;
            /* ห่างจาก header */
            padding-bottom: 60px;
            /* ห่างจาก bottom-bar */
        }

        .header,
        .bottom-bar{
            position: absolute;
            left: 0;
            right: 0;
            background: rgba(56, 55, 55, 0.5);
            color: white;
            z-index: 10;
        }

        .header {
            top: 0;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(51, 51, 51, 0.7);
            /* Transparency */
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2000;
        }

        .toggleButton_header {
            border-radius: 10px;
            width: 45px !important;
            height: 45px !important;
            border: 1px solid rgb(88, 88, 88);
            background-color: rgba(138, 138, 138, 0.6);
            color: #ffffff;
        }

        .toggleButton_header i {
            font-size: 20px;
        }

        .toggleButton {
            border-radius: 50%;
            width: 45px !important;
            height: 45px !important;
            border: 1px solid rgb(88, 88, 88);
            background-color: rgba(138, 138, 138, 0.6);
            color: #ffffff;
        }

        .toggleButton i {
            font-size: 20px;
        }

        .fadeDiv {
            position: fixed;
            bottom: -100%;
            /* เริ่มต้นให้หลบออกจากหน้าจอ */
            left: 0;
            right: 0;
            max-height: 50%;
            max-width: 100%;
            opacity: 0;
            overflow: auto;
            background-color: #f3f5fa;
            border-radius: 5px;
            transition: opacity 0.3s, bottom 0.3s ease;
            /* ใช้ transition สำหรับ opacity และ bottom */
        }

        /* Animation for the fade-in */
        .fadeDiv.show {
            opacity: 1;
            bottom: 0;
            /* แสดงจากล่างขึ้นมา */
        }


        /* Video container grid */
        .video-container {
            display: grid;
            grid-gap: 10px;
            padding: 10px;
            flex-grow: 1;
            z-index: 5;
            width: 100%;
            height: 100%;
        }

        .video-container[data-videos="1"] {
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
        }

        .video-container[data-videos="2"] {
            grid-template-columns: 1fr;
            grid-template-rows: 1fr 1fr;
        }

        .video-container[data-videos="3"] {
            grid-template-columns: 1fr;
            grid-template-rows: 1fr 1fr 1fr;
        }

        .video-container[data-videos="4"] {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
        }

        .video-container[data-videos="3"] .video-track:nth-child(3) {
            grid-column: 1 / -1;
        }

        .video-track {
            border-radius: 10px;
            background: black;
            position: relative;
            min-height: 100px;
            min-width: 100px;
            cursor: pointer;
        }

        .video-bar {
            position: fixed;
            bottom: 4rem; /* ให้อยู่เหนือ bottom-bar */
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: rgba(56, 55, 55, 0.5);
            z-index: 1000; /* ทำให้ video-bar อยู่ด้านบน */
        }

        .video-bar .video-track {
            width: 23%;
            height: 100px;
            margin: 0 5px;
            background: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="fadeDiv" id="dataDiv" style="display: none; z-index: 5000;">
            <div class="card m-4">
                <div class="card-body">
                    content
                </div>
            </div>


        </div>

        <!-- Header -->
        <div class="header">
            <button class="toggleButton_header mx-2" id="fadeButton"><i class="fa-solid fa-sidebar"></i></button>
        </div>

        <!-- Video container -->
        <div class="video-container" id="videoContainer" data-videos="1">
            {{-- <div class="video-track" id="video1" onclick="handleVideoClick(this)"></div> --}}
        </div>

        <!-- Video Bar -->
        <div class="video-bar" id="videoBar"></div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div class="left">
            </div>
            <div class="center">
                <button class="toggleButton mx-2" id="muteBtn" onclick="toggleMicrophone()"><i
                    class="fa-regular fa-microphone"></i></button>
                <button class="toggleButton mx-2" id="cameraBtn" onclick="toggleCamera()"><i
                    class="fa-regular fa-camera"></i></button>
                <button class="toggleButton mx-2" id="sidebarBtn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-phone"></i>
                </button>
                <button class="toggleButton mx-2" id="addVideoBtn" onclick="addVideo()"><i
                        class="fa-regular fa-plus"></i></button>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <script>
        let fadeButton = document.getElementById("fadeButton");
        let dataDiv = document.getElementById("dataDiv");

        fadeButton.addEventListener("click", () => {
            if (!dataDiv.classList.contains("show")) {
                dataDiv.style.display = "block";
                setTimeout(() => {
                    dataDiv.classList.add("show"); // เพิ่มคลาส .show เมื่อแสดง
                }, 10);
            } else {
                dataDiv.classList.remove("show"); // ลบคลาส .show เมื่อปิด
                setTimeout(() => {
                    dataDiv.style.display = "none";
                }, 500); // รอให้แอนิเมชั่นเสร็จสิ้นก่อนที่จะซ่อน
            }
        });

        function addVideo() {
            const container = document.getElementById("videoContainer");

            let newVideo = document.createElement("div");
            newVideo.classList.add("video-track");
            newVideo.textContent = "User " + (container.childElementCount + 1);

            newVideo.addEventListener("click", function () {
                handleClick(newVideo);
            });

            container.appendChild(newVideo);

            checkchild();
        }


        function moveDivsToUserVideoCallBar(clickedDiv) {
            let container = document.getElementById("videoContainer"); // ปรับจาก container_user_video_call
            let customDivs = container.querySelectorAll(".video-track"); // ปรับจาก .custom-div
            let userVideoCallBar = document.querySelector(".video-bar"); // ปรับจาก .user-video-call-bar

            if (customDivs.length > 1) {
                document.querySelector(".user-video-call-container").classList.remove("d-none");

                customDivs.forEach(function(div) {
                    if (div !== clickedDiv) {
                        // ตรวจสอบว่า div นี้มีคลาส "information-user" หรือไม่
                        let infomationUser = div.querySelector(".information-user");
                        if (infomationUser) {
                            infomationUser.classList.add("d-none");
                        }

                        if (!isInUserVideoCallBar(div)) {
                            userVideoCallBar.appendChild(div);
                        }
                    }
                });

                // ย้าย div ที่ถูกคลิกไปยังตำแหน่งที่ถูกคลิก
                if (!isInUserVideoCallBar(clickedDiv)) {
                    let infomationUser = clickedDiv.querySelector(".information-user");
                    if (infomationUser) {
                        infomationUser.classList.remove("d-none");
                    }

                    container.appendChild(clickedDiv);
                }

                type_advice = "dec";
                showTextAdvice(type_advice);
            } else {
                type_advice = "inc";
                showTextAdvice(type_advice);
            }
        }

        function moveAllDivsToContainer() {
            let container = document.getElementById("videoContainer"); // ปรับจาก container_user_video_call
            let userVideoCallBar = document.querySelector(".video-bar"); // ปรับจาก .user-video-call-bar
            let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".video-track"); // ปรับจาก .custom-div
            document.querySelector(".user-video-call-container").classList.add("d-none");

            customDivsInUserVideoCallBar.forEach(function(div) {
                let infomationUser = div.querySelector(".information-user");
                if (infomationUser) {
                    infomationUser.classList.remove("d-none");
                }
                container.appendChild(div);
            });

            document.querySelector(".btn-video-call-container").classList.remove("d-none");

            type_advice = "inc";
            showTextAdvice(type_advice);

            checkchild();
        }

        function showTextAdvice(type) {
            let div_advice = document.querySelector('#adive_text_video_call');
            let container = document.getElementById("videoContainer"); // ปรับจาก container_user_video_call
            let customDivs = container.querySelectorAll(".video-track"); // ปรับจาก .custom-div
            let userVideoCallBar = document.querySelector(".video-bar"); // ปรับจาก .user-video-call-bar

            div_advice.innerHTML = '';

            if (type == "inc") {
                div_advice.innerHTML =
                    '<p style="font-size: 36px;" class="font-14 text-danger">กดที่จอวิดีโอเพื่อขยาย*</p>';
            } else {
                div_advice.innerHTML =
                    '<p style="font-size: 36px;" class="font-14 text-danger">กดที่จอวิดีโอเพื่อกลับขนาดปกติ*</p>';
            }
        }

        function checkchild() {
    const container = document.querySelector("#videoContainer");
    const customDivs = container.querySelectorAll(".video-track");
    const childCount = customDivs.length;

    var existingStyle = document.querySelector("#custom-style");
    if (existingStyle) {
        existingStyle.remove();
    }

    var x = document.createElement("STYLE");
    x.id = "custom-style";

    let cssText = `
        #videoContainer {
            display: grid;
            gap: 10px;
            justify-content: center;
            align-items: center;
            height: calc(100% - 120px); /* ลดขนาดลงเพื่อให้เหลือที่ว่างสำหรับ video-bar */
            width: 100%;
        }

        #videoContainer .video-track {
            width: 100%;
            background: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            border-radius: 10px;
        }


    `;

    // ซ่อนวิดีโอที่เกิน 4 ตัว
    customDivs.forEach((div, index) => {
        if (index >= 4) {
            div.style.display = "none";
        } else {
            div.style.display = "flex";
        }
    });

    if (childCount === 1) {
        cssText += `
            #videoContainer {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr;
            }
            #videoContainer .video-track {
                aspect-ratio: 16/9;
            }
        `;
    } else if (childCount === 2) {
        cssText += `
            #videoContainer {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr 1fr;
            }
            #videoContainer .video-track {
                aspect-ratio: 16/9;
            }
        `;
    } else if (childCount === 3) {
        cssText += `
            #videoContainer {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(3, 1fr);
            }
            #videoContainer .video-track {
                aspect-ratio: 16/9;
            }
        `;
    } else {
        cssText += `
            #videoContainer {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(2, 1fr);
            }
            #videoContainer .video-track {
                aspect-ratio: 3/4;
            }
        `;
    }

    var t = document.createTextNode(cssText);
    x.appendChild(t);
    document.head.appendChild(x);
}


        function handleClick(clickedDiv) {
            let userVideoCallBar = document.querySelector(".user-video-call-bar");
            let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".custom-div");

            if (customDivsInUserVideoCallBar.length > 0) {
                moveAllDivsToContainer();
            } else {
                moveDivsToUserVideoCallBar(clickedDiv);
            }
        }


        document.querySelector(".video-bar").addEventListener("click", function(e) { // ปรับจาก .user-video-call-bar
            if (e.target.classList.contains("video-track")) { // ปรับจาก .custom-div
                handleClick(e.target);
            }
        });


        function toggleMicrophone() {
            // Logic to toggle microphone
        }

        function toggleCamera() {
            // Logic to toggle camera
        }

        function endCall() {
            // Logic to end the call
        }

        function openSettings() {
            // Logic to open settings
        }
    </script>
</body>

</html>
