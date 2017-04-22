@extends('layouts.app')

@section('title', 'Entry Transaksi')

@section('content')
<?php use Facades\App\Cart\CartCollection; ?>

@includeWhen (! CartCollection::isEmpty(), 'cart.partials.transaction-draft-tabs')
@if ($draft)
    @include ('cart.partials.product-search-box')
    @include('cart.partials.draft-item-list')
@endif
@endsection