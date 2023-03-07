@extends('layout.template')

@section('title')
{{$title}}
@endsection

@section('content')
<!-- checkout section  -->
<section class="checkout-section spad" style="margin-top: 200px">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 order-2 order-lg-1">
				<div class="checkout-form">
					@livewire('delivery-address')
					@livewire('delivery-info')
					{{-- <div class="cf-title">Payment</div>
					<ul class="payment-list">
						<li>Paypal<a href="#"><img src="{{ asset('asset/img/paypal.png') }}" alt=""></a></li>
						<li>Credit / Debit card<a href="#"><img src="{{ asset('asset/img/mastercart.png') }}"
									alt=""></a></li>
						<li>Pay when you get the package</li>
					</ul> --}}
					<div class="cf-title">Payment</div>
					<div class="row m-0">
						<ul>
							@foreach ($paymentMethod as $row)
							<li class="form-check">
								<input type="radio" name="paymentMethod" id="payment{{ $row->id }}" value="{{ $row->id }}">
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
					@livewire('total-price-checkout', ['cartItemsID' => $cartItemsID])
				</div>
			</div>
		</div>
	</div>
</section>
<!-- checkout section end -->
@endsection

@push('js')
<script>
	$(document).on('click', '#placeOrder', () => {
		let orderItems = @json($orderItems);
		let address = $('.user-address').attr('data-user-address');
		let shippingCost = $('#shippingCost').attr('data-shipping-fee');
		let paymentMethodID = $('input[name="paymentMethod"]:checked').val();

		if (!address) {
			let event = new CustomEvent('toastr', {
				'detail': {
					'status': 'info', 
					'message': 'Isi alamatmu dluu yuk'
				}
			});
			
			window.dispatchEvent(event);
			
			setTimeout(() => {
				$('#addressModal').modal('show');
			}, 1000);
		} else if (!shippingCost) {
			let event = new CustomEvent('toastr', {
				'detail': {
					'status': 'info', 
					'message': 'Pilih layanan pengiriman dulu ya'
				}
			});
	
			window.dispatchEvent(event);

			setTimeout(() => {
				$('#deliveryModal').modal('show');
			}, 1000);
		} else if (!paymentMethodID) {
			let event = new CustomEvent('toastr', {
				'detail': {
					'status': 'info', 
					'message': 'Pilih metode pembayaran dulu ya'
				}
			});
	
			window.dispatchEvent(event);
		} else {
			$.ajax({
				type: "POST",
				url: `/save-order`,
				dataType: 'json',
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: {
					'data': orderItems,
					'shippingCost': shippingCost,
					'paymentMethodID': paymentMethodID
				},
				success: function(result) {
					window.livewire.emit('refreshCart');
					let event = new CustomEvent('toastr', {
						'detail': {
							'status': 'success', 
							'message': result.message
						}
					});
			
					window.dispatchEvent(event);
				}
			})
		}
	})
</script>
@endpush