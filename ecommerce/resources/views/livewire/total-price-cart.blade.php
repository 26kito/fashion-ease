<div class="total-cost" id="total-cost">
  <input type="hidden" name="total_price_cart" value="{{ $total }}">
  <input type="hidden" name="grand_total_cart" value="{{ $grandTotal }}">
  @if ($appliedDiscPrice)
  {{-- <h6>Total<span>{{ rupiah($total) }}</span></h6> --}}
  <h6>Discount<span>{{ "-" . rupiah($appliedDiscPrice) }}</span></h6>
  @endif
  <h6>Grand Total<span><wire:model>{{ rupiah($grandTotal) }}</wire:model></span></h6>
</div>

@push('script')
<script>
  let initialTotalPrice = $('input[name="total_price_cart"]').val();

  setInterval(function () {
    let currentTotalPrice = $('input[name="total_price_cart"]').val();

    if (currentTotalPrice !== initialTotalPrice) {
      initialTotalPrice = currentTotalPrice;

      // You can perform additional actions here
      localStorage.setItem("total_price_cart", initialTotalPrice); // set localstorage
      Livewire.emit('setTotalPriceCart', initialTotalPrice);
    }
  }, 100);

  localStorage.setItem("total_price_cart", initialTotalPrice); // set localstorage
  Livewire.emit('setTotalPriceCart', initialTotalPrice);
</script>
@endpush