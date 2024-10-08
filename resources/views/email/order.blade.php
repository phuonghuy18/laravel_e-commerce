<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px">

    @if ($mailData['userType'] == 'customer')
    <h1>Cảm ơn bạn đã mua hàng tại </h1>
    <h2>Mã đơn hàng của bạn là #{{ $mailData['order']->id }}</h2>
    @else
    <h1>Bạn có một đơn hàng </h1>
    <h2>Mã đơn hàng là #{{ $mailData['order']->id }}</h2>
    @endif

    
    <h2>Địa chỉ nhận hàng</h2>
    <address>
        <strong>{{ $mailData['order']->first_name.' '.$mailData['order']->last_name }}</strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
        Số điện thoại: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </address>
    <h2>Đơn hàng</h2>
    <table cellpadding="3" cellspacing="3" border="0" width="400">
        <thead>
            <tr style="background: #CCC;">
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>                                        
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td style="text-align: right">${{ number_format($item->price,3) }}</td>                                        
                <td style="text-align: right">{{ $item->qty }}</td>
                <td style="text-align: right">${{ number_format($item->total,3) }}</td>
            </tr>
            @endforeach
            
            
            <tr>
                <th colspan="3" style="text-align: left" >Tổng đơn:</th>
                <td style="text-align: right">${{ number_format($mailData['order']->subtotal,3) }}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: left">Khuyến mãi:{{ (!empty($mailData['order']->coupon_code)) ? '('.$mailData['order']->coupon_code.')' : '' }}</th>
                <td style="text-align: right">${{ number_format($mailData['order']->discount,3) }}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: left">Phí vận chuyển:</th>
                <td style="text-align: right">${{ number_format($mailData['order']->shipping,3) }}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: left">Tổng cộng:</th>
                <td style="text-align: right">${{ number_format($mailData['order']->grand_total,3) }}</td>
            </tr>
        </tbody>
    </table>								

</body>
</html>