<div class="cart-table-warp">
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
                <div class="float-end me-4">
                    <a onclick="confirm('Yakin?') || event.stopImmediatePropagation()"
                        wire:click.prevent="remove('{{ $row->WishlistID }}', '{{ $row->ProductID }}')"
                        class="btn btn-sm btn-info d-block">
                        Tambahkan ke keranjang
                    </a>
                    <a onclick="confirm('Yakin?') || event.stopImmediatePropagation()"
                        wire:click.prevent="remove('{{ $row->WishlistID }}', '{{ $row->ProductID }}')"
                        class="btn btn-sm btn-danger d-block mt-2">
                        Hapus
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>