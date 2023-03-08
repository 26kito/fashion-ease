<div>
    <h4 class="mt-5">Wishlist</h4>
    <div class="owl-carousel owl-theme owl-loaded owl-drag">
        @foreach ($wishlists as $row)
        <div class="product-item">
            <div class="pi-pic">
                <div class="tag-new">NEW</div>
                <a href="{{ url('product/'.$row->product_id) }}">
                    <img src='{{ asset("storage/products-images/$row->image") }}'>
                </a>
                <div class="pi-links">
                    <a href="{{ url('product/'.$row->product_id) }}" class="add-card add-to-cart">
                        <i class="flaticon-bag"></i><span>ADD TO CART</span>
                    </a>
                </div>
            </div>
            <div class="pi-text">
                <h6>{{ rupiah($row->price) }}</h6>
                <p>{{ $row->name }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('js')
<script>
    $('.owl-carousel').owlCarousel({
            margin: 20,
            items: 4
        })
</script>
@endpush