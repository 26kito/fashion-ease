<div class="cart-table-warp">
    {{-- Modal --}}
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-center mt-3 mb-4">Hapus barang?</h5>
                    <p class="text-center mb-4">Produk yang kamu pilih akan dihapus dari keranjang.</p>
                    <div class="d-flex flex-column">
                        {{-- <button type="button" wire:click='removeAllCartItems' class="btn btn-primary mb-2">Hapus
                            Barang</button> --}}
                        <button type="button" id='removeAllCartItems' class="btn btn-primary mb-2">Hapus Barang</button>
                        {{-- <button type="button" wire:click='addAllCartItemsToWishlist' class="btn btn-secondary mb-3"
                            data-dismiss="modal">Pindahkan ke Wishlist</button> --}}
                        <button type="button" id="addAllCartItemsToWishlist" class="btn btn-secondary mb-3"
                            data-dismiss="modal">Pindahkan ke Wishlist</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End of Modal --}}

    <div class="select-all mb-4 ms-auto">
        <div class="select-all-checkbox">
            <input type="checkbox" id="select-all">
            <label for="select-all" class="select-all-text">Pilih Semua</label>
        </div>
        <a onclick="confirmDelete()" class="delete-all-cart-items text-danger ms-auto">Hapus</a>
    </div>

    <table>
        <thead>
            <tr>
                <th class="product-th">Product</th>
                <th class="quy-th">Quantity</th>
                <th class="size-th">Size</th>
                <th class="total-th">Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $carts as $row )
            <tr>
                <td>
                    <input type="checkbox" name="id[]" id="id[{{$row->CartID}}]" value="{{ $row->CartID }}"
                        class="cartid">
                </td>
                <td class="product-col">
                    <a href="/product/{{ $row->product_id }}">
                        <img src="{{ asset('asset/img/cart/'. $row->image ) }}" alt="{{ $row->image }}">
                    </a>
                    <div class="pc-title">
                        <h4>{{ $row->ProdName }}</h4>
                        <p class="text-danger">{{ "Sisa stok: ". $row->AvailStock }}</p>
                    </div>
                </td>
                <td class="quy-col">
                    <div class="quantity form-group">
                        <input wire:click="decrement('{{ $row->CartID }}', '{{ $row->ProductID }}')" type="button"
                            class="btn" value="-">
                        <input type="text" value="{{ $row->qty }}" class="qty" readonly disabled>
                        <input wire:click="increment('{{ $row->CartID }}', '{{ $row->ProductID }}')" type="button"
                            class="btn" value="+">
                    </div>
                </td>
                <td class="size-col">
                    <h4>{{ $row->size }}</h4>
                </td>
                <td class="total-col">
                    <h4>{{ rupiah($row->price) }}</h4>
                </td>
                <td>
                    <a onclick="confirm('Yakin?') || event.stopImmediatePropagation()"
                        wire:click.prevent="remove('{{ $row->CartID }}', '{{ $row->ProductID }}')"
                        class="btn btn-danger">
                        Hapus
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('js')
@if (Session::has('status'))
<script>
    let status = {{ Session::get('status') }}

    if (status == 200) {
        let event = new CustomEvent('toastr', {
            'detail': {
                'status': 'success', 
                'message': 'Pesanan berhasil dihapus'
            }
        });

        window.dispatchEvent(event);
	}
</script>
@endif
<script>
    $('#select-all').on('click', () => {
        if ($('#select-all').is(':checked')) {
            $('.cartid').prop('checked', true);
            $('.delete-all-cart-items').show();
        } else {
            $('.cartid').prop('checked', false);
            $('.delete-all-cart-items').hide();
        }
    })

    function confirmDelete() {
        $('.modal').modal('show');
    }

    $('#removeAllCartItems').on('click', () => {
        Livewire.emit('removeAllCartItems');
        $('.modal').modal('hide');
    })

    $('#addAllCartItemsToWishlist').on('click', () => {
        Livewire.emit('addAllCartItemsToWishlist');
        $('.modal').modal('hide');
    })
</script>
@endpush