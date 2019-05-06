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
                <img src="{{ asset('img/'.$info->domain().'.svg') }}" alt="" class="img-fluid order-icon">
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
                      @if($r->gt($u->id))

                      @if($r->locked($u->id))
                        {!! $r->gt($u->id) ? '<a class="badge badge-success" href="/users/unlock/'.$u->id.'"><i class="fa fa-unlock" aria-hidden="true"></i></a>' : '' !!}
                      @else
                        {!! $r->gt($u->id) ? '<a class="badge badge-warning" href="/users/lock/'.$u->id.'"><i class="fa fa-lock" aria-hidden="true"></i></a>' : '' !!}
                      @endif

                    @endif
                      {{ $u->mobile }} 
                      {{$u->name}} 

                      {!! $r->boss($u->id) ? '<span class="badge badge-primary">店主</span>' :"" !!} 

                      @if($r->admin() && $r->boss($u->id))
                        <a href="/users/remove_boss/{{$u->id}}" class="badge badge-dark">取消店主!</a>
                      @else
                        <a href="/users/set_boss/{{$u->id}}" class="badge badge-danger">设为店主!</a>
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