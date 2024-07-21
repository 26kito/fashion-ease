<ul class="price-list">
    <li>Total<span>{{ rupiah($totalPriceCart) }}</span></li>
    @if ($shippingFee)
        <li id="shippingCost" data-shipping-fee="{{ $shippingFee }}">Shipping<span>{{ rupiah($shippingFee) }}</span></li>
    @endif
    @if ($voucherPrice)
        <li id="voucherPrice" data-voucher-price="{{ $voucherPrice }}">Voucher<span>{{ rupiah($voucherPrice) }}</span></li>
    @endif
    <li class="total">Total<span>{{ $grandTotal }}</span></li>
</ul>