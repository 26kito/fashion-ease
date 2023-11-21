<ul class="price-list">
    {{-- <li>Total<span>{{ $total }}</span></li> --}}
    <li>Total<span>{{ rupiah($totalPriceCart) }}</span></li>
    @if ($shippingFee)
        {{-- <li id="shippingCost" data-shipping-fee="{{ $shippingFee }}">Shipping<span>{{ rupiah($shippingFee) }}</span></li> --}}
        <li id="shippingCost" data-shipping-fee="{{ $totalPriceCart }}">Shipping<span>{{ rupiah($shippingFee) }}</span></li>
    @endif
    <li class="total">Total<span>{{ $grandTotal }}</span></li>
</ul>