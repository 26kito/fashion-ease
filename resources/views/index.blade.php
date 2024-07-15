@extends('layout.template')

{{-- @section('title'){{ $title }}@endsection --}}

@push('stylesheet')
<link rel="stylesheet" href="{{asset('asset/css/owl.carousel.min.css')}}">
@endpush

@section('content')
<!-- Hero section -->
<section class="hero-section">
	<div class="hero-slider owl-carousel">
		@foreach ($latestProducts as $row)
		<div class="hs-item set-bg" data-setbg="{{ asset('asset/img/bg.jpg') }}">
			<div class="container">
				<div class="row">
					<div class="col-xl-6 col-lg-7 text-white">
						<span>New Arrivals</span>
						<h2>{{ $row->name }}</h2>
						<p>{{ $row->description }}</p>
						<a href="{{ 'product/'.$row->product_id }}" class="site-btn sb-line">DISCOVER</a>
						<a href="{{ 'product/'.$row->product_id }}" class="site-btn sb-white">ADD TO CART</a>
					</div>
				</div>
				<div class="offer-card text-white">
					<span>from</span>
					<h2>{{ $row->price }}</h2>
					<p>SHOP NOW</p>
				</div>
			</div>
		</div>
		@endforeach
	</div>
</section>
<!-- Hero section end -->

<!-- Features section -->
<section class="features-section">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 p-0 feature">
				<div class="feature-inner">
					<div class="feature-icon">
						<img src="{{ asset('asset/img/icons/1.png') }}" alt="">
					</div>
					<h2>Fast Secure Payments</h2>
				</div>
			</div>
			<div class="col-md-4 p-0 feature">
				<div class="feature-inner">
					<div class="feature-icon">
						<img src="{{ asset('asset/img/icons/2.png') }}" alt="#">
					</div>
					<h2>Premium Products</h2>
				</div>
			</div>
			<div class="col-md-4 p-0 feature">
				<div class="feature-inner">
					<div class="feature-icon">
						<img src="{{ asset('asset/img/icons/3.png') }}" alt="#">
					</div>
					<h2>Free & fast Delivery</h2>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Features section end -->

<!-- latest product section -->
<section class="top-letest-product-section">
	<div class="container">
		<div class="section-title">
			<h2>LATEST PRODUCTS</h2>
		</div>
		@livewire('latest-products')
	</div>
</section>
<!-- latest product section end -->

<!-- Product filter section -->
<section class="product-filter-section">
	<div class="container">
		<div class="section-title">
			<h2>BROWSE TOP SELLING PRODUCTS</h2>
		</div>
		@livewire('products-list')
	</div>
</section>
<!-- Product filter section end -->

<!-- Banner section -->
<section class="banner-section">
	<div class="container">
		<div class="banner set-bg" data-setbg="{{ asset('asset/img/banner-bg.jpg') }}">
			<div class="tag-new">NEW</div>
			<span>New Arrivals</span>
			<h2>STRIPED SHIRTS</h2>
			<a href="#" class="site-btn">SHOP NOW</a>
		</div>
	</div>
</section>
<!-- Banner section end  -->
@endsection

@push('script')
<script src="{{asset('asset/js/owl.carousel.min.js')}}"></script>
@endpush