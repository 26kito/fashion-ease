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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="editModal"></div>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal -->

{{-- <div class="container-fluid">
    <div class="row">
        <div class="col-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <div>
                    <p>Nama:</p>
                    <p>Total:</p>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center">Product Name</th>
                            <th style="text-align: center">Size</th>
                            <th style="text-align: center">Qty</th>
                            <th style="text-align: center">Price</th>
                        </tr>
                    </thead>
                    <tbody id="orderList">
                        @foreach ($orderList as $row)
                        <tr>
                            <td>{{ $row->product_name }}</td>
                            <td>{{ $row->size }}</td>
                            <td>{{ $row->qty }}</td>
                            <td>{{ rupiah($row->product_price) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>Total</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> --}}

<div class="container-fluid">
    <div class="row">
        <div class="col-12 ">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="post">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-light mb-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a><br>
                        <div class="d-flex flex-column align-items-center">
                            <div>
                                <button class="btn btn-sm btn-success btn-accept-order mb-1">
                                    <i class="fas fa-check"></i> Terima Pesanan
                                </button>
                                <button class="btn btn-sm btn-danger btn-cancel-order mb-1">
                                    <i class="fas fa-times"></i> Batalkan Pesanan
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 col-md-6">
                                <b>Order ID : </b>{{ $orderInformation->order_id }}<br>
                                <b>Tanggal Kirim : </b>{{ date('d F Y', strtotime($orderInformation->order_date)) }}<br>
                                <b>Shipping To : </b>{{ "$orderInformation->city_name, $orderInformation->province_name" }}<br>
                                <b>Metode Pembayaran : </b>{{ "$orderInformation->payment_method_name
                                ($orderInformation->payment_method_category)" }}
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
                                    <img src="{{ asset('asset/img/products/'.$row->product_image) }}" alt="" width="100">
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
                                            <p class="font-weight-bold">{{ rupiah($row->qty * $row->product_price) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('adminscript')
<script src="{{asset('asset/bootstrap-growl/jquery.bootstrap-growl.min.js')}}"></script>
@endpush