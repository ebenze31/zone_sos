<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FordevController extends Controller
{
    public function create_zone_partner()
    {
        if (Auth::check()) {

            $user_id = Auth::user()->id;

            if ($user_id == '1' || $user_id == '64' || $user_id == '11003429') {
                return view('ForDev.create_zone_partner');
            }
            else{
                return redirect('/admin_home');
            }
        }
        else{
            return redirect('/login');
        }
    }
}
