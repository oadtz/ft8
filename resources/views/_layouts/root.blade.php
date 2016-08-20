<!DOCTYPE html>
<html lang="en" ng-app="ft8">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Thanapat Pirmphol">
    <title>@yield('title') - @lang('site.app_name')</title>

    <link href="{{ elixir('css/styles.css') }}" rel="stylesheet">
    <link href="http://vjs.zencdn.net/5.10.8/video-js.css" rel="stylesheet">
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
          <a class="navbar-brand" href="#">@lang('site.app_name')</a>
        </div>
      </div>
    </nav>

    <div class="container">
  
	  @yield('content')

    </div>

    <script src="http://vjs.zencdn.net/5.10.8/video.js"></script>
    <script src="{{ elixir('js/scripts.js') }}"></script>
    @yield('javascript')
  </body>
</html>