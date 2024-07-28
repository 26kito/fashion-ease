@extends('layout.template')

{{-- @section('title'){{ '-' . ucwords($products->name) }}@endsection --}}
@section('title'){{ $title }}@endsection

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
	@livewire('details-product', ['products' => $products])
</section>
<!-- product section end -->

<!-- RELATED PRODUCTS section -->
<section class="related-product-section">
	{{-- @livewire('related-products', ['relatedProducts' => $relatedProducts]); --}}
</section>
<!-- RELATED PRODUCTS section end -->
@endsection

@push('script')
<script src="{{asset('asset/js/owl.carousel.min.js')}}"></script>
@endpush