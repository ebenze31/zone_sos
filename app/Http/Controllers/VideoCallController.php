<?php

namespace App\Http\Controllers;

use App\Models\Zone_agora_chat;
use App\Models\Zone_data_officer_command;
use App\Models\Zone_data_operating_officer;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Willywes\AgoraSDK\RtcTokenBuilder;

class VideoCallController extends Controller
{
    public function before_video_call(Request $request){
        $user = Auth::user();

        // $sos_id = $request->sos_id; //รหัสขอความช่วยเหลือ
        $sos_id = '1'; //รหัสขอความช่วยเหลือ
        $type = "zone_sos"; // ใช้บอกประเภทขอความช่วยเหลือ ตัวอย่าง sos_1669 user_sos_1669 zone_sos

        $consult_doctor_id = 123; // ยังไม่ใช้
        $request->user_to_call;

        //ตรวจอุปกรณ์
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        // ตรวจสอบชนิดของอุปกรณ์
        if (preg_match('/android/i', $userAgent)) {
            $type_device = "mobile_video_call";
            $type_brand = "android";
        }
        else if (preg_match('/iPad|iPhone|iPod/', $userAgent) && !strpos($userAgent, 'MSStream')) {
            $type_device = "mobile_video_call";
            $type_brand = "ios";
        }
        else{
            $type_device = "pc_video_call";
            $type_brand = "pc";
        }

        return view('agora_video_call/before_video_call', compact('user','sos_id','type','type_brand','type_device'));
    }

    public function video_call_pc(Request $request,$type ,$sos_id){
        $type = "zone_sos";
        $agoraAppId = config('agora.app_id');
        $agoraAppCertificate = config('agora.app_certificate');

        return view('agora_video_call/video_call_pc', compact('type','sos_id','agoraAppId','agoraAppCertificate'));
    }

    public function video_call_mobile(Request $request){
        $type = "zone_sos";

        return view('agora_video_call/video_call_mobile', compact('type'));

    }

    function check_user_in_room(Request $request)
    {
        $sos_id = $request->sos_id;
        $type_sos = $request->type;

        $user_in_room = [];
        $user_in_room['data'] = 'ไม่มีข้อมูล';

        if($type_sos == 'zone_sos'){
            $type_text = "zone_sos";
        }else{
            $type_text = "sos_map";
        }

        $agora_chat = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' , $type_text)->first();

        if ( empty($agora_chat) ){ // ถ้ายังไม่มีข้อมูล ให้สร้างข้อมูลเบื้องต้นก่อนเพื่อใช้เข้าห้องวิดีโอคอล
            $data_create = [];
            $data_create['room_for'] = $type_text; //สร้างตามประเภท
            $data_create['sos_id'] = $sos_id;

            Zone_agora_chat::create($data_create);
            $agora_chat = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' ,$type_text)->first();
        }

        $user_in_room['data_agora'] = $agora_chat;

        if( !empty($agora_chat->member_in_room) ){ //หาข้อมูล user จาก id คนที่อยู่ในห้องวิดีโอคอล
            $data_member_in_room = $agora_chat->member_in_room;
            $check_array_user = json_decode($data_member_in_room, true);

            if( !empty($check_array_user) ){
                $data_users = [];
                for ($ii=0; $ii < count($check_array_user); $ii++) {
                    $data_users[] = User::where('id' , $check_array_user[$ii])->first();
                }
                $user_in_room['data'] = $data_users;
            }
        }

        if ($user_in_room['data'] != "ไม่มีข้อมูล") { //เช็คคนในห้อง ก่อนเข้า
            if(empty($user_in_room['data']) || count($user_in_room['data']) < 4) // ถ้ามีน้อยกว่า 4 คน
            {
                $user_in_room['status'] = "ok";
            }else{
                $user_in_room['status'] = "no";
            }
        } else { // ไม่มีคนในห้อง
            $user_in_room['status'] = "ok";
        }

        return $user_in_room;
    }

    public function token(Request $request)
    {

        if (!empty($request->appId) && !empty($request->appCertificate)) {
            $appID = $request->appId;
            $appCertificate = $request->appCertificate;
        } else {
            $appID = env('AGORA_APP_ID');
            $appCertificate = env('AGORA_APP_CERTIFICATE');
        }

        $data_user = User::where('id' ,$request->user_id)->first();

        $user = $data_user->id;
        $channelName = $request->type . $request->sos_id;

        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 5;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        $agora_data = [
            'token' => $token,
            'privilegeExpiredTs' => $privilegeExpiredTs,
            'channel' => $channelName,
        ];

        // $agora_data = [
        //     'token' => "token",
        //     'privilegeExpiredTs' => "privilegeExpiredTs",
        //     'channel' => "channel",
        // ];
        return $agora_data;
    }

