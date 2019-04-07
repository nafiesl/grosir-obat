<div class="panel-body table-responsive">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>{{ trans('product.name') }}</th>
                <th>{{ trans('product.unit') }}</th>
                <th>{{ trans('product.price') }} ({{ $draftType }})</th>
                <th>{{ trans('app.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($queriedProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->unit->name }}</td>
                <td>{{ format_rp($product->getPrice($draftType)) }}</td>
                <td>
                    <form action="{{ route('cart.add-draft-item', [$draftKey, $product->id]) }}" method="post" style="display:inline">
                        <input type="hidden" name="query" value="{{ isset($query) ? $query : request('query') }}">
                        <input type="hidden" name="_token" value="{{ isset($formToken) ? $formToken : csrf_token() }}">
                        <input type="number" id="qty-{{ $product->id }}" style="width:50px" name="qty" value="1" min="1">
                        <input type="submit" id="add-product-{{ $product->id }}" value="Tambah">
                    </form>
                    @if ($loop->last)
                    {{ link_to_route('cart.show', trans('cart.search_box_cleanup'), [$draftKey], [
                        'class' => 'btn btn-sm btn-default pull-right'
                    ]) }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">
                    Produk tidak ditemukan dengan keyword : <strong><em>{{ request('query') }}</em></strong>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>