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
                <li class="breadcrumb-item active">Update Product</li>
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
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h1 class="card-title">Update Data</h1>
                </div>
                <form action="{{url('admin/edit/product/'.$product->id)}}" method="POST">

                    @csrf

                    {{ method_field('PUT') }}

                    <div class="card-body"> <!-- Card Body -->
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input type="text" name="id" class="form-control" id="id" value="{{old('id', $product->id)}}">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" class="form-control">
                            @foreach ($categories as $row)
                            <option value="{{$row->id}}" {{$product->category_id == $row->id ? 'selected' : ''}}>
                                {{$row->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" name="code" class="form-control" id="code" value="{{old('code', $product->code)}}">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{old('name', $product->name)}}">
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="text" name="stock" class="form-control" id="stock" value="{{old('stock', $product->stock)}}">
                    </div>
                    <div class="form-group">
                        <label for="varian">Varian</label>
                        <input type="text" name="varian" class="form-control" id="varian" value="{{old('varian', $product->varian)}}">
                    </div>
                    <div class="form-group">
                        <label for="description">description</label>
                        <input type="text" name="description" class="form-control" id="description" value="{{old('description', $product->description)}}">
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="text" name="image" class="form-control" id="image" value="{{old('image', $product->image)}}">
                    </div>
                    </div> <!-- ./Card Body-->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection