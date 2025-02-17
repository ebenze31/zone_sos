<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Zone_data_operating_unit;
use Illuminate\Http\Request;

class Zone_data_operating_unitsController extends Controller
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
            $zone_data_operating_units = Zone_data_operating_unit::where('name', 'LIKE', "%$keyword%")
                ->orWhere('zone_area', 'LIKE', "%$keyword%")
                ->orWhere('level', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $zone_data_operating_units = Zone_data_operating_unit::latest()->paginate($perPage);
        }

        return view('zone_data_operating_units.index', compact('zone_data_operating_units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('zone_data_operating_units.create');
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
        
        Zone_data_operating_unit::create($requestData);

        return redirect('zone_data_operating_units')->with('flash_message', 'Zone_data_operating_unit added!');
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
        $zone_data_operating_unit = Zone_data_operating_unit::findOrFail($id);

        return view('zone_data_operating_units.show', compact('zone_data_operating_unit'));
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
        $zone_data_operating_unit = Zone_data_operating_unit::findOrFail($id);

        return view('zone_data_operating_units.edit', compact('zone_data_operating_unit'));
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
        
        $zone_data_operating_unit = Zone_data_operating_unit::findOrFail($id);
        $zone_data_operating_unit->update($requestData);

        return redirect('zone_data_operating_units')->with('flash_message', 'Zone_data_operating_unit updated!');
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
        Zone_data_operating_unit::destroy($id);

        return redirect('zone_data_operating_units')->with('flash_message', 'Zone_data_operating_unit deleted!');
    }
}
