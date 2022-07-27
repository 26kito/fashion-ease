@extends('layout.adminlte.template')

@section('title')
    {{$title}}
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('admin/')}}">Home</a></li>
                    <li class="breadcrumb-item active">User</li>
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
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Insert Data</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{url('admin/form/user/insert')}}" method="POST" id="quickForm">

                    @csrf

                    <div class="card-body"> <!-- Card Body -->
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name" value="{{old('name')}}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" value="{{old('email')}}">
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label><br>
                            <input class="form-group-label" type="radio" name="level" id="roleUser" value="USER" checked>
                            <label class="form-group-label" for="roleUser">
                              User
                            </label>
                            <input class="form-group-label" type="radio" name="level" id="roleAdmin" value="ADMIN">
                            <label class="form-group-label" for="roleAdmin">
                              Admin
                            </label>
                          </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" value="{{old('password')}}">
                        </div>
                    </div> <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@push('js')
    <!-- jquery-validation -->
    <script src="{{asset('asset/adminlte/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/jquery-validation/additional-methods.min.js')}}"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
        $('#quickForm').validate({
            rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 5
            },
            },
            messages: {
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            }
        });
        });
    </script>
@endpush