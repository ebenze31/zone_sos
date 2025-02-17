@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Zone_partner {{ $zone_partner->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/zone_partners') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/zone_partners/' . $zone_partner->id . '/edit') }}" title="Edit Zone_partner"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('zone_partners' . '/' . $zone_partner->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Zone_partner" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $zone_partner->id }}</td>
                                    </tr>
                                    <tr><th> Name </th><td> {{ $zone_partner->name }} </td></tr><tr><th> Full Name </th><td> {{ $zone_partner->full_name }} </td></tr><tr><th> Phone </th><td> {{ $zone_partner->phone }} </td></tr><tr><th> Type Partner </th><td> {{ $zone_partner->type_partner }} </td></tr><tr><th> Group Line Id </th><td> {{ $zone_partner->group_line_id }} </td></tr><tr><th> Mail </th><td> {{ $zone_partner->mail }} </td></tr><tr><th> Logo </th><td> {{ $zone_partner->logo }} </td></tr><tr><th> Color Ci 1 </th><td> {{ $zone_partner->color_ci_1 }} </td></tr><tr><th> Color Ci 2 </th><td> {{ $zone_partner->color_ci_2 }} </td></tr><tr><th> Color Ci 3 </th><td> {{ $zone_partner->color_ci_3 }} </td></tr><tr><th> Province </th><td> {{ $zone_partner->province }} </td></tr><tr><th> District </th><td> {{ $zone_partner->district }} </td></tr><tr><th> Sub District </th><td> {{ $zone_partner->sub_district }} </td></tr><tr><th> Sub Area </th><td> {{ $zone_partner->sub_area }} </td></tr><tr><th> Show Homepage </th><td> {{ $zone_partner->show_homepage }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
