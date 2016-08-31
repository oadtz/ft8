@extends('_layouts.default')

@section('title', !empty($gif->settings['caption']) ? $gif->settings['caption'] : 'Untitled GIF animation')

@section('meta')
@parent

<meta property="og:site_name" content="{{trans('site.app_name')}}" />
<meta property="og:title" content="{{!empty($gif->settings['caption']) ? $gif->settings['caption'] : 'Untitled GIF animation'}}" />
<meta property="og:url" content="{{asset('gif/' . $gif->_id . '/thumbnail.gif')}}">
<meta property="og:type" content="video.other">
<meta property="og:image" content="{{$gif->thumbnailUrl}}">
<meta property="og:image:type" content="image/gif">
<meta property="og:image:width" content="{{$gif->output['thumbnailWidth']}}">
<meta property="og:image:height" content="{{$gif->output['thumbnailHeight']}}">
<meta property="og:type" content="image">
<meta property="og:image" content="{{$gif->gifUrl}}">
<meta property="og:image:type" content="image/gif">
<meta property="og:image:width" content="{{$gif->output['width']}}">
<meta property="og:image:height" content="{{$gif->output['height']}}">
<meta property="og:type" content="video">
<meta property="og:video" content="{{$gif->videoUrl}}">
<meta property="og:video:type" content="video/mp4">
<meta property="og:video:width" content="{{$gif->output['width']}}">
<meta property="og:video:height" content="{{$gif->output['height']}}">
@endsection

@section('javascript')
@parent

@if(config('site.facebook_app_id') != '')
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId={{config('site.facebook_app_id')}}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
@endif
<script src="//apis.google.com/js/platform.js" async defer></script>
@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="bs-component" ng-controller="GifViewController">
        <input type="hidden" ng-init="setGif({{htmlspecialchars(json_encode($gif))}})">
	    <div class="well">
	    	<div class="row">
	    		<div class="col-lg-12 text-center">
				  <video autoplay="autoplay" loop="loop" width="{{$gif->output['width']}}" height="{{$gif->output['height']}}" style="max-width: 100%">
				  	<source src="{{$gif->videoUrl}}" type="video/mp4" />
				  	<img src="{{$gif->gifUrl}}" ng-src="@{{gif.gifUrl}}" ng-if="gif.status == 6"  style="max-width: 100%" />
				  	{{--<!--span ng-switch="gif.status">
				  		<span ng-switch-when="6">
		    				<figure class="text-center">
				  				<img src="{{$gif->gifUrl}}" ng-if="gif.status == 6"  style="max-width: 100%" />
							</figure>
				  		</span>
				  		<span ng-switch-default>
				  			GIF Preview is being generated...
			                <div class="progress">
			                  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
			                    <span class="sr-only">
			                      @{{gif.statusName}}
			                    </span>
			                  </div>
			                </div>
				  		</span>
				  	</span-->--}}

				  </video>
	    		</div>
		        <div class="form-group">
		            <label class="col-md-2 control-label">URL</label>
		            <div class="col-md-10">
			              <input type="text" class="form-control" ng-model="gif.url" readonly="readonly">
					      <span class="input-group-btn">
					        <button type="button" class="btn btn-default" clipboard supported="clipboard.supported" text="gif.url" on-copied="copied()"><i class="fa fa-copy"></i> Copy Link</button>
					        {{--<!--button type="button" class="btn btn-default"><i class="fa fa-envelope-o"></i> Email GIF</button-->--}}
					        <button type="button" class="btn btn-default" ng-show="gif.status == 6" ng-click="download('gif')"><i class="fa fa-download"></i> Download GIF</button>
					        {{--<!--button type="button" class="btn btn-default" ng-click="download('mp4')"><i class="fa fa-download"></i> Download MP4</button-->--}}
					      </span>
		            </div>
		        </div>
		        <div class="form-group">
		            <label class="col-md-2 control-label">Share</label>
			        <div class="col-md-10">
			        	<ul class="list-inline">
			        		{{--<!--li><a facebook-feed-share class="btn btn-link facebook-share" data-url="{{$gif->url}}" data-shares="shares" title="Share on facebook"><img src="{{url('img/facebook.png')}}" alt="Share on facebook"></a></li>
			        					        					        		<li><a href="http://line.me/R/msg/text/?{{$gif->gifUrl}}" target="_blank" class="btn btn-link" title="Share to LINE"><img src="{{url('img/line.png')}}" alt="Share to LINE"></a></li-->--}}

			        		<li class="btn btn-link">
			        			<div class="fb-share-button" data-href="{{action('GifController@upload', [ 'referer' => $gif->_id ])}}" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{action('GifController@upload', [ 'referer' => $gif->_id ])}}F&amp;src=sdkpreparse">Share</a></div>
			        		</li>
			        		<li class="btn btn-link">
			        			<div class="g-plus" data-action="share" data-annotation="none" data-height="24" data-href="{{$gif->url}}"></div>
			        		</li>
			        		<li class="btn btn-link">
			        			<span>
								<script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411" ></script>
								<script type="text/javascript">
								new media_line_me.LineButton({"pc":false,"lang":"en","type":"a"});
								</script>
								</span>
							</li>
			        	</ul>

			        	{{--
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
			        	--}}
			        </div>
		        </div>
	    	</div>
	    </div>
    </div>
  </div>
</div>
@endsection