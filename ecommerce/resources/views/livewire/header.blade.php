<div class="header-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 text-center text-lg-left">
                <!-- logo -->
                <a href="home" class="site-logo">
                    <img src="{{asset('asset/img/logo.png')}}" alt="">
                </a>
            </div>
            <div class="col-xl-6 col-lg-5">
                <form class="header-search-form">
                    <input type="text" placeholder="Search on divisima ....">
                    <button><i class="flaticon-search"></i></button>
                </form>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="user-panel d-flex">
                    <div class="up-item">
                        @auth
                        <i class="flaticon-profile"></i>
                        <div class="nav-item dropdown d-inline-block">
                            <a class="nav-link dropdown-toggle profile-dropdown" id="dropdownUser" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                                {{ __('Hi! '). Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownUser">
                                @if ( Auth::user()->level === 'ADMIN' )
                                    <a class="dropdown-item" href="{{ route('admin') }}">Admin Page</a>
                                @elseif ( Auth::user()->level === 'USER' )
                                    <a class="dropdown-item" href="#">My Profile</a>
                                    <a class="dropdown-item" href="#">Wishlist</a>
                                @endif
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        Log Out
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </div>
                        </div>
                        @else
                        <div class="anon">
                            <i class="flaticon-profile"></i>
                            <a href="{{ route('login') }}">Sign In</a> or <a href="{{ route('register') }}">Create Account</a>
                        </div>
                        @endauth
                    </div>
                    <div class="up-item">
                        <div class="shopping-card">
                            <i class="flaticon-bag"></i>
                            @auth
                            @if ( Auth::user()->level === 'USER' )
                                @if ( $cartQty->qty > 0 )
                                <span class="qty">{{ $cartQty->qty }}</span>
                                @endif
                            @endif
                            @endauth
                        </div>
                        <a href="{{ route('cart') }}" class="orderCart">Your Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>