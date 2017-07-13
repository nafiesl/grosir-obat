<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            @if (Auth::check())
            <ul class="nav navbar-nav">
                <li {{ in_array(Request::segment(1), ['drafts','home']) ? 'class=active' : '' }}>
                    {{ link_to_route('cart.index', trans('nav_menu.draft_list'), [], ['class' => 'strong text-primary']) }}
                </li>
                <li {{ (Request::segment(1) == 'transactions') ? 'class=active' : '' }}>
                    {{ link_to_route('transactions.index', trans('transaction.list')) }}
                </li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                <li>
                    <form class="" style="padding-left: 10px;" action="{{ route('cart.add') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-default navbar-btn" name="create-cash-draft" id="cash-draft-create-button" value="{{ trans('transaction.create_cash') }}">
                        <input type="submit" class="btn btn-default navbar-btn" name="create-credit-draft" id="credit-draft-create-button" value="{{ trans('transaction.create_credit') }}">
                    </form>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ trans('product.product') }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>{{ link_to_route('products.index', trans('product.list')) }}</li>
                        <li>{{ link_to_route('units.index', trans('unit.product_unit')) }}</li>
                        <li role="separator" class="divider"></li>
                        <li>{{ link_to_route('products.price-list', trans('product.print_price_list')) }}</li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li>{{ link_to_route('users.index', trans('user.list')) }}</li>
                        <li>{{ link_to_route('backups.index', trans('backup.list')) }}</li>
                        <li>{{ link_to_route('log-files.index', 'Log Files') }}</li>
                        <li>{{ link_to_route('change-password', trans('auth.change_password')) }}</li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <button type="submit" style="display: none;" id="logout-button" >Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif
        </div>
    </div>
</nav>