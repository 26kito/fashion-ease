@extends('layout.template')

@section('title')
{{$title}}
@endsection

@section('content')
<section class="cart-section spad" style="margin-top: 200px">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="cart-table">
					<h3>Wishlist</h3>
					@livewire('wishlist')
				</div>
			</div>
		</div>
	</div>
</section>
@endsection