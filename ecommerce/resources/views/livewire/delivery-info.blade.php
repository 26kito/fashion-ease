<div>
    {{-- Modal --}}
    <div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog" wire:ignore>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-center mt-3 mb-4">Pilih layanan pengiriman</h5>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-group">
                                <label for="courier" class="mb-2">Layanan pengiriman</label>
                                <select class="form-control" id="courier">
                                    <option value="null" selected disabled>Pilih layanan pengiriman</option>
                                    @foreach ($serviceDelivery as $row)
                                    <option value="{{ $row->code }}">{{ $row->courier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-group">
                                <label for="service" class="mb-2">Nama layanan servis</label>
                                <select class="form-control" id="service" disabled>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End of Modal --}}

    <div class="cf-title">Pilih Pengiriman</div>
    <a id="deliveryService" class="btn btn-outline-dark btn-sm ms-3 mb-3" role="button">Pilih pengiriman</a>
</div>

@push('js')
<script>
    let custAddress = @js($custAddress);
    let cityID = null;

    document.addEventListener('addressChanged', (e) => {
        e.detail.forEach(element => {
            cityID = element.city;
        });
    })

    custAddress.forEach(element => {
        cityID = element.city;
    });

    $(document).on('click', '#deliveryService', () => {
        if (custAddress.length == 0) {
            let event = new CustomEvent('toastr', {
                'detail': {
                    'status': 'info', 
                    'message': 'Isi alamatmu dluu yuk'
                }
            });
            
            window.dispatchEvent(event);
            
            setTimeout(() => {
                $('#addressModal').modal('show');
            }, 1000);
        } else {
            $('#deliveryModal').modal('show');
        }
    })

    $('#courier').on('change', () => {
        let courierCode = $('#courier').val();
        $('#service').prop('disabled', false);

        $.ajax({
            type: "POST",
            url: `/api/check-cost`,
            dataType: 'json',
            data: {
                'origin': 1,
                'destination': cityID,
                'weight': 100,
                'courierCode': courierCode,
            },
            success: function(result) {
                $('#service').html(serviceName(result));
                console.log(serviceName(result))
            }
        })
    })

    function serviceName(data) {
        let res = '';

        data.forEach((d) => {
            let services = d.costs.values();
            for (let service of services ) {
                if (d.costs.length > 0) {
                    res += `<option>${service.service} ongkir ${service.cost[0].value} estimasi sampai ${service.cost[0].etd} hari</option>`;
                } else {
                    res += `<option value="null" disabled>Tidak tersedia pengiriman</option>`;
                }
            }
        })

        return res;
    }
</script>
@endpush