@extends('layouts.theme')

@section('content')


<div class="container mx-auto">

    <div class="w-100 h-[calc(100dvh-96px)] mt-[48px] flex justify-center items-center h-100">

        <div class="block max-w-[528px] shadow-lg w-full p-6 bg-white border border-gray-200 rounded-[12px] shadow  white:bg-gray-800 white:border-gray-700 mx-3">
            <center>
                <img src="{{ url('/images/logos/default_logo.png') }}" class="h-[150px] my-1 me-2" >
            </center>
            <p class="text-[35px] font-extrabold header-text mt-4">เข้าสู่ระบบ</p>
            <form method="POST" action="{{ route('login') }}" class="mt-4">
                @csrf

                <div class="form-group row">
                    
                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __(' รหัสผู้ใช้') }}</label>
                    <input type="username" id="username" class=" bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 " name="username" required autocomplete="username" autofocus/>
                </div>
                <div class="form-group row mt-4">
                    
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('รหัสผ่าน') }}</label>
                    <input type="password" id="password" class=" bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 " name="password" required autocomplete="password" autofocus/>
                </div>

         
                <div class="flex w-100 my-2 mt-5">
                    <button type="submit" class="btn rounded-full bg-slate-950 w-full text-white p-2 button ">เข้าสู่ระบบ</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection