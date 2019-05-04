<?php
    $p = new App\Helpers\Picker;
    $info = new App\Helpers\Info;
    $f = new App\Helpers\Filter;

?>
@extends('../nav')

@section('content')


<div class="row top-pad"></div>
<section>
    <div class="container">
        <h3><i class="fa fa-bookmark-o" aria-hidden="true"></i> 店面</h3>
        <p><a href="/shops/create/1" class="btn btn-outline-primary btn-sm">+ 增加 </a></p>
        <div class="card">
          <div class="card-body">
              @if(isset($records) && count($records))
                <ul class="list-unstyled">
                @foreach($records as $level_1)
                  <li>
                    <a href="javascript:edit({{$level_1->id}})">
                      <strong><i class="fa fa-bookmark" aria-hidden="true"></i> {{ $level_1->domain }} [{{ $f->show($level_1->info, 'name') }}]</strong>
                    </a> 
                    <a href="/shops/create/{{ $level_1->id }}" class="badge badge-success"> + 分店</a>
                    @if(count($level_1->users))
                      <ul class="list-unstyled">
                          @foreach($level_1->users as $u1)
                            <li><small><i class="fa fa-user-o" aria-hidden="true"></i> {{ $u1->name }}</small></li>
                          @endforeach
                      </ul>
                      @endif
                    @if(count($level_1->subs))
                    <ul class="list-unstyled">
                      @foreach($level_1->subs as $level_2)
                        <li>&nbsp <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="javascript:edit({{$level_2->id}})"><strong>{{ $level_2->domain }} [{{ $f->show($level_2->info, 'name') }}]</strong></a> 

                        </li>
                      @endforeach
                    </ul>
                    @endif
                  <li>
                @endforeach
                </ul>
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