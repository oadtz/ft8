<!DOCTYPE html>
<html lang="en" ng-app="ft8">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  	<meta name="description" content="">
  	<meta name="author" content="Thanapat Pirmphol">
    @if(config('broadcasting.default') == 'pusher')
    <meta name="broadcast_token" content="{{config('broadcasting.connections.pusher.key')}}">
    @else
    <meta name="broadcast_url" content="{{config('site.broadcast_url')}}">
    @endif
    <meta property="fb:app_id" content="{{config('site.facebook_app_id')}}">
    <base href="{{ url('/') }}">
    <title>@yield('title') - @lang('site.app_name')</title>

    <link href="{{ elixir('css/styles.css') }}" rel="stylesheet">
    <style>
    body {
      padding-top: 70px !important;
    }

    [ng\:cloak], [ng-cloak], .ng-cloak {
        display: none !important;
    }
    </style>
    @yield('stylesheet')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-cloak>


    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Vdo2Gif</a>
        </div>
      </div>
    </nav>

    <div class="container">
  
	  @yield('content')

    </div>

    @if(config('broadcasting.default') == 'pusher')
    <script src="//js.pusher.com/3.2/pusher.min.js"></script>
    @else
    <script src="{{ config('site.broadcast_url') }}/socket.io/socket.io.js"></script>
    @endif
    <script src="{{ elixir('js/scripts.js') }}"></script>
    @yield('javascript')
  </body>
</html>