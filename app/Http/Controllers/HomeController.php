<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::check()) {

            $role = Auth::user()->role;

            if ($role === 'admin_zone') {
                return redirect('/admin_home');
            }
            elseif ($role === 'officer') {
                // 
            }
            else{
                return redirect('/404');
            }
        }
        else{
            return redirect('/login');
        }
    }
}
