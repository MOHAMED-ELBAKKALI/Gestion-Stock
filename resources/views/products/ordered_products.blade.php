@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Liste des produits commandés (Juillet 2024)</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom du produit</th>
                <th>Nom du client</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Date de commande</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->product_name }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->category_name }}</td>
                    <td>{{ $order->supplier_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune commande trouvée pour juillet 2024.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection