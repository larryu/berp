@extends('layout')

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-3">
            <h1> Berp Home </h1>
            <router-view class="view"></router-view>
        </div>
    </div>

@stop