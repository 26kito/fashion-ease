@extends('layout.adminlte.template')

@section('title')
    {{$title}}
@endsection

@push('css')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('asset/adminlte/plugins/daterangepicker/daterangepicker.css')}}">
@endpush

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Order Page</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                <li class="breadcrumb-item active">Order</li>
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
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Order</h3>
                    </div>
                    <form action="{{url('admin/order/insert')}}" method="POST">

                        @csrf

                        <div class="card-body"> <!-- Card Body -->
                            <div class="form-group"> <!-- Nama Pelanggan -->
                                <label for="user_id">Nama Pelanggan:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                        <i class="fas fa-user-alt"></i>
                                        </span>
                                    </div>
                                    <select name="user_id" class="form-control">
                                        @foreach( $users as $row )
                                            <option value="{{$row->id}}">{{$row->id. ' - '. $row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> <!-- End of Nama Pelanggan -->
                            <div class="form-group"> <!-- Tanggal Order -->
                                <label for="tanggal_order">Tanggal Order:</label>
                
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="tanggal_order" class="form-control" id="tanggal_order">
                                </div>
                            </div> <!-- End of Tanggal Order -->
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Page specific script -->
    <script>
        $('#tanggal_order').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY',
            },
            singleDatePicker: true,
            showDropdowns: true
        });
    </script>
@endpush