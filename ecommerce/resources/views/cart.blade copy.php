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
					<div class="cart-table-warp">
						<table>
							<thead>
								<tr>
									<th></th>
									<th class="product-th">Product</th>
									<th class="quy-th">Quantity</th>
									<th class="size-th">Size</th>
									<th class="total-th">Price</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="content">
							</tbody>
						</table>
					</div>
					<div class="total-cost">
						<h6>Total<span>{{ rupiah($total) }}</span></h6>
					</div>
				</div>
			</div>
			<div class="col-lg-4 card-right">
				<div class="promo-code-form">
					<input type="text" placeholder="Enter promo code">
					<button>Submit</button>
				</div>
				@if ( $total_orders->order_items_count > 0 )
				<button type="submit" class="site-btn">Proceed to Checkout</button>
				@endif
				<a href="{{ route('home') }}" class="site-btn sb-dark">Continue Shopping</a>
			</div>
		</div>
	</div>
</section>
<!-- cart section end -->
@endsection

@push('js')
<script src="{{asset('asset/bootstrap-growl/jquery.bootstrap-growl.min.js')}}"></script>
<script>
	$(document).ready(() => {
		$.ajax({
			type: "GET",
			url: `/cart/get/`,
			success: (result) => {
				$('#content').html(table(result));
			}
		})
	})

	function table(data) {
		let table = ``;
		let baseImg = "asset/img/cart/";
		data.forEach((data) => {
			table += 
			`
			<tr>
				<td>
					<input type="checkbox" name="id[]" id="orderItemsID"
						value="${data.OrderItemsID}">
				</td>
				<td class="product-col">
					<a href="/products/${data.ProductID}">
						<img src="${baseImg + data.image}">
					</a>
					<div class="pc-title">
						<h4>${data.ProductName}</h4>
					</div>
				</td>
				<td class="quy-col">
					<div class="quantity form-group">
						<input type="text" class="qty" value="${data.qty}" readonly disabled>
					</div>
				</td>
				<td class="size-col">
					<h4>${data.size}</h4>
				</td>
				<td class="total-col">
					<h4>${data.price}</h4>
				</td>
				<td>
					<button
					data-order-items-id="${data.OrderItemsID}" 
					data-order-id="${data.OrderID}" 
					class="btn btn-danger delete">
					Hapus
					</button>
				</td>
			</tr>
			`;
		})
		return table;
	}

	$(document).on('click', (e) => {
		let orderID = $(e.target).data('order-id');
		let orderItemsID = $(e.target).data('order-items-id');

		if ($(e.target).hasClass('delete')) {
			if (confirm('kmu yakin ingin menghapus pesanan ini?')) {
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'DELETE',
					url: `/cart/remove-cart-item/orderID/${orderID}/orderItemsID/${orderItemsID}`,
					success: (result) => {
						$('#content').html(table(result.data));
						if (result.success == true) {
							toastr.success(result.message);
							toastr.options = {
								"preventDuplicates": true,
							};
						} else {
							toastr.error(result.message);
							toastr.options = {
								"preventDuplicates": true,
							};
						}
					}
				})
			}
		}
	})
</script>
@endpush