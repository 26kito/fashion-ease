<div class="container">
    <div class="section-title">
        <h2>RELATED PRODUCTS</h2>
    </div>
    <div class="product-slider owl-carousel">
        @foreach( $relatedProducts as $row )
        <div class="product-item">
            <div class="pi-pic">
                <a href="{{ url('product/' . $row->name . '/' . $row->code . '/' . $row->product_id) }}">
                    <img src="{{ asset('asset/img/products/'.$row->image) }}" alt="">
                </a>
                <div class="pi-links">
                    <a href="{{ url('product/' . $row->name . '/' . $row->code . '/' . $row->product_id) }}" class="add-card">
                        <i class="flaticon-bag"></i><span>ADD TO CART</span>
                    </a>
                    <a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
                </div>
            </div>
            <div class="pi-text">
                <h6>{{ $row->price }}</h6>
                <p>{{ $row->name }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>