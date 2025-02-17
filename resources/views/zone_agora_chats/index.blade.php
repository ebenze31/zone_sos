@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Zone_agora_chats</div>
                    <div class="card-body">
                        <a href="{{ url('/zone_agora_chats/create') }}" class="btn btn-success btn-sm" title="Add New Zone_agora_chat">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/zone_agora_chats') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                                        <th>#</th><th>Room For</th><th>Time Start</th><th>Member In Room</th><th>Total Timemeet</th><th>Amount Meet</th><th>Detail</th><th>Sos Id</th><th>Consult Doctor Id</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($zone_agora_chats as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->room_for }}</td><td>{{ $item->time_start }}</td><td>{{ $item->member_in_room }}</td><td>{{ $item->total_timemeet }}</td><td>{{ $item->amount_meet }}</td><td>{{ $item->detail }}</td><td>{{ $item->sos_id }}</td><td>{{ $item->consult_doctor_id }}</td>
                                        <td>
                                            <a href="{{ url('/zone_agora_chats/' . $item->id) }}" title="View Zone_agora_chat"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/zone_agora_chats/' . $item->id . '/edit') }}" title="Edit Zone_agora_chat"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/zone_agora_chats' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Zone_agora_chat" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $zone_agora_chats->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
