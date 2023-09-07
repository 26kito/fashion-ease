@extends('layout.adminlte.template')
@push('adminstylesheet')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('asset/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet"
    href="{{asset('asset/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('asset/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush

@section('title'){{ $title }}@endsection

@section('heading-navbar'){{ $headingNavbar }}@endsection

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Products List</li>
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
                <h3 class="card-title">Voucher List</h3>
            </div>
            <div class="card-body">
                <a href="{{ url('admin/voucher/insert') }}" class="btn btn-success">Add Data</a>
                <table id="table-content" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center">No.</th>
                            <th style="text-align: center">Nama Voucher</th>
                            <th style="text-align: center">Kode Voucher</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $index => $row)
                        <tr>
                            <td>{{ $vouchers->firstItem() + $index }}</td>
                            <td>{{ $row->title }}</td>
                            <td>{{ $row->code }}</td>
                            <td>{{ $row->is_active }}</td>
                            <td class="row">
                                <a href="" class="btn btn-primary col">
                                    Edit
                                </a>
                                <a href="" class="btn btn-danger col"
                                    onclick="return confirm('Are you sure want do delete this data? It cannot be undo')">
                                    Delete
                                </a>
                            </td>
                            {{-- <td>
                                <a href="" class="btn btn-primary">
                                    Edit
                                </a>
                            </td>
                            <td>
                                <a href="" class="btn btn-danger"
                                    onclick="return confirm('Are you sure want do delete this data? It cannot be undo')">
                                    Delete
                                </a>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- @if ($products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->links() }}
                </div>
                @endif --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('adminscript')
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
    $(document).ready(() => {
        $(function () {
            $('#table-content').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    })
</script>
@endpush