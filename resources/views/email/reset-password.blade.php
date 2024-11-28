<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px">
    <p>Xin chào, {{ $formData['user']->name }}</p>

    <h1>Bạn có yêu cầu lấy lại mật khẩu</h1>

    <p>Vui lòng nhấn vào link để thực hiện</p>
    
    <a href="{{ route('front.resetPassword',$formData['token']) }}">Nhấp vào đây</a>

    <p>Trân trọng</p>
</body>
</html>