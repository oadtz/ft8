@extends('_layouts.default')

@section('title', 'Easy VDO to Gif Convert')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="bs-component" ng-controller="GifUploadController">
      <form class="form-horizontal" ng-submit="void(0)">

            <div class="jumbotron">
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