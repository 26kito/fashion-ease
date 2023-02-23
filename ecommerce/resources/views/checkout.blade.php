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
					<div class="cf-title">Payment</div>
					<ul class="payment-list">
						<li>Paypal<a href="#"><img src="{{ asset('asset/img/paypal.png') }}" alt=""></a></li>
						<li>Credit / Debit card<a href="#"><img src="{{ asset('asset/img/mastercart.png') }}" alt=""></a></li>
						<li>Pay when you get the package</li>
					</ul>
					<a class="site-btn submit-order-btn">Place Order</a>
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