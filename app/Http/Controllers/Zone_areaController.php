<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Zone_area;
use Illuminate\Http\Request;

class Zone_areaController extends Controller
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
            $zone_area = Zone_area::where('name_zone_area', 'LIKE', "%$keyword%")
                ->orWhere('polygon_area', 'LIKE', "%$keyword%")
                ->orWhere('zone_partner_id', 'LIKE', "%$keyword%")
                ->orWhere('creator', 'LIKE', "%$keyword%")
                ->orWhere('check_send_to', 'LIKE', "%$keyword%")
                ->orWhere('group_line_id', 'LIKE', "%$keyword%")
                ->orWhere('last_edit_polygon_user_id', 'LIKE', "%$keyword%")
                ->orWhere('last_edit_polygon_time', 'LIKE', "%$keyword%")
                ->orWhere('old_polygon_area', 'LIKE', "%$keyword%")
                ->orWhere('old_edit_polygon_user_id', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $zone_area = Zone_area::latest()->paginate($perPage);
        }

        return view('zone_area.index', compact('zone_area'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('zone_area.create');
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
        
        Zone_area::create($requestData);

        return redirect('zone_area')->with('flash_message', 'Zone_area added!');
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
        $zone_area = Zone_area::findOrFail($id);

        return view('zone_area.show', compact('zone_area'));
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
        $zone_area = Zone_area::findOrFail($id);

        return view('zone_area.edit', compact('zone_area'));
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
        
        $zone_area = Zone_area::findOrFail($id);
        $zone_area->update($requestData);

        return redirect('zone_area')->with('flash_message', 'Zone_area updated!');
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
        Zone_area::destroy($id);

        return redirect('zone_area')->with('flash_message', 'Zone_area deleted!');
    }
}
