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

        $requestData = $request->all();
        $user = Auth::user();

        $agoraAppId = config('agora.app_id');
        $agoraAppCertificate = config('agora.app_certificate');

        if (!empty($requestData['useSpeaker'])) {
            $useSpeaker = $requestData['useSpeaker'];
        } else {
            $useSpeaker = '';
        }

        if (!empty($requestData['useMicrophone'])) {
            $useMicrophone = $requestData['useMicrophone'];
        } else {
            $useMicrophone = '';
        }

        if (!empty($requestData['useCamera'])) {
            $useCamera = $requestData['useCamera'];
        } else {
            $useCamera = '';
        }

        if($type == 'zone_sos'){
            // $sos_data  = Zone_Sos_help_center::join('sos_1669_form_yellows', 'sos_help_centers.id', '=', 'sos_1669_form_yellows.sos_help_center_id')
            // ->where('sos_help_centers.id',$sos_id)
            // ->select('sos_help_centers.*','sos_1669_form_yellows.*','sos_help_centers.time_create_sos as created_sos')
            // ->first();

            $sos_data = DB::table('zone_sos_help_centers')
                ->join('sos_1669_form_yellows', 'zone_sos_help_centers.id', '=', 'sos_1669_form_yellows.sos_help_center_id')
                ->where('zone_sos_help_centers.id', $sos_id)
                ->select('zone_sos_help_centers.*', 'sos_1669_form_yellows.*', 'zone_sos_help_centers.time_create_sos as created_sos')
                ->first();

            $groupId = '';

            if($user->id == $sos_data->user_id){
                $role_permission = 'help_seeker'; //ผู้ขอความช่วยเหลือ
            }else{
                $role_permission = 'helper';
            }

            $data_agora = Zone_agora_chat::where('sos_id',$sos_id)->where('room_for','meet_operating_1669')->first();
        }

        $videoTrack = $requestData['videoTrack'];
        $audioTrack = $requestData['audioTrack'];

        return view('agora_video_call/video_call_pc',compact('type','sos_id','agoraAppId','agoraAppCertificate','videoTrack','audioTrack','useSpeaker','useMicrophone','useCamera'));
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
        $expireTimeInSeconds = 1800;
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
            $data_sos = DB::table('zone_sos_help_centers')->where('id',$sos_id)->first();
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

    function get_remote_data(Request $request){
        $user_id = $request->user_id;
        $type = $request->type;
        $sos_id = $request->sos_id;

        if($type == 'zone_sos'){
            // $data_sos = Sos_help_center::where('id',$sos_id)->first();
            DB::table('zone_sos_help_centers')->where('id',$sos_id)->first();
        }

        $remote_data = User::where('id',$user_id)->first();

        $data = [];
        $data['photo'] = $remote_data->photo;
        $data['avatar'] = $remote_data->avatar;

        if($type == 'zone_sos'){
            $data_command = Zone_data_officer_command::where('user_id',$user_id)->first();
            $data_officer = Zone_data_operating_officer::where('user_id',$user_id)->first();
            // $data_hospital_officer = Data_1669_officer_hospital::where('user_id',$user_id)->first();

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

            // if(!empty($data_command->name_officer_command)){
            //     $data['user_type'] = "ศูนย์อำนวยการ";
            //     $data['name_user'] = $data_command->name_officer_command;
            //     // $data['unit'] = '';
            // }elseif(!empty($data_officer->name_officer)){
            //     $data['user_type'] = "หน่วยแพทย์ฉุกเฉิน";
            //     $data['name_user'] = $data_officer->name_officer;
            //     // $data['unit'] = $data_officer->operating_unit->name;
            // }
            // // elseif(!empty($data_hospital_officer->name_officer_hospital)){
            // //     $data['user_type'] = "เจ้าหน้าที่ห้อง ER";
            // //     $data['name_user'] = $data_hospital_officer->name_officer_hospital;
            // //     // $data['name_user'] = $data_hospital_officer->user->name;
            // // }
            // else{
            //     $data['user_type'] = "--";
            //     $data['name_user'] = $remote_data->name;
            // }
        }

        // if (!empty($remote_data->photo)) {
        //     $text_path_remote = storage_path('app/public/' . $remote_data->photo);
        //     $img_remote = Image::make($text_path_remote);

        //     // โหลดข้อมูลขนาดของรูปภาพ
        //     $imageData_remote = file_get_contents($text_path_remote);
        //     list($width, $height) = getimagesizefromstring($imageData_remote);
        //     // หาจุดตรงกลาง
        //     $centerX = round($width / 2);
        //     $centerY = round($height / 2);

        //     // ตรวจสอบสีที่จุดกึ่งกลางรูปถาพ
        //     $hexcolor = $img_remote->pickColor($centerX, $centerY, 'hex');

        //     // $hexcolor = '#2b2d31';
        // } else {
        //     $hexcolor = '#2b2d26';
        // }
        $hexcolor = '#2b2d26';

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

        DB::table('zone_agora_chats')
            ->where([
                    ['sos_id', $sos_id],
                    ['room_for', $type_text],
                ])
            ->update([
                    'member_in_room' => $data_update,
                    'time_start' => $update_time_start,
                    'than_2_people_time_start' => $update_than_2_time_start,
                    'amount_meet' => DB::raw("IFNULL(amount_meet, 0) + 1"), // เพิ่มค่าทีละ 1 ถ้าเป็น null หรือ 0 ให้เริ่มจาก 1
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

    function left_room(Request $request)
    {

        $sos_id = $request->sos_id;
        $user_id = $request->user_id;
        $type = $request->type;
        $leave = $request->leave;

        // if($type == 'zone_sos'){
        //     $type_text = "zone_sos";
        // }

        $type_text = "zone_sos";


        if($leave == "leave_fast"){
            $agora_chat_old = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' , $type_text)->first();
            $data_old = $agora_chat_old->member_in_room;

            $data_array = json_decode($data_old, true);
            // นับจำนวนข้อมูลใน $data_update

            if(!empty($data_array) ){
                // ใช้ array_filter เพื่อกรองข้อมูลที่ต้องการลบ
                $data_array = array_filter($data_array, function($value) use ($user_id) {
                    return $value != $user_id;
                });

                if (!empty($data_array)) {
                    // ใช้ array_values เพื่อรีเดิมดัชนีของอาร์เรย์ให้ต่อเนื่องโดยไม่มีช่องว่าง
                    $data_array = array_values($data_array);
                    // แปลงกลับเป็น JSON
                    $data_update = json_encode($data_array);
                    // นับจำนวนข้อมูลใน $data_update
                    $number_of_data = count($data_array);
                } else {
                    $data_update = null;
                }
            }else{
                $data_update = null;
            }

            DB::table('zone_agora_chats')
            ->where([
                    ['sos_id', $sos_id],
                    ['room_for', $type_text],
                ])
            ->update([
                    'member_in_room' => $data_update,
            ]);

            $agora_chat = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' , $type_text)->first();
            $data_new = $agora_chat->member_in_room;

            $check_time_Start = $agora_chat->time_start;
            $check_time_Start_2_people = $agora_chat->than_2_people_time_start;
            // นับจำนวนข้อมูลใน $data_update

            if(!empty($agora_chat->than_2_people_time_start)){
                if($number_of_data < 2){
                    $update_than_2_people_timemeet = null;
                    $date_now_2 = date("Y-m-d H:i:s");

                    $than_2_people_time_start = $agora_chat->than_2_people_time_start ;

                    $than_2_time_start_seconds = strtotime($than_2_people_time_start);
                    $date_now_seconds_2 = strtotime($date_now_2);
                    $seconds_passed_2 =  (int)$date_now_seconds_2 - (int)$than_2_time_start_seconds ;

                    $current_than_2_people_timemeet = (int)$seconds_passed_2;
                }else{
                    $current_than_2_people_timemeet = null;
                }

                if(!empty($agora_chat->than_2_people_timemeet) ){
                    $update_than_2_people_timemeet = (int)$agora_chat->than_2_people_timemeet + (int)$current_than_2_people_timemeet ;
                }else{
                    $update_than_2_people_timemeet = $current_than_2_people_timemeet ;
                }
            }

            if($data_new == null){
                $update_time_start = null ;

                $date_now = date("Y-m-d H:i:s");

                $time_start = $agora_chat->time_start;

                $time_start_seconds = strtotime($time_start);
                $date_now_seconds = strtotime($date_now);
                $seconds_passed =  (int)$date_now_seconds - (int)$time_start_seconds ;

                $update_total_timemeet = (int)$agora_chat->total_timemeet + (int)$seconds_passed ;

                $update_than_2_people_time_start = null;
            }else{
                $update_time_start = $agora_chat->time_start ;
                $update_total_timemeet = $agora_chat->total_timemeet ;

                if($number_of_data < 2){
                    //ลบ ข้อมูล than_2_people_time_start
                    $update_than_2_people_time_start = null;
                }else{
                    $update_than_2_people_time_start = $agora_chat->than_2_people_time_start;
                }
            }

            if (!empty($check_time_Start)) {
                DB::table('zone_agora_chats')
                ->where([
                        ['sos_id', $sos_id],
                        ['room_for', $type_text],
                    ])
                ->update([
                        'time_start' => $update_time_start,
                        'total_timemeet' => $update_total_timemeet,
                    ]);
            }

            if (!empty($check_time_Start_2_people)) {
                DB::table('zone_agora_chats')
                ->where([
                        ['sos_id', $sos_id],
                        ['room_for', $type_text],
                    ])
                ->update([
                        'than_2_people_timemeet' => $update_than_2_people_timemeet,
                        'than_2_people_time_start' => $update_than_2_people_time_start,
                    ]);
            }


        }else{

            $agora_chat_old = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' , $type_text)->first();
            $data_old = $agora_chat_old->member_in_room;

            $data_array = json_decode($data_old, true);
            // นับจำนวนข้อมูลใน $data_update

            if(!empty($data_array) ){
                // ใช้ array_filter เพื่อกรองข้อมูลที่ต้องการลบ
                $data_array = array_filter($data_array, function($value) use ($user_id) {
                    return $value != $user_id;
                });

                if (!empty($data_array)) {
                    // ใช้ array_values เพื่อรีเดิมดัชนีของอาร์เรย์ให้ต่อเนื่องโดยไม่มีช่องว่าง
                    $data_array = array_values($data_array);
                    // แปลงกลับเป็น JSON
                    $data_update = json_encode($data_array);
                    // นับจำนวนข้อมูลใน $data_update
                    $number_of_data = count($data_array);
                } else {
                    $data_update = null;
                }
            }else{
                $data_update = null;
            }

            DB::table('zone_agora_chats')
            ->where([
                    ['sos_id', $sos_id],
                    ['room_for', $type_text],
                ])
            ->update([
                    'member_in_room' => $data_update,
            ]);

            $agora_chat = Zone_agora_chat::where('sos_id' , $sos_id)->where('room_for' , $type_text)->first();
            $data_new = $agora_chat->member_in_room;

            $check_time_Start = $agora_chat->time_start;
            $check_time_Start_2_people = $agora_chat->than_2_people_time_start;
            // นับจำนวนข้อมูลใน $data_update

            if(!empty($agora_chat->than_2_people_time_start)){
                if($number_of_data < 2){
                    $update_than_2_people_timemeet = null;
                    $date_now_2 = date("Y-m-d H:i:s");

                    $than_2_people_time_start = $agora_chat->than_2_people_time_start ;

                    $than_2_time_start_seconds = strtotime($than_2_people_time_start);
                    $date_now_seconds_2 = strtotime($date_now_2);
                    $seconds_passed_2 =  (int)$date_now_seconds_2 - (int)$than_2_time_start_seconds ;

                    $current_than_2_people_timemeet = (int)$seconds_passed_2;
                }else{
                    $current_than_2_people_timemeet = null;
                }

                if(!empty($agora_chat->than_2_people_timemeet) ){
                    $update_than_2_people_timemeet = (int)$agora_chat->than_2_people_timemeet + (int)$current_than_2_people_timemeet ;
                }else{
                    $update_than_2_people_timemeet = $current_than_2_people_timemeet ;
                }
            }

            if($data_new == null){
                $update_time_start = null ;

                $date_now = date("Y-m-d H:i:s");

                $time_start = $agora_chat->time_start;

                $time_start_seconds = strtotime($time_start);
                $date_now_seconds = strtotime($date_now);
                $seconds_passed =  (int)$date_now_seconds - (int)$time_start_seconds ;

                $update_total_timemeet = (int)$agora_chat->total_timemeet + (int)$seconds_passed ;

                $update_than_2_people_time_start = null;
            }else{
                $update_time_start = $agora_chat->time_start ;
                $update_total_timemeet = $agora_chat->total_timemeet ;

                if($number_of_data < 2){
                    //ลบ ข้อมูล than_2_people_time_start
                    $update_than_2_people_time_start = null;
                }else{
                    $update_than_2_people_time_start = $agora_chat->than_2_people_time_start;
                }
            }

            if (!empty($check_time_Start)) {
                DB::table('zone_agora_chats')
                ->where([
                        ['sos_id', $sos_id],
                        ['room_for', $type_text],
                    ])
                ->update([
                        'time_start' => $update_time_start,
                        'total_timemeet' => $update_total_timemeet,
                    ]);
            }

            if (!empty($check_time_Start_2_people)) {
                DB::table('zone_agora_chats')
                ->where([
                        ['sos_id', $sos_id],
                        ['room_for', $type_text],
                    ])
                ->update([
                        'than_2_people_timemeet' => $update_than_2_people_timemeet,
                        'than_2_people_time_start' => $update_than_2_people_time_start,
                    ]);
            }

            return "OK";
        }
    }

    function check_status_room(Request $request){

        $sos_id = $request->sos_id;
        $type_sos = $request->type;

        if($type_sos == 'zone_sos'){
            $type_text = "zone_sos";
        }else if($type_sos == 'sos_1669'){
            $type_text = "meet_operating_1669";
        }else if($type_sos == 'user_sos_1669'){
            $type_text = "user_sos_1669";
        }else if($type_sos == 'sos_personal_assistant'){
            $type_text = "sos_personal_assistant";
        }else{
            $type_text = "sos_map";
        }

        $sos_data = Zone_agora_chat::where('sos_id',$sos_id)->where('room_for',$type_text)->first();

        return $sos_data;
    }

}
