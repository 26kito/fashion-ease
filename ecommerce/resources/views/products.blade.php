@extends('layout.template')

@section('title')
{{ ucwords($products->name) }}
@endsection

@push('stylesheet')
<link rel="stylesheet" href="{{asset('asset/css/owl.carousel.min.css')}}">
@endpush

@section('content')
<!-- Page info -->
{{-- <div class="page-top-info">
	<div class="container">
		<h4>Details Product</h4>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb site-pagination">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item" aria-current="page">Details Product</li>
				<li class="breadcrumb-item active" aria-current="page">{{ $products->name }}</li>
			</ol>
		</nav>
	</div>
</div> --}}
<!-- Page info end -->

<!-- product section -->
<section class="product-section mt-5">
	{{-- Param 1:View. Param 2:Passing Model --}}
	@livewire('details-product', ['products' => $products, 'defaultSize' => $defaultSize])
</section>
<!-- product section end -->

<!-- RELATED PRODUCTS section -->
<section class="related-product-section">
	<div class="container">
		<div class="section-title">
			<h2>RELATED PRODUCTS</h2>
		</div>
		<div class="product-slider owl-carousel">
			@foreach( $relatedProducts as $row )
			<div class="product-item">
				<div class="pi-pic">
					<a href="{{ url('product/'.$row->product_id) }}">
						<img src="{{ asset('asset/img/products/'.$row->image) }}" alt="">
					</a>
					<div class="pi-links">
						<a href="{{ url('product/'.$row->product_id) }}" class="add-card">
							<i class="flaticon-bag"></i><span>ADD TO CART</span>
						</a>
						<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
					</div>
				</div>
				<div class="pi-text">
					<h6>{{ $row->price }}</h6>
					<p>{{ $row->name }}</p>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>
<!-- RELATED PRODUCTS section end -->
@endsection

@push('script')
<script src="{{asset('asset/js/owl.carousel.min.js')}}"></script>
@endpush