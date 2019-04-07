@extends('layouts.app')

@section('title', __('report.monthly', ['year_month' => $months[$month].' '.$year]))

@section('content')

{{ Form::open(['method' => 'get', 'class' => 'form-inline well well-sm']) }}
{{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label']) }}
{{ Form::select('month', $months, $month, ['class' => 'form-control']) }}
{{ Form::select('year', $years, $year, ['class' => 'form-control']) }}
{{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info btn-sm']) }}
{{ link_to_route('reports.sales.monthly', __('report.this_month'), [], ['class' => 'btn btn-default btn-sm']) }}
{{ link_to_route('reports.sales.yearly', __('report.view_yearly'), ['year' => $year], ['class' => 'btn btn-default btn-sm']) }}
{{ Form::close() }}

<div class="panel panel-primary">
    <div class="panel-heading"><h3 class="panel-title">{{ __('report.sales_graph') }} {{ $months[$month] }} {{ $year }}</h3></div>
    <div class="panel-body">
        <strong>Rp.</strong>
        <div id="monthly-chart" style="height: 250px;"></div>
        <div class="text-center"><strong>{{ __('time.date') }}</strong></div>
    </div>
</div>

<div class="panel panel-success table-responsive">
    <div class="panel-heading"><h3 class="panel-title">{{ __('report.detail') }}</h3></div>
    <div class="panel-body">
        <table class="table table-condensed">
            <thead>
                <th class="text-center">{{ __('time.date') }}</th>
                <th class="text-center">{{ __('transaction.transaction') }}</th>
                <th class="text-right">{{ __('report.omzet') }}</th>
                <th class="text-center">{{ __('app.action') }}</th>
            </thead>
            <tbody>
                @php $chartData = []; @endphp
                @foreach(month_date_array($year, $month) as $dateNumber)
                @php
                    $any = isset($reports[$dateNumber]);
                    $count = $any ? $reports[$dateNumber]->count : 0;
                    $subtotal = $any ? $reports[$dateNumber]->amount : 0;
                @endphp
                @if ($any)
                    <tr>
                        <td class="text-center">{{ date_id($date = $year.'-'.$month.'-'.$dateNumber) }}</td>
                        <td class="text-center">{{ $count }}</td>
                        <td class="text-right">{{ format_rp($subtotal) }}</td>
                        <td class="text-center">
                            {{ link_to_route(
                                'reports.sales.daily',
                                __('report.view_daily'),
                                ['date' => $date],
                                [
                                    'class' => 'btn btn-info btn-xs',
                                    'title' => __('report.daily', ['date' => date_id($date)]),
                                ]
                            ) }}
                        </td>
                    </tr>
                @endif
                @php
                    $chartData[] = ['date' => $dateNumber, 'value' => ($subtotal) ];
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right">{{ __('app.total') }}</th>
                    <th class="text-center">{{ $reports->sum('count') }}</th>
                    <th class="text-right">{{ format_rp($reports->sum('amount')) }}</th>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('ext_css')
    {{ Html::style(url('css/plugins/morris.css')) }}
@endsection

@push('ext_js')
    {{ Html::script(url('js/plugins/raphael.min.js')) }}
    {{ Html::script(url('js/plugins/morris.min.js')) }}
@endpush

@section('script')
<script>
(function() {
    new Morris.Line({
        element: 'monthly-chart',
        data: {!! collect($chartData)->toJson() !!},
        xkey: 'date',
        ykeys: ['value'],
        labels: ["{{ __('report.omzet') }} Rp"],
        parseTime:false,
        xLabelAngle: 30,
        goals: [0],
        goalLineColors : ['red'],
        lineWidth: 2,
    });
})();
</script>
@endsection
