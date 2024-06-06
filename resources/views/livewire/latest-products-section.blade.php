@push('stylesheet')
<link rel="stylesheet" href="{{ asset('asset/css/owl.carousel.min.css') }}">
@endpush

<div class="latest-product-slider owl-carousel owl-loaded">
    @foreach ( $latestProducts as $row )
    <div class="product-item">
        <div class="pi-pic">
            <div class="tag-new">NEW</div>
            {{-- <a href="{{ url('product/'.$row->product_id) }}"> --}}
            <a href="{{ url('product/' . $row->name . '/' . $row->code . '/' . $row->product_id) }}">
                <img src="{{ asset('storage/products-images/'.$row->image) }}" alt="{{ 'image of '.$row->name }}">
            </a>
            <div class="pi-links">
                {{-- <a href="{{ url('product/'.$row->product_id) }}" class="add-card add-card-s add-to-cart"> --}}
                <a href="{{ url('product/' . $row->name . '/' . $row->code . '/' . $row->product_id) }}" class="add-card add-to-cart">
                    <i class="flaticon-bag"></i><span>ADD TO CART</span>
                </a>
                <a wire:click.prevent='addToWishlist({{ $row->id }})' class="wishlist-btn">
                    <i class="flaticon-heart"></i>
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

@push('script')
<script src="{{ asset('asset/js/owl.carousel.min.js') }}"></script>
<script>
    $('.latest-product-slider').owlCarousel({
        autoplay: false,
        margin: 20,
        loop: false,
        items: 4,
        nav: false,
    })
</script>
@endpush