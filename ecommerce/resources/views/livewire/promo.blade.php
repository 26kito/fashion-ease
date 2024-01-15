<div class="promo-code-form" wire:ignore>
    {{-- Modal --}}
    <div id="modalpromo" class="modal" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pakai Promo</h5>
                    <div>
                        {{-- <a href="#" class="btn btn-reset disabled" onclick="reset()">Reset Promo</a> --}}
                        <a href="#" class="btn btn-reset disabled">Reset Promo</a>
                        {{-- <a href="#" role="button" class="btn btn-close" onclick="reset()" data-bs-dismiss="modal" aria-label="Close"></a> --}}
                        <a href="#" role="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> --}}
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            {{-- <input wire:model='keyword' type="search" class="form-control" name="searchVoucher"
                                id="searchVoucher" placeholder="Masukkan kode promo"> --}}
                            <input type="search" class="form-control" name="searchVoucher" id="searchVoucher"
                                placeholder="Masukkan kode promo">
                        </div>
                        <div class="col-md-4">
                            {{-- <a wire:click='searchVoucher' href="#"
                                class="btn btn-apply-voucher btn-success disabled">Terapkan</a> --}}
                            <a href="#" class="btn btn-apply-voucher btn-success disabled">Terapkan</a>
                        </div>
                    </div>
                    <br>
                    <div class="dropdown-divider"></div>
                    <br>
                    <div class="row">
                        <div class="col-md-12" id="voucher-parent">
                            {{-- @foreach ($vouchers as $row)
                            <div class="card mb-3">
                                <div class="card-body voucher-card {{ ($row->available == true) ? 'available' : 'not-available' }}"
                                    data-voucher-id="{{ $row->id }}"
                                    style="{{ ($row->available == true) ? 'cursor: pointer' : '' }}" id="voucherCard">
                                    <p class="fw-bold">{{ $row->title }}</p>
                                </div>
                            </div>
                            @endforeach --}}
                        </div>
                    </div>
                </div>
                <div class="row mb-4 voucher-calculate d-none">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                            <p class="ms-3">
                                Kamu bisa hemat<br>
                                <span class="total-discount-cart"></span>
                                <b><span style="font-size: 25px"></span></b>
                            </p>
                        </div>
                    </div>
                    {{-- <div class="col-lg-4 col-md-4 col-sm-4 ms-auto">
                        <a href="#" class="btn btn-success py-3" id="use-voucher">Pakai Promo</a>
                    </div> --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-end">
                        <a href="#" class="btn btn-sm btn-danger py-4 d-none" id="cancel-voucher">Batalkan Promo</a>
                        <a href="#" class="btn btn-sm ms-2 me-3 btn-success py-4" id="use-voucher">Pakai Promo</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script src="{{ asset('js/customNotif.js') }}"></script>
<script>
    let selectedVouchers = []
    let selectedVouchersCode = []

    $(document).on('click', '.btn-apply-voucher', function() {
        let searchKeyword = $('#searchVoucher').val()

        $.ajax({
            type: "POST",
            url: `/api/search-voucher`,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'keyword': searchKeyword
            },
            success: function(result) {
                let currentText = $('.voucher-card').text().trim()

                if (result.status == 'ok') {
                    selectedVouchers = []
                    selectedVouchersCode = []

                    if (result.data.title != currentText) {
                        $('.voucher-calculate').addClass('d-none')
                        $('#voucher-parent').html(fetchVoucher(result.data))
                    }
                } else {
                    let event = customNotif.notif(result.status, result.message)
        
                    window.dispatchEvent(event)
                }
            }
        })
    })

    function fetchVoucher(data) {
        let temp = ''

        temp += `
            <div class="card mb-3">
                <div class="card-body voucher-card ${(data.available == true) ? 'available' : 'not-available'}" style="${(data.available == true) ? 'cursor: pointer' : ''}" id="voucherCard" data-voucher-id="${data.id}" data-voucher-code="${data.code}">
                    <p class="fw-bold">${data.title}</p>
                </div>
            </div>
        `

        return temp
    }

    $(document).on('click', '#enterPromo', function() {
        if ($('.voucher-card').hasClass('is-used')) {
            $('.voucher-calculate #cancel-voucher').removeClass('d-none')
        }
    })

    $(document).on('click', '.voucher-card', function() {
        let voucherID = $(this).data('voucher-id')
        let voucherCode = $(this).data('voucher-code')
        let index = selectedVouchers.indexOf(voucherID)

        if ($(this).hasClass('available')) {
            $('.btn-reset, .btn-close').attr('onclick', 'reset()')

            if ($(this).hasClass('selected')) {
                $(this).removeClass('bg-success text-white is-used selected')
                $('.btn-reset').addClass('disabled')

                // If voucherID is in the array, remove it using splice
                if (index !== -1) {
                    selectedVouchers.splice(index, 1);
                    selectedVouchersCode.splice(index, 1);
                }
            } else {
                $(this).addClass('bg-success')
                $(this).addClass('text-white')
                
                $('.btn-reset').removeClass('disabled')
                $(this).addClass('selected')
                
                Livewire.emit('setSelectedVoucher', voucherID)

                if (!selectedVouchers.includes(voucherID)) {
                    selectedVouchers.push(voucherID);
                    selectedVouchersCode.push(voucherCode);
                }

                let totalPriceCart = localStorage.getItem("total_price_cart")

                $.ajax({
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
                    success: function(result) {
                        $('.total-discount-cart').data('discount-price', result.discount_applied)
                        // $('.total-discount-cart').attr('data-discount-price', result.discount_applied)
                        $('.total-discount-cart').html(result.discount_applied_format)
                    }
                });
            }
    
            if (selectedVouchers.length > 0) {
                $('.voucher-calculate').removeClass('d-none')
            } else {
                $('.voucher-calculate').addClass('d-none')
            }
        }
    });

    $(document).on('click', '#use-voucher', function() {
        let discPrice = $('.total-discount-cart').data('discount-price')
        $('.voucher-card').addClass('is-used')
        $('.voucher-calculate #cancel-voucher').removeClass('d-none')
        Livewire.emit('setAppliedDiscPrice', discPrice)
        // $('#modalpromo').modal('hide')
    })

    $(document).on('click', '#cancel-voucher', function() {
        Livewire.emit('setAppliedDiscPrice', 0)
    })

    function reset() {
        if ($('.voucher-card').hasClass('is-used') == false) {
            $('.voucher-calculate').addClass('d-none')
            $('#voucher-parent').html('')
            $('.voucher-card').removeClass('selected bg-success text-white')
            $('#searchVoucher').val('')
            $('.btn-reset').addClass('disabled');
        }
    }

    $(document).on('input', '#searchVoucher', function (e) {
        // Get the input value
        var inputValue = $(this).val();

        // Check if the input length is greater than 3
        if (inputValue.length > 3) {
            // Do something here, for example, enable the button
            $('.btn-apply-voucher').removeClass('disabled');
        } else {
            // Do something else, for example, disable the button
            $('.btn-apply-voucher').addClass('disabled');
        }
    });

    $(document).on('keypress', '#searchVoucher', function (e) {
        if (e.keyCode == 32) {
            e.preventDefault();
        }
    })
</script>
@endpush