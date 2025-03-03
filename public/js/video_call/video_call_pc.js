
function btn_toggle_mic_camera(videoTrack,audioTrack,bg_local){ // สำหรับ สร้างปุ่มที่ใช้ เปิด-ปิด กล้องและไมโครโฟน

    const div_for_AudioButton = document.querySelector('#div_for_AudioButton');
    const div_for_VideoButton = document.querySelector('#div_for_VideoButton');

    const muteButton = document.createElement('button');
        muteButton.type = "button";
        muteButton.id = "muteAudio";
        muteButton.classList.add('audio_button');
        muteButton.innerHTML = '<i class="fa-solid fa-microphone"></i>';

        div_for_AudioButton.appendChild(muteButton);

    //สร้างปุ่ม เปิด-ปิด วิดีโอ
    const muteVideoButton = document.createElement('button');
        muteVideoButton.type = "button";
        muteVideoButton.id = "muteVideo";
        muteVideoButton.classList.add('video_button');
        muteVideoButton.innerHTML = '<i class="fa-solid fa-video"></i>';

        div_for_VideoButton.appendChild(muteVideoButton);

    muteButton.onclick = async function() {
        if (isAudio == true) {
            // Update the button text.
            document.getElementById(`muteAudio`).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #f00505; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
            document.getElementById('div_for_AudioButton').classList.remove('btnSpecial_unmute');
            document.getElementById('div_for_AudioButton').classList.add('btnSpecial_mute');
            // Mute the local video.
            channelParameters.localAudioTrack.setEnabled(false);

            // เปลี่ยน icon microphone ให้เป็นปิด ใน divVideo_
            document.getElementById(`mic_local`).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #f00505; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';

            // เปลี่ยน icon microphone ให้เป็น ปิด ใน sidebar ด้านขวา
            document.getElementById(`icon_microphone_in_sidebar`).innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #e60000; --fa-secondary-color: #000000; --fa-secondary-opacity: 1; display: inline-block; z-index: 6;"></i>';

            isAudio = false;

        } else {
            // Update the button text.
            document.getElementById(`muteAudio`).innerHTML = '<i class="fa-solid fa-microphone"></i>';
            document.getElementById('div_for_AudioButton').classList.add('btnSpecial_unmute');
            document.getElementById('div_for_AudioButton').classList.remove('btnSpecial_mute');
            // Unmute the local video.
            channelParameters.localAudioTrack.setEnabled(true);

            // เปลี่ยน icon microphone ให้เป็นเปิด ใน divVideo_
            document.getElementById(`mic_local`).innerHTML = '<i class="fa-solid fa-microphone"></i>';

            // เปลี่ยน icon microphone ให้เป็น เปิด ใน sidebar ด้านขวา
            document.getElementById(`icon_microphone_in_sidebar`).innerHTML = '<i class="fa-solid fa-microphone" style="display: inline-block; z-index: 6;"></i>';

            isAudio = true;

            // SoundTest(); //เช็คไมค์
        }
    }

    muteVideoButton.onclick = async function() {
        if (isVideo == true) {
            // Update the button text.
            document.getElementById(`muteVideo`).innerHTML = '<i class="fa-duotone fa-video-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
            document.getElementById('div_for_VideoButton').classList.remove('btnSpecial_unmute');
            document.getElementById('div_for_VideoButton').classList.add('btnSpecial_mute');
            // Mute the local video.
            channelParameters.localVideoTrack.setEnabled(false);
            muteVideoButton.classList.add('btn-disabled');
            // เปลี่ยน icon camera ให้เป็นปิด ใน divVideo_
            document.getElementById(`camera_local`).innerHTML = '<i class="fa-duotone fa-video-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';

            // แสดงโปรไฟล์ ตอนปิดกล้อง
            document.querySelector('.profile-input-output-local').classList.remove('d-none');

            changeBgColor(bg_local);

            isVideo = false;

        } else {
            // Update the button text.
            document.getElementById(`muteVideo`).innerHTML = '<i class="fa-solid fa-video"></i>';
            document.getElementById('div_for_VideoButton').classList.add('btnSpecial_unmute');
            document.getElementById('div_for_VideoButton').classList.remove('btnSpecial_mute');
            // Unmute the local video.
            channelParameters.localVideoTrack.setEnabled(true);
            // muteVideoButton.classList.add('btn-success');
            muteVideoButton.classList.remove('btn-disabled');

            // เปลี่ยน icon camera ให้เป็นเปิด ใน divVideo_
            document.getElementById(`camera_local`).innerHTML = '<i class="fa-solid fa-video"></i>';

            // ซ่อนโปรไฟล์ ตอนเปิดกล้อง
            document.querySelector('.profile-input-output-local').classList.add('d-none');

            isVideo = true;

            if(document.querySelector('.imgdivLocal')){
                document.querySelector('.imgdivLocal').remove();
            }
        }
    }

}

