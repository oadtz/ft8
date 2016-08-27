@extends('_layouts.default')

@section('title', 'Easy VDO to Gif Convert')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="bs-component">
	    <div class="jumbotron">
	    	<div class="row">
		    	<figure class="text-center">
				  <img src="{{url('uploads/out.gif')}}" />
				  <figcaption>
				    vdo2gif.com
				  </figcaption>
				</figure>
		        <div class="form-group">
		            <label class="col-md-2 control-label">URL</label>
		            <div class="col-md-10">
		              <input type="text" class="form-control" value="http://bit.ly/sddfsS" readonly="readonly">
		            </div>
		        </div>
		        <div class="col-md-12 text-center">
		        	<a href>
		        		<img src="{{url('build/img/facebook.png')}}" alt="Share on Facebook">
		        	</a>
		        	<a href>
		        		<img src="{{url('build/img/twitter.png')}}" alt="Share on Twitter">
		        	</a>
		        	<a href>
		        		<img src="{{url('build/img/google_plus.png')}}" alt="Share on Google">
		        	</a>
		        	<a href>
		        		<img src="{{url('build/img/instagram.png')}}" alt="Share on Instagram">
		        	</a>
		        	<a href>
		        		<img src="{{url('build/img/line.png')}}" alt="Share on LINE">
		        	</a>
		        	<a href>
		        		<img src="{{url('build/img/telegram.png')}}" alt="Share to Email">
		        	</a>
		        </div>
	    	</div>
	    </div>
    </div>
  </div>
</div>
@endsection