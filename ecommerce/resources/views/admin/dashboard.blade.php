@extends('layout.adminlte.template')

@section('title')
    {{$title}}
@endsection

@section('content')
  <div class="row">
    <h3>Hi {{ Auth::user()->name }}!</h3>
  </div>
@endsection