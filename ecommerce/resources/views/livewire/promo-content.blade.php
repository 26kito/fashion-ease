<div>
    @foreach ($vouchers as $row)
    {{ var_export($row->available) }}
    <div class="card mb-3">
        <div class="card-body voucher-card {{ ($row->available == true) ? 'available' : 'not-available' }}"
            data-voucher-id="{{ $row->id }}" style="{{ ($row->available == true) ? 'cursor: pointer' : '' }}"
            id="voucherCard">
            <p class="fw-bold">{{ $row->title }}</p>
        </div>
    </div>
    @endforeach
</div>

@push('script')
<script>
    let selectedVouchers = [];

    $(document).on('click', '.voucher-card', function() {
        let voucherID = $(this).data('voucher-id');
        let index = selectedVouchers.indexOf(voucherID);

        if ($(this).hasClass('available')) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('bg-success')
                $(this).removeClass('text-white')
    
                $('.btn-reset').addClass('disabled');
                $(this).removeClass('selected');
    
                // If voucherID is in the array, remove it using splice
                if (index !== -1) {
                    selectedVouchers.splice(index, 1);
                }
    
            } else {
                $(this).addClass('bg-success')
                $(this).addClass('text-white')
                // $(this).css({
                //     'background-color': '#00AA5B'
                // });
                
                $('.btn-reset').removeClass('disabled');
                $(this).addClass('selected');
                
                Livewire.emit('setSelectedVoucher', voucherID);
    
                if (!selectedVouchers.includes(voucherID)) {
                    selectedVouchers.push(voucherID);
                }
    
                let totalPriceCart = 0;
    
                // Function to fetch total price
                function fetchTotalPrice() {
                    return $.ajax({
                        type: "GET",
                        url: `/api/total-price-cart`,
                    });
                }
    
                // Function to apply voucher
                function applyVoucher() {
                    return $.ajax({
                        type: "POST",
                        url: `/api/apply-voucher`,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'totalPriceCart': totalPriceCart,
                            'voucherID': selectedVouchers
                        },
                    });
                }
    
                // Using promises to chain the requests
                fetchTotalPrice()
                    .then(function(result) {
                        totalPriceCart = result;
                        return applyVoucher();
                    })
                    .then(function(result) {
                        $('.total-discount-cart').data('discount-price', result.discount_applied)
                        $('.total-discount-cart').html(result.discount_applied_format)
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                    });
            }
    
            if (selectedVouchers.length > 0) {
                $('.voucher-calculate').removeClass('d-none')
            } else {
                $('.voucher-calculate').addClass('d-none')
            }
        }
    });

    $(document).on('click', '#use-voucher', () => {
        let discPrice = $('.total-discount-cart').data('discount-price')
        Livewire.emit('setAppliedDiscPrice', discPrice);
        $('#modalpromo').modal('hide');
    })

    $(document).on('click', '.btn-reset', () => {
        $('.voucher-card').removeClass('selected');
        $('.btn-reset').addClass('disabled');
        $('.voucher-calculate').addClass('d-none')

        $('.voucher-card').css({
            'background-color': 'white'
        });
    })

    $(document).on('click', '.btn-close', (e) => {
        $('.voucher-calculate').addClass('d-none')        
        $('.voucher-card').removeClass('selected')
        $('.voucher-card').removeClass('bg-success')
        $('.voucher-card').removeClass('text-white')
    })
</script>
@endpush