// สำหรับ Div ต่างๆของ Local
function create_element_localvideo_call(localPlayerContainer ,name_local ,type_local ,profile_local, bg_local) {


        console.log("create_element_localvideo_call Here");
        console.log(name_local);
        console.log(type_local);
        // ใส่เนื้อหาใน divVideo ที่ถูกใช้โดยผู้ใช้
        if(document.getElementById('videoDiv_' + localPlayerContainer.id)) {
            var divVideo = document.getElementById('videoDiv_' + localPlayerContainer.id);
        }else{
            var divVideo = document.createElement('div');
            divVideo.setAttribute('id','videoDiv_' + localPlayerContainer.id);
            divVideo.setAttribute('class','video-card');
            divVideo.style.backgroundColor = bg_local;
            divVideo.style.position = 'relative';
        }

        //======= สร้างปุ่มสถานะ && รูปโปรไฟล์ ==========

        // สร้างแท็ก <img> สำหรับรูปโปรไฟล์
        let ProfileInputOutputDiv = document.createElement("div");
            ProfileInputOutputDiv.className = "profile-input-output-local";
            ProfileInputOutputDiv.setAttribute('style','z-index: 1; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);');

        let profileImage = document.createElement('img');
            profileImage.setAttribute('src', profile_local); // เปลี่ยน 'ลิงก์รูปโปรไฟล์' เป็น URL ของรูปโปรไฟล์ของผู้ใช้
            profileImage.setAttribute('alt', 'โปรไฟล์');
            // profileImage.setAttribute('style', 'border-radius: 50%; width: 100px; height: 100px; max-width: 100%; max-height: 30%;');
            profileImage.setAttribute('class', 'profile_image');


        ProfileInputOutputDiv.appendChild(profileImage);

        // เพิ่มแท็ก แสดงเสียงไมค์เวลาพูด
        let statusMicrophoneOutput = document.createElement("div");
        statusMicrophoneOutput.id = "statusMicrophoneOutput_local";
        statusMicrophoneOutput.className = "status-sound-output d-none";
        statusMicrophoneOutput.setAttribute('style','z-index: 1;');

        let soundDiv = document.createElement("div");
            soundDiv.id = "sound_local";
            soundDiv.className = "sound";
            soundDiv.innerHTML = '<i class="fa-sharp fa-solid fa-volume fa-beat-fade" style="color: #ffffff;"></i>';

        statusMicrophoneOutput.appendChild(soundDiv);

        // เพิ่มแท็ก ไมค์และกล้อง

        let statusInputOutputDiv = document.createElement("div");
            statusInputOutputDiv.className = "status-input-output";
            // statusInputOutputDiv.setAttribute('style','z-index: 4;','pointer-events: none;');

        let micDiv = document.createElement("div");
            micDiv.id = "mic_local";
            micDiv.className = "mic";
            micDiv.innerHTML = '<i class="fa-solid fa-microphone"></i>';

        let cameraDiv = document.createElement("div");
            cameraDiv.id = "camera_local";
            cameraDiv.className = "camera";
            cameraDiv.innerHTML = '<i class="fa-solid fa-video"></i>';

        statusInputOutputDiv.appendChild(micDiv);
        statusInputOutputDiv.appendChild(cameraDiv);

        // เพิ่มแท็ก ชื่อและสถานะ

        let infomationUserDiv = document.createElement("div");
            infomationUserDiv.id = "infomation-user-local";
            infomationUserDiv.className = "infomation-user";
            infomationUserDiv.setAttribute('style','z-index: 1;');

        let nameUserVideoCallDiv = document.createElement("div");
            nameUserVideoCallDiv.id = "name_local_video_call";
            nameUserVideoCallDiv.className = "name-user-video-call";
            nameUserVideoCallDiv.innerHTML = '<p class="m-0 text-white float-end">'+ name_local +'</p>';

        let br = document.createElement('br'); // สร้าง <br> tag

        let roleUserVideoCallDiv = document.createElement("div");
            roleUserVideoCallDiv.id = "role_local_video_call";
            roleUserVideoCallDiv.className = "role-user-video-call";
            roleUserVideoCallDiv.innerHTML = '<small class="d-block float-end">'+type_local+'</small>';

        infomationUserDiv.appendChild(nameUserVideoCallDiv);
        infomationUserDiv.appendChild(br);
        infomationUserDiv.appendChild(roleUserVideoCallDiv);

        // สร้าง div โปร่งใส
        let transparentDiv = document.createElement("div");
        transparentDiv.classList.add("transparent-div"); // เพิ่มคลาส CSS

        // เพิ่ม div ด้านในลงใน div หลัก
        divVideo.appendChild(ProfileInputOutputDiv);
        divVideo.appendChild(statusMicrophoneOutput);
        divVideo.appendChild(statusInputOutputDiv);
        divVideo.appendChild(infomationUserDiv);
        divVideo.appendChild(transparentDiv);
        //======= จบการ สร้างปุ่มสถานะ ==========

        // เพิ่ม div หลักลงใน div รวม
        divVideo.append(localPlayerContainer);

        let container_user_video_call = document.querySelector("#video-container");
        container_user_video_call.append(divVideo);

        divVideo.addEventListener("click", function() {
            console.log("divVideo");
            handleClick(divVideo);
        });

        transparentDiv.addEventListener("click", function() {
            let id_agora_create = localPlayerContainer.id;
            console.log("transparentDiv Clicked!! Unpublished");
            console.log(id_agora_create);
            let clickvideoDiv = document.querySelector('#videoDiv_'+id_agora_create);
            clickvideoDiv.click();

            let userVideoCallBar = document.querySelector(".video-bar");
            let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".video-card");

            if (customDivsInUserVideoCallBar.length > 0) {
                moveAllDivsToContainer();
            } else {
                moveDivsToUserVideoCallBar(clickvideoDiv);
            }
        });

}

