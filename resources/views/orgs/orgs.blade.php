@extends('nav')

@section('content')

<div class="row top-pad"></div>
<section>
    <div class="container">
        <div class="col-sm-6 cent">
          <div class="row text-left">
            <div class="card card-light form-card col-12">
            <h4><i class="fa fa-cubes" aria-hidden="true"></i> 供应商</h4>
            <p><a href="/orgs/create" class="btn btn-sm btn-primary">+ 供应商</a></p>
            @if($records->count())
              @foreach($records as $record)
                <blockquote class="blockquote mb-5 text-left product-content">
                  <strong>{{ $record->name }}</strong> 编码: {{ $record->code }}
                  <a href="/orgs/edit/{{ $record->id }}" class="badge badge-danger">修改</a>
                  <ul class="list-unstyled">
                    <li><span class="badge badge-secondary">配置</span> </li>
                    <li>{{ $record->config }}</li>
                  </ul>
                </blockquote>
              @endforeach
            @else
              <div class="alert alert-info">空</div>
            @endif
            </div>
          </div>
        </div>
    </div>
</section>

@endsection