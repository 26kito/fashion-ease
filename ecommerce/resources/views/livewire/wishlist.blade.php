<div class="cart-table-warp">
    <table id="cartform">
        @foreach ( $wishlists as $key => $row )
        <tr>
            <td class="product-col">
                <a href="/product/{{ $row->product_id }}">
                    <img src="{{ asset('asset/img/cart/'. $row->image ) }}" alt="{{ $row->image }}">
                </a>
                <div class="pc-title">
                    <h4>{{ $row->ProdName }}</h4>
                    <p>{{ rupiah($row->price) }}</p>
                    <p>{{ "Tanggal dimasukkan: ". $row->created_at }}</p>
                </div>
            </td>
            <td>
                <select class="form-select" aria-label="Default select example">
                    <option selected disabled value="null">Pilih size</option>
                    @foreach ($row->sizeAndStock as $sizeRow => $stockRow)
                    <option value="{{ " $row->ProductID, $sizeRow" }}" {{ ($stockRow==0) ? 'disabled' : '' }}>
                        {{ $sizeRow }} {{ ($stockRow == 0) ? ' - Stok habis' : '' }}
                    </option>
                    @endforeach
                </select>
            </td>
            <td>
                <div class="float-end me-4">
                    <a wire:click.prevent="addToCart('{{ $row->ProductID }}', '{{ $size }}')"
                        class="btn btn-sm btn-info d-block">
                        Tambahkan ke keranjang
                    </a>
                    <a wire:click.prevent="remove('{{ $row->WishlistID }}', '{{ $row->ProductID }}')"
                        class="btn btn-sm btn-danger d-block mt-2" data-bs-toggle="modal"
                        data-bs-target="#wishlistModal">
                        Hapus
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

@push('script')
<script>
    $('select').on('change', function() {
        let size = this.value;
        Livewire.emit('setSize', size);
    });
</script>
@endpush