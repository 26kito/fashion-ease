@extends('layout.template')

@section('title'){{ $title }}@endsection

@section('content')
<section class="wishlist-section spad" style="margin-top: 20px">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="wishlist-table">
					<h4 class="wishlist-table-heading mb-3">Wishlist</h4>
					@livewire('wishlist')
				</div>
			</div>
		</div>
	</div>
</section>
<div class="container">
	<div class="row mb-3">
		<div class="col-lg-4 ps-4">
			<h4>Produk Terbaru</h4>
		</div>
		<div class="col-lg-4 text-lg-end">
			<a class="h5 text-reset" href="#">Lihat Semua</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8">
			@livewire('latest-products-section')
		</div>
	</div>
</div>
@endsection