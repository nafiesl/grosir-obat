<div class="panel-body">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga Satuan ({{ $draft->type }})</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($queriedProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ formatRp($draft->type == 'cash' ? $product->cash_price : $product->credit_price) }}</td>
                <td>
                    <form action="{{ route('cart.add-draft-item', [$draft->draftKey, $product->id]) }}" method="post" style="display:inline">
                        {{ csrf_field() }}
                        <input type="number" id="qty-{{ $product->id }}" style="width:50px" name="qty" value="1">
                        <input type="submit" id="add-product-{{ $product->id }}" value="Tambah">
                    </form>
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