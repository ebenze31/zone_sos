@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Zone_area {{ $zone_area->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/zone_area') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/zone_area/' . $zone_area->id . '/edit') }}" title="Edit Zone_area"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('zone_area' . '/' . $zone_area->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Zone_area" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $zone_area->id }}</td>
                                    </tr>
                                    <tr><th> Name Zone Area </th><td> {{ $zone_area->name_zone_area }} </td></tr><tr><th> Polygon Area </th><td> {{ $zone_area->polygon_area }} </td></tr><tr><th> Zone Partner Id </th><td> {{ $zone_area->zone_partner_id }} </td></tr><tr><th> Creator </th><td> {{ $zone_area->creator }} </td></tr><tr><th> Check Send To </th><td> {{ $zone_area->check_send_to }} </td></tr><tr><th> Group Line Id </th><td> {{ $zone_area->group_line_id }} </td></tr><tr><th> Last Edit Polygon User Id </th><td> {{ $zone_area->last_edit_polygon_user_id }} </td></tr><tr><th> Last Edit Polygon Time </th><td> {{ $zone_area->last_edit_polygon_time }} </td></tr><tr><th> Old Polygon Area </th><td> {{ $zone_area->old_polygon_area }} </td></tr><tr><th> Old Edit Polygon User Id </th><td> {{ $zone_area->old_edit_polygon_user_id }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
