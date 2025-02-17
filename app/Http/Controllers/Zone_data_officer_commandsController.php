<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Zone_data_officer_command;
use Illuminate\Http\Request;

class Zone_data_officer_commandsController extends Controller
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
            $zone_data_officer_commands = Zone_data_officer_command::where('name_officer_command', 'LIKE', "%$keyword%")
                ->orWhere('user_id', 'LIKE', "%$keyword%")
                ->orWhere('zone_area_id', 'LIKE', "%$keyword%")
                ->orWhere('officer_role', 'LIKE', "%$keyword%")
                ->orWhere('number', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('creator', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $zone_data_officer_commands = Zone_data_officer_command::latest()->paginate($perPage);
        }

        return view('zone_data_officer_commands.index', compact('zone_data_officer_commands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('zone_data_officer_commands.create');
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
        
        Zone_data_officer_command::create($requestData);

        return redirect('zone_data_officer_commands')->with('flash_message', 'Zone_data_officer_command added!');
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
        $zone_data_officer_command = Zone_data_officer_command::findOrFail($id);

        return view('zone_data_officer_commands.show', compact('zone_data_officer_command'));
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
        $zone_data_officer_command = Zone_data_officer_command::findOrFail($id);

        return view('zone_data_officer_commands.edit', compact('zone_data_officer_command'));
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
        
        $zone_data_officer_command = Zone_data_officer_command::findOrFail($id);
        $zone_data_officer_command->update($requestData);

        return redirect('zone_data_officer_commands')->with('flash_message', 'Zone_data_officer_command updated!');
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
        Zone_data_officer_command::destroy($id);

        return redirect('zone_data_officer_commands')->with('flash_message', 'Zone_data_officer_command deleted!');
    }
}
