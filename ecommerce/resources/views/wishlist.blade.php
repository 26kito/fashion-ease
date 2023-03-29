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
@endsection