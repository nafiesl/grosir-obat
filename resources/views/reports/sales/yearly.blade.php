@extends('layouts.app')

@section('title', __('report.yearly', ['year' => $year]))

@section('content')

{{ Form::open(['method' => 'get', 'class' => 'form-inline well well-sm']) }}
{{ Form::label('year', __('report.view_yearly_label'), ['class' => 'control-label']) }}
{{ Form::select('year', $years, $year, ['class' => 'form-control']) }}
{{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info btn-sm']) }}
{{ link_to_route('reports.sales.yearly', __('report.this_year'), [], ['class' => 'btn btn-default btn-sm']) }}
{{ Form::close() }}

<div class="panel panel-primary">
    <div class="panel-heading"><h3 class="panel-title">{{ __('report.sales_graph') }} {{ $year }}</h3></div>
    <div class="panel-body">
        <strong>Rp.</strong>
        <div id="yearly-chart" style="height: 250px;"></div>
        <div class="text-center"><strong>{{ __('time.month') }}</strong></div>
    </div>
</div>

<div class="panel panel-success table-responsive">
    <div class="panel-heading"><h3 class="panel-title">{{ __('report.detail') }}</h3></div>
    <div class="panel-body table-responsive">
        <table class="table table-condensed">
            <thead>
                <th class="text-center">{{ __('time.month') }}</th>
                <th class="text-center">{{ __('transaction.transaction') }}</th>
                <th class="text-right">{{ __('report.omzet') }}</th>
                <th class="text-center">{{ __('app.action') }}</th>
            </thead>
            <tbody>
                @php $chartData = []; @endphp
                @foreach(get_months() as $month_number => $monthName)
                @php
                    $any = isset($reports[$month_number]);
                    $omzet = $any ? $reports[$month_number]->omzet : 0
                @endphp
                <tr>
                    <td class="text-center">{{ month_id($month_number) }}</td>
                    <td class="text-center">{{ $any ? $reports[$month_number]->count : 0 }}</td>
                    <td class="text-right">{{ format_rp($omzet) }}</td>
                    <td class="text-center">
                        {{ link_to_route(
                            'reports.sales.monthly',
                            __('report.view_monthly'),
                            ['month' => $month_number, 'year' => $year],
                            [
                                'class' => 'btn btn-info btn-xs',
                                'title' => __('report.monthly', ['year_month' => month_id($month_number)]),
                                'title' => __('report.monthly', ['year_month' => month_id($month_number).' '.$year]),
                            ]
                        ) }}
                    </td>
                </tr>
                @php
                    $chartData[] = ['month' => month_id($month_number), 'value' => $omzet];
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">{{ trans('app.total') }}</th>
                    <th class="text-center">{{ $reports->sum('count') }}</th>
                    <th class="text-right">{{ format_rp($reports->sum('omzet')) }}</th>
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
        element: 'yearly-chart',
        data: {!! collect($chartData)->toJson() !!},
        xkey: 'month',
        ykeys: ['value'],
        labels: ["{{ __('report.omzet') }} Rp"],
        parseTime:false,
        goals: [0],
        goalLineColors : ['red'],
        smooth: true,
        lineWidth: 2,
    });
})();
</script>
@endsection
