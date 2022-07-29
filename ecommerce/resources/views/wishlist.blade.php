@extends('layout.template')

@section('title')
	{{$title}}
@endsection

@section('content')
<!-- Page info -->
<div class="page-top-info">
	<div class="container">
		<h4>My Wishlist</h4>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb site-pagination">
			  <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			  <li class="breadcrumb-item active" aria-current="page">My Wishlist</li>
			</ol>
		  </nav>
	</div>
</div>
<!-- Page info end -->
<div class="container">
    <div class="p-3 mb-2 bg-dark text-white">My Wishlist</div>
</div>
@endsection