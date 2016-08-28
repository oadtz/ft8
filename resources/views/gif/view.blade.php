@extends('_layouts.default')

@section('title', 'Easy VDO to Gif Convert')

@section('stylesheet')
@parent

<meta name="thumbnail" content="{{asset('gif/' . $gif->_id . '/thumbnail.gif')}}">
<meta property="og:url" content="{{$gif->output['url']}}">
<meta property="og:type" content="video.other">
<meta property="og:image" content="{{asset('gif/' . $gif->_id . '/thumbnail.gif')}}">
<meta property="og:image:width" content="{{ceil($gif->output['width'] * 0.75)}}">
<meta property="og:image:height" content="{{ceil($gif->output['height'] * 0.75)}}">
@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="bs-component" ng-controller="GifViewController">
        <input type="hidden" ng-init="setGif({{htmlspecialchars(json_encode($gif))}})">
	    <div class="jumbotron">
	    	<div class="row">
		    	<figure class="text-center">
				  <img src="{{$gif->output['url']}}" class="img-responsive" />
				  <figcaption>
				    vdo2gif.com
				  </figcaption>
				</figure>
		        <div class="form-group">
		            <label class="col-md-2 control-label">URL</label>
		            <div class="col-md-10">
		              <input type="text" class="form-control" ng-model="gif.output.url" readonly="readonly">
		            </div>
		        </div>
		        <div class="col-md-12 text-center">
		        	<button type="button" class="btn btn-link" title="Share on facebook" ng-click="shareFacebook()">
		        		<img src="{{url('img/facebook.png')}}" alt="Share on facebook">
		        	</button>
		        	<a facebook-feed-share data-url="{{asset('gif/' . $gif->_id . '.html')}}" data-shares="shares"><img src="{{url('img/facebook.png')}}" alt="Share on facebook"></a>
		        	<!--a href>
		        		<img src="{{url('img/twitter.png')}}" alt="Share on Twitter">
		        	</a>
		        	<a href>
		        		<img src="{{url('img/google_plus.png')}}" alt="Share on Google">
		        	</a>
		        	<a href>
		        		<img src="{{url('img/instagram.png')}}" alt="Share on Instagram">
		        	</a>
		        	<a href>
		        		<img src="{{url('img/line.png')}}" alt="Share on LINE">
		        	</a>
		        	<a href>
		        		<img src="{{url('img/telegram.png')}}" alt="Share to Email">
		        	</a-->
		        </div>
	    	</div>
	    </div>
    </div>
  </div>
</div>
@endsection