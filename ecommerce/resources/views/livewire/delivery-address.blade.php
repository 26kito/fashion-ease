<div>
    {{-- Modal --}}
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" wire:ignore>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-center mt-3 mb-4">Tambah alamat</h5>
                    <div class="form-group">
                        <label for="inputAddress" class="mb-2">Alamat</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="Masukkan alamat kamu">
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-group">
                                <label for="province" class="mb-2">Provinsi</label>
                                <select class="form-control" id="province">
                                    <option value="null" selected disabled>Pilih provinsi kamu</option>
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="city" class="mb-2">Kota</label>
                                <select class="form-control" id="city" disabled>
                                    <option value="null" selected disabled>Pilih kota kamu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <a type="button" id="saveDeliveryAddress" class="btn btn-primary mb-2">Simpan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End of Modal --}}

    <div class="cf-title">Alamat Pengiriman</div>
    <div class="row m-0">
        @if ($userInfo->address !== null)
        <p class="m-0 fw-bold">{{ "$userInfo->first_name $userInfo->last_name" }}</p>
        <p class="m-0">{{ $userInfo->phone_number }}</p>
        <p class="mb-4 user-address" data-user-address={{ $userInfo->address }}>{{ $userInfo->address }}</p>
        @endif
    </div>
    @if ($userInfo->address === null)
    <a class="btn btn-outline-dark btn-sm ms-3 mb-3" role="button" data-bs-toggle="modal"
        data-bs-target="#addressModal">Tambah alamat</a>
    @endif
</div>

@push('js')
<script>
    $.ajax({
        type: "GET",
        url: `/api/get-province`,
        success: function(result) {
            $('#province').append(provinceDropdown(result));
        }
    })

    $('#province').on('change', () => {
        let provinceID = $('#province').val()

        $('#city').prop('disabled', false);

        $.ajax({
            type: "GET",
            url: `/api/get-city/${provinceID}`,
            success: function(result) {
                $('#city').html(
                    "<option value='null' selected disabled>Pilih kota kamu</option>"+cityDropdown(result)
                );
            }
        })
    })

    function provinceDropdown(data) {
        let res = '';
        
        data.forEach((d) => {
            res += `<option value="${d.province_id}">${d.province}</option>`;
        });

        return res;
    }

    function cityDropdown(data) {
        let res = '';
        
        data.forEach((d) => {
            res += `<option value="${d.city_id}">${d.city_name} (${d.type})</option>`;
        });

        return res;
    }

    $(document).on('click', '#saveDeliveryAddress', () => {
        let address = $('#inputAddress').val();
        let province = $('#province').val();
        let city = $('#city').val();
        let data = {'address': address, 'province': province, 'city': city};
    
        if (!address) {
            let event = new CustomEvent('toastr', {
                'detail': {
                    'status': 'info', 
                    'message': 'Masukin alamat kamu dulu ya, biar kurirnya tidak tersesat:)'
                }
            });
    
            window.dispatchEvent(event);
        } else if (!province) {
            let event = new CustomEvent('toastr', {
                'detail': {
                    'status': 'info', 
                    'message': 'Jangan lupa isi provinsi:)'
                }
            });
    
            window.dispatchEvent(event);
        } else if (!city) {
            let event = new CustomEvent('toastr', {
                'detail': {
                    'status': 'info', 
                    'message': 'Jangan lupa isi kota nya juga ya!'
                }
            });
    
            window.dispatchEvent(event);
        } else {
            Livewire.emit('saveDeliveryAddress', data);
        }
    })

    window.livewire.on('saveDeliveryAddress', (status) => {
        $('#addressModal').modal('hide');
    })
</script>
@endpush