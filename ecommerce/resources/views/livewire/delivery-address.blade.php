<div>
    <div class="cf-title">Alamat Pengiriman</div>
    <div class="row m-0 p-0">
        <p class="m-0 p-0 fw-bold">{{ "$userInfo->first_name $userInfo->last_name" }}</p>
        <p class="m-0 p-0">{{ $userInfo->phone_number }}</p>
        @if ($userInfo->address !== null)
        <p class="p-0">{{ $userInfo->address }}</p>
        @endif
    </div>
    @if ($userInfo->address === null)
    <a class="btn btn-outline-dark btn-sm mt-3 mb-3" role="button">Tambah alamat</a>
    @endif
</div>