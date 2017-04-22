<?php use Facades\App\Cart\CartCollection; ?>
<ul class="nav nav-tabs transaction-draft-tabs">
    @foreach(CartCollection::content() as $key => $content)
        <?php $active = ($draft->draftKey == $key) ? 'class=active' : '' ?>
        <li {{ $active }} role="presentation">
            <a href="{{ route('cart.show', $key) }}">
                {{ trans('transaction.' . $content->type) }} - {{ $key }}
                <form action="{{ route('cart.remove') }}" method="post" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus Draft Transaksi ini?')">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <input type="hidden" name="draft_key" value="{{ $key }}">
                    <input type="submit" value="x" style="margin: -2px -7px 0px 0px" class="btn-link btn-xs pull-right">
                </form>
            </a>
        </li>
    @endforeach
</ul><!-- Tab panes -->