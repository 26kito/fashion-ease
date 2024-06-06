@extends('layout.template')

@section('title'){{ $title }}@endsection

@section('content')
<!-- Page info -->
{{-- <div class="page-top-info">
	<div class="container">
		<h4>Products Category</h4>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb site-pagination">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Products Category</li>
			</ol>
		</nav>
	</div>
</div> --}}
<!-- Page info end -->

<section class="category-section spad mt-5">
	<div class="container">
		@livewire('search-result', ['keyword' => $keyword])
	</div>
</section>
@endsection