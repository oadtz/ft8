@extends('_layouts.default')

@section('title', trans('site.slogan'))

@section('stylesheet')
@parent

<meta property="og:site_name" content="{{trans('site.app_name')}}" />
@if($gif)
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
@endif

@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="bs-component" ng-controller="GifUploadController">
      <form class="form-horizontal" ng-submit="void(0)">

            <div class="well">
              <h1>Upload Video</h1>

              <div class="input-group">
                <span class="input-group-btn">

                  
                  <!-- image-preview-input --> 
                  <span class="btn btn-info btn-raised" ng-if="!file" ngf-select="upload($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'"> 
                    <i class="fa fa-folder-open"></i> Choose...
                  </span>
                  <!-- image-preview-clear button -->
                  <button type="button" class="btn btn-default btn-raised" ng-click="cancelUpload()" ng-if="file"> 
                    <i class="fa fa-remove"></i> Cancel 
                  </button>
                </span> 
              </div>


              <div class="upload-zone" ng-if="!file">

                <div class="upload-drop-zone" ngf-drop="setFile($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'" ngf-allow-dir="false">
                  <i class="fa fa-film"></i> You can drag n drop your video here.

                </div>


                <p>House rules:</p>
                <ul>
                  <li>Maximum video size is @{{settings.max_file_size|bytes}}.</li>
                  <li>For best result, your GIF will be limited to {{config('site.gif_max_time')}} seconds long.</li>
                </ul>
              </div>

              <div class="upload-status" ng-if="file">
                <div class="text-center">Uploading...</div>
                <div class="progress">
                  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" ng-style="{ 'width': progress + '%' }">
                    <span class="sr-only">
                      Uploading...
                    </span>
                  </div>
                </div>
              </div>
            </div>


      </form>
    </div>
  </div>
</div>

@endsection