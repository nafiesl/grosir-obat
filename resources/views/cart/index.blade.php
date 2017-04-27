@extends('layouts.app')

@section('title', 'Entry Transaksi')

@section('content')
<?php use Facades\App\Cart\CartCollection; ?>

@includeWhen(! CartCollection::isEmpty(), 'cart.partials.transaction-draft-tabs')
@if ($draft)
    @if (Request::get('action') == 'confirm')
        @include('cart.partials.draft-confirm')
    @else
        @include('cart.partials.product-search-box')
        <div class="row">
            <div class="col-md-9">@include('cart.partials.draft-item-list')</div>
            <div class="col-md-3">@include('cart.partials.form-draft-detail')</div>
        </div>
    @endif
@endif
@endsection