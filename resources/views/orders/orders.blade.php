<?php
    $p = new App\Helpers\Picker;
    $info = new App\Helpers\Info;
    $f = new App\Helpers\Filter;
    $r = new App\Helpers\Role;
?>

@extends('../nav')

@section('content')



<section>
    <div class="container">
        <h3><i class="fa fa-hearts-o" aria-hidden="true"></i> 订单报备</h3>
        <p>
          @if($r->admin() || $r->boss())
          <a href="/orders/create" class="btn btn-outline-primary btn-sm">+ 增加 </a>
          @else
          <div class="alert alert-info"><small>尊敬的{{ Auth::user()->name }}, 您需要在下单当日晚9:30前报备下款成功的订单, 若累计3个订单超时或反馈错误,本平台将终止您的服务, 并不再另行通知。 若需帮助请联系客服</small></div>
          @endif
        </p>
      @if(isset($records) && count($records))
          <ul class="list-unstyled">
        @if($r->admin() || $r->boss())
              @foreach($records as $record)
                <li><span class="badge badge-light">{{ $record->customer->name }}: {{ $record->customer->mobile }}</span>
                  <span class="badge badge-info">{{ $record->product->name }}</span>
                @if($record->finish)
                  @if($record->success)
                  <span class="badge badge-success">报备: 成功, 下载资料</span>
                  @else
                  <span class="badge badge-danger">下款失败</span>
                  @endif
                @else
                  @if($p->orderValid($record))
                    <span class="badge badge-success">截止{{ $p->orderValid($record) }}</span>
                  @else
                    <span class="badge badge-danger">已过期</span>
                  @endif
                  </li>
                @endif
              @endforeach
          
        @else
          @foreach($records as $record)
            <li class="list-pad">
              <img src="{{ $record->product->img }}" class="rounded order-icon">
              <strong>{{ $record->product->name }}</strong>
              [{{ $record->created_at->diffForHumans() }}]
              @if($record->finish)
                @if($record->success)
                  <span class="badge badge-success">报备: 成功</span>
                @else
                  <span class="badge badge-warning">报备: 下款失败</span>
                @endif
              @else
                @if($p->orderValid($record))
                  <a href="javascript:bb({{$record->id}})" class="btn btn-sm btn-outline-primary">报备</a>
                @else
                  <span class="badge badge-warning">已过期</span>
                @endif
              @endif

            </li>
          @endforeach
        @endif

        </ul>
      @else
        <div class="alert alert-info">尚无记录</div>
      @endif
    
    </div>
</section>

   
  <!-- create -->
  <div class="modal fade" id="new_type">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <strong>订单报备</strong>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="alert alert-info note-pad">
          <small>
          <i class="fa fa-bell-o" aria-hidden="true"></i> 温馨提醒: 请您按实际订单结果报备: <br>
          1. 若订单未下款, 直接点击 [下款不成功] <br>
          2. 下款订单请点击报备 <br>
          3. 订单报备将有助于系统为您推荐高成功率的产品<br>
          4. <strong>错报(如将下款订单标记为下款不成功的)或过期的(超过当日晚9:30)累计达3次,本平台将终止服务</strong><br>
          5. 若需帮助,请联系客服,将有专人悉心指引
          </small>
        </div>
   <form id="form" method="post" action="/orders/bb">
        <div class="modal-body">

          @csrf

          <input type="hidden" name="order_id" id="order_id">
          <input type="hidden" name="success" id="success" value="yes">
        </div>
   
        <div class="modal-footer">
          <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">关闭</button>
          <button type="button" class="btn btn-outline-primary btn-sm" onclick="javascript:fail()">下款不成功</button>
          <button type="submit" class="btn btn-primary btn-sm">报备</button>
        </div>
   </form>

      </div>
    </div>
  </div>

<script>
  function clear() {
    // $("#edit_id").val('');s
    $("#order_id").val('');
    $("#success").val('yes');
    // $("#code").val('');
  }

  function bb(id) {
      clear();
      $("#order_id").val(id);
      $("#new_type").modal();
  }

  function fail(id) {

      $("#success").val('no');
      $("#form").submit();
  }

</script>

@endsection