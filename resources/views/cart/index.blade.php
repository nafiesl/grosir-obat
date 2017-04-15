@extends('layouts.app')

@section('content')
<?php use Facades\App\Cart\CartCollection; ?>

<h3 class="page-header">drafts</h3>
@if (! CartCollection::isEmpty())
<ul class="nav nav-tabs draft-drafts-list">
    @foreach(CartCollection::content() as $key => $content)
        <?php $active = ($draft->draftKey == $key) ? 'class=active' : '' ?>
        <li {{ $active }} role="presentation">
            <a href="{{ route('cart.show', $key) }}">
                {{ $content->type }} - {{ $key }}
                <form action="{{ route('cart.remove') }}" method="post" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus Draft Transaksi ini?')">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="hidden" name="draft_key" value="{{ $key }}">
                    <input type="submit" value="x" style="margin: -2px -7px 0px 0px" class="btn-link btn-xs pull-right">
                </form>
            </a>
        </li>
    @endforeach
</ul><!-- Tab panes -->
<br>
@endif
@if ($draft)
    {{ $draft ? $draft->type : '' }}
    <form method="get" action="{{ route('cart.show', $draft->draftKey) }}">
        <input type="text" name="query" value="{{ request('query') }}">
        <input type="submit" value="{{ trans('product.search') }}" style="display:none">
    </form>
    @if (isset($queriedProducts))
    <ul>
        @foreach($queriedProducts as $product)
        <li>{{ $product->name }}</li>
        <li>{{ $draft->type == 'cash' ? $product->cash_price : $product->credit_price }}</li>
        <li>
            <form action="{{ route('cart.add-draft-item', [$draft->draftKey, $product->id]) }}" method="post">
                <input type="number" id="qty-{{ $product->id }}" name="qty" value="1">
                <input type="submit" id="add-product-{{ $product->id }}">
            </form>
        </li>
        @endforeach
    </ul>
    @endif
@endif
@endsection