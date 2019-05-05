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
        <div class="col-sm-5 cent">
            <div class="row text-left">
                <div class="card card-light form-card col-12">
                    <p><a href="/orders">订单报备</a> / {{ $record->product->name }} {{ $record->created_at }}</p>
                    <strong>{{$record->customer->name}}</strong>
                    <strong>{{$record->customer->mobile}}</strong>
                    @if($record->bb && $record->bb->bb)
                        @foreach(json_decode($record->bb->info, true) as $key => $value)
                            @if($key == 'video')
                                <br>
                                <video autoplay="autoplay" poster="{{ asset('img/movie.jpg') }}" width="100%" controls>
                                    <source src="{{ asset('storage/'.$value) }}" type="video/mp4" />
                                </video>
                            @else
                                <br>
                                <img class="rounded img-thumbnail" src="{{ asset('storage/'.$value) }}">
                            @endif

                        @endforeach
                    @else

                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection