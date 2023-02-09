<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="product-pic">
                <img class="product-big-img" src="{{asset('storage/products-images/'.$products->image)}}"
                    alt="{{'image of '.$products->image}}">
            </div>
        </div>
        <div class="col-lg-6 product-details">
            <h2 class="p-title">{{ $products->name }}</h2>
            <h3 class="p-price">{{ rupiah($products->price) }}</h3>
            @if ( $products->detailsProduct->isNotEmpty() )
            <h4 class="p-stock">Available: <span>In Stock !</span></h4>
            @else
            <h4 class="p-stock">Available: <span>Out of Stock !</span></h4>
            @endif
            <form wire:submit.prevent='addToCart'>
                <div class="fw-size-choose">
                    <p>Size</p>
                    <div class="sc-item">
                        @foreach ( $defaultSize as $row )
                        <input wire:model='size' wire:click="checkSize('{{ $row }}')" type="radio" name="size"
                            id="{{ $row }}-size" value="{{ $row }}">
                        <label for="{{ $row }}-size">{{ $row }}</label>
                        @endforeach
                    </div>
                </div>
                <div class="quantity form-group">
                    <p>Quantity</p>
                    <input wire:click='decrement' type="button" class="btn" value="-">
                    <input wire:model.lazy='qty' type="text" class="qty" readonly disabled value="{{ $qty }}">
                    <input wire:click='increment' type="button" {{ $isDisabled ? 'disabled' : '' }} class="btn" value="+">
                </div>
                <button type="submit" class="site-btn">Buy Now</button>
            </form>
            <div id="accordion" class="accordion-area">
                <div class="panel">
                    <div class="panel-header" id="headingOne">
                        <button class="panel-link active" data-toggle="collapse" data-target="#collapse1"
                            aria-expanded="true" aria-controls="collapse1">information</button>
                    </div>
                    <div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="panel-body">
                            <p>{{ $products->description }}</p>
                            <p>{{ 'Stock: '.$stock }}</p>
                            <p>{{ $products->varian }}</p>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header" id="headingTwo">
                        <button class="panel-link" data-toggle="collapse" data-target="#collapse2" aria-expanded="false"
                            aria-controls="collapse2">care details </button>
                    </div>
                    <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="panel-body">
                            <img src="{{asset('asset/img/cards.png')}}" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pharetra tempor so dales.
                                Phasellus sagittis auctor gravida. Integer bibendum sodales arcu id te mpus. Ut
                                consectetur lacus leo, non scelerisque nulla euismod nec.</p>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header" id="headingThree">
                        <button class="panel-link" data-toggle="collapse" data-target="#collapse3" aria-expanded="false"
                            aria-controls="collapse3">shipping & Returns</button>
                    </div>
                    <div id="collapse3" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="panel-body">
                            <h4>7 Days Returns</h4>
                            <p>Cash on Delivery Available<br>Home Delivery <span>3 - 4 days</span></p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pharetra tempor so dales.
                                Phasellus sagittis auctor gravida. Integer bibendum sodales arcu id te mpus. Ut
                                consectetur lacus leo, non scelerisque nulla euismod nec.</p>
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