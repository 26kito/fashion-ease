<div class="promo-code-form">
    {{-- Modal --}}
    <div id="modalpromo" class="modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pakai Promo</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End of Modal --}}

    <button type="button" id="enterPromo" class="btn btn-success mt-5 mb-4">Makin hemat pakai promo</button>
</div>

@push('script')
<script>
    $('#enterPromo').on('click', () => {
        $('#modalpromo').modal('show');
    })
</script>
@endpush