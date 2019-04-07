@extends('layouts.app')

@section('title', trans('transaction.list'))

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        {{ Form::open(['method' => 'get','class' => 'form-inline pull-right']) }}
        {!! FormField::text('q', [
            'value' => request('q'),
            'label' => trans('transaction.search'),
            'class' => 'input-sm',
            'placeholder' => 'Ketik No. Invoice / Nama / Telp. Customer..',
            'style' => 'width:300px',
        ]) !!}
        {!! FormField::text('date', [
            'value' => request('date', $date),
            'label' => trans('app.date'),
            'class' => 'input-sm date-select',
            'placeholder' => 'yyyy-mm-dd',
        ]) !!}
        {{ Form::submit(trans('transaction.search'), ['class' => 'btn btn-sm']) }}
        {{ link_to_route('transactions.index', trans('app.reset')) }}
        {{ Form::close() }}
        <h3 class="panel-title" style="padding:6px 0">
            {{ trans('transaction.list') }} |
            {{ trans('app.total') }} : {{ $transactions->total() }} {{ trans('transaction.transaction') }}
        </h3>
    </div>
    <div class="panel-body">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>{{ trans('app.table_no') }}</th>
                    <th>{{ trans('transaction.invoice_no') }}</th>
                    <th>{{ trans('app.date') }}</th>
                    <th>{{ trans('transaction.customer') }}</th>
                    <th>{{ trans('transaction.items_count') }}</th>
                    <th class="text-right">{{ trans('transaction.total') }}</th>
                    <th class="text-center">{{ trans('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $key => $transaction)
                <tr>
                    <td>{{ $transactions->firstItem() + $key }}</td>
                    <td>{{ $transaction->invoice_no }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    <td>
                        {{ $transaction->customer['name'] }}
                        {{ $transaction->customer['phone'] ? '(' . $transaction->customer['phone'] . ')' : '' }}
                    </td>
                    <td>{{ $transaction->items_count }}</td>
                    <td class="text-right">{{ format_rp($transaction->total) }}</td>
                    <td class="text-center">
                        {{ link_to_route('transactions.show', trans('app.show'), $transaction->invoice_no) }} |
                        {{ link_to_route('transactions.receipt', trans('app.print'), $transaction->invoice_no) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        Transaksi tidak ditemukan
                        @if (request('q'))
                            dengan keyword <em class="strong">{{ request('q') }}</em>
                        @endif
                        @if (request('date'))
                            dan pada tanggal <em class="strong">{{ request('date') }}</em>.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>{!! str_replace('/?', '?', $transactions->appends(Request::except('page'))->render()) !!}</div>
    </div>
</div>
@endsection

@section('ext_css')
{!! Html::style(url('css/plugins/jquery.datetimepicker.css')) !!}
@endsection

@push('ext_js')
{!! Html::script(url('js/plugins/jquery.datetimepicker.js')) !!}
@endpush

@section('script')
<script>
(function() {
    $('.date-select').datetimepicker({
        timepicker: false,
        format:'Y-m-d',
        closeOnDateSelect: true
    });
})();
</script>
@endsection