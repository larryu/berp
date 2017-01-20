@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1> All orders </h1>
            <table class="table table-bordered table-hover table-responsive">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->customer_name}}</td>
                            <td>{{$order->order_date}}</td>
                            <td>{{$order->status}}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@stop