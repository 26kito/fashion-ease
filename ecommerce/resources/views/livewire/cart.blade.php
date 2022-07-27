<div class="cart-table-warp">
    <table>
        <thead>
            <tr>
                <th class="product-th">Product</th>
                <th class="quy-th">Quantity</th>
                <th class="size-th">Size</th>
                <th class="total-th">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $order_items as $row )
            <tr>
                <td class="product-col">
                    <img src="{{ asset('asset/img/cart/'. $row->image ) }}" alt="">
                    <div class="pc-title">
                        <h4>{{ $row->prodName }}</h4>
                        {{-- <span><p class="text-danger">{{ ' left!' }}</p></span> --}}
                    </div>
                </td>
                <td class="quy-col">
                    <div class="quantity form-group">
                        <input type="text" class="qty" readonly disabled value="{{ $row->qty }}">
                    </div>
                </td>
                <td class="size-col"><h4>{{ $row->size }}</h4></td>
                <td class="total-col"><h4>{{ rupiah($row->price) }}</h4></td>
            </tr>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>