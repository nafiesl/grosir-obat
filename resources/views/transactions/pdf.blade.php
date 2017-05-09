@extends('layouts.pdf')

@section('title', $transaction->invoice_no.' - '.trans('transaction.invoice_print'))

@section('content')

<table style="width: 760px; border-collapse: collapse; page-break-inside: avoid; /*border:1px solid #aaa;*/">
    <tbody>
        <tr>
            <td colspan="3">
                <h2 class="text-center">{{ config('store.name') }}</h2>
                <p class="text-center">{{ config('store.address') }} | Telp: {{ config('store.phone') }}</p>
                <br>
                <table style="width:300px;margin: auto;">
                    <tbody>
                        <tr>
                            <td style="width:90px">{{ trans('transaction.invoice_no') }}</td>
                            <td>:</td>
                            <td class="strong">{{ $transaction->invoice_no }}</td>
                            <td class="text-right">{{ $transaction->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr><td>{{ trans('transaction.cashier') }}</td><td>:</td><td>{{ $transaction->user->name }}</td><td class="text-right">{{ $transaction->created_at->format('H:i:s') }}</td></tr>
                        <tr><td>{{ trans('transaction.customer') }}</td><td>:</td><td colspan="2">{{ $transaction->customer['name'] }}</td></tr>
                        <tr><td>{{ trans('transaction.customer_phone') }}</td><td>:</td><td colspan="2">{{ $transaction->customer['phone'] }}</td></tr>
                    </tbody>
                </table>
                <br>
            </td>
        </tr>
        <?php $discountTotal = 0; ?>
        @foreach(collect($transaction->items)->chunk(30) as $chuncked30Items)
        <tr>
            @foreach($chuncked30Items->chunk(10) as $chunckedItems)
            <td style="width:250px;padding-right: 10px">
                <table class="main-table">
                    <tbody>
                        <tr><th colspan="3">@if ($loop->first) {{ trans('product.product') }} @else &nbsp; @endif</th></tr>
                        <tr>
                            <th class="">@if ($loop->first) {{ trans('product.item_qty') }} @else &nbsp; @endif</th>
                            <th class="text-right">@if ($loop->first) {{ trans('product.price') }} ({{ trans('product.item_discount') }}) @else &nbsp;<br>&nbsp; @endif</th>
                            <th class="text-right" style="width:90px">@if ($loop->first) {{ trans('product.item_subtotal') }} @else &nbsp; @endif</th>
                        </tr>
                        @foreach($chunckedItems as $key => $item)
                        <tr>
                            <td class="strong" colspan="3">{{ $key + 1 }})&nbsp;{{ $item['name'] }} ({{ $item['unit'] }})</td>
                        </tr>
                        <tr>
                            <td class="text-center border-bottom">{{ $item['qty'] }}</td>
                            <td class="text-right border-bottom">
                                {{ formatRp($item['price']) }}<br>({{ formatRp($item['item_discount']) }})
                            </td>
                            <td class="text-right border-bottom">{{ formatRp($item['subtotal']) }}</td>
                        </tr>
                        <?php $discountTotal += $item['item_discount_subtotal'] ?>
                        @endforeach
                        @if ($loop->last && $loop->parent->last)
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
                        @endif
                    </tbody>
                </table>
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
@endsection