@extends('layout.adminlte.template')

@section('title')
{{$title}}
@endsection

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Voucher</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('admin/')}}">Home</a></li>
                    <li class="breadcrumb-item active">Voucher</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@push('adminstylesheet')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush

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
        <div class="col-md-10 offset-md-1">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create Voucher</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ url('admin/voucher/insert') }}" method="POST" id="quickForm">

                    @csrf

                    <div class="card-body">
                        <!-- Card Body -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="vouchername">Voucher Name</label>
                                <input type="text" name="vouchername" class="form-control" id="vouchername" placeholder="Enter voucher name" value="{{old('name')}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vouchercode">Voucher Code</label>
                                <input type="text" name="vouchercode" class="form-control" id="vouchercode" placeholder="Enter voucher name" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label for="startdate">Start Date</label>
                                <input type="datetime" name="startdate" id="startdate" class="form-control">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="enddate">End Date</label>
                                <input type="datetime" name="enddate" id="enddate" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="quota">Quota</label>
                                <input type="number" name="quota" class="form-control" id="quota" value="{{old('email')}}" inputmode="numeric">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label for="disctype">Discount Type</label>
                                <select id="disctype" name="disctype" class="form-control" >
                                    <option selected disabled>Choose Discount Type...</option>
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="discval">Discount Value</label>
                                <input type="number" name="discval" class="form-control" id="discval" value="{{old('email')}}" inputmode="numeric">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="maxdiscount">Max Discount</label>
                                <input type="number" name="maxdiscount" class="form-control" id="maxdiscount" value="{{old('email')}}" inputmode="numeric">
                            </div>
                        </div>
                    </div> <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="insertBtn">Insert</button>
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

@push('adminscript')
<script src="{{ asset('asset/adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('asset/adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ asset('asset/bootstrap-growl/jquery.bootstrap-growl.min.js') }}"></script>
<script src="{{ asset('js/customNotif.js') }}"></script>
@if (Session::has('toastr'))
<script>
    let message = {{ Session::get('toastr') }}

    let event = customNotif.notif('success', message)
    window.dispatchEvent(event)
</script>
@endif
<script>
    $('input[name="startdate"]').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
    });

    $('input[name="startdate"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY HH:mm'));
        $('input[name="enddate"]').prop('disabled', false);
        let mindate = picker.startDate.format('MM/DD/YYYY'); // Format as MM/DD/YYYY

        $('input[name="enddate"]').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            minDate: mindate,
        });
    });

    if ($('input[name="enddate"]').prop('disabled') == false) {
        $('input[name="enddate"]').prop('disabled', true);
    }

    $('input[name="enddate"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY HH:mm'));
    });

    $('#quickForm').validate({
        rules: {
            vouchername: {
                required: true,
                minlength: 5
            },
            vouchercode: {
                required: true,
                minlength: 5
            },
            quota: {
                required: true,
                maxlength: 4,
            },
            startdate: {
                required: true,
            },
            enddate: {
                required: true,
            },
            discval: {
                required: true,
            },
            maxdiscount: {
                required: true,
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
            },
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

    function numberInputValidation(inputElement, message) {
        let regex = /^(?!0)/;

        $(inputElement).on('keydown', function(e) {
            if ($(this).val().length === 0 && !regex.test(e.key)) {
                e.preventDefault();
                let status = 'error';

                let event = customNotif.notif(status, message);
                window.dispatchEvent(event);
            }

            if (inputElement == '#quota') {
                if ($(this).val().length >= 4 && e.keyCode !== 8 && e.keyCode !== 46) {
                    e.preventDefault(); // Prevent input if length is 4 or greater, except for Backspace and Delete
                }
            }
        });
    }

    // Call the function with the appropriate input elements
    numberInputValidation('#quota', 'Kuota harus lebih besar dari 0!');
    numberInputValidation('#discval', 'Discount value harus lebih besar dari 0!');
    numberInputValidation('#maxdiscount', 'Max discount harus lebih besari dari 0!');

    $(document).on('click', '#insertBtn', (e) => {
        let disctype = $('#disctype').val()

        if (disctype == null || disctype == '') {
            e.preventDefault()
            let status = 'error';
            let message = 'Pilih discount type terlebih dulu'

            let event = customNotif.notif(status, message);
            window.dispatchEvent(event);
            return;
        }
    })
</script>
@endpush