<?php
  $info = new App\Helpers\Info;
  $role = new App\Helpers\Role;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $info->show('name') ? $info->show('name') : 'opal'  }}</title>
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Lightbox-->
    <link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.css') }}">
    <!-- Custom font icons-->
    <link rel="stylesheet" href="{{ asset('css/fontastic.css') }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset($info->show('color') ? 'css/style.'.$info->show('color').'.css' : 'css/style.blue.css') }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Favicon-->
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
        <!-- navbar-->
    <header class="header">
      <nav class="navbar navbar-expand-sm fixed-top">
        <div class="container"><a href="/" class="navbar-brand"><img src="{{ asset('img/'.$info->domain().'.svg') }}" alt="" class="img-fluid logo"></a>
         <div class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle menu-item text-{{ Auth::check() ? 'text-primary': 'dark' }}">
              @if(Auth::check())
                <i class="fa fa-user-circle-o" aria-hidden="true"></i> {{ Auth::user()->name }} <span class="badge badge-primary">{{ $info->bbNum() }}</span> 
              @else
                <i class="fa fa-user-o" aria-hidden="true"></i>
              @endif
             
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              @if(Auth::check())
                @if($role->boss() || $role->admin())
                <a class="dropdown-item menu-text" href="/shops"><i class="fa fa-users" aria-hidden="true"></i> 店和用户</a>
                @else
                  @if($role->limited())
                  <span class="dropdown-item menu-text text-danger"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> 我的次数限制: {{ $role->limit() }}</span>
                  @else
                  <span class="dropdown-item menu-text"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> 我的次数限制: {{ $role->limit() }}</span>
                  @endif
                @endif

                <a class="dropdown-item menu-text" href="/orders"><i class="fa fa-heart-o" aria-hidden="true"></i> 订单报备 <span class="badge badge-primary">{{ $info->bbNum() }}</span></a>
                

                @if($role->root())
                <a class="dropdown-item menu-text" href="/orgs"><i class="fa fa-cubes" aria-hidden="true"></i> 供应商</a>
                @endif



                @if($role->issuer())
                <a class="dropdown-item menu-text" href="/products/create"><i class="fa fa-money" aria-hidden="true"></i> 发布产品</a>
                @endif

                @if($role->shopBoss($info->id()) && count($info->lackOrgIds())) 
                <a class="dropdown-item menu-text" href="/shops/active"><i class="fa fa-magic" aria-hidden="true"></i> 激活代理</a>
                @endif
                
                <a class="dropdown-item menu-text" href="/password_reset"><i class="fa fa-wrench" aria-hidden="true"></i> 修改密码</a>
                <a class="dropdown-item menu-text" href="/logout"><i class="fa fa-power-off" aria-hidden="true"></i> 安全退出</a>
              @else
                <a class="dropdown-item menu-text" href="/register"><i class="fa fa-magic" aria-hidden="true"></i> 马上注册</a>
                <a class="dropdown-item menu-text" href="/login"><i class="fa fa-key" aria-hidden="true"></i> 登录</a>
              @endif
            </div>
          </div>
      </nav>
    </header>

    @yield('content')

    <div class="container cent">
      <img class="footer-img" src="{{ asset('img/'.$info->domain().'.svg') }}" alt="..." class="img-fluid"><br>
      <small>&copy; {{ today()->year }}. {{ $info->show('full_name') }}</small><br>
      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->errorCorrection('L')->size(120)->generate($info->show('wechat'))) !!} "><br>
      <small class="text-primary"><i class="fa fa-comments-o" aria-hidden="true"></i> 客服请扫码</small>
      <p></p>
    </div>

    <!-- JavaScript files-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery.cookie/jquery.cookie.js') }}"> </script>
    <script src="{{ asset('vendor/lightbox2/js/lightbox.js') }}"></script>
    <script src="{{ asset('js/front.js') }}"></script>
  <script>
    // ajax csrf
    $(function(){ 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
    });
</script>
  </body>
</html>