@extends('_layouts.default')

@section('title', trans('site.title'))


@section('content')

<div class="row" ng-controller="UploadController">
  <div class="col-md-12">
    <div class="bs-component">
      <form class="form-horizontal" ng-submit="void(0)">
      
      <input type="hidden" id="error_max_file_size" value="@lang('error.max_file_size')">
      <div class="row">
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
                  <div class="progress-bar progress-bar-striped active" ng-class="{ 'progress-bar-success': video.status == 0, 'progress-bar-primary': video.status == 1, 'progress-bar-info': video.status == 2, 'progress-bar-danger': video.status == 3 }" role="progressbar" aria-valuemin="0" aria-valuemax="100" ng-style="{ 'width': progressPct + '%' }">
                    <span class="sr-only">
                      <span ng-switch="video.status">
                        <span ng-switch-when="3">@lang('upload.error')</span>
                        <span ng-switch-when="2">@lang('upload.done')</span>
                        <span ng-switch-when="1">@lang('upload.processing')</span>
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

                  <!--div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.preset')</label>

                    <div class="col-md-10">
                      <select class="form-control" ng-model="video.preset" ng-options="p as p.name for p in presets" ng-init="presets = {{json_encode(config('ft8.presets'))}}; video.preset = presets[0]">
                      </select>
                    </div>
                  </div-->

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.resolution')</label>
                    <div class="col-md-10">
                      <select class="form-control" ng-model="video.resolution" ng-options="r.resolution as r.name for r in resolutions" ng-init="resolutions = {{json_encode(config('ft8.video_resolutions'))}}; video.resolution = resolutions[0].resolution">
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.format')</label>
                    <div class="col-md-10">
                      <select class="form-control" ng-model="video.format" ng-options="f.format as f.name for f in formats" ng-init="formats = {{json_encode(config('ft8.video_formats'))}}; video.format = formats[0].format">
                      </select>
                    </div>
                  </div>

                  <div class="form-group is-fileinput">
                    <label class="col-md-2 control-label">@lang('upload.overlay')</label>
                    <div class="col-md-10">

                      <span ng-if="video.overlay">@{{video.overlay}}</span>
                      <span class="btn btn-default" ngf-select="setOverlay($file)" ngf-pattern="'image/*'" ngf-accept="'image/*'" ngf-max-size="5MB"> 
                        <i class="fa fa-folder-open"></i> @lang('upload.browse')
                      </span>

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

        </div>
      </form>
    </div>
  </div>
</div>

@endsection