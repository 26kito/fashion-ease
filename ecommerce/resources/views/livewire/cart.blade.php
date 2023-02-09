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
        <tbody>
            @foreach ( $carts as $row )
            <tr>
                <td>
                    <input type="checkbox" name="id[]" id="id" value="{{ $row->CartID }}">
                </td>
                <td class="product-col">
                    <a href="/product/{{ $row->product_id }}">
                        <img src="{{ asset('asset/img/cart/'. $row->image ) }}" alt="{{ $row->image }}">
                    </a>
                    <div class="pc-title">
                        <h4>{{ $row->prodName }}</h4>
                        <p>{{ "Sisa stok: ". $stock }}</p>
                    </div>
                </td>
                <td class="quy-col">
                    <div class="quantity form-group">
                        {{-- <h4>{{ $row->qty }}</h4> --}}
                        <input wire:click="decrement('{{ $row->CartID }}', '{{ $row->ProductID }}')" type="button" class="btn" value="-">
                        <input type="text" value="{{ $row->qty }}" class="qty" readonly disabled>
                        <input wire:click="increment('{{ $row->CartID }}', '{{ $row->ProductID }}')" type="button" class="btn" value="+">
                    </div>
                </td>
                <td class="size-col">
                    <h4>{{ $row->size }}</h4>
                </td>
                <td class="total-col">
                    <h4>{{ rupiah($row->price) }}</h4>
                </td>
                <td>
                    <a onclick="confirm('Yakin?') || event.stopImmediatePropagation()" wire:click.prevent="remove('{{ $row->CartID }}', '{{ $row->ProductID }}')" class="btn btn-danger">
                        Hapus
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>