@extends('layout.template')

@section('title')
{{$title}}
@endsection

@section('content')
<form action="{{ route('checkout') }}" method="POST" style="margin-top: 150px">
	@csrf
	<section class="cart-section spad">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="cart-table">
						{{-- @if ( $totalOrders > 0 ) --}}
						<h3>Wishlist</h3>
						@livewire('wishlist')
						{{-- @else --}}
						{{-- <h3 class="text-center">Duh, keranjangmu kosong nih:(</h3> --}}
						{{-- <p class="text-center">Yuk isi keranjangmu dengan barang-barang impianmu!</p> --}}
						{{-- @endif --}}
					</div>
				</div>
				{{-- <div class="col-lg-4 card-right">
					@livewire('promo')
					<div>
						@if ( $totalOrders > 0 )
						<button type="submit" id="proceedCheckout" class="site-btn">Proceed to Checkout</button>
						@endif
						<a href="{{ route('home') }}" class="site-btn sb-dark">Continue Shopping</a>
					</div>
				</div> --}}
			</div>
		</div>
	</section>
</form>
@endsection