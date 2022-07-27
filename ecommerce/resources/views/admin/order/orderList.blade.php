@extends('layout.adminlte.template')

@section('title')
    {{$title}}
@endsection

@section('content-header')
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
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    <div class="container-fluid">
        <div class="row">
            {{-- List Order --}}
            <div class="col-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Order List</h3>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nama Pelanggan</th>
                                <th style="text-align: center">Nama Produk</th>
                                <th style="text-align: center">Qty</th>
                                <th colspan="2" style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="orderList">
                            @foreach($orderList as $row)
                                <tr>
                                    <td>{{$row->nama_user}}</td>
                                    <td>{{$row->nama_produk}}</td>
                                    <td>{{$row->qty}}</td>
                                    <td><button class="btn btn-primary btn-edit" onclick="edit({{$row->id}})">Ubah</button></td>
                                    {{-- <td><button class="btn btn-primary btn-edit">Ubah</button></td> --}}
                                    <td><a href="{{url('admin/delete/order_items/'.$row->id)}}" class="btn btn-danger btn-delete">Hapus</a></td>
                                    {{-- <td><button class="btn btn-danger btn-delete">Hapus</button></td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Insert Order --}}
            <div class="col-6">
                <div class="card card-primary" id="actionForm">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Barang</h3>
                    </div>
                    <form method="POST" id="insert-order-form">
                    {{-- <form action="{{url('admin/order/lihat-pesanan/{id}')}}" method="POST" id="insert-order-form"> --}}
                        @csrf
        
                        <div class="card-body"> <!-- Card Body -->
                            <div class="form-group"> <!-- Nama Produk -->
                                <label for="product_id">Nama Produk:</label>
                                <input name="order_id" type="hidden" value="{{$id}}" id="order_id">
                                <div class="input-group">
                                    <select name="product_id" id="product_id" class="form-control">
                                        @foreach( $products as $row )
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> <!-- End of Nama Produk -->
                            <div class="form-group"> <!-- Jumlah -->
                                <label for="qty">Jumlah:</label>
                                {{-- <input type="number" name="qty" id="qty" min="1" max="20" value="1" class="form-control"> --}}
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <button type="button" onclick="decrement()" class="btn btn-danger" data-type="minus" data-field="quant[1]">
                                            <span class="fa fa-minus"></span>
                                        </button>
                                    </span>
                                    <input type="number" name="qty" id="qty" min="1" max="20" value="1" class="qty form-control col-md-2 quantity-field border-0 text-center w-25">
                                    <span class="input-group-append">
                                        <button type="button" onclick="increment()" class="btn btn-success" data-type="plus" data-field="quant[1]">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </span>
                                </div>
                            </div> <!-- End of Jumlah -->
                        </div>
                        <div class="card-footer">
                            <button type="button" onclick="insert()" name="submit" class="btn btn-primary">Submit</button>
                            {{-- <button type="submit" name="submit" class="btn btn-primary">Submit</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('asset/bootstrap-growl/jquery.bootstrap-growl.min.js')}}"></script>
    <script>
        // Insert Data
        function insert() {
            const order_id = $('#order_id').val();
            const product_id = $('#product_id').val();
            const qty = $('#qty').val();
            $.ajax({
                type: 'POST',
                url: '/api/order/order_items/' + order_id,
                data: {
                    'order_id': order_id,
                    'product_id': product_id,
                    'qty': qty
                },
                success: function(result) {
                    $('#orderList').html(dataTable(result.data));
                    // alert
                    $.bootstrapGrowl("Berhasil Menambahkan Pesanan", {
                        type: 'success',
                        offset: {from: 'top', amount: 75},
                        align: 'center',
                        width: 400,
                        stackup_spacing: 15
                    });
                }
            })
        }
        // Delete Data
        $(document).on('click', function(e) {
            if($(e.target).hasClass('btn-delete')) {
                const order_id = $(e.target).data('order-id');
                const id = $(e.target).data('id');
                $.ajax({
                    type: 'DELETE',
                    url: `/api/order/${order_id}/order_items/${id}/delete`,
                    success: function(result) {
                        $('#orderList').html(dataTable(result.data));
                        // alert
                        $.bootstrapGrowl("Berhasil Menghapus Pesanan", {
                            type: 'danger',
                            offset: {from: 'top', amount: 75},
                            align: 'center',
                            width: 400,
                            stackup_spacing: 15
                        });
                    }
                })
            }
        })
        // Show Data
        function dataTable(data) {
            let table = '';
            data.forEach((d) => {
                table += `
                    <tr>
                        <td>${d.name}</td>
                        <td>${d.product.name}</td>
                        <td>${d.qty}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-edit"
                            data-order-id="${d.order_id}"
                            data-id="${d.id}" 
                            onclick="edit(${d.id})">Ubah</button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-delete"
                            data-order-id="${d.order_id}"
                            data-id="${d.id}"
                            onclick="return confirm('Anda yakin ingin menghapus pesanan ini?')">Hapus</button>
                        </td>
                    </tr>
                `;
            })
            return table
        }
        // Edit
        function edit(id) {
            $.ajax({
                type: 'GET',
                url: `/api/order/order_items/${id}/edit`,
                success: function(result) {
                    $('#exampleModal').modal('show');
                    $('#editModal').html(result);
                }
            })
        }
        // Update
        function update(id) {
            let order_id = $('#updateOrderId').val();
            let product_id = $('#updateProduct').val();
            let qty = $('#updateQty').val();
            $.ajax({
                type: 'PUT',
                url: `/api/order/order_items/${id}/edit`,
                data: {
                    'order_id' : order_id,
                    'product_id' : product_id,
                    'qty' : qty
                },
                success: function(result) {
                    $('#orderList').html(dataTable(result.data));
                    $('#exampleModal').modal('hide');
                }
            })
        }
        function increment() {
            let num = parseInt($('.qty').val());
            i = num+1;
            $('.qty').val(i);
        }
        function decrement() {
            let num = parseInt($('.qty').val());
            i = num-1;
            if ( i < 0 ) {
                i = 0;
            }
            $('.qty').val(i);
        }
    </script>
@endpush