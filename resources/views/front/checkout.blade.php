@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">TRANG CHỦ</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.cart') }}">GIỎ HÀNG</a></li>
                <li class="breadcrumb-item">THANH TOÁN</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form id="orderForm" name="orderForm" method="post" action="">
        <div class="row">
            <div class="col-md-8">
                <div class="sub-title">
                    <h2>Thông tin giao hàng</h2>
                </div>
                <div class="card shadow-lg border-0">
                    <div class="card-body checkout-form">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Họ" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Tên" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : '' }}">
                                    <p></p>
                                </div>            
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : '' }}">
                                    <p></p>
                                </div>            
                            </div>

                            {{-- <div class="col-md-12">
                                <div class="mb-3">
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Select a Country</option>
                                        @if ($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                                <option {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : ''  }} value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>            
                            </div> --}}

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <select name="province" id="province" class="form-control">
                                        <option value="">Tỉnh, thành phố</option>
                                        @if ($provinces->isNotEmpty())
                                            @foreach ($provinces as $province)
                                                <option {{ (!empty($customerAddress) && $customerAddress->province_id == $province->id) ? 'selected' : ''  }} value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="address" id="address" cols="30" rows="3" placeholder="Địa chỉ" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->address : '' }}</textarea>
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="apartment" id="apartment" class="form-control" placeholder="Địa chỉ khác" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : '' }}">
                                </div>            
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}">
                                    <p></p>
                                </div>            
                            </div> --}}

                            {{-- <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ (!empty($customerAddress)) ? $customerAddress->state : '' }}">
                                    <p></p>
                                </div>            
                            </div> --}}
                            
                            {{-- <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (!empty($customerAddress)) ? $customerAddress->zip : '' }}">
                                    <p></p>
                                </div>            
                            </div> --}}

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Số điện thoại" value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}">
                                    <p></p>
                                </div>            
                            </div>
                            

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Ghi chú (nếu có)" class="form-control"></textarea>
                                </div>            
                            </div>

                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-md-4">
                <div class="sub-title">
                    <h2>Tóm tắt đơn hàng</h3>
                </div>                    
                <div class="card cart-summery">
                    <div class="card-body">
                        @foreach (Cart::content() as $item)
                        <div class="d-flex justify-content-between pb-2">
                            <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                            <div class="h6">{{ $item->price*$item->qty }}đ</div>
                        </div>
                        @endforeach
                        
                        
                        <div class="d-flex justify-content-between summery-end">
                            <div class="h6"><strong>Thành tiền</strong></div>
                            <div class="h6"><strong>{{ Cart::subtotal() }}</strong></div>
                        </div>

                        <div class="d-flex justify-content-between summery-end">
                            <div class="h6"><strong>Giá giảm</strong></div>
                            <div class="h6"><strong id="discount_value">{{ $discount }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="h6"><strong>Phí vận chuyển</strong></div>
                            <div class="h6"><strong id="shippingAmount">{{ number_format($totalShippingCharge) }}</strong></div>
                        </div>
                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Nhập mã giảm" class="form-control" name="discount_code" id="discount_code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Thêm mã giảm giá</button>
                        </div>
                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                            <div class="mt-4" id="discount-response">
                                {{ Session::get('code')->code }}
                                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                            </div> 
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between mt-2 summery-end">
                            <div class="h5"><strong>Tổng</strong></div>
                            <div class="h5"><strong id="grandTotal">{{ number_format($grandTotal) }}đ</strong></div>
                        </div>                            
                    </div>
                    
                </div>   
                
                <div class="card payment-form ">  
                    <h3 class="card-title h5 mb-3">Chọn phương thức thanh toán</h3>
                    <div class="form-check">
                        <input checked type="radio" name="payment_method" id="payment_method_1" value="cod">
                        <label for="payment_method_1" class="form-check-label">COD</label>
                    </div>

                    <div class="form-check">
                        <input type="radio" name="payment_method" id="payment_method_2" value="atm">
                        <label for="payment_method_2" class="form-check-label">Momo</label>
                    </div>
                    
                    
                    {{-- <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                        <div class="mb-3">
                            <label for="card_number" class="mb-2">Card Number</label>
                            <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="expiry_date" class="mb-2">Expiry Date</label>
                                <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="expiry_date" class="mb-2">CVV Code</label>
                                <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                            </div>
                        </div>
                        
                    </div>   --}}
                    <div class="pt-4">
                        {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                        <button type="submit" class="btn-dark btn btn-block w-100">Thanh toán ngay</button>
                    </div>                      
                </div>

                      
                <!-- CREDIT CARD FORM ENDS HERE -->
                
            </div>
        </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $("#payment_method_1").click(function(){
            if ($(this).is(":checked") == true){
                $("#card-payment-form").addClass('d-none');
            }
        })

        $("#payment_method_2").click(function(){
            if ($(this).is(":checked") == true){
                $("#card-payment-form").removeClass('d-none');
            }
        });

        $("#orderForm").submit(function(){
            event.preventDefault();

            $('button[type="submit"]').prop('disabled',true);
            $.ajax({
                url: '{{ route("front.processCheckout") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    var errors = response.errors;
                    $('button[type="submit"]').prop('disabled',false);

                    if (response.status == false){
                        if (errors.first_name){
                        $("#first_name").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feedback')
                            .html(errors.first_name);
                        } else {
                        $("#first_name").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feedback')
                            .html('');
                        }   

                        if (errors.last_name){
                            $("#last_name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.last_name);
                        } else {
                                $("#last_name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.email){
                            $("#email").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.country){
                            $("#country").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.country);
                        } else {
                            $("#country").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.province){
                            $("#province").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.province);
                        } else {
                            $("#province").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.address){
                            $("#address").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.address);
                        } else {
                            $("#address").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }


                        if (errors.city){
                            $("#city").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.city);
                        } else {
                            $("#city").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                    

                        if (errors.mobile){
                            $("#mobile").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.mobile);
                        } else {
                            $("#mobile").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        } else if (response.atm == true){
        
                            window.location.href="{{ url('payment_ATM') }}/"+response.orderId;
                        } 
                        else {
                            window.location.href="{{ url('thanks/') }}/"+response.orderId;
                        }

                    
                }
            });
        });

        $("#province").change(function(){
            $.ajax({
                url: '{{ route("front.getOrderSummery") }}',
                type: 'post',
                data: {province_id: $(this).val()},
                dataType: 'json',
                success: function(response){
                    if (response.status == true){
                        $("#shippingAmount").html('$'+response.shippingCharge);
                        $("#grandTotal").html('$'+response.grandTotal);

                    }
                }
            })
        });

        $("#apply-discount").click(function(){
            $.ajax({
                url: '{{ route("front.applyDiscount") }}',
                type: 'post',
                data: {code: $("#discount_code").val(), country_id: $("#country").val()},
                dataType: 'json',
                success: function(response){
                    if (response.status == true){
                        $("#shippingAmount").html('$'+response.shippingCharge);
                        $("#grandTotal").html('$'+response.grandTotal);
                        $("#discount_value").html('$'+response.discount);
                        $("#discount-response-wrapper").html(response.discountString);
                    } else {
                        $("#discount-response-wrapper").html("<span class='text-danger'>"+response.message+"</span>");

                    }
                }
            });
        });

        $('body').on('click',"#remove-discount", function(){
            $.ajax({
                url: '{{ route("front.removeCoupon") }}',
                type: 'post',
                data: {country_id: $("#country").val()},
                dataType: 'json',
                success: function(response){
                    if (response.status == true){
                        $("#shippingAmount").html('$'+response.shippingCharge);
                        $("#grandTotal").html('$'+response.grandTotal);
                        $("#discount_value").html('$'+response.discount);
                        $("#discount-response").html('');
                        $("#discount_code").val('');
                    }
                }
            });
        });

       /*  $("#remove-discount").click(function(){
            
        }); */

    </script>
@endsection