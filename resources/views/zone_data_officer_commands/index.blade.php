@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Zone_data_officer_commands</div>
                    <div class="card-body">
                        <a href="{{ url('/zone_data_officer_commands/create') }}" class="btn btn-success btn-sm" title="Add New Zone_data_officer_command">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/zone_data_officer_commands') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                                        <th>#</th><th>Name Officer Command</th><th>User Id</th><th>Zone Area Id</th><th>Officer Role</th><th>Number</th><th>Status</th><th>Creator</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($zone_data_officer_commands as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name_officer_command }}</td><td>{{ $item->user_id }}</td><td>{{ $item->zone_area_id }}</td><td>{{ $item->officer_role }}</td><td>{{ $item->number }}</td><td>{{ $item->status }}</td><td>{{ $item->creator }}</td>
                                        <td>
                                            <a href="{{ url('/zone_data_officer_commands/' . $item->id) }}" title="View Zone_data_officer_command"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/zone_data_officer_commands/' . $item->id . '/edit') }}" title="Edit Zone_data_officer_command"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/zone_data_officer_commands' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Zone_data_officer_command" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $zone_data_officer_commands->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