// สำหรับ Div ต่างๆของ Remote ตอน published
function create_element_remotevideo_call(remotePlayerContainer, name_remote , type_remote , bg_remote, user) {
    if(remotePlayerContainer.id){
        console.log("remotePlayerContainer");
        console.log(remotePlayerContainer);

        let containerId = remotePlayerContainer.id;

        let divVideo_New = document.createElement('div');
        divVideo_New.setAttribute('id','videoDiv_' + containerId);
        divVideo_New.setAttribute('class','custom-div');
        divVideo_New.setAttribute('style', 'background-color:' + bg_remote);

        //======= สร้างปุ่มสถานะ && รูปโปรไฟล์ ==========

        // เพิ่มแท็ก แสดงเสียงไมค์เวลาพูด
        let statusMicrophoneOutput = document.createElement("div");
        statusMicrophoneOutput.id = "statusMicrophoneOutput_remote_"+containerId;
        statusMicrophoneOutput.className = "status-sound-output d-none";
        statusMicrophoneOutput.setAttribute('style','z-index: 1;');

        let soundDiv = document.createElement("div");
            soundDiv.id = "sound_remote_" + containerId;
            soundDiv.className = "sound";
            soundDiv.innerHTML = '<i class="fa-sharp fa-solid fa-volume fa-beat-fade" style="color: #ffffff;"></i>';

        statusMicrophoneOutput.appendChild(soundDiv);

        // เพิ่มแท็ก ไมค์และกล้อง
        let statusInputOutputDiv = document.createElement("div");
            statusInputOutputDiv.className = "status-input-output";
            // statusInputOutputDiv.setAttribute('style','z-index: 4;','pointer-events: none;');

        let micDiv = document.createElement("div");
            micDiv.id = "mic_remote_"+containerId;
            micDiv.className = "mic";
            if(user.hasAudio == false){
                micDiv.innerHTML = '<i class="fa-duotone fa-microphone-slash" style="--fa-primary-color: #f00505; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
            }else{
                micDiv.innerHTML = '<i class="fa-solid fa-microphone"></i>';
            }

        let cameraDiv = document.createElement("div");
            cameraDiv.id = "camera_remote_"+containerId;
            cameraDiv.className = "camera";
            if(user.hasVideo == false){
                cameraDiv.innerHTML = '<i class="fa-duotone fa-video-slash" style="--fa-primary-color: #ff0000; --fa-secondary-color: #ffffff; --fa-secondary-opacity: 1;"></i>';
            }else{
                cameraDiv.innerHTML = '<i class="fa-solid fa-video"></i>';
            }

        // สร้างรายการอุปกรณ์ส่งข้อมูลและเพิ่มลงในรายการ
        // let settingDiv = document.createElement("label");
        //     settingDiv.className = "settings dropdown_volume_label";
        //     settingDiv.innerHTML = createHtmlInputbar(containerId);

        statusInputOutputDiv.appendChild(micDiv);
        statusInputOutputDiv.appendChild(cameraDiv);
        // statusInputOutputDiv.appendChild(settingDiv);

        // เพิ่มแท็ก ชื่อและสถานะ
        let infomationUserDiv = document.createElement("div");
            infomationUserDiv.className = "infomation-user";
            infomationUserDiv.setAttribute('style','z-index: 1;');

        let nameUserVideoCallDiv = document.createElement("div");
            nameUserVideoCallDiv.className = "name-user-video-call";
            nameUserVideoCallDiv.innerHTML = '<p class="m-0 text-white float-end">'+name_remote+'</p>';

        let br = document.createElement('br'); // สร้าง <br> tag

        let roleUserVideoCallDiv = document.createElement("div");
            roleUserVideoCallDiv.className = "role-user-video-call";
            roleUserVideoCallDiv.innerHTML = '<small class="d-block float-end">'+type_remote+'</small>';

        infomationUserDiv.appendChild(nameUserVideoCallDiv);
        infomationUserDiv.appendChild(br);
        infomationUserDiv.appendChild(roleUserVideoCallDiv);

        // เพิ่ม div ด้านในลงใน div หลัก
        divVideo_New.appendChild(statusMicrophoneOutput);
        divVideo_New.appendChild(statusInputOutputDiv);
        divVideo_New.appendChild(infomationUserDiv);

        //======= จบการ สร้างปุ่มสถานะ ==========

        divVideo_New.append(remotePlayerContainer);

        // หา div เดิมที่ต้องการแทนที่
        let oldDiv = document.getElementById("videoDiv_"+ containerId);

        // เพิ่ม div ใหม่ลงใน div หลัก หรือ div bar
        let userVideoCallBar = document.querySelector(".user-video-call-bar");
        let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".custom-div");
        let container_user_video_call = document.querySelector("#container_user_video_call");

        // ตรวจสอบว่าเจอ div เดิมหรือไม่
        if (oldDiv) {
            // ใช้ parentNode.replaceChild() เพื่อแทนที่ div เดิมด้วย div ใหม่
            oldDiv.parentNode.replaceChild(divVideo_New, oldDiv);
        } else {
            if (customDivsInUserVideoCallBar.length > 0) {
                userVideoCallBar.append(divVideo_New);
            } else {
                container_user_video_call.append(divVideo_New);
            }
        }

        // คลิ๊ก div ให้เปลี่ยนขนาด
        divVideo_New.addEventListener("click", function() {
            handleClick(divVideo_New);
        });

        remotePlayerContainer.addEventListener("click", function() {
            console.log("remotePlayerContainer Click ---->");
            let id_agora_create = remotePlayerContainer.id;
            console.log(id_agora_create);
            let clickvideoDiv = document.querySelector('#videoDiv_'+id_agora_create);
            clickvideoDiv.click();

            let userVideoCallBar = document.querySelector(".user-video-call-bar");
            let customDivsInUserVideoCallBar = userVideoCallBar.querySelectorAll(".custom-div");

            if (customDivsInUserVideoCallBar.length > 0) {
                moveAllDivsToContainer();
            } else {
                moveDivsToUserVideoCallBar(clickvideoDiv);
            }
        });

    }else{
        console.log("================ สร้าง divVideo_New remote ไม่สำเร็จ =================");
    }
}

