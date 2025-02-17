@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Zone_data_officer_command {{ $zone_data_officer_command->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/zone_data_officer_commands') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/zone_data_officer_commands/' . $zone_data_officer_command->id . '/edit') }}" title="Edit Zone_data_officer_command"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('zone_data_officer_commands' . '/' . $zone_data_officer_command->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Zone_data_officer_command" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $zone_data_officer_command->id }}</td>
                                    </tr>
                                    <tr><th> Name Officer Command </th><td> {{ $zone_data_officer_command->name_officer_command }} </td></tr><tr><th> User Id </th><td> {{ $zone_data_officer_command->user_id }} </td></tr><tr><th> Zone Area Id </th><td> {{ $zone_data_officer_command->zone_area_id }} </td></tr><tr><th> Officer Role </th><td> {{ $zone_data_officer_command->officer_role }} </td></tr><tr><th> Number </th><td> {{ $zone_data_officer_command->number }} </td></tr><tr><th> Status </th><td> {{ $zone_data_officer_command->status }} </td></tr><tr><th> Creator </th><td> {{ $zone_data_officer_command->creator }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