    function get_local_data(Request $request){
        $user_id = $request->user_id;
        $type = $request->type;
        $sos_id = $request->sos_id;

        if($type == 'zone_sos'){
            // $data_sos = Sos_help_center::where('id',$sos_id)->first();
        }

        $local_data = User::where('id',$user_id)->first();

        $data = [];
        $data['photo'] = $local_data->photo;
        $data['avatar'] = $local_data->avatar;

        if($type == 'zone_sos'){
            $data_command = Zone_data_officer_command::where('user_id',$user_id)->first();
            $data_officer = Zone_data_operating_officer::where('user_id',$user_id)->first();

            if(!empty($data_command->name_officer_command)){
                $data['user_type'] = "ทดสอบ";
                $data['name_user'] = "ทดสอบ";
                // $data['unit'] = '';
            }elseif(!empty($data_officer->name_officer)){
                $data['user_type'] = "ทดสอบ";
                $data['name_user'] = "ทดสอบ";
                // $data['unit'] = $data_officer->operating_unit->name;
            }else{
                $data['user_type'] = "ทดสอบ";
                $data['name_user'] = "ทดสอบ";
            }
        }

        // if (!empty($local_data->photo)) {
        //     $text_path_local = storage_path('app/public/' . $local_data->photo);
        //     $img_local = Image::make($text_path_local);

        //     // โหลดข้อมูลขนาดของรูปภาพ
        //     $imageData_local = file_get_contents($text_path_local);
        //     list($width, $height) = getimagesizefromstring($imageData_local);
        //     // หาจุดตรงกลาง
        //     $centerX = round($width / 2);
        //     $centerY = round($height / 2);

        //     // ตรวจสอบสีที่จุดกึ่งกลางรูปถาพ
        //     $hexcolor = $img_local->pickColor($centerX, $centerY, 'hex');
        // } else {
        //     $hexcolor = '#2b2d31';
        // }
        $hexcolor = '#2b2d31';

        $data['hexcolor'] = $hexcolor;

        return $data;
    }

    function join_room(Request $request)
    {
        $sos_id = $request->sos_id;
        $user_id = $request->user_id;
        $type = $request->type;
        $type_join = $request->type_join; // มาจากแค่ฝั่ง command ใน 1v1 เท่านั้น

        if($type == 'zone_sos'){
            $type_text = "zone_sos";
        }

        $agora_chat = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' , $type_text)->first();

        if (!empty($agora_chat->member_in_room) ){
            // มีข้อมูล ใน member_in_room
            $data_update = $agora_chat->member_in_room;

            $data_array = json_decode($data_update, true);

            // ป้องกัน array มีค่าซ้ำกัน
            if (!in_array($user_id, $data_array)) {
                $data_array[] = $user_id;
            }
            // แปลงกลับเป็น JSON
            $data_update = json_encode($data_array);

            $update_time_start = $agora_chat->time_start ;

            //ถ้าผู้ใช้มากกว่าหรือเท่ากับ 2
            if(count($data_array) >= 2){
                if(empty($agora_chat->than_2_people_time_start)){

                    $update_than_2_time_start = date("Y-m-d H:i:s");
                }else{
                    $update_than_2_time_start = $agora_chat->than_2_people_time_start;
                }
            }else{
                $update_than_2_time_start = null;
            }
        }else{
            // ไม่มีข้อมูล ใน member_in_room
            $data_update = [];

            $data_update[] = $user_id;
            $update_time_start = date("Y-m-d H:i:s");

            $update_than_2_time_start = null;
        }

        DB::table('agora_chats')
            ->where([
                    ['sos_id', $sos_id],
                    ['room_for', $type_text],
                ])
            ->update([
                    'member_in_room' => $data_update,
                    'time_start' => $update_time_start,
                    'than_2_people_time_start' => $update_than_2_time_start,
                ]);

        if (!empty($type_join)) {
            //=============== อัพเดตใหม่ ใช้สำหรับ 1ต่อ1 ของ เจ้าหน้าที่ หน้า from_yellow =================
            $agora_chat_new = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' , $type_text)->first();

            $new_data_array = [];
            $new_data_array['data'] = []; // สร้าง array ย่อยเพื่อเก็บข้อมูล
            $new_data_array['data_agora'] = $agora_chat_new;

            $data_member = $agora_chat_new->member_in_room;
            $data_array = json_decode($data_member, true);

            // ถ้ามีเพียงสมาชิกคนเดียว
            if (count($data_array) == 1) {
                $member = reset($data_array);  // ได้สมาชิกคนแรก
                $data_command = Zone_data_officer_command::where('user_id', $member)->first();

                if (!empty($data_command->name_officer_command)) {
                    $new_data_array['data'] = ['command' => $user_id, 'user' => ''];
                } else {
                    $new_data_array['data'] = ['user' => $user_id, 'command' => ''];
                }

                if (!empty($new_data_array['data']['command'])) {
                    $new_data_array['data_command'] = User::where('id', $new_data_array['data']['command'])->first();
                }

                if (!empty($new_data_array['data']['user'])) {
                    $new_data_array['data_user'] = User::where('id', $new_data_array['data']['user'])->first();
                }
            } else {
                // ถ้ามีหลายสมาชิก
                $new_data_array['data'] = [];

                $command = '';
                $user = '';

                foreach ($data_array as $member) {
                    $data_command = Zone_data_officer_command::where('user_id', $member)->first();

                    if (!empty($data_command->name_officer_command)) {
                        $command = $member;
                    } else {
                        $user = $member;
                    }

                    // กำหนดค่าให้กับ 'data'
                    $new_data_array['data'] = ['command' => $command, 'user' => $user];

                    if (!empty($new_data_array['data']['command'])) {
                        $new_data_array['data_command'] = User::where('id', $new_data_array['data']['command'])->first();
                    }

                    if (!empty($new_data_array['data']['user'])) {
                        $new_data_array['data_user'] = User::where('id', $new_data_array['data']['user'])->first();
                    }
                }
            }

            return $new_data_array ;
            //===============================================================================
        } else {
            return $data_update ;
        }

    }
}
