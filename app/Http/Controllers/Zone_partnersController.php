<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Zone_partner;
use Illuminate\Http\Request;

class Zone_partnersController extends Controller
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
            $zone_partners = Zone_partner::where('name', 'LIKE', "%$keyword%")
                ->orWhere('full_name', 'LIKE', "%$keyword%")
                ->orWhere('phone', 'LIKE', "%$keyword%")
                ->orWhere('type_partner', 'LIKE', "%$keyword%")
                ->orWhere('group_line_id', 'LIKE', "%$keyword%")
                ->orWhere('mail', 'LIKE', "%$keyword%")
                ->orWhere('logo', 'LIKE', "%$keyword%")
                ->orWhere('color_ci_1', 'LIKE', "%$keyword%")
                ->orWhere('color_ci_2', 'LIKE', "%$keyword%")
                ->orWhere('color_ci_3', 'LIKE', "%$keyword%")
                ->orWhere('province', 'LIKE', "%$keyword%")
                ->orWhere('district', 'LIKE', "%$keyword%")
                ->orWhere('sub_district', 'LIKE', "%$keyword%")
                ->orWhere('sub_area', 'LIKE', "%$keyword%")
                ->orWhere('show_homepage', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $zone_partners = Zone_partner::latest()->paginate($perPage);
        }

        return view('zone_partners.index', compact('zone_partners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('zone_partners.create');
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
        
        Zone_partner::create($requestData);

        return redirect('zone_partners')->with('flash_message', 'Zone_partner added!');
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
        $zone_partner = Zone_partner::findOrFail($id);

        return view('zone_partners.show', compact('zone_partner'));
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
        $zone_partner = Zone_partner::findOrFail($id);

        return view('zone_partners.edit', compact('zone_partner'));
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
        
        $zone_partner = Zone_partner::findOrFail($id);
        $zone_partner->update($requestData);

        return redirect('zone_partners')->with('flash_message', 'Zone_partner updated!');
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
        Zone_partner::destroy($id);

        return redirect('zone_partners')->with('flash_message', 'Zone_partner deleted!');
    }
}
