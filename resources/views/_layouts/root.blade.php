<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Thanapat Pirmphol">
    <title>@yield('title') - FT8.io</title>

    <link href="{{ elixir('css/styles.css') }}" rel="stylesheet">
    @yield('stylesheet')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="javascript:void(0)">Brand</a>
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="javascript:void(0)">Active<div class="ripple-container"></div></a></li>
                <li><a href="javascript:void(0)">Link</a></li>
                <li class="dropdown">
                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown
                    <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="javascript:void(0)">Action</a></li>
                    <li><a href="javascript:void(0)">Another action</a></li>
                    <li><a href="javascript:void(0)">Something else here</a></li>
                    <li class="divider"></li>
                    <li class="dropdown-header">Dropdown header</li>
                    <li><a href="javascript:void(0)">Separated link</a></li>
                    <li><a href="javascript:void(0)">One more separated link</a></li>
                </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left">
                <div class="form-group is-empty">
                <input type="text" class="form-control col-md-8" placeholder="Search">
                </div>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="javascript:void(0)">Link</a></li>
                <li class="dropdown">
                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown
                    <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="javascript:void(0)">Action</a></li>
                    <li><a href="javascript:void(0)">Another action</a></li>
                    <li><a href="javascript:void(0)">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0)">Separated link</a></li>
                </ul>
                </li>
            </ul>
            </div>
        </div>
    </nav>
  
	@yield('content')


    <script src="{{ elixir('js/scripts.js') }}"></script>
    @yield('javascript')
  </body>
</html>