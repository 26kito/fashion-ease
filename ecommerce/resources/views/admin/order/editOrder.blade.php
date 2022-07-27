<!-- Nama Produk -->
<div class="form-group">
    <input name="order_id" type="hidden" value="{{$orderItem->order_id}}" id="updateOrderId">
    <label for="insertProduct">Nama Produk:</label>
    <div class="input-group">
        <select name="insertProduct" id="updateProduct" class="form-control">
            @foreach ($products as $product)
                <option {{$product->id == $orderItem->product_id ? 'selected' : ''}} value="{{$product->id}}">{{$product->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<!-- End of Nama Produk -->
<!-- Jumlah -->
<div class="form-group"> 
    <label for="qty">Jumlah:</label>
    <div class="input-group">
        <span class="input-group-prepend">
            <button type="button" onclick="decrement()" class="btn btn-danger" data-type="minus" data-field="quant[1]">
                <span class="fa fa-minus"></span>
            </button>
        </span>
        <input type="number" name="qty" id="updateQty" min="1" max="20" value="{{$orderItem->qty}}" class="qty form-control col-md-2 quantity-field border-0 text-center w-25">
        <span class="input-group-append">
            <button type="button" onclick="increment()" class="btn btn-success" data-type="plus" data-field="quant[1]">
                <span class="fa fa-plus"></span>
            </button>
        </span>
    </div>
</div> 
<!-- End of Jumlah -->
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" onclick="update({{$id}})">Update</button>
</div>