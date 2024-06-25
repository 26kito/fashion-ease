@extends('layout.template')

@section('title'){{ $title }}@endsection

@section('content')
<!-- checkout section  -->
<section class="checkout-section spad mt-5">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 order-2 order-lg-1">
				<div class="checkout-form">
					@livewire('delivery-address')
					@livewire('delivery-info')
					<div class="cf-title">Payment</div>
					<div class="row m-0">
						<ul>
							@foreach ($paymentMethod as $row)
							<li class="form-check">
								<input type="radio" name="paymentMethod" id="payment{{ $row->id }}"
									value="{{ $row->id }}">
								<label for="payment{{ $row->id }}">{{ $row->name }}</label>
							</li>
							@endforeach
						</ul>
					</div>
					<a class="site-btn submit-order-btn" id="placeOrder">Place Order</a>
				</div>
			</div>
			<div class="col-lg-4 order-1 order-lg-2">
				<div class="checkout-cart">
					<h3>Your Cart</h3>
					<ul class="product-list">
						@foreach ( $orderItems as $row )
						<li>
							<div class="pl-thumb"><img src="{{ asset('asset/img/cart/'. $row->image ) }}" alt=""></div>
							<h6>{{ $row->ProdName }}</h6>
							<p>Size : {{ $row->size }}</p>
							<p>Qty : {{ $row->qty }}</p>
							<p>{{ rupiah($row->Price) }}</p>
						</li>
						@endforeach
					</ul>
					@livewire('total-price-checkout', ['cartItemsID' => $cartItemsID, 'totalPriceCart' => $totalPriceCart, 'grandTotalPriceCart' => $grandTotalPriceCart])
				</div>
			</div>
		</div>
	</div>
</section>
<!-- checkout section end -->
@endsection

@push('script')
<script src="{{ asset('js/customNotif.js') }}"></script>
<script src="{{ asset('js/cookie.js') }}"></script>
<script>
	$(document).on('click', '#placeOrder', () => {
		let orderItems = @json($orderItems);
		let address = $('.user-address').attr('data-user-address')
		let shippingCost = $('#shippingCost').attr('data-shipping-fee')
		let voucherPrice = $('#voucherPrice').attr('data-voucher-price')
		let paymentMethodID = $('input[name="paymentMethod"]:checked').val()

		if (!address) {
			let event = customNotif.notif('info', 'Isi alamatmu dluu yuk');

			window.dispatchEvent(event);

			return setTimeout(() => {
				$('#addressModal').modal('show');
			}, 1000);
		} 
		
		if (!shippingCost) {
			let event = customNotif.notif('info', 'Pilih layanan pengiriman dulu ya');

			window.dispatchEvent(event);

			return setTimeout(() => {
				$('#deliveryModal').modal('show');
			}, 1000);
		} 
		
		if (!paymentMethodID) {
			let event = customNotif.notif('info', 'Pilih metode pembayaran dulu ya');

			return window.dispatchEvent(event);
		}

		if (!voucherPrice) {
			voucherPrice = null
		}

		$.ajax({
			type: "POST",
			url: `/save-order`,
			dataType: 'json',
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {
				'data': orderItems,
				'shippingCost': shippingCost,
				'shippingTo': address,
				'paymentMethodID': paymentMethodID,
				'voucherFee': voucherPrice
			},
			success: function(result) {
				// window.livewire.emit('refreshCart');

				// let event = customNotif.notif('success', result.message);
		
				// window.dispatchEvent(event);

				cookie.setCookie('payment', true, 2);

				window.location.href = '/payment-status'
			}
		})
	})
</script>
@endpush