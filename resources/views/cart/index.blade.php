@extends('layouts.app')

@section('title', 'Entry Transaksi')

@section('content')
<?php use Facades\App\Cart\CartCollection; ?>
@if (CartCollection::isEmpty())
    <h3 class="page-header">{{ trans('nav_menu.draft_list') }}</h3>
    <form action="{{ route('cart.add') }}" method="POST">
        {{ csrf_field() }}
        <p class="text-muted">Anda belum memiliki Draft Transaksi, silakan buat Transaksi Baru:</p>
        <input type="submit" class="btn btn-default navbar-btn" name="create-cash-draft" id="cash-draft-create-button" value="{{ trans('transaction.create_cash') }}">
        <input type="submit" class="btn btn-default navbar-btn" name="create-credit-draft" id="credit-draft-create-button" value="{{ trans('transaction.create_credit') }}">
    </form>
@endif
@includeWhen(! CartCollection::isEmpty(), 'cart.partials.transaction-draft-tabs')
@if ($draft)
    @if (Request::get('action') == 'confirm')
        @include('cart.partials.draft-confirm')
    @else
        @include('cart.partials.product-search-box')
        <div class="row">
            <div class="col-md-9">@include('cart.partials.draft-item-list')</div>
            <div class="col-md-3">@include('cart.partials.form-draft-detail')</div>
        </div>
    @endif
@endif
@endsection

@if ($draft)
@section('script')
<script>
(function() {
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#query').keyup(function() {
        delay(function() {
            var query = $('#query').val();
            if (query.length >= 3) {
                $.post(
                    "{{ route('api.products.search') }}",
                    {
                        query: query,
                        draftKey: '{{ $draft->draftKey }}',
                        draftType: '{{ $draft->type }}',
                        formToken: '{{ csrf_token() }}',
                    },
                    function(data) {
                    $('#product-search-result-box').html(data);
                });
            }
        }, 200 );
    });
})();
</script>
@endsection
@endif