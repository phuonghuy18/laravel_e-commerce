@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2)
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="" method="post" id="shippingForm" name="shippingForm">
        <div class="card">
            <div class="card-body">								
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            
                            <select name="province" id="province" class="form-control">
                                <option value="">Select a Country</option>
                                @if ($provinces->isNotEmpty())
                                    @foreach ($provinces as $province)
                                    <option {{ ($shippingCharge->province_id == $province->id) ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                   <option {{ ($shippingCharge->province_id == $province->id) ? 'selected' : '' }} value="other">Khác</option>
                                 @endif
                            </select>
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <input value="{{ $shippingCharge->amount }}" type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>											
                </div>
            </div>							
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
    $("#shippingForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("shipping.update", $shippingCharge->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled',false);
                if(response["status"] == true){

                    window.location.href="{{ Route('shipping.create') }}";

                }else {
                    var errors = response['errors'];
                if(errors['province']){
                    $("#province").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['province']);
                } else{
                    $("#province").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if(errors['amount']){
                    $("#amount").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['amount']);
                } else{
                    $("#amount").removeClass('is-invalid')
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