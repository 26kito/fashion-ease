<div class="promo-code-form">
    {{-- Modal --}}
    <div id="modalpromo" class="modal" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pakai Promo</h5>
                    <div>
                        <a href="#" class="btn btn-reset disabled">Reset Promo</a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="search" class="form-control" name="" id="" placeholder="Masukkan kode promo">
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="btn disabled">Terapkan</a>
                        </div>
                    </div>
                    <br>
                    <div class="dropdown-divider"></div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($vouchers as $row)
                            <div class="card mb-3">
                                <div class="card-body voucher-card {{ ($row->available == true) ? 'available' : 'not-available' }}" data-voucher-id="{{ $row->id }}"
                                    style="{{ ($row->available == true) ? 'cursor: pointer' : '' }}" id="voucherCard">
                                    <p class="fw-bold">{{ $row->title }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row mb-4 voucher-calculate d-none">
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="row">
                            <p class="ms-3">
                                Kamu bisa hemat<br>
                                <span class="total-discount-cart"></span>
                                <b><span style="font-size: 25px"></span></b>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 ms-auto">
                        <a href="#" class="btn btn-success py-3" id="use-voucher">Pakai Promo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End of Modal --}}

    <button type="button" id="enterPromo" class="btn btn-success mt-5 mb-4" data-bs-toggle="modal"
        data-bs-target="#modalpromo">Makin hemat pakai promo</button>
</div>

@push('script')
<script>
    let selectedVouchers = [];

    // $('.voucher-card').on('click', function() {
    $(document).on('click', '.voucher-card', function() {
        let voucherID = $(this).data('voucher-id');
        let index = selectedVouchers.indexOf(voucherID);

        if ($(this).hasClass('available')) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('bg-success')
                $(this).removeClass('text-white')
                // $(this).css({
                //     'background-color': 'white'
                // });
    
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

    $('#use-voucher').on('click', () => {
        let discPrice = $('.total-discount-cart').data('discount-price')
        Livewire.emit('setAppliedDiscPrice', discPrice);
        $('#modalpromo').modal('hide');
    })

    $('.btn-reset').on('click', () => {
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