@push('stylesheet')
<link rel="stylesheet" href="{{asset('asset/css/owl.carousel.min.css')}}">
@endpush

@if ( $totalWishlist > 0 )
<div>
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-4 ps-4">
                <h4>Wishlist</h4>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a class="h5 text-reset" href="{{ route('wishlist') }}">Lihat Semua</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="wishlist-carousel owl-carousel owl-loaded">
                    @foreach ($wishlists as $row)
                    <div class="product-item">
                        <div class="pi-pic">
                            <div class="tag-new">NEW</div>
                            <a href="{{ url("product/$row->name/$row->code/$row->product_id") }}">
                                <img src='{{ asset("asset/img/products/$row->image") }}'>
                            </a>
                            <div class="pi-links">
                                <a href="{{ url("product/$row->name/$row->code/$row->product_id") }}" class="add-card add-to-cart">
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
        </div>
    </div>
</div>
@endif

@push('script')
<script src="{{asset('asset/js/owl.carousel.min.js')}}"></script>
<script>
    $('.wishlist-carousel').owlCarousel({
        interval: false,
        margin: 20,
        loop: true,
        items: 4,
        nav: true,
    })
</script>
@endpush