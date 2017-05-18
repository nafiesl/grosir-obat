<div class="panel panel-default" style="border-radius:0px">
    <div class="panel-heading">
        <form method="get" action="{{ route('cart.show', $draft->draftKey) }}">
            <label for="query">{{ trans('cart.product_search') }}</label>
            <input type="text" id="query" name="query" value="{{ request('query') }}" placeholder="{{ trans('cart.min_search_query') }}">
            <input type="submit" value="{{ trans('cart.product_search') }}" class="btn btn-sm">
            @if ($queriedProducts)
            {{ link_to_route('cart.show', trans('cart.search_box_cleanup'), [$draft->draftKey], ['class' => 'btn btn-sm']) }}
            @endif
        </form>
    </div>
    <div id="product-search-result-box">
    @includeWhen ($queriedProducts, 'cart.partials.product-search-result-box', [
        'draftType' => $draft->type,
        'draftKey' => $draft->draftKey
    ])
    </div>
</div>