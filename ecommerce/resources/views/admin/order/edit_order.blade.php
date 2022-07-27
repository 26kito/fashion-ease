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
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{session()->get('message')}}
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            {{-- Insert Order --}}
            <div class="col-6">
                <div class="card card-primary">
                    <div class="card-header">
                        {{-- <h3 class="card-title">Ubah Pesanan</h3> --}}
                    </div>
                    {{-- <form action="{{url('admin/edit/order_items/'.$orderItems->id)}}" method="POST">

                        @csrf

                        {{ method_field('PUT') }}
        
                        <div class="card-body"> <!-- Card Body -->
                            <div class="form-group"> <!-- Nama Produk -->
                                <label for="insertProduct">Nama Produk:</label>
                                <input name="order_id" type="hidden" value={{$orderItems->order_id}}>
                                <div class="input-group">
                                    <select name="insertProduct" id="insertProduct" class="form-control">
                                        @foreach( $products as $row )
                                        <option value="{{$row->id}}" {{$orderItems->product_id == $row->id ? 'selected' : ''}}>
                                            {{$row->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> <!-- End of Nama Produk -->
                            <div class="form-group"> <!-- Jumlah -->
                                <label for="qty">Jumlah:</label>
                                <input type="number" name="qty" id="qty" min="1" max="20" value="{{old('qty', $orderItems->qty)}}" class="form-control">
                            </div> <!-- End of Jumlah -->
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
@endsection