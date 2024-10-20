@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2)
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Counpon Code</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('coupons.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="discountForm" name="discountForm">
        <div class="card">
            <div class="card-body">								
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Code</label>
                            <input type="text" name="code" id="code" class="form-control" placeholder="Coupon Code">
                            <p></p>	
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name">
                            <p></p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Max Uses</label>
                            <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Max Uses User</label>
                            <input type="number" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="percent">Percent</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Discount Amount</label>
                            <input type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Min Amount</label>
                            <input type="text" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Block</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Starts at</label>
                            <input type="text" autocomplete="off" name="starts_at" id="starts_at" class="form-control" placeholder="Starts at">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Expires at</label>
                            <input type="text" autocomplete="off" name="expires_at" id="expires_at" class="form-control" placeholder="Expires at">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="4"></textarea>
                        </div>
                    </div>
                    										
                </div>
            </div>							
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('coupons.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
        </form>
    </div>
    <!-- /.card -->
</section>
@else
{{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin -->
@endif
<!-- /.content -->
@endsection

@section('customJs')
<script>
$(document).ready(function(){
    $('#starts_at').datetimepicker({
        // options here
        format:'Y-m-d H:i:s',
    });
    $('#expires_at').datetimepicker({
        // options here
        format:'Y-m-d H:i:s',
    });
});


    $("#discountForm").submit(function(event){
        event.preventDefault();
        var element = $(this);

        $("button[type=submit]").prop('disabled',true);

        $.ajax({
            url: '{{ route("coupons.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled',false);
                if(response["status"] == true){

                    window.location.href="{{ Route('coupons.index') }}";

                    $("#code").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#discount_amount").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                    
                    $("#starts_at").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                    
                    $("#expires_at").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                }else {
                    var errors = response['errors'];
                if(errors['code']){
                    $("#code").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['code']);
                } else{
                    $("#code").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if(errors['discount_amount']){
                    $("#discount_amount").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['discount_amount']);
                } else{
                    $("#discount_amount").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
                if(errors['starts_at']){
                    $("#starts_at").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['starts_at']);
                } else{
                    $("#starts_at").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
                if(errors['expires_at']){
                    $("#expires_at").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['expires_at']);
                } else{
                    $("#expires_at").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
                }

                

            }, error: function(jqXHR, exeption){
                console.log("Lỗi");
            }
        });
    });


    

</script>
@endsection