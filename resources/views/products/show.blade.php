<?php
    $p = new App\Helpers\Picker;
    $link = new App\Helpers\Link;
    $r = new App\Helpers\Role;
?>

@extends('../nav')

@section('content')

<section>
    <p></p>
    <div class="container">
        <div class="col-sm-5 cent">
          <div class="row text-left">
            <div class="card card-light form-card col-12 align-items-center">
            <div class="row ">
                
            <a class="pull-left" href="/"><h3 class="text-primary pull-left"><i class="fa fa-money" aria-hidden="true"></i> {{ $record->name }}</h3></a>
            </div>
               <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->errorCorrection('H')->size(180)->merge('/public/'.$record->img, .2)->margin(0)->generate($link->link($record))) !!} ">
                <strong>¥{{ $record->quota }}</strong> 
               <div class="row">
                <blockquote class="blockquote mb-5 text-left product-content">
                    <span class="badge badge-dark">芝麻分 ≥ {{ $record->zm }}</span>
                    <span class="badge badge-dark">实名手机号6个月以上</span><br>

                @if($r->issuer())
                    @if($record->fs)
                        <a href="/products/unfs/{{ $record->id }}" class="badge text-white water"><i class="fa fa-shower" aria-hidden="true"></i> 正在放水!</a>
                    @else
                        <a href="/products/fs/{{ $record->id }}" class="badge badge-light"><i class="fa fa-shower" aria-hidden="true"></i> 还没放水</a>
                    @endif
                @else
                    @if($record->fs)
                        <span class="badge text-white water"><i class="fa fa-shower" aria-hidden="true"></i> 正在放水!</span>
                    @endif
                @endif


                    @if($p->fresh($record->id))
                        <span class="badge text-white leaf"><i class="fa fa-leaf" aria-hidden="true"></i> 新品</span>
                    @endif
                    <br>
                    <small><i class="fa fa-rocket" aria-hidden="true"></i> 提现在请扫码, 或直接点下方链接</small><br>
                    <small><i class="fa fa-bell-o" aria-hidden="true"></i> 温馨提示: 借贷和风险防范属于您自身义务，务必谨慎。</small><br>

                    @if($link->mustFinish($record))
                    <p><small class="text-primary"><span class="badge badge-primary"><i class="fa fa-handshake-o" aria-hidden="true"></i> 报备产品</span> 下款率高于常规产品, 但您需报备订单即:按要求提交简单的截图等; 每次下款报备通过后返红包10元, 可累计或即时联系客服提取! 3次不报备或者不能接受的, 本平台将终止服务。</small></p>
                    @endif
                    
               </blockquote>
               </div>
            <a class="btn btn-primary btn-block text-white" href="{{ $link->link($record) }}"><i class="fa fa-check-square-o" aria-hidden="true"></i> 我已知晓, 立即提现!</a>
            </div>
            
          </div>
        </div>
    </div>
</section>

@endsection