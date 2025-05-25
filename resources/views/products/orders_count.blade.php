@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Nombre de commandes par produit</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Nombre de commandes</th>
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
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Retour au Dashboard</a>
</div>
@endsection
