<div class="total-cost" id="total-cost">
  <input type="hidden" name="total_price_cart" value="{{ $total }}">
  <input type="hidden" name="grand_total_cart" value="{{ $grandTotal }}">
  @if ($isVoucherUsed == true)
  <h6>Discount<span>{{ "-" . rupiah($appliedDiscPrice) }}</span></h6>
  @endif
  <h6>Grand Total<span>
      <wire:model>{{ rupiah($grandTotal) }}</wire:model>
    </span></h6>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/cookie.js') }}"></script>
<script>
// Declare initialTotalPrice in the global scope
let initialTotalPrice = $('input[name="total_price_cart"]').val();
// let voucherUsed = localStorage.getItem('selected_vouchers_code');
let voucherUsed = cookie.getCookie('selectedVouchersCode')


// Function to handle changes in the total price
function handleTotalPriceChange(mutationsList) {
    for (const mutation of mutationsList) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
            let currentTotalPrice = $('input[name="total_price_cart"]').val();

            if (currentTotalPrice !== initialTotalPrice) {
                initialTotalPrice = currentTotalPrice;

                // Your additional actions here
                // localStorage.setItem("total_price_cart", initialTotalPrice);
                cookie.setCookie('total_price_cart', initialTotalPrice, 2);
                Livewire.emit('setTotalPriceCart', initialTotalPrice);

                let voucherUsed = cookie.getCookie('selectedVouchersCode')

                if (voucherUsed) {
                    $.ajax({
                        type: "POST",
                        url: `/api/search-voucher`,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'keyword': voucherUsed
                        },
                        success: function (result) {
                            if (result.data.available === false) {
                                Swal.fire({
                                    title: "",
                                    text: "Kamu tidak memenuhi persyaratan voucher ini",
                                    icon: "error"
                                }).then((result) => {
                                    if (result.isConfirmed === true || result.isDismissed === true) {
                                        // localStorage.removeItem('selected_vouchers_code');
                                        cookie.unsetCookie('selectedVouchersCode')
                                        Livewire.emit('setAppliedDiscPrice', 0);
                                        $(document).trigger('resetVoucher');
                                    }
                                });
                            }
                        }
                    });
                }
            }
        }
    }
}

// Set up MutationObserver
const targetNode = document.querySelector('input[name="total_price_cart"]');
const config = { attributes: true, attributeFilter: ['value'] };

const observer = new MutationObserver(handleTotalPriceChange);

// Start observing the target node for configured mutations
observer.observe(targetNode, config);

// Set initial values
// localStorage.setItem("total_price_cart", initialTotalPrice);
cookie.setCookie('total_price_cart', initialTotalPrice, 2);
Livewire.emit('setTotalPriceCart', initialTotalPrice);
</script>
@endpush