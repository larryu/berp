@extends('layout')

@section('content')
    <h1> Berp Home </h1>
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <router-link to="/" class="list-group-item ">Home</router-link>
                <router-link to="/orders" class="list-group-item ">Orders</router-link>
                <router-link to="/products" class="list-group-item ">Products</router-link>
                <router-link to="/items/" class="list-group-item ">Items</router-link>
            </div>
        </div>
    </div>

@stop