<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="product-pic">
                <img class="product-big-img" src="{{asset('storage/products-images/'.$products->image)}}" alt="{{'image of '.$products->image}}">
            </div>
            {{-- <div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none;">
                <div class="product-thumbs-track">
                    <div class="pt active" data-imgbigurl="{{asset('asset/img/single-product/1.jpg')}}"><img src="{{asset('asset/img/single-product/thumb-1.jpg')}}" alt=""></div>
                    <div class="pt" data-imgbigurl="{{asset('asset/img/single-product/2jpg')}}"><img src="{{asset('asset/img/single-product/thumb-2.jpg')}}" alt=""></div>
                    <div class="pt" data-imgbigurl="{{asset('asset/img/single-product/3.jpg')}}"><img src="{{asset('asset/img/single-product/thumb-3.jpg')}}" alt=""></div>
                    <div class="pt" data-imgbigurl="{{asset('asset/img/single-product/4.jpg')}}"><img src="{{asset('asset/img/single-product/thumb-4.jpg')}}" alt=""></div>
                </div>
            </div> --}}
        </div>
        <div class="col-lg-6 product-details">
            <h2 class="p-title">{{ $products->name }}</h2>
            <h3 class="p-price">{{ rupiah($products->price) }}</h3>
            @if ( $products->stock > 0 )
                <h4 class="p-stock">Available: <span>In Stock !</span></h4>
            @else
                <h4 class="p-stock">Available: <span>Out of Stock !</span></h4>
            @endif
            <div class="p-rating">
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o fa-fade"></i>
            </div>
            <div class="p-review">
                <a href="#">3 reviews</a>|<a href="">Add your review</a>
            </div>
            <form wire:submit.prevent='addToCart'>
                <div class="fw-size-choose">
                    <p>Size</p>
                    @foreach ($products->detailsProduct as $row)
                    <div class="sc-item">
                        <input wire:model='size' type="radio" name="sc" id="{{ $row->size }}-size" value="{{ $row->size }}">
                        <label for="{{ $row->size }}-size">{{ $row->size }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="quantity form-group">
                    <p>Quantity</p>
                    <a wire:click='decrement' class="btn">-</a>
                    <input wire:model='qty' type="text" class="qty" readonly disabled value="{{ $qty }}">
                    <a wire:click='increment' class="btn">+</a>
                </div>
                <button type="submit" class="site-btn">Buy Now</button>
            </form>
            <div id="accordion" class="accordion-area">
                <div class="panel">
                    <div class="panel-header" id="headingOne">
                        <button class="panel-link active" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">information</button>
                    </div>
                    <div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="panel-body">
                            <p>{{ $products->description }}</p>
                            <p>{{ 'Stock: '.$products->stock }}</p>
                            <p>{{ $products->varian }}</p>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header" id="headingTwo">
                        <button class="panel-link" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">care details </button>
                    </div>
                    <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="panel-body">
                            <img src="{{asset('asset/img/cards.png')}}" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pharetra tempor so dales. Phasellus sagittis auctor gravida. Integer bibendum sodales arcu id te mpus. Ut consectetur lacus leo, non scelerisque nulla euismod nec.</p>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header" id="headingThree">
                        <button class="panel-link" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">shipping & Returns</button>
                    </div>
                    <div id="collapse3" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="panel-body">
                            <h4>7 Days Returns</h4>
                            <p>Cash on Delivery Available<br>Home Delivery <span>3 - 4 days</span></p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pharetra tempor so dales. Phasellus sagittis auctor gravida. Integer bibendum sodales arcu id te mpus. Ut consectetur lacus leo, non scelerisque nulla euismod nec.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="social-sharing">
                <a href=""><i class="fa fa-google-plus"></i></a>
                <a href=""><i class="fa fa-pinterest"></i></a>
                <a href=""><i class="fa fa-facebook"></i></a>
                <a href=""><i class="fa fa-twitter"></i></a>
                <a href=""><i class="fa fa-youtube"></i></a>
            </div>
        </div>
    </div>
</div>