<div class="cart-table-warp">
    {{-- Modal --}}
    <div class="modal" id="wishlistModal" tabindex="-1" role="dialog" wire:ignore>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    {{-- End of Modal --}}

    <table id="cartform">
        @foreach ( $wishlists as $row )
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
                    <option selected disabled>Pilih size</option>
                    @foreach ($row->size as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <div class="float-end me-4">
                    <a wire:click.prevent="addToCart('{{ $row->WishlistID }}', '{{ $row->ProductID }}')"
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