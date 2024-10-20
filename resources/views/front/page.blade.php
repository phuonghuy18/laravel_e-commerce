@extends('front.layouts.appcustom')


@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item">{{ $page->name }}</li>
            </ol>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-sm-5 block-terms-title" style="width: 400px">
            <div id="undefined-sticky-wrapper" class="sticky-wrapper" style="margin-top: 30px">
                <div class="col-8">
                    <h3 class="titlemain" style="font-size: 40px; font-family:utm avo; text-transform: uppercase; line-height: 60px">{{ $page->name }}</h3>
                </div>
                
            </div>
        </div>
        
        <div class="col-md-8 col-sm-7 block-terms-content">
            <div class="col" style="font-size: 16px">
                {!! $page->content !!}
            </div>
            
        </div>
    </div>
</div>
@endsection

<div style="margin-inline: 10px"></div>