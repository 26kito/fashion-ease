<div>
    {{-- Modal --}}
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" wire:ignore>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body addressModalBody">
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
        <p class="mb-4 user-address" data-user-address={{ $userInfo->cityID }} data-selected-user-address={{ $userInfo->id }}>
            {{ "$userInfo->address, $userInfo->province_name, $userInfo->city_name" }}
            <b>{{ ($userInfo->is_default == 1) ? "(Alamat Utama)" : "" }}</b>
        </p>
        @endif
    </div>
    @if ($userInfo->address === null)
    <a class="btn btn-outline-dark btn-sm ms-3 mb-3" id="addAddress" role="button" data-bs-toggle="modal"
        data-bs-target="#addressModal">
        Tambah alamat
    </a>
    @else
    <a class="btn btn-outline-dark btn-sm ms-3 mb-3" id="changeAddress" role="button" data-bs-toggle="modal"
        data-bs-target="#addressModal">
        Ubah alamat
    </a>
    @endif
</div>

@push('script')
<script src="{{ asset('js/customNotif.js') }}"></script>
<script>
    addressModalContent();

    $(document).on('click', '#changeAddress', () => {
        $.ajax({
            type: "GET",
            url: '/user/address',
            success: (result) => {
                let content = getUserAddresses(result.data);

                $('.addressModalBody').html(`
                    <h5 class="text-center mt-3 mb-4">Alamat</h5>
                    <div class="row">
                        ${content}
                    </div>
                `);
            }
        });
    })

    $(document).on('click', '#saveDeliveryAddress', () => {
        let address = $('#inputAddress').val();
        let province = $('#province').val();
        let city = $('#city').val();
        let data = {
            'address': address, 
            'province': province, 
            'city': city
        };

        if (!address) {
            let event = customNotif.notif('info', 'Masukin alamat kamu dulu ya, biar kurirnya tidak tersesat:)');

            window.dispatchEvent(event);

            return;
        }

        if (!province) {
            let event = customNotif.notif('info', 'Jangan lupa isi provinsi:)');

            window.dispatchEvent(event);

            return;
        }

        if (!city) {
            let event = customNotif.notif('info', 'Jangan lupa isi kota nya juga ya!');

            window.dispatchEvent(event);

            return;
        }

        Livewire.emit('saveDeliveryAddress', data);
    })

    $(document).on('click', '#addressModal', (event) => {
        let userAddress = event.target.getAttribute('data-address-id');
        let selectedUserAdressID = $('.user-address').attr('data-selected-user-address');

        if (event.target.tagName == 'A' && $(event.target).attr('id') == 'changeDeliveryAddress') {
            if (userAddress != selectedUserAdressID) {
                event.preventDefault();
                Livewire.emit('changeDeliveryAddress', userAddress);
                Livewire.emit('refreshDeliveryService');
                
                $('#courier').val('null');
                $('#service').empty().prop('disabled', true);
                $('#addressModal').modal('hide');
                return;
            }

            $('#addressModal').modal('hide');
        }
    })

    function addressModalContent() {
        $('.addressModalBody').html(`
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
        `);
    
        $.ajax({
            type: "GET",
            url: `/api/get-province`,
            success: (result) => {
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
    }

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

    function getUserAddresses(data) {
        let res = '';
        let length = data.length;

        data.forEach((d) => {
            let isDefault = d.is_default;

            res += `
                <div class="col-sm-${(length == 2) ? 6 : 12}">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title ${(length != 2) ? 'text-center' : ''}">Alamat ${(isDefault == 1) ? '(Utama)' : ''}</h5>
                            <p class="card-text ${(length != 2) ? 'text-center' : ''}">${d.address}</p>
                            <div class="${(length != 2) ? 'text-center' : ''}">
                                <a href="#" id="changeDeliveryAddress" role="button" data-address-id=${d.id} class="btn btn-primary ${(isDefault == 1) ? '' : ''}" aria-disabled=${(isDefault == 1) ? 'false' : 'false'}>${(isDefault == 1 ? 'Digunakan' : 'Gunakan')}</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })

        return res;
    }

    window.livewire.on('saveDeliveryAddress', (status) => {
        $('#addressModal').modal('hide');
    })
</script>
@endpush