@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Info Akun Anda</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Nama : {{ Auth::user()->name }}
                    <br>
                    Email : {{ Auth::user()->email }}
                    <br>
                    Username : {{ '@'. Auth::user()->username }}
                    <br>
                    <br>
                    <a href="https://maba.my.id" class="btn btn-success btn-block">Kembali Ke Aplikasi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
