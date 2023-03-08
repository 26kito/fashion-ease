@extends('layout.template')

@section('title')
{{$title}}
@endsection

@section('content')
<!-- cart section -->
<form action="{{ route('checkout') }}" method="POST" style="margin-top: 220px">
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
					@livewire('promo')
					<div>
						@if ( $totalOrders > 0 )
						<button type="submit" id="proceedCheckout" class="site-btn">Proceed to Checkout</button>
						@endif
						<a href="{{ route('home') }}" class="site-btn sb-dark">Continue Shopping</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</form>
<!-- cart section end -->
@endsection

@push('js')
@if (Session::has('error'))
<script>
	let status = 400;
</script>
@endif
@if (Session::has('status'))
<script>
	let status = {{ Session::get('status') }}

    if (status == 200) {
        let event = new CustomEvent('toastr', {
            'detail': {
                'status': 'success', 
                'message': 'Pesanan berhasil dihapus'
            }
        });

        window.dispatchEvent(event);
	}
</script>
@endif
<script>
	if (status == 400) {
		toastr.info('Pilih pesanan yang mau di checkout dulu yaa')
	}
</script>
@endpush