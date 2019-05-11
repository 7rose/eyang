@extends('../nav')

@section('content')

<div class="row top-pad"></div>

<section>
    <div class="container">
        <div class="col-sm-5 cent">
          <div class="alert alert-warning">
              <h1><i class="fa fa-eye-slash" aria-hidden="true"></i></h1>
              <div class="text text-left">
                  尊敬的{{ Auth::user()->name }}， 因为您没有按时报备订单, 且次数超过3次, 系统已自动停止了您的产品服务。 若需恢复使用，请联系客服手动开通。若已经提交报备资料, 可能是管理员正在审核, 审核通过会自动开通, 请耐心等待, 谢谢!
                  <a href="http://xn--rht832e.xn--io0a7i">入口</a>
              </div>
          </div>
        </div>
    </div>
</section>
<div class="row top-pad"></div>


@endsection