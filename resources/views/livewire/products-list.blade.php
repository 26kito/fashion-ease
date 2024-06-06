<div class="row">
    <ul class="product-filter-menu">
        <li>
            <a href="{{ route('category') }}">Lihat semua kategori</a>
        </li>
        @foreach ( $productCategories as $row )
        <li>
            <a wire:click.prevent='category({{ $row->id }})'>{{ $row->name }}</a>
        </li>
        @endforeach
    </ul>
    @foreach ( $products as $item )
    <div class="col-lg-3 col-sm-6">
        <div class="product-item">
            <div class="pi-pic">
                {{-- <a href="{{ url('product/'.$item->product_id) }}"> --}}
                <a href="{{ url('product/' . $item->name . '/' . $item->code . '/' . $item->product_id) }}">
                    <img src="{{ asset('asset/img/products/'.$item->image) }}" alt="{{ 'image of '.$item->name }}">
                </a>
                <div class="pi-links">
                    {{-- <a href="{{ url('product/'.$item->product_id) }}" class="add-card add-to-cart"> --}}
                    <a href="{{ url('product/' . $item->name . '/' . $item->code . '/' . $item->product_id) }}" class="add-card add-to-cart">
                        <i class="flaticon-bag"></i><span>ADD TO CART</span>
                    </a>
                    <a wire:click.prevent='addToWishlist({{$item->id}})' class="wishlist-btn">
                        <i class="flaticon-heart"></i>
                    </a>
                </div>
            </div>
            <div class="pi-text">
                <h6>{{ rupiah($item->price) }}</h6>
                <p class="prodName">{{ $item->name }}</p>
            </div>
        </div>
    </div>
    @endforeach
    @if ( count($products) < $totalProducts ) 
    <div class="text-center pt-5">
        <button wire:click='load' class="site-btn sb-line sb-dark">LOAD MORE</button>
    </div>
    @endif
</div>