function create_profile_in_sidebar_local_only(user_data , name , type , profile_pic){
    let sidebar_profile =
        `
        <div class="col-12 row">
            <div class="col-2 my-auto">
                <img src="`+profile_pic+`" alt="Profile Picture" class="profile-picture">
            </div>
            <div class="col-7 my-auto">
                <div class="profile-info">
                    <p>(Me) `+name+`</p>
                </div>
            </div>
            <div class="col-3 my-auto ">
                <div id="icon_microphone_in_sidebar">
                    <i class="fa-solid fa-microphone" style="display: inline-block; z-index: 6;"></i>
                </div>
            </div>
        </div>

        `;

        // <label class="dropdown_volume_label">
        //     <i class="fa-solid fa-volume-high" style="display: inline-block; z-index: 6;" onclick="closeCheckboxAllexceptThis(`+user_data.uid+`)"></i>
        //     <input type="checkbox" class="dd-input" id="checkbox_`+user_data.uid+`">
        //     <ul class="dd-menu">
        //         <li>
        //             <p class="mb-0" style="cursor: default; color: #000000; font-size: 14px !important;">ระดับเสียง</p>
        //             <input style="z-index: 4;" type="range" id="localAudioVolume" min="0" max="1000" value="100" class="w-100" onChange="onChangeVolumeRemote(100);">
        //         </li>
        //     </ul>
        // </label>

    return sidebar_profile;
}


