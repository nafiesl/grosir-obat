<div class="panel panel-default" style="border-radius:0px">
    <div class="panel-heading">
        <form method="get" action="{{ route('cart.show', $draft->draftKey) }}">
            <label for="query">{{ trans('cart.product_search') }}</label>
            <input type="text" id="query" name="query" value="{{ request('query') }}">
            <input type="submit" value="{{ trans('cart.product_search') }}" class="btn btn-sm">
            <a href="{{ route('cart.show', $draft->draftKey) }}" class="btn btn-sm">Refresh</a>
        </form>
    </div>
    @includeWhen ($queriedProducts, 'cart.partials.product-search-result-box')
</div>