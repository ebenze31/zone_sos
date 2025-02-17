<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Zone_agora_chat;
use Illuminate\Http\Request;

class Zone_agora_chatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $zone_agora_chats = Zone_agora_chat::where('room_for', 'LIKE', "%$keyword%")
                ->orWhere('time_start', 'LIKE', "%$keyword%")
                ->orWhere('member_in_room', 'LIKE', "%$keyword%")
                ->orWhere('total_timemeet', 'LIKE', "%$keyword%")
                ->orWhere('amount_meet', 'LIKE', "%$keyword%")
                ->orWhere('detail', 'LIKE', "%$keyword%")
                ->orWhere('sos_id', 'LIKE', "%$keyword%")
                ->orWhere('consult_doctor_id', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $zone_agora_chats = Zone_agora_chat::latest()->paginate($perPage);
        }

        return view('zone_agora_chats.index', compact('zone_agora_chats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('zone_agora_chats.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        Zone_agora_chat::create($requestData);

        return redirect('zone_agora_chats')->with('flash_message', 'Zone_agora_chat added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $zone_agora_chat = Zone_agora_chat::findOrFail($id);

        return view('zone_agora_chats.show', compact('zone_agora_chat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $zone_agora_chat = Zone_agora_chat::findOrFail($id);

        return view('zone_agora_chats.edit', compact('zone_agora_chat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $zone_agora_chat = Zone_agora_chat::findOrFail($id);
        $zone_agora_chat->update($requestData);

        return redirect('zone_agora_chats')->with('flash_message', 'Zone_agora_chat updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Zone_agora_chat::destroy($id);

        return redirect('zone_agora_chats')->with('flash_message', 'Zone_agora_chat deleted!');
    }
}
