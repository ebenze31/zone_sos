@extends('layouts.theme_video_call')

@section('content')
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background-color: #918e8e
        }

        /* ปรับขนาด Container ตาม Sidebar */
        .container {
            display: flex;
            height: calc(100vh - 80px);
            width: 100%;
            max-width: 74%;
            padding-right: var(--bs-gutter-x, 0.75rem);
            padding-left: var(--bs-gutter-x, 0.75rem);
            margin-right: auto;
            margin-left: auto;
        }

        .main-video-container {
            width: 100%;
            height: calc(100vh - 150px);
            background-color: #222;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease-in-out;
        }

        .video-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            /* กระจายพื้นที่ให้เหมาะสม */
            width: 100%;
            height: 100%;
        }

        .video-container {
            display: grid;
            gap: 10px;
            /* width: 100%;
            height: 100%; */
            max-height: calc(100vh - 150px);
            overflow: hidden;
            justify-items: center;
            align-items: center;
            padding: 5px;
        }

        /* ปรับขนาดวิดีโอ */
        .video-container .video-card {
            aspect-ratio: 16 / 9;
            /* สัดส่วนวิดีโอ */
            border-radius: 5px;
            background-color: #4d4d4d;
            position: relative;
            width: 100%;
            max-width: 100%;
            flex-shrink: 0;
            transition: width 0.2s ease, height 0.2s ease;
        }

        /* กรณีมี 1 วิดีโอ: ขยายเต็ม */
        .video-container:has(.video-card:only-child) {
            grid-template-columns: 1fr;
        }

        /* กรณีมี 2 วิดีโอ: แบ่งเป็น 50% */
        .video-container:has(.video-card:nth-child(1):nth-last-child(2)) {
            grid-template-columns: repeat(2, 1fr);
        }

        /* กรณีมี 3 แบ่งเป็น 2 แถว */
        .video-container:has(.video-card:nth-child(1):nth-last-child(3)) {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
        }
        .video-container:has(.video-card:nth-child(1):nth-last-child(3)) .video-card:nth-child(1) {
            grid-column: 1;
        }
        .video-container:has(.video-card:nth-child(1):nth-last-child(3)) .video-card:nth-child(2) {
            grid-column: 2;
        }
        .video-container:has(.video-card:nth-child(1):nth-last-child(3)) .video-card:nth-child(3) {
            grid-column: 1 / span 2;
            justify-self: center;
            width: 50%;
            grid-row: 2;
        }

        /* กรณีมี 4 แบ่ง บน ล่าง ซ้าย ขวา*/
        .video-container:has(.video-card:nth-child(1):nth-last-child(4)) {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
        }

        /*--------- กรณีมี 5 วิดีโอ: -----------*/
        .video-container:has(.video-card:nth-child(1):nth-last-child(5)) {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 10px;
        }

        .video-container:has(.video-card:nth-child(1):nth-last-child(5)) .video-card:nth-child(1) {
            grid-column: 1;
        }
        .video-container:has(.video-card:nth-child(1):nth-last-child(5)) .video-card:nth-child(2) {
            grid-column: 2;
        }
        .video-container:has(.video-card:nth-child(1):nth-last-child(5)) .video-card:nth-child(3) {
            grid-column: 3;
        }
        .video-container:has(.video-card:nth-child(1):nth-last-child(5)) .video-card:nth-child(4) {
            grid-column: 1 / span 2;
            justify-self: center;
            width: 50%;
            grid-row: 2;
        }
        .video-container:has(.video-card:nth-child(1):nth-last-child(5)) .video-card:nth-child(5) {
            grid-column: 2 / span 2;
            justify-self: center;
            width: 50%;
            grid-row: 2;
        }

        /*-------------- กรณีมี 6 วิดีโอ: ---------------*/
        .video-container:has(.video-card:nth-child(1):nth-last-child(6)) {
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto auto;
        }

        .video-container:has(.video-card:nth-child(1):nth-last-child(n+5)) .video-card {
            width: 100%;
            /* ลดขนาดเพื่อให้พอดีกับ container */
        }

        /*---------- กรณีมี 7 วิดีโอขึ้นไป: แบ่งเป็น 3 คอลัมน์ 3 แถว ----------*/
        .video-container:has(.video-card:nth-child(1):nth-last-child(n+7)) {
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto;
        }

        .video-container:has(.video-card:nth-child(1):nth-last-child(n+7)) .video-card {
            width: 95%;
            /* ลดขนาดลงอีกเมื่อมี 7+ วิดีโอ */
        }


        /* .video-card.hidden {
                display: none;
            } */

        .video-bar {
            display: flex;
            gap: 10px;
            width: 100%;
            flex-wrap: wrap;
            /* ป้องกัน overflow */
            justify-content: center;
            padding: 10px;
            position: relative;
            /* background: rgba(0, 0, 0, 0.5); */
            z-index: 1000;
        }


        /* ปรับขนาด video card ให้อยู่ใน bar โดยไม่ให้เกินขอบ */
        .video-bar .video-card {
            width: min(150px, 15%);
            /* ปรับขนาดวิดีโออัตโนมัติ */
            height: auto;
            aspect-ratio: 3 / 2;
            /* คงอัตราส่วน */
            flex-shrink: 1;
            /* ย่อขนาดเมื่อพื้นที่ไม่พอ */
            cursor: pointer;
            transition: transform 0.2s;
            border-radius: 5px;
        }

        /* ลดขนาด video card เพิ่มเติมถ้ามีเยอะ */
        @media (max-width: 768px) {
            .video-bar .video-card {
                width: min(120px, 18%);
            }
        }

        @media (max-width: 480px) {
            .video-bar .video-card {
                width: min(100px, 20%);
            }
        }

        .video-bar .video-card:hover {
            transform: scale(1.1);
        }

        /* เมื่อ Sidebar เปิด */
        .sidebar.open~.container .video-bar {
            width: 100%;
        }

        /* เมื่อ Sidebar เปิด */
        .sidebar.open~.container {
            margin-left: 333px;
            /* ดัน Container ไปทางขวา */
            width: calc(100vw - 333px);
            /* ลดขนาดของ container เมื่อ Sidebar เปิด */
            max-width: calc(100vw - 333px);
            /* กำหนด max-width ให้เหมาะสมเมื่อ Sidebar เปิด */
        }


        /* Sidebar */
        .sidebar {
            position: fixed;
            left: -333px;
            top: 0;
            width: 333px;
            height: 100%;
            background-color: #444;
            transition: left 0.4s;
            z-index: 10;
        }

        .sidebar.open {
            left: 0;
        }

        /* ปรับ Container เมื่อ Sidebar เปิด */
        .container.shifted {
            width: calc(100% - 333px);
            margin-left: 333px;
        }

        /* Controls Bar */
        .controls-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2000;
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

        /*-------- ปุ่มซ่อน/แสดง video-bar ----------*/

        /* เมื่อ Video Bar ถูกซ่อน */
        .video-bar.hidden {
            /* margin-bottom: -150px; */
            display: none;
        }

       /* ปุ่มซ่อน/แสดง (เปิดอยู่) */
        .toggle-video-bar-btn {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.8);
            color: black;
            border: none;
            padding: 5px 10px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1500;
            transition: top 0.3s ease-in-out, bottom 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        /* ปุ่มปิด (ซ่อน video-bar อยู่) */
        .toggle-video-bar-btn-close {
            position: absolute;
            bottom: 4.5rem; /* ซ่อน video-bar ให้ปุ่มอยู่ต่ำลงมา */
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.8);
            color: black;
            border: none;
            padding: 5px 10px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1500;
            transition: top 0.3s ease-in-out, bottom 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        /* ไอคอนหมุนขึ้น */
        .toggle-video-bar-btn i {
            font-size: 16px;
            transition: transform 0.3s ease-in-out;
        }

        .toggle-video-bar-btn-close i {
            transform: rotate(180deg);
            transition: transform 0.3s ease-in-out;
        }


    </style>

    <div class="container" id="mainContainer">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">

        </div>

        <div class="video-wrapper">
            <!-- Video Container -->
            <div class="video-container" id="video-container" data-count="1"></div>

            <!-- ปุ่มซ่อน/แสดง Video Bar -->
            <button class="toggle-video-bar-btn d-none" id="toggleVideoBarBtn" onclick="toggleVideoBar()">
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <!-- Video Bar (Bottom) -->
            <div class="video-bar" id="video-bar">

            </div>
        </div>


    </div>

    <!-- Controls Bar -->
    <div class="controls-bar">
        <div class="left">
            <button class="toggleButton mx-2" id="sidebarBtn" onclick="toggleSidebar()">
                <i class="fa-solid fa-sidebar"></i>
            </button>
        </div>
        <div class="center">
            <button class="toggleButton mx-2" id="muteBtn" onclick="toggleMute()"><i
                    class="fa-regular fa-microphone"></i></button>
            <button class="toggleButton mx-2" id="cameraBtn" onclick="toggleCamera()"><i
                    class="fa-regular fa-camera"></i></button>
        </div>
        <div class="right">
            <button class="toggleButton mx-2" id="addVideoBtn" onclick="createAndAttachCustomDiv()"><i
                    class="fa-regular fa-plus"></i></button>
        </div>
    </div>

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
            return '#' + Math.floor(Math.random() * 16777215).toString(16);
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
            let randomColor = getRandomColor();
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
                toggleBtn.style.bottom = '4.5rem';
            }
        }


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

        window.addEventListener('DOMContentLoaded', positionToggleButton);
        window.addEventListener('resize', positionToggleButton);



        //======================================= จบโยกย้าย Div   ==================================================//

    </script>
@endsection
