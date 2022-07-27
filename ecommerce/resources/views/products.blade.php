@extends('layout.template')

@section('title')
	{{ ucwords($products->name) }}
@endsection

@section('content')
<!-- Page info -->
<div class="page-top-info">
	<div class="container">
		<h4>Details Product</h4>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb site-pagination">
			  <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			  <li class="breadcrumb-item active" aria-current="page">Details Product</li>
			</ol>
		  </nav>
	</div>
</div>
<!-- Page info end -->

<!-- product section -->
<section class="product-section">
	{{-- Param 1:View. Param 2:Passing Model --}}
	@livewire('details-product', ['products' => $products])
</section>
<!-- product section end -->

<!-- RELATED PRODUCTS section -->
<section class="related-product-section">
	<div class="container">
		<div class="section-title">
			<h2>RELATED PRODUCTS</h2>
		</div>
		<div class="product-slider owl-carousel">
			<div class="product-item">
				<div class="pi-pic">
					<img src="{{asset('asset/img/product/1.jpg')}}" alt="">
					<div class="pi-links">
						<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
						<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
					</div>
				</div>
				<div class="pi-text">
					<h6>$35,00</h6>
					<p>Flamboyant Pink Top </p>
				</div>
			</div>
			<div class="product-item">
				<div class="pi-pic">
					<div class="tag-new">New</div>
					<img src="{{asset('asset/img/product/2.jpg')}}" alt="">
					<div class="pi-links">
						<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
						<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
					</div>
				</div>
				<div class="pi-text">
					<h6>$35,00</h6>
					<p>Black and White Stripes Dress</p>
				</div>
			</div>
			<div class="product-item">
				<div class="pi-pic">
					<img src="{{asset('asset/img/product/3.jpg')}}" alt="">
					<div class="pi-links">
						<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
						<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
					</div>
				</div>
				<div class="pi-text">
					<h6>$35,00</h6>
					<p>Flamboyant Pink Top </p>
				</div>
			</div>
			<div class="product-item">
					<div class="pi-pic">
						<img src="{{asset('asset/img/product/4.jpg')}}" alt="">
						<div class="pi-links">
							<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
							<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
						</div>
					</div>
					<div class="pi-text">
						<h6>$35,00</h6>
						<p>Flamboyant Pink Top </p>
					</div>
				</div>
			<div class="product-item">
				<div class="pi-pic">
					<img src="{{asset('asset/img/product/6.jpg')}}" alt="">
					<div class="pi-links">
						<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
						<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
					</div>
				</div>
				<div class="pi-text">
					<h6>$35,00</h6>
					<p>Flamboyant Pink Top </p>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- RELATED PRODUCTS section end -->
@endsection