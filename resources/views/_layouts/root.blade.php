<!DOCTYPE html>
<html lang="en" ng-app="ft8">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  	<meta name="description" content="">
  	<meta name="author" content="Thanapat Pirmphol">
    <meta name="broadcast_url" content="{{config('broadcasting.connections.pusher.key')}}">
    <meta property="fb:app_id" content="{{config('site.facebook_app_id')}}">
    <base href="{{ url('/') }}">
    <title>@yield('title') - Vdo2Gif</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
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

    <script src="https://js.pusher.com/3.2/pusher.min.js"></script>
    <script src="{{ elixir('js/scripts.js') }}"></script>
    @yield('javascript')
  </body>
</html>