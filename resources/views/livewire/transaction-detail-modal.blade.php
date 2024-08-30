<div>
    <div id="transactionDetailModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <div>
                        <a href="#" role="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                    </div>
                </div>
                <div class="modal-body">
                    @if ($data)
                    <p class="fw-bold">Detail Produk</p>
                    @foreach ($data as $row)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex me-2">
                                <div style="width: 80px; height: 80px; overflow: hidden">
                                    <img src="{{ asset('asset/img/products/'.$row->image) }}" alt="" class="img-fluid"
                                        style="min-width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div class="me-5" style="width: 400px">
                                    <div>
                                        <a href='#' class="fw-bold text-dark text-decoration-none">{{ $row->name }}</a>
                                    </div>
                                    <div>
                                        <p class="text-muted">{{ $row->qty . " barang x " . $row->price }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p>Total</p>
                                    <p class="fw-bold">{{ $row->total_price }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('openModalTransactionDetail', () => {
            $('#transactionDetailModal').modal('show')
        })
    </script>
</div>