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
            @foreach ( $orderItems as $row => $value )
            <tr>
                <td>
                    <input type="checkbox" name="id[]" id="id" value="{{ $value->OrderItemsID }}">
                    {{-- <input type="checkbox" wire:model='orderItems.{{$row}}.OrderItemsID' name="id[]" id="id"> --}}
                </td>
                <td class="product-col">
                    {{-- <a href="/products/{{ $row->productID }}"><img
                            src="{{ asset('asset/img/cart/'. $row->image ) }}" alt="{{ $row->image }}"></a> --}}
                    <a href="/products/{{ $value->productID }}">
                        <img src="{{ asset('asset/img/cart/'. $value->image ) }}" alt="{{ $value->image }}">
                    </a>
                    <div class="pc-title">
                        <h4>{{ $value->prodName }}</h4>
                    </div>
                </td>
                <td class="quy-col">
                    <div class="quantity form-group">
                        <input type="text" wire:model='orderItems.{{$row}}.qty' class="qty" readonly disabled>
                    </div>
                </td>
                <td class="size-col">
                    <h4>{{ $value->size }}</h4>
                </td>
                <td class="total-col">
                    <h4>{{ rupiah($value->price) }}</h4>
                </td>
                <td>
                    <a href="#" wire:click='remove({{ $value->OrderID }}, {{ $value->OrderItemsID }})'
                        class="btn btn-danger">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="total-cost">
    <h6>Total<span>{{ rupiah($total) }}</span></h6>
</div>