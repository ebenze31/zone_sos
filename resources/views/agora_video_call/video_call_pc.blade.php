@extends('layouts.theme_video_call')

@section('content')
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        /* ปรับขนาด Container ตาม Sidebar */
        .container {
            display: flex;
            height: calc(100vh - 70px); /* ความสูงเต็มหน้าจอลบกับความสูงของแถบเมนู (เพิ่ม 10px ให้กับพื้นที่ในแถบเมนู) */
            width: 100%; /* ใช้ความกว้างเต็มหน้าจอ */
            max-width: 87%; /* จำกัดไม่ให้เกินขอบหน้าจอ */
            padding-right: var(--bs-gutter-x, 0.75rem);
            padding-left: var(--bs-gutter-x, 0.75rem);
            margin-right: auto;
            margin-left: auto;
        }

        /* เมื่อ Sidebar เปิด */
        .sidebar.open ~ .container {
            margin-left: 333px; /* ดัน Container ไปทางขวา */
            width: calc(100vw - 333px); /* ลดขนาดของ container เมื่อ Sidebar เปิด */
            max-width: calc(100vw - 333px); /* กำหนด max-width ให้เหมาะสมเมื่อ Sidebar เปิด */
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

        /* Video Wrapper */
        .video-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            transition: width 0.3s;
        }

        /* Video Container */
        .video-container {
            display: grid;
            gap: 10px;
            width: 100%;
            max-height: calc(100vh - 80px);
            /* กันไม่ให้ชนแถบเมนู */
            overflow-y: auto;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            /* ปรับขนาดอัตโนมัติ */
        }

        /* Video Card */
        .video-card {
            background-color: #000;
            border-radius: 10px;
            aspect-ratio: 16 / 9;
            width: 100%;
        }

        .video-card.hidden {
            display: none;
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
            border: 1px solid rgb(4, 80, 20);
            background-color: rgba(68, 230, 116, 0.6);
            color: #ffffff;
        }

        .toggleButton i {
            font-size: 20px;
        }
    </style>

    <div class="container" id="mainContainer">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            {{-- <button class="btn">Menu Item 1</button>
            <button class="btn">Menu Item 2</button>
            <button class="btn">Menu Item 3</button> --}}
        </div>

        <!-- Video Wrapper -->
        <div class="video-wrapper">
            <div class="video-container" id="videoContainer">
                <div class="video-card" id="video1"></div>
                {{-- <div class="video-card" id="video2"></div> --}}
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
            <button class="toggleButton mx-2" id="addVideoBtn" onclick="addVideo()"><i
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

        function addVideo() {
            const videoContainer = document.getElementById('videoContainer');
            const newVideoCard = document.createElement('div');
            newVideoCard.classList.add('video-card');
            videoContainer.appendChild(newVideoCard);

            const videoCards = document.querySelectorAll('.video-card');
            const maxVisible = 9;

            videoCards.forEach((card, index) => {
                if (index >= maxVisible) {
                    card.classList.add('hidden'); // ซ่อนวิดีโอที่เกิน 9 อัน
                } else {
                    card.classList.remove('hidden');
                }
            });

            updateGrid();
        }

        function updateGrid() {
            const videoContainer = document.getElementById('videoContainer');
            const videoCount = document.querySelectorAll('.video-card:not(.hidden)').length;

            if (videoCount === 1) {
                videoContainer.style.gridTemplateColumns = '1fr';
            } else if (videoCount === 2) {
                videoContainer.style.gridTemplateColumns = '1fr 1fr';
            } else if (videoCount <= 4) {
                videoContainer.style.gridTemplateColumns = '1fr 1fr';
            } else {
                videoContainer.style.gridTemplateColumns = '1fr 1fr 1fr';
            }
        }
    </script>
@endsection
