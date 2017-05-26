@extends('layouts.app')

@section('title', trans('transaction.list'))

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        {{ Form::open(['method' => 'get','class' => 'form-inline pull-right']) }}
        {!! FormField::text('q', ['value' => request('q'), 'label' => trans('transaction.search'), 'class' => 'input-sm']) !!}
        {{ Form::submit(trans('transaction.search'), ['class' => 'btn btn-sm']) }}
        {{ link_to_route('transactions.index', trans('app.reset')) }}
        {{ Form::close() }}
        <h3 class="panel-title">
            {{ trans('transaction.list') }} |
            {{ trans('app.total') }} : {{ $transactions->total() }} {{ trans('transaction.transaction') }}
        </h3><div class="clearfix"></div>
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
                    <th>{{ trans('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $key => $transaction)
                <tr>
                    <td>{{ 1 + $key }}</td>
                    <td>{{ $transaction->invoice_no }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    <td>{{ $transaction->customer['name'] }}</td>
                    <td>{{ $transaction->items_count }}</td>
                    <td class="text-right">{{ formatRp($transaction->total) }}</td>
                    <td>
                        {{ link_to_route('transactions.show', trans('app.show'), $transaction->invoice_no) }} |
                        {{ link_to_route('transactions.pdf', trans('app.print'), $transaction->invoice_no) }}
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection