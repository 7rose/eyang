<?php
    $p = new App\Helpers\Picker;
    $info = new App\Helpers\Info;
    $r = new App\Helpers\Role;
    $f = new App\Helpers\Filter;
?>
@extends('../nav')

@section('content')


<section>
    <div class="container">
    

    <div class="row">
        @if($p->slide())

            <div id="demo" class="carousel slide" data-ride="carousel"> 
              <!-- 轮播图片 -->
              <div class="carousel-inner">
                <div class="carousel-item active">
                <a href="/products/show/{{ $p->slide()['id'] }}">
                  <img src="{{ asset('storage/'.$p->slide()['img']) }}" class="img-fluid">
                </a>
                </div>
              </div>
            </div>
        
        @endif

        @if(isset($records) && $records->count())
            @foreach($records as $record)
            <div class="container product-title">  
                <strong class="text-primary "><i class="fa fa-connectdevelop" aria-hidden="true"></i> {{ $record->text }}</strong>
            </div>

                @foreach($record->products as $product)
                    @if($f->onLine($product))

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
           
                            <p class="text-center">
                                <strong>¥{{ $product->quota }}</strong><br>
                                <strong>{{ $product->name }}</strong>

                                @if($r->issuer())
                                    <br>({{ $product->org->name }}-{{ $product->type->val }})
                                    <br>
                                    <a href="/products/slide/{{ $product->id }}" class="badge badge-warning"><i class="fa fa-star" aria-hidden="true"></i> 明星</a>
                                    @if($product->show)
                                        <a href="/products/off/{{ $product->id }}" class="badge badge-warning"><i class="fa fa-fire" aria-hidden="true"></i> 下架</a>
                                    @else
                                        <a href="/products/on/{{ $product->id }}" class="badge badge-success"><i class="fa fa-leaf" aria-hidden="true"></i> 上架</a>
                                    @endif
                                    <a href="/products/delete/{{ $product->id }}" class="badge badge-danger"><i class="fa fa-flash" aria-hidden="true"></i> 删除!</a>
                                @endif
                            </p>
                        </div>
                        
                    @endif
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