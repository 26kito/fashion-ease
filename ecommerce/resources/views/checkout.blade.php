@extends('layout.template')

@section('title')
	{{$title}}
@endsection

@section('content')
<!-- Page info -->
<div class="page-top-info">
	<div class="container">
		<h4>Checkout</h4>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb site-pagination">
			  <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			  <li class="breadcrumb-item active" aria-current="page">Checkout</li>
			</ol>
		  </nav>
	</div>
</div>
<!-- Page info end -->

<!-- checkout section  -->
<section class="checkout-section spad">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 order-2 order-lg-1">
				<form class="checkout-form">
					<div class="cf-title">Billing Address</div>
					<div class="row">
						<div class="col-md-7">
							<p>*Billing Information</p>
						</div>
						<div class="col-md-5">
							<div class="cf-radio-btns address-rb">
								<div class="cfr-item">
									<input type="radio" name="pm" id="one">
									<label for="one">Use my regular address</label>
								</div>
								<div class="cfr-item">
									<input type="radio" name="pm" id="two">
									<label for="two">Use a different address</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row address-inputs">
						<div class="col-md-12">
							<input type="text" placeholder="Address">
							<input type="text" placeholder="Address line 2">
							<input type="text" placeholder="Country">
						</div>
						<div class="col-md-6">
							<input type="text" placeholder="Zip code">
						</div>
						<div class="col-md-6">
							<input type="text" placeholder="Phone no.">
						</div>
					</div>
					<div class="cf-title">Delievery Info</div>
					<div class="row shipping-btns">
						<div class="col-6">
							<h4>Standard</h4>
						</div>
						<div class="col-6">
							<div class="cf-radio-btns">
								<div class="cfr-item">
									<input type="radio" name="shipping" id="ship-1">
									<label for="ship-1">Free</label>
								</div>
							</div>
						</div>
						<div class="col-6">
							<h4>Next day delievery  </h4>
						</div>
						<div class="col-6">
							<div class="cf-radio-btns">
								<div class="cfr-item">
									<input type="radio" name="shipping" id="ship-2">
									<label for="ship-2">$3.45</label>
								</div>
							</div>
						</div>
					</div>
					<div class="cf-title">Payment</div>
					<ul class="payment-list">
						<li>Paypal<a href="#"><img src="{{asset('asset/img/paypal.png')}}" alt=""></a></li>
						<li>Credit / Debit card<a href="#"><img src="{{asset('asset/img/mastercart.png')}}" alt=""></a></li>
						<li>Pay when you get the package</li>
					</ul>
					<button class="site-btn submit-order-btn">Place Order</button>
				</form>
			</div>
			<div class="col-lg-4 order-1 order-lg-2">
				<div class="checkout-cart">
					<h3>Your Cart</h3>
					<ul class="product-list">
						@foreach ( $order_items as $row )
						<li>
							<div class="pl-thumb"><img src="{{ asset('asset/img/cart/'. $row->image )}}" alt=""></div>
							<h6>{{ $row->prodName }}</h6>
							<p>Size : {{ $row->size }}</p>
							<p>{{ rupiah($row->price) }}</p>
						</li>
						@endforeach
					</ul>
					<ul class="price-list">
						<li>Total<span>{{ rupiah($total) }}</span></li>
						<li>Shipping<span>free</span></li>
						<li class="total">Total<span>{{ rupiah($total) }}</span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- checkout section end -->
@endsection