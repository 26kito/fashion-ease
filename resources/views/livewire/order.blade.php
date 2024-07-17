<div class="card-body">
    <div class="form-row mb-3">
        <div class="col-7">
            <div class="input-group">
                <input type="text" wire:model='keywordOrderSearch' wire:keydown.enter='searchOrder'
                    placeholder="Ketik nama produk yang ingin km cari :)" class="form-control">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" wire:click='search' type="button">
                        <i class="flaticon-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <p class="h5 fw-bold">Status</p>
                </div>
                <div>
                    @foreach ($orderStatus as $key => $value)
                        {{-- <a href="#" wire:click.prevent="selectStatus('{{ $value }}')" class="btn border me-1 shadow-none" role="button" style="height: 40px; border-style: solid; border-radius: 12px; {{ ($orderStatusSelected == $value) ? 'background: #ECFEF4; border-color: #20CE7D; color: #00AA5B;' : '' }}">{{ $value }}</a> --}}
                        {{-- <button wire:click.prevent="selectStatus('{{ $value }}')" type="button" class="btn border me-1 shadow-none" style="height: 40px; border-width: 1px; border-style: solid; border-radius: 12px; {{ ($orderStatusSelected == $value) ? 'background: #ECFEF4; border-color: #20CE7D; color: #00AA5B;' : '' }}">{{ $value }}</button> --}}
                        <button wire:click.prevent="selectStatus('{{ $value }}')" type="button" class="btn order-status-btn border me-1 shadow-none {{ ($orderStatusSelected == $value) ? 'order-status-selected-btn' : '' }}">{{ $value }}</button>
                    @endforeach
                    {{-- <a href="#" class="btn border me-1" role="button" style="height: 40px; border-radius: 12px; background: #ECFEF4; border-color: #20CE7D; color: #00AA5B">Semua</a>
                    <a href="#" class="btn border me-1" role="button" style="height: 40px; border-radius: 12px; background: #ECFEF4; border-color: #20CE7D; color: #00AA5B">Berlangsung</a>
                    <a href="#" class="btn border me-1" role="button" style="height: 40px; border-radius: 12px; background: #ECFEF4; border-color: #20CE7D; color: #00AA5B">Berhasil</a>
                    <a href="#" class="btn border" role="button" style="height: 40px; border-radius: 12px; background: #ECFEF4; border-color: #20CE7D; color: #00AA5B">Tidak Berhasil</a> --}}
                </div>
                <div class="ms-4">
                    <a href="#" wire:click.prevent="resetSelectedOrderStatus" class="fw-bold" style="color: #00AA5B">Reset Filter</a>
                </div>
            </div>
        </div>
    </div>
    @if (count($data) == 0)
        <div class="card mb-3">
            <div class="card-body">
                <h4>Kamu belum memiliki transaksi</h4>
            </div>
        </div>
    @else
        @foreach ($data as $row)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex">
                        <div class="ms-4 me-3">
                            <p class="fw-bold">Belanja</p>
                        </div>
                        <div class="me-3">
                            <p>{{ $row->order_date }}</p>
                        </div>
                        <div class="me-3">
                            <p class="fw-bold">{{ $row->order_status }}</p>
                        </div>
                        <div>
                            <p>{{ $row->order_id }}</p>
                        </div>
                    </div>
                    <div class="d-flex me-2">
                        <div style="width: 120px; height: 120px; overflow: hidden">
                            <img src="{{ asset('asset/img/products/'.$row->product_image) }}" alt="" class="img-fluid" 
                            style="min-width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <div class="me-5" style="width: 600px">
                            <a href='#' class="fw-bold text-dark text-decoration-none">{{ $row->product_name }}</a>
                            <p class="text-muted">{{ $row->qty . " barang x " . $row->product_price }}</p>
                        </div>
                        <div>
                            <p>Total</p>
                            <p class="fw-bold">{{ $row->grand_total }}</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex mt-3 justify-content-end">
                    <div class="justify-content-center">
                        <a href="#" class="btn fw-bold me-3" role="button" style="color: #00AA5B">Lihat Detail Transaksi</a>
                    </div>
                    <a href="#" class="btn fw-bold border me-3" role="button" style="width: 200px; color: #00AA5B">Ulas</a>
                    <a href="#" class="btn fw-bold border me-3 text-white" role="button" style="width: 200px; background: #00AA5B">Beli Lagi</a>
                    <a href="#" class="btn border" role="button">...</a>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>