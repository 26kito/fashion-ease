@extends('layout.template')

@section('title'){{ $title }}@endsection

@section('content')
<!-- cart section -->
<form action="{{ route('checkout') }}" method="POST" style="margin-top: 20px">
	@csrf
	<section class="cart-section spad">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="cart-table">
						@if ( $totalOrders > 0 )
						<h4 class="cart-table-heading">Keranjang Kamu</h4>
						@livewire('cart', ['page' => request()->fullUrl()])
						@livewire('total-price-cart')
						@else
						<h3 class="text-center mt-5">Duh, keranjangmu kosong nih:(</h3>
						@if ( $wishlist > 0 )
						<p class="text-center">Yuk isi keranjangmu dengan barang-barang impianmu!</p>
						@endif
						@endif
					</div>
				</div>
				<div class="col-lg-4 card-right">
					<div>
						@if ( $totalOrders > 0 )
						@livewire('promo')
						<button type="submit" id="proceedCheckout" class="site-btn">Proceed to Checkout</button>
						@endif
						<a href="{{ route('home') }}"
							class="site-btn sb-dark {{ $totalOrders == 0 ? 'mt-5' : ''}}">Continue Shopping</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</form>

<!-- cart section end -->
{{-- @if ( $wishlist > 0 )
<div class="container">
	<div class="row mb-3">
		<div class="col-lg-4 ps-4">
			<h4>Wishlist</h4>
		</div>
		<div class="col-lg-4 text-lg-end">
			<a class="h5 text-reset" href="{{ route('wishlist') }}">Lihat Semua</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8">
			@livewire('wishlist-section')
		</div>
	</div>
</div>
@endif --}}
{{-- @livewire('wishlist-section') --}}

<div class="container">
	<div class="row mb-3">
		<div class="col-lg-4 ps-4">
			<h4>Produk Terbaru</h4>
		</div>
		<div class="col-lg-4 text-lg-end">
			<a class="h5 text-reset" href="#">Lihat Semua</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8">
			{{-- @livewire('latest-products-section') --}}
			@livewire('latest-products')
		</div>
	</div>
</div>
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