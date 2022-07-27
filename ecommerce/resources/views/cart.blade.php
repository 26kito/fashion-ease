@extends('layout.template')

@section('title')
	{{$title}}
@endsection

@section('content')
<!-- Page info -->
<div class="page-top-info">
	<div class="container">
		<h4>Your Cart</h4>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb site-pagination">
			  <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			  <li class="breadcrumb-item active" aria-current="page">Cart</li>
			</ol>
		  </nav>
	</div>
</div>
<!-- Page info end -->

<!-- cart section -->
<section class="cart-section spad">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="cart-table">
					<h3>Your Cart</h3>
					@livewire('cart', ['order_items' => $order_items])
					<div class="total-cost">
						<h6>Total<span>{{ rupiah($total) }}</span></h6>
					</div>
				</div>
			</div>
			<div class="col-lg-4 card-right">
				<form class="promo-code-form">
					<input type="text" placeholder="Enter promo code">
					<button>Submit</button>
				</form>
				@if ( $total_orders->order_items_count > 0 )
				<a href="{{ route('checkout') }}" class="site-btn">Proceed to checkout</a>
				@endif
				<a href="{{ route('home') }}" class="site-btn sb-dark">Continue shopping</a>
			</div>
		</div>
	</div>
</section>
<!-- cart section end -->
@endsection
