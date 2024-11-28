@extends('front.layouts.appwithoutsearch')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">TRANG CHỦ</a></li>
                <li class="breadcrumb-item">QUÊN MẬT KHẨU</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}

        </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}

        </div>
        @endif
        <div class="login-form">    
            <form action="{{ route('front.processForgotPassword') }}" method="post">
                @csrf
                <h4 class="modal-title"><mark>QUÊN MẬT KHẨU</mark></h4>
                <div class="form-group">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                
                
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Xác nhận">              
            </form>			
            
        </div>
    </div>
</section>
@endsection