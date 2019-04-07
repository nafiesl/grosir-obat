@extends('layouts.app')

@section('title', __('report.daily', ['date' => date_id($date)]))

@section('content')

@php $dt = Carbon\Carbon::parse($date); @endphp

{{ Form::open(['method' => 'get', 'class' => 'form-inline well well-sm']) }}
{{ Form::label('date', __('report.view_daily_label'), ['class' => 'control-label']) }}
{{ Form::text('date', $date, ['required', 'class' => 'form-control', 'style' => 'width:100px']) }}
{{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info btn-sm']) }}
{{ link_to_route('reports.sales.daily', __('report.today'), [], ['class' => 'btn btn-default btn-sm']) }}
{{ link_to_route(
    'reports.sales.monthly',
    __('report.view_monthly'),
    ['month' => month_number($dt->month), 'year' => $dt->year],
    ['class' => 'btn btn-default btn-sm']
) }}
{{ Form::close() }}

<div class="panel panel-default table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
            <th class="text-center">{{ __('app.table_no') }}</th>
            <th class="text-center">{{ __('app.date') }}</th>
            <th class="text-right">{{ __('report.omzet') }}</th>
            <th class="text-center">{{ __('app.action') }}</th>
        </thead>
        <tbody>
            @forelse($transactions as $key => $transaction)
            <tr>
                <td class="text-center">{{ 1 + $key }}</td>
                <td class="text-center">{{ date_id($transaction->created_at->format('Y-m-d')) }}</td>
                <td class="text-right">{{ format_rp($transaction->total) }}</td>
                <td class="text-center">
                    {{ link_to_route(
                        'transactions.show',
                        __('app.show'),
                        [$transaction],
                        [
                            'title' => __('app.show_detail_title', ['name' => $transaction->number, 'type' => trans('transaction.transaction')]),
                            'target' => '_blank',
                            'class' => 'btn btn-info btn-xs'
                        ]
                    ) }}
                </td>
            </tr>
            @empty
            <tr><td colspan="4">{{ __('transaction.not_found') }}</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="2">{{ __('app.total') }}</th>
                <th class="text-right">{{ format_rp($transactions->sum('total')) }}</th>
                <th>&nbsp;</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

@section('ext_css')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@section('script')
{{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function() {
    $('#date').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false
    });
})();
</script>
@endsection
