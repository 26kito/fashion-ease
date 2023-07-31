@extends('layout.adminlte.template')

@section('title'){{ $title }}@endsection

@section('heading-navbar'){{ $headingNavbar }}@endsection

{{-- @section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Order List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Order List</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 ">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="post">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-light mb-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a><br>
                        @if ($orderInformation->status_order_id == 1)
                        <div class="d-flex flex-column align-items-center">
                            <div>
                                <button class="btn btn-sm btn-success btn-accept-order mb-1" data-orderid="{{ $orderInformation->order_id }}">
                                    <i class="fas fa-check"></i> Terima Pesanan
                                </button>
                                <button class="btn btn-sm btn-danger btn-cancel-order mb-1" data-orderid="{{ $orderInformation->order_id }}">
                                    <i class="fas fa-times"></i> Batalkan Pesanan
                                </button>
                            </div>
                        </div>
                        @endif
                        <div class="row mt-2">
                            <div class="col-12 col-md-6">
                                <b>Order ID : </b>{{ $orderInformation->order_id }}<br>
                                <b>Tanggal Kirim : </b>{{ date('d F Y', strtotime($orderInformation->order_date)) }}<br>
                                <b>Shipping To : </b>{{ "$orderInformation->city_name, $orderInformation->province_name" }}<br>
                                <b>Metode Pembayaran : </b>{{ "$orderInformation->payment_method_name ($orderInformation->payment_method_category)" }}<br>
                                <b>Status : </b>{{ $orderInformation->status }}<br>
                            </div>
                            <div class="col-12 col-md-6">
                                <b>Nama : </b>{{ "$orderInformation->first_name $orderInformation->last_name" }}<br>
                                <b>Email : </b>{{ $orderInformation->email }}<br>
                                <b>No. HP : </b>{{ $orderInformation->phone_number }}
                            </div>
                        </div>
                    </div>
                    <div class="post">
                        <div>
                            @foreach ($orderList as $row)
                            <div class="row detail-product">
                                <div class="col-md-3 col-12 text-center align-self-center mt-2">
                                    <img src="{{ asset('asset/img/products/'.$row->product_image) }}" alt=""
                                        width="100">
                                    <p class="mb-0">{{ $row->product_name }}</p>
                                </div>
                                <div class="col-md-9 col-12 align-self-center">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <label class="mb-0">Kuantitas Beli</label>
                                            <p>{{ $row->qty }}</p>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label class="mb-0">Harga Satuan</label>
                                            <p class="price">{{ rupiah($row->product_price) }}</p>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label class="mb-0">Total Harga Produk</label>
                                            <p class="font-weight-bold">{{ rupiah($row->qty * $row->product_price) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 d-md-flex justify-content-end">
                                <div class="col-md-6 col-12">
                                    <div class="row">
                                        <div class="col-6 text-right">
                                            <label>SubTotal :</label>
                                        </div>
                                        <div class="col-6">
                                            <p class="font-weight-bold mb-0" id="sub_total">
                                                {{ rupiah($orderInformation->sub_total) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right">
                                            <label>Biaya Pengiriman :</label>
                                        </div>
                                        <div class="col-6">
                                            <p class="font-weight-bold text-danger mb-0">
                                                {{ rupiah($orderInformation->shipment_fee) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right">
                                            <label>GrandTotal :</label>
                                        </div>
                                        <div class="col-6">
                                            <p class="font-weight-bold text-success mb-0" id="grand_total">
                                                {{ rupiah($orderInformation->grand_total) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('adminscript')
<script src="{{ asset('asset/bootstrap-growl/jquery.bootstrap-growl.min.js') }}"></script>
<script>
    $('.btn-accept-order').on('click', () => {
        let orderID = $('.btn-accept-order').data('orderid');

        $.ajax({
            url: '/api/accept-order',
            method: 'POST',
            data: {
                orderIDParam: orderID
            },
            success: function(response) {
                // alert
                $.bootstrapGrowl(response.message, {
                    type: 'success',
                    offset: {from: 'top', amount: 75},
                    align: 'center',
                    width: 400,
                    stackup_spacing: 15
                });
            }
        })
    })

    $('.btn-cancel-order').on('click', () => {
        let orderID = $('.btn-cancel-order').data('orderid');

        $.ajax({
            url: '/api/cancel-order',
            method: 'POST',
            data: {
                orderIDParam: orderID
            },
            success: function(response) {
                // alert
                $.bootstrapGrowl(response.message, {
                    type: 'success',
                    offset: {from: 'top', amount: 75},
                    align: 'center',
                    width: 400,
                    stackup_spacing: 15
                });
            }
        })
    })
</script>
@endpush