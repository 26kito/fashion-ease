@extends('layout.template')

@section('title'){{ $title }}@endsection

@section('content')
<!-- cart section -->
<form action="{{ route('checkout') }}" method="GET" style="margin-top: 20px">
	@csrf
	<section class="cart-section spad">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="cart-table">
						@if ( $totalOrders > 0 )
						<h4 class="cart-table-heading">Keranjang Kamu</h4>
						{{-- Modal --}}
						<div class="modal" id="modalCart" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="cart-modal-body modal-body">
									</div>
								</div>
							</div>
						</div>
						{{-- End of Modal --}}

						<div class="select-all mb-4">
							<div class="select-all-checkbox form-check d-inline-block ms-1">
								<input type="checkbox" id="select-all" class="form-check-input" checked>
								<label for="select-all" class="select-all-text form-check-label">Pilih Semua</label>
							</div>
							<a data-bs-toggle="modal" data-bs-target="#modalCart"
								class="delete-all-cart-items text-danger">Hapus</a>
						</div>
						<table id="cartform">
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
							<tbody>
							</tbody>
						</table>
						<div class="total-cost" id="total-cost">
						</div>
						@else
						<h3 class="text-center mt-5">{{ "Duh, keranjangmu kosong nih:(" }}</h3>
						@if ( $wishlist > 0 )
						<p class="text-center">Yuk isi keranjangmu dengan barang-barang impianmu!</p>
						@endif
						@endif
					</div>
				</div>
				<div class="col-lg-4 card-right">
					<div>
						@if ( $totalOrders > 0 )
						@livewire('promo')
						<button type="submit" id="proceedCheckout" class="site-btn">Proceed to Checkout</button>
						@endif
						<a href="{{ route('home') }}"
							class="site-btn sb-dark {{ $totalOrders == 0 ? 'mt-5' : ''}}">Continue Shopping</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</form>

<!-- cart section end -->
@if ( $wishlist > 0 )
<div class="container">
	<div class="row mb-3">
		<div class="col-lg-4 ps-4">
			<h4>Wishlist</h4>
		</div>
		<div class="col-lg-4 text-lg-end">
			<a class="h5 text-reset" href="{{ route('wishlist') }}">Lihat Semua</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8">
			@livewire('wishlist-section')
		</div>
	</div>
</div>
@endif
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

@push('script')
@if (Session::has('status'))
<script src="{{ asset('js/customNotif.js') }}"></script>
<script>
	const statusCode = {{ Session::get('status') }};
	let status, message;

	if (statusCode == 200) {
		message = 'Pesanan berhasil dihapus';
		status = 'success';
	} else {
		message = 'Pilih pesanan yang mau di checkout dulu yaa';
		status = 'info';
	}

	let event = customNotif.notif(status, message);
		
	window.dispatchEvent(event);
</script>
@endif
<script>
	render()

	function render() {
		fetchCartItems()
	}

	function fetchCartItems() {
		$.ajax({
			type: "GET",
			url: `/cart-items`,
			success: function(result) {
				$('#cartform tbody').html(displayCartItems(result));
			},
			complete: function() {
				if ($('#select-all').prop('checked', true)) {
					$('.availstock').prop('checked', true)
					totalPriceCart()
				}
			}
		});
	
		function displayCartItems(data) {
			let div = '';
	
			data.forEach((d) => {
				div += `
					<tr>
						<td>
							<input type="checkbox" name="cartid[]" id="id[${d.CartID}]" value="${d.CartID}" style="cursor: pointer"
								class="cartid form-check-input ms-1 ${(d.AvailStock != 0) ? 'availstock' : ''}" 
								${(d.AvailStock == 0) ? 'disabled' : ''}>
						</td>
						<td class="product-col">
							<a href="#">
								<img src="{{ asset('asset/img/cart/${d.image}') }}">
							</a>
							<div class="pc-title">
								<h4 class="cart-product-name">${d.ProdName}</h4>
								<p class="text-danger">Sisa stok: ${d.AvailStock}</p>
							</div>
						</td>
						<td class="quy-col">
							<div class="quantity form-group">
								<input type="button" class="btn decrease-btn" value="-" onclick="updateQty('decrement', ${d.CartID}, ${d.ProductID})">
								<input type="text" value="${d.qty}" class="qty" readonly disabled>
								<input type="button" class="btn increase-btn" value="+" onclick="updateQty('increment', ${d.CartID}, ${d.ProductID})">
							</div>
						</td>
						<td class="size-col">
							<h4>${d.size}</h4>
						</td>
						<td class="total-col">
							<h4>${d.formattedPrice}</h4>
						</td>
						<td>
							<a data-bs-toggle="modal" data-bs-target="#modalCart"
								class="removeCartItem btn btn-danger">
								Hapus
							</a>
						</td>
					</tr>
				`
			})
	
			return div;
		}
	}

	function totalPriceCart() {
		// Create an array to store the checked values
		var checkedValues = [];

		// Use jQuery to select all checked checkboxes within the table with id "cartform"
		$('#cartform input[type="checkbox"]:checked').each(function() {
			// Retrieve and push the value to the array
			checkedValues.push($(this).val());
		});

		// Log or use the array of checked values as needed
		$.ajax({
			type: "POST",
			url: '/carts/total-price',
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				'cartItemsID': checkedValues,
			},
			success: function(result) {
				// console.log(result)
				$('#total-cost').html(displayTotalPriceCart(result))
			}
		});

		function displayTotalPriceCart(data) {
			return `<h6>Total<span>${data}</span></h6>`
		}
	}

	function updateQty(status, cartID, productID) {
		// console.log(`${status} dan ${cartID} dan ${productID}`)

		$.ajax({
			type: "POST",
			url: '/carts/update-qty',
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				'status': status,
				'cartID': cartID,
				'productID': productID
			},
			success: function(result) {
				// console.log(result)
				render()
			}
		});
	}

	// Attach a click event handler to all checkboxes inside #cartform
	$('#cartform').on('click', 'input[type="checkbox"]', function() {
		// Get the ID of the clicked checkbox
		var checkboxId = $(this).attr('id');

		// Check if the checkbox is checked
		var isChecked = $(this).prop('checked');

		// Log the results
		if (isChecked) {
			totalPriceCart()
		} else {
			totalPriceCart()
		}
	});

	// Define the checkedValues array outside of the click event handler
	let checkedValues = [];

	$('#select-all').on('click', function() {
		if ($('.availstock').prop('checked') == true) {
			$('.availstock').prop('checked', false)
			totalPriceCart()
		} else {
			$('.availstock').prop('checked', true)
			totalPriceCart()
		}
	});

	$(".availstock").change(function() {
		if ($('.availstock:checked').length == $('.availstock').length) {
			alert('semua sudah diceklis')
		} else {
			alert('semua belum diceklis')
		}
	});
</script>
@endpush