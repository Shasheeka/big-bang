@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-heading">Please select a store to login.</div>
                <div class="panel-body">
                    {{$user->tenants[0]->subdomain}}
                    
                    <a href='{{$user->tenants[0]->url}}'>
                    
                    {{$user->tenants[0]->url}}
                    </a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
