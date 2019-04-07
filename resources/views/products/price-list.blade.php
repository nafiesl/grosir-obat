@extends('layouts.pdf')

@section('title', __('product.price_list') . ' (per ' . date('Y-m-d H:i') . ')')

@section('content')

<table style="width: 760px; border-collapse: collapse;">
    <tbody>
        <tr>
            <td colspan="4">
                <h2 class="text-center">{{ __('product.price_list') }} - {{ config('store.name') }}</h2>
                <p class="text-center strong">Per: {{ date('Y-m-d H:i') }}</p>
                <br>
            </td>
        </tr>
        @foreach($products->chunk(80) as $chuncked40Products)
        <tr class="border-bottom" style="padding-bottom:20px">
            @foreach($chuncked40Products->chunk(40) as $chunckedProducts)
            <td style="width:50%;padding: 5px">
                <table class="main-table">
                    <tbody>
                        @foreach($chunckedProducts as $key => $product)
                        <tr>
                            <td>{{ $key + 1 }})&nbsp;{{ $product->name }} ({{ $product->unit->name }})</td>
                            <td class="text-right" style="width:20%">{{ format_rp($product->cash_price) }}</td>
                            <td class="text-right" style="width:20%">{{ format_rp($product->credit_price) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
