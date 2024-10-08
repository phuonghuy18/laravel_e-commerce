@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-12">
                @include('front.account.common.message')
            </div>
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Hồ sơ</h2>
                    </div>
                    <form action="" name="profileForm" id="profileForm">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="mb-3">               
                                <label for="name">Tên</label>
                                <input value="{{ $user->name }}" type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">            
                                <label for="email">Email</label>
                                <input value="{{ $user->email }}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">                                    
                                <label for="phone">Số điện thoại</label>
                                <input value="{{ $user->phone }}" type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                <p></p>
                            </div>

                            <div class="d-flex">
                                <button class="btn btn-dark">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Địa chỉ</h2>
                    </div>
                    <form action="" name="addressForm" id="addressForm">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">               
                                <label for="name">Họ</label>
                                <input value="{{ (!empty($address)) ? $address->first_name : '' }}" type="text" name="first_name" id="first_name" placeholder="Nhập họ" class="form-control">
                                <p></p>
                            </div>
                            <div class="col-md-6 mb-3">               
                                <label for="name">Tên</label>
                                <input value="{{ (!empty($address)) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Nhập tên" class="form-control">
                                <p></p>
                            </div>
                            <div class="col-md-6 mb-3">            
                                <label for="email">Email</label>
                                <input value="{{ (!empty($address)) ? $address->email : '' }}" type="text" name="email" id="email" placeholder="Nhập Email" class="form-control">
                                <p></p>
                            </div>
                            <div class="col-md-6 mb-3">                                    
                                <label for="phone">Số điện thoại</label>
                                <input value="{{ (!empty($address)) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile" placeholder="Nhập số điện thoại" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">                                    
                                <label for="province">Quốc gia</label>
                                <select class="form-control" name="country_id" id="country_id">
                                    <option value="">Chọn quốc gia</option>
                                    @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                            <option {{ (!empty($country) && $address->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                        
                                    @endif
                                </select>
                                <p></p>
                            </div>
                            <div class="mb-3">                                    
                                <label for="province">Tỉnh/Thành phố</label>
                                <select class="form-control" name="province_id" id="province_id">
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    @if ($provinces->isNotEmpty())
                                        @foreach ($provinces as $province)
                                            <option {{ (!empty($address) && $address->province_id == $province->id) ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                        
                                    @endif
                                </select>
                                <p></p>
                            </div>
                            <div class="mb-3">                                    
                                <label for="phone">Địa chỉ chi tiết</label>
                                <textarea name="address" id="address" cols="30" rows="4" class="form-control">{{ (!empty($address)) ? $address->address : '' }}</textarea>
                                <p></p>
                            </div>

                            <div class="d-flex">
                                <button class="btn btn-dark">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $("#profileForm").submit(function(event){
            event.preventDefault();

            $.ajax({
                url: '{{ route("account.updateProfile") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    if(response.status == true){

                        $("#name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $("#email").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $("#phone").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    
                        window.location.href ='{{ route('account.profile') }}';

                    } else{
                        var errors = response.errors;
                        if (errors.name){
                            $("#profileForm #name").addClass('is-invalid').siblings('p').html(errors.name).addClass('invalid-feedback');
                        } else{
                            $("#profileForm #name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.email){
                            $("#profileForm #email").addClass('is-invalid').siblings('p').html(errors.email).addClass('invalid-feedback');
                        } else{
                            $("#profileForm #email").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.phone){
                            $("#profileForm #phone").addClass('is-invalid').siblings('p').html(errors.phone).addClass('invalid-feedback');
                        } else{
                            $("#profileForm #phone").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });

        $("#addressForm").submit(function(event){
            event.preventDefault();

            $.ajax({
                url: '{{ route("account.updateAddress") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    if(response.status == true){

                        $("#name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $("#email").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $("#phone").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    
                        window.location.href ='{{ route('account.profile') }}';

                    } else{
                        var errors = response.errors;
                        if (errors.name){
                            $("#addressForm #name").addClass('is-invalid').siblings('p').html(errors.name).addClass('invalid-feedback');
                        } else{
                            $("#addressForm #name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.first_name){
                            $("#addressForm #first_name").addClass('is-invalid').siblings('p').html(errors.first_name).addClass('invalid-feedback');
                        } else{
                            $("#addressForm #first_name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.email){
                            $("#addressForm #email").addClass('is-invalid').siblings('p').html(errors.email).addClass('invalid-feedback');
                        } else{
                            $("#addressForm #email").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.mobile){
                            $("#addressForm #mobile").addClass('is-invalid').siblings('p').html(errors.mobile).addClass('invalid-feedback');
                        } else{
                            $("#addressForm #mobile").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.country_id){
                            $("#addressForm #country_id").addClass('is-invalid').siblings('p').html(errors.country_id).addClass('invalid-feedback');
                        } else{
                            $("#addressForm #country_id").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.province_id){
                            $("#addressForm #province_id").addClass('is-invalid').siblings('p').html(errors.province_id).addClass('invalid-feedback');
                        } else{
                            $("#addressForm #province_id").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.address){
                            $("#addressForm #address").addClass('is-invalid').siblings('p').html(errors.address).addClass('invalid-feedback');
                        } else{
                            $("#addressForm #address").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });
    </script>
@endsection