<?php
    $p = new App\Helpers\Picker;
    $info = new App\Helpers\Info;
?>
@extends('../nav')

@section('content')


<section>
    <div class="alert alert-info note-pad"><small>135***** 在小鸟有钱下款3000!</small> </div>

    <div class="container">
    <div class="row">

        @if(isset($records) && $records->count())
            @foreach($records as $record)
            <div class="container product-title">  
                <strong class="text-primary "><i class="fa fa-connectdevelop" aria-hidden="true"></i> {{ $record->text }}</strong>
            </div>

                @foreach($record->products as $product)
                <div class="col-4 col-sm-2 text-center">
                    <a href="/products/show/{{ $product->id }}">
                      <img src="{{ $product->img }}" class="rounded product-icon"></a>
                    @if($product->fs)
                        <span class="badge text-white water product-badge"><i class="fa fa-shower" aria-hidden="true"></i></span>
                    @else
                        @if($p->fresh($product->id))
                            <span class="badge text-white leaf product-badge"><i class="fa fa-leaf" aria-hidden="true"></i></span>
                        @endif
                    @endif
   
                    <p>
                        <strong>¥{{ $product->quota }}</strong><br>
                        <strong>{{ $product->name }}</strong>
                    </p>
                    <p>
                        
                    </p>
                </div>
                @endforeach

            @endforeach
        @else
        <div class="container">
          <div class="alert alert-info cent">
            <h1><i class="fa fa-money" aria-hidden="true"></i></h1>
            <p>尚无记录. 可能是当前产品都不符合您的筛选条件, 若需咨询或者人工推荐, 请联系客服</p>
          </div>
        </div>
          
        @endif
    </div>
</div>
</section>

@endsection