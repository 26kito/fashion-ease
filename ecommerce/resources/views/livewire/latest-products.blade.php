<div class="product-slider owl-carousel owl-loaded owl-drag">
    @foreach ( $products as $item )
        <div class="product-item">
            <div class="pi-pic">
                <a href="{{ url('products/'.$item->id) }}">
                    <img src="{{ asset('storage/products-images/'.$item->image) }}" alt="{{ 'image of '.$item->name }}">
                </a>
                <div class="pi-links">
                    <a href="{{ url('products/'.$item->id) }}" class="add-card add-to-cart"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
                    <a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
                </div>
            </div>
            <div class="pi-text">
                <h6>{{ rupiah($item->price) }}</h6>
                <p>{{ $item->name }}</p>
            </div>
        </div>
    @endforeach
</div>