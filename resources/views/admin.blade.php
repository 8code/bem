@extends('layouts.app')

@section('content')
<div class="container">

<div class="card row">
    <div class="col-md-12 row py-4">
        <div class="col-md-3 text-center">
                
                <h1 class="text-success">
                <small class="text-secondary">Group</small>  {{ \App\group::count() }}
                </h1>
        </div>
    
        <div class="col-md-3 text-center">
                
                <h1 class="text-primary">
                <small class="text-secondary">Users</small> {{ \App\User::count() }}
                </h1>
        </div>

        <div class="col-md-3 text-center">
                
                <h1 class="text-success">
                   <small class="text-secondary">Quest </small>  {{ \App\qna::count() }}
                </h1>
        </div>
       
        
        <div class="col-md-3 text-center">
               <h1 class="text-warning">
                <small class="text-secondary">University</small>  {{ \App\user::distinct()->count('university') }}
                </h1>
        </div>
        
    </div>
    <div class="col-md-12 row py-4">
        <div class="col-md-3 text-center">
            <h1 class="text-warning">
                <small class="text-secondary">Event</small>  {{ \App\event::count() }}
                </h1>
        </div>
        <div class="col-md-3 text-center">
            <h1 class="text-warning">
                <small class="text-secondary">Chatting</small>  {{ \App\messages::count() }}
                </h1>
        </div>
        <div class="col-md-3 text-center">
               
            <h1 class="text-danger">
            <small class="text-secondary">Aktivitas </small>  {{ \App\activity::count() }}
             </h1>
     </div>
     <div class="col-md-3 text-center">
               
        <h1 class="text-danger">
        <small class="text-secondary">Report </small>  {{ \App\report::count() }}
         </h1>
 </div>
    </div>
    {{-- <div class="card col-md-12">

            <div class="card-body">
            
                <h4 >
                1. Topup Diamon 
                
              
                </h4>
               
                <br>
                <table class="table table-bordered">
                <tr>
                    <td>Id</td>
                    <td>Username</td>
                    <td>Diamon</td>
                </tr>
                @foreach(App\diamon::where("user_id",null)->latest()->take(5)->get() as $i => $d)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ '@'. \App\User::find($d->to_id)->username }}</td>
                    <td>{{ $d->diamon }} </td>
                </tr>
                @endforeach
              </table>
              <small>
                "Harga Per 1 Diamon Rp. 1000" <br>

                </small>
              <br>
                <form action="/topup-diamon" method="post" class="row">
                    @csrf 
                    <div class="form-group col-md-4">
                        <label for="username">Username (Tanpa @) </label>
                        <input type="text" name="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="diamon">Jumlah Diamon</label>
                        <input type="text" name="diamon" class="form-control" placeholder="Jumlah Diamon">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="submit">.</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
    </div>

    <div class="card col-md-12">
            <div class="card-body">
            
            
            <h4>2. Widraw Diamon</h4>
            <small>
             - Biaya Widraw 10% <br>
             - Minimal Widraw 1000 Diamon / Rp. 1.000.000 <br>
            </small>
            <br>
            <div class="table-responsive">
              <table class="table table-bordered">
                <tr>
                    <td>Id</td>
                    <td>Username</td>
                    <td>Diamon</td>
                    <td>Tujuan (Bank) </td>
                    <td>Tanggal</td>
                    <td>Status</td>
                    <td>Aksi</td>
                </tr>
              </table>
              
            </div>

            </div>
    </div> --}}

    
    {{-- <div class="card col-md-12">
            <div class="card-body">
            
            3. Brodcast Email
            </div>
    </div> --}}
</div>
</div>
@endsection
