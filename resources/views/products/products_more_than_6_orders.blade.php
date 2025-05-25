@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Products with More Than 6 Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Orders Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->orders_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection