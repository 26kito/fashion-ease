@extends('layout.adminlte.template')

@section('title')
    {{$title}}
@endsection

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
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Order</h3>
                </div>
                <div class="card-body">
                    <a href="{{url('admin/order/insert')}}" class="btn btn-success">Insert</a>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nama</th>
                                <th style="text-align: center">Tanggal Order</th>
                                <th colspan="2" style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order as $row)
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->order_date}}</td>
                                    <td><a href="{{url('admin/order/lihat-pesanan/'.$row->id)}}" class="btn btn-primary">Lihat Pesanan</a></td>
                                    <td><a href="{{url('admin/delete/order/'.$row->id)}}" class="btn btn-danger" onclick="return confirm('Seluruh pesanan akan di hapus. Anda yakin ingin menghapus ini?')">Hapus</a></td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('asset/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('asset/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "order": [[ 1, "asc" ]],
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endpush