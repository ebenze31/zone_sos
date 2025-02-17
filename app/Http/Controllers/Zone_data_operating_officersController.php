<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Zone_data_operating_officer;
use Illuminate\Http\Request;

class Zone_data_operating_officersController extends Controller
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
            $zone_data_operating_officers = Zone_data_operating_officer::where('name_officer', 'LIKE', "%$keyword%")
                ->orWhere('lat', 'LIKE', "%$keyword%")
                ->orWhere('lng', 'LIKE', "%$keyword%")
                ->orWhere('zone_operating_unit_id', 'LIKE', "%$keyword%")
                ->orWhere('user_id', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $zone_data_operating_officers = Zone_data_operating_officer::latest()->paginate($perPage);
        }

        return view('zone_data_operating_officers.index', compact('zone_data_operating_officers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('zone_data_operating_officers.create');
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
        
        Zone_data_operating_officer::create($requestData);

        return redirect('zone_data_operating_officers')->with('flash_message', 'Zone_data_operating_officer added!');
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
        $zone_data_operating_officer = Zone_data_operating_officer::findOrFail($id);

        return view('zone_data_operating_officers.show', compact('zone_data_operating_officer'));
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
        $zone_data_operating_officer = Zone_data_operating_officer::findOrFail($id);

        return view('zone_data_operating_officers.edit', compact('zone_data_operating_officer'));
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
        
        $zone_data_operating_officer = Zone_data_operating_officer::findOrFail($id);
        $zone_data_operating_officer->update($requestData);

        return redirect('zone_data_operating_officers')->with('flash_message', 'Zone_data_operating_officer updated!');
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
        Zone_data_operating_officer::destroy($id);

        return redirect('zone_data_operating_officers')->with('flash_message', 'Zone_data_operating_officer deleted!');
    }
}
