@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Zone_area</div>
                    <div class="card-body">
                        <a href="{{ url('/zone_area/create') }}" class="btn btn-success btn-sm" title="Add New Zone_area">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/zone_area') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name Zone Area</th><th>Polygon Area</th><th>Zone Partner Id</th><th>Creator</th><th>Check Send To</th><th>Group Line Id</th><th>Last Edit Polygon User Id</th><th>Last Edit Polygon Time</th><th>Old Polygon Area</th><th>Old Edit Polygon User Id</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($zone_area as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name_zone_area }}</td><td>{{ $item->polygon_area }}</td><td>{{ $item->zone_partner_id }}</td><td>{{ $item->creator }}</td><td>{{ $item->check_send_to }}</td><td>{{ $item->group_line_id }}</td><td>{{ $item->last_edit_polygon_user_id }}</td><td>{{ $item->last_edit_polygon_time }}</td><td>{{ $item->old_polygon_area }}</td><td>{{ $item->old_edit_polygon_user_id }}</td>
                                        <td>
                                            <a href="{{ url('/zone_area/' . $item->id) }}" title="View Zone_area"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/zone_area/' . $item->id . '/edit') }}" title="Edit Zone_area"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/zone_area' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Zone_area" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $zone_area->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
