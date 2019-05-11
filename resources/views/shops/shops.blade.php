<?php
    $p = new App\Helpers\Picker;
    $info = new App\Helpers\Info;
    $f = new App\Helpers\Filter;
    $r = new App\Helpers\Role;

?>
@extends('../nav')

@section('content')

<div class="row top-pad"></div>
<section>
    <div class="container">
        <h3><i class="fa fa-bookmark-o" aria-hidden="true"></i> 店和用户</h3>
        <p>
          @if($r->admin())
            <a href="/shops/create/1" class="btn btn-outline-primary btn-sm">+ 增加 </a>
          @endif
        </p>
        <div class="card">
          <div class="card-body">
              @if(isset($record))
               
                <p class="text-primary">
                  <strong>{{ $f->show($record->info, 'name') }}</strong> {{ $record->domain }} 
                  @if($r->admin())
                  <a href="/shops/create/{{$record->id}}" class="btn btn-outline-primary btn-sm">+ 增加 </a>
                  @endif
                </p>
                @if($record->users->count())
                  <ul class="list-unstyled">
                  @foreach($record->users as $u)
                    
                    <li class="text-{{ $r->locked($u->id) ? 'warning' : 'dark' }}">
                      <div class="dropdown-divider"></div>
                      <h5>
                      
                    @if($r->gt($u->id))
                      <a href="#" class="badge badge-info"  id="dropdownMenua" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                      </a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        @if($r->locked($u->id))
                          <a class="dropdown-item" href="/users/unlock/{{ $u->id }}">解锁: {{ $u->name }}</a>
                        @else
                          <a class="dropdown-item" href="/users/lock/{{ $u->id }}">锁定: {{ $u->name }}</a>
                        @endif

                        @if($r->boss($u->id))
                          <a class="dropdown-item" href="/users/remove_boss/{{ $u->id }}">撤销店主: {{ $u->name }}</a>
                        @else
                          <a class="dropdown-item" href="/users/set_boss/{{ $u->id }}">升级为店主: {{ $u->name }}</a>
                        @endif

                        @if(!$r->boss($u->id) && !$r->admin($u->id))
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="/users/limit_reset/{{ $u->id }}">限制复位, 现有:{{ $u->limit }}</a>
                          <a class="dropdown-item" href="/users/limit_add/{{ $u->id }}">次数 +1, 现有:{{ $u->limit }}</a>
                          <a class="dropdown-item" href="/users/limit_cut/{{ $u->id }}">次数 -1, 现有:{{ $u->limit }}</a>
                        @endif

                      </div>
                    @endif

                      {{ $u->mobile }} 
                      {{$u->name}} 

                      {!! $r->boss($u->id) ? '<span class="badge badge-primary">店主</span>' :"" !!} 
                      {!! $r->limit($u->id) && !$r->boss($u->id) && !$r->admin($u->id) ? '<span class="badge badge-success">'.$r->limit($u->id).'</span>' :"" !!} 
                      </h5>

                      @if($r->admin())
                      <p class="text-center">
                        <span class="text-secondary">{{ $u->created_at->diffForHumans() }}在{{ $u->shop->domain }}注册</span>
                      </p>
                      @endif


                    </li>
                    
                  @endforeach
                  </ul>
                @endif
              @else
                <div class="alert alert-info">尚无记录</div>
              @endif
          </div>
        </div>
    </div>
</section>

   
  <!-- create -->
  <div class="modal fade" id="new_type">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <strong>店和人员</strong>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
   <form method="post" action="/conf/categories/do">
        <div class="modal-body">

          @csrf
          <input type="hidden" name="id" id="edit_id">
          <input type="hidden" name="parent_id" id="parent_id">
          <div class="form-group"  >
            <label for="name" class="control-label">Name</label>
            <input class="form-control" minlength="2" maxlength="32" name="name" type="text" id="name">
          </div>
          <div class="form-group"  >
            <label for="code" class="control-label">Code</label>
            <input class="form-control" minlength="1" maxlength="1" name="code" type="text" id="code">
          </div>

        </div>
   
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal">close</button>
          <button type="submit" class="btn btn-primary btn-sm">Confirm</button>
        </div>
   </form>

      </div>
    </div>
  </div>

<script>
  function clear() {
    $("#edit_id").val('');
    $("#parent_id").val('');
    $("#name").val('');
    $("#code").val('');
  }

  function create(parent_id) {
      clear();
      $("#parent_id").val(parent_id);
      $("#new_type").modal();
  }

  function edit(id) {
      clear();
      $("#edit_id").val(id);
      $("#new_type").modal();
  }

</script>

@endsection