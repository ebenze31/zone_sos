<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoCallController extends Controller
{
    public function before_video_call(Request $request){
        $user = Auth::user();

        $sos_id = $request->sos_id; //รหัสขอความช่วยเหลือ
        $type = "zone_sos"; //ใช้บอกประเภทขอความช่วยเหลือ ตัวอย่าง sos_1669 user_sos_1669 zone_sos

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

        return view('agora_video_call/before_video_call', compact('user','type','type_brand','type_device'));
    }

    public function video_call_pc(Request $request){
        $type = "zone_sos";

        return view('agora_video_call/video_call_pc', compact('type'));

    }
}
