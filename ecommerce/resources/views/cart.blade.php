@extends('layout.template')

@section('title'){{ $title }}@endsection

@section('content')
<!-- cart section -->
<form action="{{ route('checkout') }}" method="GET" style="margin-top: 20px">
	@csrf
	<section class="cart-section spad">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="cart-table">
						@if ( $totalOrders > 0 )
						<h3>Your Cart</h3>
						@livewire('cart', ['page' => request()->fullUrl()])
						@livewire('total-price-cart')
						@else
						<h3 class="text-center">{{ "Duh, keranjangmu kosong nih:(" }}</h3>
						@if ( $wishlist > 0 )
						<p class="text-center">Yuk isi keranjangmu dengan barang-barang impianmu!</p>
						@endif
						@endif
					</div>
					@if ( $wishlist > 0 )
					@livewire('wishlist-section')
					@endif
				</div>
				<div class="col-lg-4 card-right">
					<div>
						@if ( $totalOrders > 0 )
						@livewire('promo')
						<button type="submit" id="proceedCheckout" class="site-btn">Proceed to Checkout</button>
						@endif
						<a href="{{ route('home') }}" class="site-btn sb-dark {{ $totalOrders == 0 ? 'mt-5' : ''}}">Continue Shopping</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</form>
<!-- cart section end -->
@endsection

@push('script')
@if (Session::has('status'))
<script src="{{ asset('js/customNotif.js') }}"></script>
<script>
	const statusCode = {{ Session::get('status') }};
	let status, message;

	if (statusCode == 200) {
		message = 'Pesanan berhasil dihapus';
		status = 'success';
	} else {
		message = 'Pilih pesanan yang mau di checkout dulu yaa';
		status = 'info';
	}

	let event = customNotif.notif(status, message);
		
	window.dispatchEvent(event);
</script>
@endif
@endpush