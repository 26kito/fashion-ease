<div class="row">
    <div class="col-lg-3 order-2 order-lg-1">
        <div class="filter-widget">
            <h2 class="fw-title">Categories</h2>
            <ul class="category-menu">
                @foreach ($category as $row)
                <li class="form-check">
                    <input wire:model='categoryId' wire:click='refresh' type="radio" name="category" id="category({{$row->id}})" value="{{$row->id}}">
                    <label for="category({{$row->id}})">{{ $row->name }}</label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-9  order-1 order-lg-2 mb-5 mb-lg-0">
        <div class="row">
            @foreach ( $products as $row )
                <div class="col-lg-4 col-sm-6">
                    <div class="product-item">
                        <div class="pi-pic">
                            <a href="{{ url('products/'.$row->id) }}">
                                <img src="{{ asset('storage/products-images/'.$row->image) }}" alt="{{ 'image of '.$row->name }}">
                            </a>
                            <div class="pi-links">
                                <a href="{{ url('products/'.$row->id) }}" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
                                <a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
                            </div>
                        </div>
                        <div class="pi-text">
                            <h6>{{ rupiah($row->price) }}</h6>
                            <p>{{ $row->name }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ( count($products) <= $totalProducts )
            <div class="text-center w-100 pt-3">
                <button wire:click='load' class="site-btn sb-line sb-dark">LOAD MORE</button>
            </div> 
            @endif
        </div>
    </div>
</div>