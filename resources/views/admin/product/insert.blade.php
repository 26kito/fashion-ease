@extends('layout.adminlte.template')

@section('title')
    {{$title}}
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Products</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('admin/')}}">Home</a></li>
                    <li class="breadcrumb-item active">Products List</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{session()->get('message')}}
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Insert Data</h3>
                    </div>
                    <form action="{{url('admin/form/product/insert')}}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="card-body"> <!-- Card Body -->
                            {{-- <div class="form-group">
                                <label for="id">ID</label>
                                <input type="text" name="id" class="form-control" id="id" value="{{old('id')}}">
                            </div> --}}
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control">
                                    @foreach ($categories as $row)
                                        <option value="{{$row->id}}">{{$row->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code" class="form-control" id="code" value="{{old('code')}}">
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}">
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="text" name="stock" class="form-control" id="stock" value="{{old('stock')}}">
                            </div>
                            <div class="form-group">
                                <label for="varian">Varian</label>
                                <input type="text" name="varian" class="form-control" id="varian" value="{{old('varian')}}">
                            </div>
                            <div class="form-group">
                                <label for="description">description</label>
                                <input type="text" name="description" class="form-control" id="description" value="{{old('description')}}">
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="formImage" class="form-label">Image</label>
                                    <input class="form-control" name="productsImage" type="file" id="formImage" accept="image/x-png,image/jpeg">
                                </div>
                                {{-- <input type="text" name="image" class="form-control" id="image" value="{{old('image')}}"> --}}
                            </div>
                        </div> <!-- ./Card Body-->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection