<div class="total-cost" id="total-cost">
  <input type="hidden" name="total_price_cart" value="{{ $total }}">
  <input type="hidden" name="grand_total_cart" value="{{ $grandTotal }}">
  {{-- <h6>Discount<span>{{ "-" . rupiah($appliedDiscPrice) }}</span></h6> --}}
  @if ($isVoucherUsed == true)
  <h6>Discount<span>{{ "-" . rupiah($appliedDiscPrice) }}</span></h6>
  @endif
  {{-- @if ($appliedDiscPrice) --}}
  {{-- <h6>Total<span>{{ rupiah($total) }}</span></h6> --}}
  {{-- <h6>Discount<span>{{ "-" . rupiah($appliedDiscPrice) }}</span></h6> --}}
  {{-- @endif --}}
  <h6>Grand Total<span>
      <wire:model>{{ rupiah($grandTotal) }}</wire:model>
    </span></h6>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/cookie.js') }}"></script>
<script>
  // let initialTotalPrice = $('input[name="total_price_cart"]').val();
  // let voucherUsed = localStorage.getItem('selected_vouchers_code')

  // setInterval(function () {
  //   let currentTotalPrice = $('input[name="total_price_cart"]').val();

  //   if (currentTotalPrice !== initialTotalPrice) {
  //     initialTotalPrice = currentTotalPrice;

  //     // You can perform additional actions here
  //     localStorage.setItem("total_price_cart", initialTotalPrice); // set localstorage
  //     Livewire.emit('setTotalPriceCart', initialTotalPrice);

  //     // let voucherUsed = localStorage.getItem('selected_vouchers_code')

  //     if (voucherUsed) {
  //       $.ajax({
  //         type: "POST",
  //         url: `/api/search-voucher`,
  //         dataType: 'json',
  //         headers: {
  //           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //         },
  //         data: {
  //           'keyword': voucherUsed
  //         },
  //         success: function(result) {
  //           if (result.data.available == false) {
  //             Swal.fire({
  //               title: "",
  //               text: "Kamu tidak memenuhi persyaratan voucher ini",
  //               icon: "error"
  //             }).then((result) => {
  //               if (result.isConfirmed == true || result.isDismissed == true) {
  //                 localStorage.removeItem('selected_vouchers_code')
  //                 Livewire.emit('setAppliedDiscPrice', 0)
  //                 $(document).trigger('resetVoucher')
  //               }
  //             });
  //           }
  //         }
  //       })
  //     }
  //   }
  // }, 1000);

  // localStorage.setItem("total_price_cart", initialTotalPrice); // set localstorage
  // Livewire.emit('setTotalPriceCart', initialTotalPrice);

  // Declare initialTotalPrice in the global scope
let initialTotalPrice = $('input[name="total_price_cart"]').val();
let voucherUsed = localStorage.getItem('selected_vouchers_code');

// Function to handle changes in the total price
function handleTotalPriceChange(mutationsList) {
    for (const mutation of mutationsList) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
            let currentTotalPrice = $('input[name="total_price_cart"]').val();

            if (currentTotalPrice !== initialTotalPrice) {
                initialTotalPrice = currentTotalPrice;

                // Your additional actions here
                localStorage.setItem("total_price_cart", initialTotalPrice);
                Livewire.emit('setTotalPriceCart', initialTotalPrice);

                let voucherUsed = localStorage.getItem('selected_vouchers_code');

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
                                        localStorage.removeItem('selected_vouchers_code');
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
localStorage.setItem("total_price_cart", initialTotalPrice);
Livewire.emit('setTotalPriceCart', initialTotalPrice);

// Rest of your code goes here

</script>
@endpush