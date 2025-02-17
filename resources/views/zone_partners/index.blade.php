@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Zone_partners</div>
                    <div class="card-body">
                        <a href="{{ url('/zone_partners/create') }}" class="btn btn-success btn-sm" title="Add New Zone_partner">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/zone_partners') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                                        <th>#</th><th>Name</th><th>Full Name</th><th>Phone</th><th>Type Partner</th><th>Group Line Id</th><th>Mail</th><th>Logo</th><th>Color Ci 1</th><th>Color Ci 2</th><th>Color Ci 3</th><th>Province</th><th>District</th><th>Sub District</th><th>Sub Area</th><th>Show Homepage</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($zone_partners as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->full_name }}</td><td>{{ $item->phone }}</td><td>{{ $item->type_partner }}</td><td>{{ $item->group_line_id }}</td><td>{{ $item->mail }}</td><td>{{ $item->logo }}</td><td>{{ $item->color_ci_1 }}</td><td>{{ $item->color_ci_2 }}</td><td>{{ $item->color_ci_3 }}</td><td>{{ $item->province }}</td><td>{{ $item->district }}</td><td>{{ $item->sub_district }}</td><td>{{ $item->sub_area }}</td><td>{{ $item->show_homepage }}</td>
                                        <td>
                                            <a href="{{ url('/zone_partners/' . $item->id) }}" title="View Zone_partner"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/zone_partners/' . $item->id . '/edit') }}" title="Edit Zone_partner"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/zone_partners' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Zone_partner" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $zone_partners->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
