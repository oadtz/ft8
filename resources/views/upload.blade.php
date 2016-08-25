@extends('_layouts.default')

@section('title', trans('site.title'))

@section('content')

<div class="row" ng-controller="UploadController">
  <div class="col-md-12">
    <div class="bs-component">
      <form class="form-horizontal" ng-submit="void(0)">
      
      <input type="hidden" id="error_max_file_size" value="@lang('error.max_file_size')">
      <div class="row">
          <div ng-show="step == 1">

            <div class="jumbotron">
              <h1>@lang('upload.title')</h1>
              <div class="input-group image-preview">
                <span class="input-group-btn">

                  <button type="button" class="btn btn-info btn-raised" ng-if="file" ng-click="uploadFile()">
                    <i class="fa fa-upload"></i> @lang('upload.upload'): @{{getFileName()}}
                  </button> 
                  
                  <!-- image-preview-input --> 
                  <span class="btn btn-default btn-raised" ngf-select="setFile($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'"> 
                    <i class="fa fa-folder-open"></i> @lang('upload.browse')
                  </span>
                  <!-- image-preview-clear button -->
                  <button type="button" class="btn btn-default btn-raised" ng-click="setFile(null)"> 
                    <i class="fa fa-remove"></i> @lang('upload.clear') 
                  </button>
                </span> 
              </div>

              <div class="alert alert-dismissible alert-danger" ng-show="$error">
                <button type="button" class="close" data-dismiss="alert">×</button>
                @{{$error}}
              </div>

              <!-- Drop Zone -->
              <!--video class="video-js vjs-default-skin" ngf-src="file" controls preload="auto" width="640" height="264" vjs-video></video-->
              <div class="upload-drop-zone" ngf-drop="setFile($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'" ngf-allow-dir="false">
                @lang('upload.drop_zone')
              </div>

            </div>
          </div>

          <div ng-show="step == 2">

            <div class="col-sm-2">
              <fieldset>
                <legend>@lang('upload.status')</legend>
                <div class="text-center">
                  <span ng-switch="video.status">
                    <span ng-switch-when="3">@lang('upload.error')</span>                    
                    <span ng-switch-when="2">@lang('upload.done')</span>
                    <span ng-switch-when="1">@lang('upload.processing')</span>
                    <span ng-switch-default>@lang('upload.uploading')</span>
                  </span>
                </div>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" ng-class="{ 'progress-bar-success': video.status == 0 || video.status == 1, 'progress-bar-primary': video.status == 2, 'progress-bar-info': video.status == 3, 'progress-bar-danger': video.status == 4 }" role="progressbar" aria-valuemin="0" aria-valuemax="100" ng-style="{ 'width': progressPct + '%' }">
                    <span class="sr-only">
                      <span ng-switch="video.status">
                        <span ng-switch-when="4">@lang('upload.error')</span>
                        <span ng-switch-when="3">@lang('upload.done')</span>
                        <span ng-switch-when="2">@lang('upload.processing')</span>
                        <span ng-switch-when="1">@lang('upload.in_queue')</span>
                        <span ng-switch-default>@lang('upload.uploading')</span>
                      </span>
                    </span>
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="col-sm-7">
              <fieldset>
                <legend>@lang('upload.settings')</legend>
                <div class="well">

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.resolution')</label>
                    <div class="col-md-10">
                      <div class="btn-group">
                          <label class="btn btn-default" ng-model="video.resolution" uib-btn-radio="0">Same as Source</label>
                          <label class="btn btn-default" ng-model="video.resolution" uib-btn-radio="1">Square</label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.caption')</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" ng-model="video.caption" maxlength="60">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.caption')</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" colorpicker="hex"  ng-model="video.captionColor" maxlength="7">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                      <button type="button" ng-click="saveSetting()" class="btn btn-primary btn-raised" ng-disabled="$saving == true">
                        <span ng-switch="$saving">
                          <span ng-switch-when="true">@lang('upload.saving')</span>
                          <span ng-switch-default>@lang('upload.save')</span>
                        </span>
                      </button>
                    </div>
                  </div>

                </div>
              </fieldset>
            </div>

            <div class="col-sm-3">
              <fieldset>
                <legend>@lang('upload.post_actions')</legend>
                <button type="button" class="btn btn-default btn-raised btn-block">
                  <i class="fa fa-envelope"></i> @lang('upload.post_action_email')...
                </button>
              </fieldset>
            </div>

          </div>

          <div ng-show="step == 3">
            <div class="jumbotron">
              <h1>Your File is Ready</h1>

              <p class="text-center">
                <img ng-src="@{{getUrl(video.url)}}" ng-if="video.status == 3" style="max-width: 100%">
              </p>

              <div class="form-group">
                <label class="col-md-1 control-label">Link</label>
                <div class="col-md-11">
                  <input type="text" class="form-control" ng-model="video.cmd">
                </div>
              </div>
              <p>
                <a class="btn btn-primary btn-lg">Download</a>
                <a class="btn btn-primary btn-lg">Send Link to Email</a>
                <a class="btn btn-primary btn-lg" ng-click="setFile(null)">Upload New File</a>
              </p>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>

@endsection