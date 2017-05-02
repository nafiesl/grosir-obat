@if (! Request::has('action'))
{{ link_to_route('products.index', trans('product.create'), ['action' => 'create'], ['class' => 'btn btn-success pull-right']) }}
@endif
@if (Request::get('action') == 'create')
    {!! Form::open(['route' => 'products.store']) !!}
    {!! FormField::text('name', ['label' => trans('product.name'), 'required' => true]) !!}
    <div class="row">
        <div class="col-md-6">{!! FormField::price('cash_price', ['label' => trans('product.cash_price'), 'required' => true]) !!}</div>
        <div class="col-md-6">{!! FormField::price('credit_price', ['label' => trans('product.credit_price')]) !!}</div>
    </div>
    {!! Form::submit(trans('product.create'), ['class' => 'btn btn-success']) !!}
    {!! Form::hidden('cat', 'product') !!}
    {{ link_to_route('products.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endif
@if (Request::get('action') == 'edit' && $editableProduct)
    {!! Form::model($editableProduct, ['route' => ['products.update', $editableProduct->id],'method' => 'patch']) !!}
    {!! FormField::text('name', ['label' => trans('product.name'), 'required' => true]) !!}
    <div class="row">
        <div class="col-md-6">{!! FormField::price('cash_price', ['label' => trans('product.cash_price'), 'required' => true]) !!}</div>
        <div class="col-md-6">{!! FormField::price('credit_price', ['label' => trans('product.credit_price')]) !!}</div>
    </div>
    @if (request('q'))
        {{ Form::hidden('q', request('q')) }}
    @endif
    {!! Form::submit(trans('product.update'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('products.index', trans('app.cancel'), Request::only('q'), ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endif
@if (Request::get('action') == 'delete' && $editableProduct)
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ trans('product.delete') }}</h3></div>
        <div class="panel-body">
            <table class="table table-condensed">
                <tbody>
                    <tr><th>{{ trans('product.name') }}</th><td>{{ $editableProduct->name }}</td></tr>
                    <tr><th>{{ trans('product.cash_price') }}</th><td>{{ formatRp($editableProduct->cash_price) }}</td></tr>
                    <tr><th>{{ trans('product.credit_price') }}</th><td>{{ formatRp($editableProduct->credit_price) }}</td></tr>
                </tbody>
            </table>
            <hr>
            {{ trans('product.delete_confirm') }}
        </div>
        <div class="panel-footer">
            {!! FormField::delete(['route'=>['products.destroy',$editableProduct->id]], trans('app.delete_confirm_button'), [
                'class'=>'btn btn-danger'
            ], [
                'product_id'=>$editableProduct->id,
                'q' => request('q')
            ]) !!}
            {{ link_to_route('products.index', trans('app.cancel'), Request::only('q'), ['class' => 'btn btn-default']) }}
        </div>
    </div>
@endif
