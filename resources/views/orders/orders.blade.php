<?php
    $r = new App\Helpers\Role;
    $info = new App\Helpers\Info;
    $filter = new App\Helpers\Filter;
?>
@extends('../nav')

@section('content')

<section>
    <div class="container">
        <div class="col-sm-6 cent">
          <div class="row text-left">
            <div class="card card-light form-card col-12">
            <h4><i class="fa fa-heart" aria-hidden="true"></i> 订单</h4>
        @if($r->admin() || $r->shopBoss())
            <p><a href="/orders/create" class="btn btn-sm btn-outline-primary">+ 新订单</a></p>
        @else
            <div class="alert alert-info">尊敬的{{ Auth::user()->name }}, "报备产品" 需要您在提示的截止日期前提交反馈信息! 若您超过3次不按时提交, 系统将自动停止为您服务, 请及时处理! 若需帮助请联系客服, 祝您在{{ $info->show('name') }}满载而归!</div>
        @endif

        @if($records->count())
            <ul class="list-unstyled">
                @foreach($records as $record)
                    <li>
                        <div class="dropdown-divider"></div>
                        <h5>
                        @if($r->admin() || $r->shopBoss())

                            @if($filter->bbTime($record->id))
                                @if($filter->submit($record))
                                    <a href="/bb/show/{{ $record->id }}" class="badge badge-primary">下款{{ $record->bb->success ? "成功" : "失败" }}</a>
                                @elseif($filter->check($record))
                                    <a href="/bb/show/{{ $record->id }}" class="badge badge-primary">下载资料</a>

                                @else
                                    <span class="badge badge-warning">待报备</span>
                                @endif
                            @else
                                <span class="badge badge-secondary">已过期</span>
                            @endif
                            
                        @else

                            @if($filter->bbTime($record->id))
                                @if($filter->submit($record))
                                    <span class="badge badge-warning">待审核</span>
                                @elseif($filter->check($record))
                                    <span class="badge badge-success">报备成功</span>
                                @else
                                    <a href="javascript:bb({{ $record->id }})" class="badge badge-primary"><i class="fa fa-heart-o" aria-hidden="true"></i> 报备</a>
                                @endif
                            @else
                                <span class="badge badge-secondary">已过期</span>
                            @endif

                        @endif


                        {{ $record->customer->mobile }} 
                        {{ $record->customer->name }} 
                        


                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-success">暂无需报备订单</div>
        @endif

            </div>
          </div>
        </div>
    </div>
</section>

  <div class="modal fade" id="baobei">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <strong>订单报备</strong>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <i class="fa fa-bell-o" aria-hidden="true"></i> 温馨提醒: 请您按实际订单结果报备: <br>

          1. 报备有助于系统为您推荐高成功率的产品<br>
          2. <strong>错报(如将下款订单标记为下款不成功的)或过期的(超过当日晚9:30)累计达3次,本平台将终止服务</strong><br>
          3. 若需帮助,请联系客服,将有专人悉心指引
          <input type="hidden" name="order_id" id="order_id">
        </div>
   
        <div class="modal-footer">
          <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">关闭</button>
          <button type="button" class="btn btn-outline-primary btn-sm" onclick="javascript:fail()">未下款</button>
          <button type="submit" class="btn btn-primary btn-sm" onclick="javascript:success()">下款成功</button>
        </div>

      </div>
    </div>
  </div>

<script>
    function bb(id) {
        $("#baobei").modal();
        $("#order_id").val(id);
    }

    function fail() {
        var id = $("#order_id").val();
        if(id != '' && id != null) window.location.href="/bb/fail/"+id;
    }

    function success() {
        var id = $("#order_id").val();
        if(id != '' && id != null) window.location.href="/bb/success/"+id;
    }
</script>
@endsection


























