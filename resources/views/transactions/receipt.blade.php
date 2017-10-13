@extends('layouts.pdf')

@section('title', $transaction->invoice_no.' - '.trans('transaction.invoice_print'))

@section('style')
<style>
    table.receipt-table {
        width: 310px;
        border-collapse: collapse;
    }
    table.receipt-table th {
        padding: 5px 0;
    }
    table.receipt-table td {
        padding: 3px 0;
    }
</style>
@endsection

@section('content')

<table class="receipt-table">
    <tbody>
        <tr><td colspan="3"><h2 class="text-center">{{ config('store.name') }}</h2></td></tr>
        <tr>
            <td colspan="3">
                <p class="text-center">{{ config('store.address') }} | Telp: {{ config('store.phone') }}</p>
            </td>
        </tr>
        <tr>
            <td style="width:90px">{{ trans('transaction.invoice_no') }}</td>
            <td class="strong">: {{ $transaction->invoice_no }}</td>
            <td class="text-right">{{ $transaction->created_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>{{ trans('transaction.cashier') }}</td>
            <td>: {{ $transaction->user->name }}</td>
            <td class="text-right">{{ $transaction->created_at->format('H:i:s') }}</td>
        </tr>
        <tr><td>{{ trans('transaction.customer') }}</td><td colspan="2">: {{ $transaction->customer['name'] }}</td></tr>
        <tr><td>{{ trans('transaction.customer_phone') }}</td><td colspan="2">: {{ $transaction->customer['phone'] }}</td></tr>
        <tr><th colspan="3" class="text-left">{{ trans('transaction.items') }}</th></tr>
        <tr>
            <th class="border-bottom">{{ trans('product.item_qty') }}</th>
            <th class="text-right border-bottom">{{ trans('product.price') }} ({{ trans('product.item_discount') }})</th>
            <th class="text-right border-bottom" style="width:90px">{{ trans('product.item_subtotal') }}</th>
        </tr>
        <?php $discountTotal = 0; ?>
        @foreach($transaction->items as $key => $item)
        <tr>
            <td class="strong" colspan="3">{{ $key + 1 }})&nbsp;{{ $item['name'] }} ({{ $item['unit'] }})</td>
        </tr>
        <tr>
            <td class="text-center border-bottom" style="vertical-align: top;">{{ $item['qty'] }}</td>
            <td class="text-right border-bottom">
                {{ formatRp($item['price']) }} ({{ formatRp($item['item_discount']) }})
            </td>
            <td class="text-right border-bottom">{{ formatRp($item['subtotal']) }}</td>
        </tr>
        <?php $discountTotal += $item['item_discount_subtotal'] ?>
        @endforeach
        <tr>
            <th colspan="2" class="text-right">{{ trans('transaction.subtotal') }} :</th>
            <th class="text-right">{{ formatRp($transaction['total'] + $discountTotal) }}</th>
        </tr>
        <tr>
            <th colspan="2" class="text-right">{{ trans('transaction.discount_total') }} :</th>
            <th class="text-right">{{ formatRp($discountTotal) }}</th>
        </tr>
        <tr>
            <th colspan="2" class="text-right">{{ trans('transaction.total') }} :</th>
            <th class="text-right">{{ formatRp($transaction['total']) }}</th>
        </tr>
        <tr>
            <th colspan="2" class="text-right">{{ trans('transaction.payment') }} :</th>
            <th class="text-right">{{ formatRp($transaction->payment) }}</th>
        </tr>
        <tr>
            <th colspan="2" class="text-right">{{ trans('transaction.exchange') }} :</th>
            <th class="text-right">{{ formatRp($transaction->getExchange()) }}</th>
        </tr>
    </tbody>
</table>
@endsection