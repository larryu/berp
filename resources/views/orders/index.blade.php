@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1> All orders </h1>
            <router-view class="view"></router-view>
        </div>
    </div>
@stop