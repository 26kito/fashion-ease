<div class="promo-code-form">
    {{-- Modal --}}
    <div id="modalpromo" class="modal" tabindex="-1" role="dialog" wire:ignore>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pakai Promo</h5>
                    <a href="#" class="btn btn-reset disabled">Reset Promo</a>
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
                                <div class="card-body voucher-card"
                                    data-voucher-id="{{ $row->id }}" style="cursor: pointer" id="voucherCard">
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
                                <b><span style="font-size: 25px"></span></b>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 ms-auto">
                        <a href="#" class="btn btn-success py-3">Pakai Promo</a>
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
    $('.voucher-card').on('click', function() {
        let voucherID = $(this).data('voucher-id');
        let index = selectedVouchers.indexOf(voucherID);

        if ($(this).hasClass('selected')) {
            $(this).css({
                'background-color': 'white'
            });

            $('.btn-reset').addClass('disabled');
            $(this).removeClass('selected');

            // If voucherID is in the array, remove it using splice
            if (index !== -1) {
                selectedVouchers.splice(index, 1);
            }
        } else {
            $(this).css({
                'background-color': '#00AA5B'
            });

            $('.btn-reset').removeClass('disabled');
            $(this).addClass('selected');

            if (!selectedVouchers.includes(voucherID)) {
                selectedVouchers.push(voucherID);
            }
        }

        if (selectedVouchers.length > 0) {
            $('.voucher-calculate').removeClass('d-none')
        } else {
            $('.voucher-calculate').addClass('d-none')
        }
    });


    $('.btn-reset').on('click', () => {
        $('.voucher-card').removeClass('selected');
        $('.btn-reset').addClass('disabled');

        $('.voucher-card').css({
            'background-color': 'white'
        });
    })
</script>
@endpush