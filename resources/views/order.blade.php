@extends('layout.template')

@section('title'){{ $title }}@endsection

@section('content')
<section class="orders-section spad" style="margin-top: 20px">
	<div class="container">
		<div class="row">
			<h3>Daftar Transaksi</h3>
			<div class="col-lg-10">
				<div class="card">
					@livewire('order')
				</div>
			</div>
		</div>
	</div>
</section>
<div class="container">
	<div class="row mb-3">
		<div class="col-lg-5 ps-4">
			<h4>Produk Terbaru</h4>
		</div>
		<div class="col-lg-5 text-lg-end">
			<a class="h5 text-reset" href="#">Lihat Semua</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-10">
			@livewire('latest-products')
		</div>
	</div>
</div>
@endsection