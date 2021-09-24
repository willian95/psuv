@extends('layout')

@section('title', 'Error en la verificacion')

@section('content')
    <img height="100" src="https://api.descomplicate.dev.cronapis.com/images/descomplicate-logo.png" />
    <div style="padding: 5px 50px">
        <h3>oops!</h3>
        <h4>Error {{ $code ?? '400' }} : {{ $message ?? 'Error en la verificacion del email' }}</h4>
    </div>
@endsection
