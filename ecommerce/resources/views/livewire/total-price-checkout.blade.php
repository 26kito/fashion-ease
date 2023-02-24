<ul class="price-list">
    <li>Total<span>{{ $total }}</span></li>
    @if ($shippingFee)
        <li id="shippingCost" data-shipping-fee="{{ $shippingFee }}">Shipping<span>{{ $shippingFee }}</span></li>
    @endif
    <li class="total">Total<span>{{ $grandTotal }}</span></li>
</ul>