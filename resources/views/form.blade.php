@extends('nav')

@section('content')

<div class="row top-pad"></div>
<section>
    <div class="container">
        <div class="col-sm-5 cent">
          <div class="row text-left">
            <div class="card card-light form-card col-12">
            <h4><i class="fa fa-{{ $icon }}" aria-hidden="true"></i> {!! $title !!}</h4>
            <p></p>
              {!! form($form) !!}
            </div>
          </div>
        </div>
    </div>
</section>

@endsection