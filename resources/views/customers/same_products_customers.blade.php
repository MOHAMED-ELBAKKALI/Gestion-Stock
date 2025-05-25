@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Clients ayant commandé les mêmes produits que Annabel Stehr</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom du client</th>
                <th>Email</th>
                <th>Produit commandé</th>
                <th>Date de commande</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->product_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->order_date)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aucun autre client n'a commandé les mêmes produits.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Retour au Dashboard</a>
</div>
@endsection
