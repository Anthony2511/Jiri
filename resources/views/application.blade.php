@extends('layouts.app')

@section('custom-class', 'application')

@section('content')
    <div class="o-wrapper">
        <router-view></router-view>
    </div>
@endsection
