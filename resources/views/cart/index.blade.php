@extends('layouts.app')

@section('content')
<?php use Facades\App\Cart\CartCollection; ?>

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
@endif
@if ($draft)
    <div class="panel panel-default">
        <div class="panel-heading">
            <form method="get" action="{{ route('cart.show', $draft->draftKey) }}">
                <label for="query">{{ trans('cart.product_search') }}</label>
                <input type="text" id="query" name="query" value="{{ request('query') }}">
                <input type="submit" value="{{ trans('cart.product_search') }}" class="btn btn-info btn-sm">
                <a href="{{ route('cart.show', $draft->draftKey) }}" class="btn btn-default btn-sm">Refresh</a>
            </form>
        </div>
        @if ($queriedProducts)
        <div class="panel-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga Satuan ({{ $draft->type }})</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queriedProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $draft->type == 'cash' ? $product->cash_price : $product->credit_price }}</td>
                        <td>
                            <form action="{{ route('cart.add-draft-item', [$draft->draftKey, $product->id]) }}" method="post" style="display:inline">
                                {{ csrf_field() }}
                                <input type="number" id="qty-{{ $product->id }}" style="width:50px" name="qty" value="1">
                                <input type="submit" id="add-product-{{ $product->id }}" value="Tambah">
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            Produk tidak ditemukan dengan keyword : <strong><em>{{ request('query') }}</em></strong>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Items</h3></div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Item</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Diskon</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            @forelse($draft->items() as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ formatRp($item->price) }}</td>
                    {!! Form::open(['route' => ['cart.update-draft-item', $draft->draftKey], 'method' => 'patch']) !!}
                    <input type="hidden" name="item_key" value="{{ $key }}">
                    <td>{!! FormField::text('qty', ['value' => $item->qty, 'id' => 'qty-' . $key, 'style' => 'width:50px', 'label' => false]) !!}</td>
                    <td>{{ Form::text('item_discount', $item->item_discount, ['id' => 'item_discount-' . $key]) }}</td>
                    {!! Form::close() !!}
                    <td>{{ formatRp($item->subtotal) }}</td>
                    <td>
                        <form
                            action="{{ route('cart.remove-draft-item', $draft->draftKey) }}"
                            method="post"
                            onsubmit="return confirm('Yakin ingin menghapus Item ini?')"
                        >
                            {{ csrf_field() }} {{ method_field('delete') }}
                            <input type="hidden" name="item_index" value="{{ $key }}">
                            <input type="submit" id="remove-item-{{ $key }}" value="x">
                        </form>
                    </td>
                </tr>
            @empty
            @endforelse
        </table>
    </div>
@endif
@endsection