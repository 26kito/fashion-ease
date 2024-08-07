@extends('layout.template')

@section('title'){{ $title }}@endsection

@push('stylesheet')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-qJjtSPLK8cyVRxZx"></script>
@endpush

@section('content')
<div class="card mt-5 mb-5" id="snap-container" style="width: 1000px; margin: 0 auto;">
	{{-- <div id="snap-container"></div> --}}
	{{-- <div class="card-header">
		<h3>Berhasil melakukan pembayaran</h3>
	</div>
	<div class="card-body">
		<h3>Detail pembayaran...</h3>
	</div> --}}
</div>
@endsection

@push('script')
<script src="{{ asset('js/cookie.js') }}"></script>
<script>
	const snapToken = cookie.getCookie('snapToken')

	window.snap.embed(snapToken, {
		embedId: 'snap-container'
	});
</script>
@endpush