@extends('layouts.auth')

@if (session('data'))
    @php
        $user = session('data') ?? '';
    @endphp
@else 
    @php
        $user = '';
    @endphp
@endif

@section('content')

@section('css-after')
<style
>

html, body {
  height: 100%;
  background-color: #00171F;
  color: #FAFAFA;
}

.form__answer {
  display: inline-block;
  box-sizing: border-box;
  width: 40%;
  vertical-align: top;
  font-size: 10px;
  text-align: center;
  margin-top: 10px
}

/* Input style */
input[type="radio"] {
  opacity: 0;
  width: 0;
  height: 0;
  
}

input[type="radio"]:checked ~ label img {
  opacity: 1;
  border: 3px solid #fff;
  border-radius: 50%
}
.rounded-full{
    border-radius:30px
}

</style>
    
@endsection

{{-- 
<img src="/img/bg.jpg" alt="Bg BEM"
style="position:fixed;
width:100%;top:0;left:0;opacity:.2"
> --}}

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card rounded-full pt-5" style="background-color: #00171F;">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- F --}}
                    <div class="row">
                        <div class="col-md-12 col-lg-6 px-lg-5">
                            <div class="col-md-12 text-center">
                                <h3>{{ __('Daftar Akun Baru') }}</h3>
    
                                @if (session('info'))
                                    <div class="alert alert-success">
                                        {{ session('info') }}
                                    </div>
                                @endif
                                
                            </div>

                            <div class="row">
                       

                                @if(!$user)
                                <div class="col-md-12 my-3">
                                    <a class="btn btn-outline-warning btn-block btn-sm mb-3 rounded-full" 
                                    href="https://mahasiswa.test/daftar/fb">
                                    
                                    <img class="mx-1" width="27px" src="/img/fb.png" alt="Facebook">
                                    Daftar dengan Facebook    
                                </a>
                                </div>
        
                                @else 
                                <div class="col-md-12 text-center mt-3">
                                    <input type="hidden" name="avatar" value="{{$user->avatar}}">
                                    <img class="img-fluid" style="border-radius:50%" src="{{$user->avatar}}" alt="avatar">
                                </div>
                                @endif
        
        
                            </div>


                            <div class="form-group row">
                                <label for="name" class="col-md-12 col-form-label">{{ __('Nama') }}</label>
                                <div class="col-md-12">
                                    <input placeholder="Masukan Nama" id="name" type="text" class="rounded-full form-control @error('name') is-invalid @enderror" 
                                    name="name" 
                                    
                                    @if($user)
                                    value="{{ $user->name }}"
                                    @else 
                                    value="{{ old('name') }}"
                                    @endif
                                    
                                    
                                    required autocomplete="name" autofocus>
    
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
    
                            <div class="form-group row">
                                <label for="username" class="col-md-12 col-form-label">{{ __('Username') }}</label>
    
                                <div class="col-md-12">
                                    <input placeholder="Pilih Username" id="username" type="text" 
                                    class="rounded-full form-control @error('username') is-invalid @enderror" 
                                    name="username" 
                                  
                                    value="{{ old('username') }}"
                                    
                                    
                                    required autocomplete="username" autofocus>
    
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                         
    
                            <div class="form-group row">
                                <label for="email" class="col-md-12 col-form-label">{{ __('Alamat Email') }}</label>
    
                                <div class="col-md-12">
                                    <input 
                                    placeholder="Masukan Email"
                                    id="email" type="email" 
                                    class="rounded-full form-control @error('email') is-invalid @enderror" 
                                    name="email" 
                                    
                                    @if($user)
                                    value="{{ $user->email }}"
                                    @else 
                                    value="{{ old('email') }}"
                                    @endif
                                    
                                    
                                    required autocomplete="email">
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="jk" class="col-md-12"> Jenis Kelamin</label>
                                <div class="col-md-12 text-center">
                                        <fieldset class="form__options">
                                            <p class="form__answer"> 
                                                <input type="radio" name="jk" id="match_1" value="L" checked> 
                                                <label for="match_1">
                                                    <img width="50px" src="/img/laki-laki.png" alt="Laki Laki">
                                                    Laki-Laki
                                                </label> 
                                              
                                            </p>
                                            
                                            <p class="form__answer"> 
                                                <input type="radio" name="jk" id="match_2" value="P"> 
                                                <label for="match_2">
                                                   <img width="50px" src="/img/perempuan.png" alt="Perempuan">
                                                   Perempuan
                                                </label> 
                                      
                                    </p>
                                </fieldset>
                            </div>
                            
                            </div>
    
                        </div>
                        <div class="col-md-12 col-lg-6 px-lg-5">


                            <div class="form-group row">
                                <label for="tgl_lahir" class="col-md-12 col-form-label">{{ __('Tanggal Lahir') }}</label>
    
                                <div class="col-md-12">
                                    <input 
                                    placeholder="Masukan tgl_lahir"
                                    id="tgl_lahir" type="date"
                                     class="rounded-full form-control @error('tgl_lahir') is-invalid @enderror" 
                                     name="tgl_lahir" 
                                    value="{{ old('tgl_lahir') }}"
                                    
                                    required autocomplete="tgl_lahir">
    
                                    @error('tgl_lahir')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="no_hp" class="col-md-12 col-form-label">{{ __('No Hp/WhatsApp') }}</label>
    
                                <div class="col-md-12">
                                    <input 
                                    placeholder="Masukan No Hp/WhatsApp"
                                    id="no_hp" type="text" 
                                    class="rounded-full form-control @error('no_hp') is-invalid @enderror"
                                     name="no_hp" 
                                    value="{{ old('no_hp') }}"
                                    
                                    required autocomplete="no_hp">
    
                                    @error('no_hp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                              
                            <div class="form-group row">
                                <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>
    
                                <div class="col-md-12">
                                    <input placeholder="Masukan Password" id="password" type="password" class="rounded-full form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-12 col-form-label">{{ __('Confirm Password') }}</label>
    
                                <div class="col-md-12">
                                    <input placeholder="Verifikasi Password"  id="password-confirm" type="password" class="rounded-full form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
    
                            <div class="form-group row ">
                                <div class="col-md-12  mt-2">
                                    <button type="submit" class="btn btn-block btn-outline-warning rounded-full mb-3">
                                        <img class="mx-1" width="20px" src="/img/send.png" alt="Daftar">
                                        {{ __('Daftarkan !') }}
                                    </button>

                                    <a href="http://localhost:3000/login" class="btn text-warning btn-link btn-block btn-sm mb-3 rounded-full" 
                                        >
                                        Sudah Punya Akun? Login..
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- F --}}


                </form>

                

                      

                        
                   

                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
