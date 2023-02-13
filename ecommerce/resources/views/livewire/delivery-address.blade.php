<div>
    <div class="cf-title">Alamat Pengiriman</div>
    <div class="row m-0 p-0">
        @foreach ($userInfo as $row)
        <p class="m-0 p-0 fw-bold">{{ "$row->first_name $row->last_name" }}</p>
        <p class="m-0 p-0">{{ $row->phone_number }}</p>
        <p class="p-0">{{ $row->address }}</p>
        @endforeach
    </div>
</div>