@extends('layouts.app')

@section('title', trans('product.list'))

@section('content')
<div class="pull-right">
    {{ link_to_route('products.price-list', trans('product.print_price_list'), [], ['class' => 'btn btn-info']) }}
    {{ link_to_route('products.index', trans('product.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
</div>
<h3 class="page-header">
    {{ trans('product.list') }}
    <small>{{ trans('app.total') }} : {{ $products->total() }} {{ trans('product.product') }}</small>
</h3>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('q', ['value' => request('q'), 'label' => trans('product.search'), 'class' => 'input-sm']) !!}
                {{ Form::submit(trans('product.search'), ['class' => 'btn btn-sm']) }}
                {{ link_to_route('products.index', trans('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th>{{ trans('product.name') }}</th>
                        <th>{{ trans('product.unit') }}</th>
                        <th class="text-right">{{ trans('product.cash_price') }}</th>
                        <th class="text-right">{{ trans('product.credit_price') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $customersTotal = 0 ?>
                    @foreach($products as $key => $product)
                    <tr>
                        <td class="text-center">{{ $products->firstItem() + $key }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->unit->name }}</td>
                        <td class="text-right">{{ formatRp($product->cash_price) }}</td>
                        <td class="text-right">{{ formatRp($product->credit_price) }}</td>
                        <td class="text-center">
                            {!! link_to_route('products.index', trans('app.edit'), ['action' => 'edit', 'id' => $product->id] + Request::only('page','q'), ['id' => 'edit-product-' . $product->id]) !!} |
                            {!! link_to_route('products.index', trans('app.delete'), ['action' => 'delete', 'id' => $product->id] + Request::only('page','q'), ['id' => 'del-product-' . $product->id]) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="panel-body">{!! str_replace('/?', '?', $products->appends(Request::except('page'))->render()) !!}</div>
        </div>
    </div>
    <div class="col-md-4">
        @include('products.partials.forms')
    </div>
</div>
@endsection