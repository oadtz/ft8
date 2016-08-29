@extends('_layouts.default')

@section('title', trans('site.slogan'))

@section('stylesheet')
@parent

<meta property="og:url" content="{{$gif->thumbnailUrl}}">
<meta property="og:type" content="video.other">
<meta property="og:image" content="{{$gif->thumbnailUrl}}">
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
				  	<span ng-switch="gif.status">
				  		<span ng-switch-when="6">
		    				<figure class="text-center">
				  				<img src="{{$gif->gifUrl}}" ng-if="gif.status == 6"  style="max-width: 100%" />
							</figure>
				  		</span>
				  		<span ng-switch-default>
				  			GIF is being generated...
			                <div class="progress">
			                  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
			                    <span class="sr-only">
			                      @{{gif.statusName}}
			                    </span>
			                  </div>
			                </div>
				  		</span>
				  	</span>

				  </video>
	    		</div>
		        <div class="form-group">
		            <label class="col-md-2 control-label">URL</label>
		            <div class="col-md-10">
    					<div class="input-group">
			              <input type="text" class="form-control" ng-model="gif.url" readonly="readonly">
					      <span class="input-group-btn">
					        <button type="button" class="btn btn-default" clipboard supported="true" text="gif.url" on-copied="copied()"><i class="fa fa-copy"></i> Copy URL</button>
					        <button type="button" class="btn btn-default" ng-show="gif.status == 6"><i class="fa fa-download"></i> Download GIF</button>
					      </span>
    					</div>
		            </div>
		        </div>
		        <div class="form-group" ng-show="gif.status == 6">
		            <label class="col-md-2 control-label">Share</label>
			        <div class="col-md-10">
			        	<a facebook-feed-share class="btn btn-link facebook-share" data-url="{{$gif->url}}" data-shares="shares" title="Share on facebook"><img src="{{url('img/facebook.png')}}" alt="Share on facebook"></a>
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
</div>
@endsection