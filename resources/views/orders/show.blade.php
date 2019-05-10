<?php
    $r = new App\Helpers\Role;
?>
@extends('../nav')

@section('content')
<section>
    <div class="container">
        <div class="col-sm-8 cent">
            <div class="row text-left">
                <div class="card card-light form-card col-12">
                    <p><a href="/orders">订单报备</a> / {{ $record->product->name }} </p>
                    {{ $record->created_at }}<br>
                    <strong>{{$record->customer->name}}</strong>
                    <strong>{{$record->customer->mobile}}</strong>
                    @if($record->bb)
                        @foreach(json_decode($record->bb->info, true) as $key => $value)
                            @if($key == 'video')
                                <br>
                                <a href="/download/video/{{ $record->bb->id }}" class="btn btn-success">下载视频</a>
                                <br>
                                <video autoplay="autoplay" poster="{{ asset('img/movie.jpg') }}" width="100%" controls>
                                    <source src="{{ asset('storage/'.$value) }}" type="video/mp4" />
                                </video>
                                <br>
                            @elseif(Storage::has($value))
                                <br>
                                <img class="rounded img-thumbnail" src="{{ asset('storage/'.$value) }}">
                            @else
                                <strong>密码: {{ $value }} , 使用后务必提醒用户修改!</strong>
                            @endif

                        @endforeach
                    @else

                    @endif
                    <div class="top-pad"></div>
                    <p>
                        @if($r->locked($record->customer->id))
                            <a class="btn btn-sm btn-success" href="/users/unlock/{{$record->customer->id}}">解除封号</a> 
                        @else
                            <a class="btn btn-sm btn-danger" href="/users/lock/{{$record->customer->id}}">恶意封号</a> 
                        @endif
                        <a class="btn btn-sm btn-outline-danger" href="/bb/check_fail/{{$record->id}}">无效</a> 
                        <a class="btn btn-sm btn-primary" href="/bb/check_ok/{{$record->id}}">有效</a> 
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection