@extends('layout.adminlte.template')

@section('title'){{ $title }}@endsection

@section('content')
  <div class="row">
    <h3>Hi {{ Auth::user()->username }}!</h3>
  </div>
@endsection