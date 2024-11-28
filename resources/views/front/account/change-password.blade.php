@extends('front.layouts.appwithoutsearch')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">TÀI KHOẢN</a></li>
                <li class="breadcrumb-item">ĐỔI MẬT KHẨU</li>
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
                        <h2 class="h5 mb-0 pt-2 pb-2">Đổi mật khẩu</h2>
                    </div>
                    <form action="" method="post" id="changePasswordForm">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="mb-3">               
                                <label for="name">Mật khẩu cũ</label>
                                <input type="password" name="old_password" id="old_password"  class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">               
                                <label for="name">Mật khẩu mới</label>
                                <input type="password" name="new_password" id="new_password" placeholder="Mật khẩu có độ dài tối thiểu 5 ký tự" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">               
                                <label for="name">Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                                <p></p>
                            </div>
                            <div class="d-flex">
                                <button id="submit" name="submit" type="submit" class="btn btn-dark">Lưu</button>
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
<script type="text/javascript">
    $("#changePasswordForm").submit(function(e){
        e.preventDefault();
        $("#submit").prop('disabled',true)
        $.ajax({
            url: '{{ route('account.processChangePassword') }}',
            type:'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                $("#submit").prop('disabled',false);
                if (response.status == true){
                    // Xử lý khi mật khẩu được thay đổi thành công
                    window.location.href = "{{ route('account.changePassword') }}";
                } else {
                    var errors = response.errors;
                    
                    // Kiểm tra lỗi cho old_password
                    if (errors.old_password){
                        $("#old_password").addClass('is-invalid').siblings('p').html(errors.old_password).addClass('invalid-feedback');
                    } else {
                        $("#old_password").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }

                    // Kiểm tra lỗi cho new_password
                    if (errors.new_password){
                        $("#new_password").addClass('is-invalid').siblings('p').html(errors.new_password).addClass('invalid-feedback');
                    } else {
                        $("#new_password").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }

                    // Kiểm tra lỗi cho confirm_password
                    if (errors.new_password_confirmation){
                        $("#new_password_confirmation").addClass('is-invalid').siblings('p').html(errors.new_password_confirmation).addClass('invalid-feedback');
                    } else {
                        $("#new_password_confirmation").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }
                }
            }
        });
    });
</script>


@endsection