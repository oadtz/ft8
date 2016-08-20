@extends('_layouts.default')

@section('title', trans('site.title'))


@section('content')

<div class="row" ng-controller="UploadController">
  <div class="col-md-12">
    <div class="bs-component">
      <form class="form-horizontal" ng-submit="void(0)">
      <div class="row" ng-switch="step">
          <div ng-switch-when="2">

            <div class="col-sm-2">
              <fieldset>
                <legend>@lang('upload.status')</legend>
                <div class="text-center">
                  @lang('upload.uploading')...
                </div>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span class="sr-only">@lang('upload.uploading')</span>
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="col-sm-7">
              <fieldset>
                <legend>@lang('upload.settings')</legend>
                <div class="well">

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.preset')</label>

                    <div class="col-md-10">
                      <select class="form-control">
                        <option>Custom</option>
                        <option>Highest Quality</option>
                        <option>High Quality</option>
                        <option>Standard Quality</option>
                        <option>Low Quality</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.resolution')</label>
                    <div class="col-md-10">
                      <select class="form-control">
                        <option>Same as uploaded</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-2 control-label">@lang('upload.format')</label>
                    <div class="col-md-10">
                      <select class="form-control">
                        <option>H.264</option>
                        <option>ProRes422</option>
                        <option>Apple PhotoJPEG</option>
                        <option>Gif Animation</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group is-fileinput">
                    <label class="col-md-2 control-label">@lang('upload.overlay')</label>
                    <div class="col-md-10">

                      <input type="file" id="inputFile4" multiple="">
                      <div class="input-group">
                        <input type="text" readonly="" class="form-control" placeholder="Placeholder w/file chooser...">
                          <span class="input-group-btn input-group-sm">
                            <button type="button" class="btn btn-fab btn-fab-mini">
                              <i class="material-icons">attach_file</i>
                            </button>
                          </span>
                      </div>


                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                      <button type="submit" class="btn btn-primary">@lang('upload.save')<div class="ripple-container"></div></button>
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
          <div ng-switch-default>

            <div class="jumbotron">
              <h1>@lang('upload.title')</h1>
              <div class="input-group image-preview">
                <span class="input-group-btn">
                  <!-- image-preview-input --> 
                  <span class="btn btn-default btn-raised" ngf-select="setFile($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'"> 
                    <i class="fa fa-folder-open"></i> @lang('upload.browse')
                  </span>
                  <!-- image-preview-clear button -->
                  <button type="button" class="btn btn-default btn-raised" ng-click="removeFile()"> 
                    <i class="fa fa-remove"></i> @lang('upload.clear') 
                  </button>

                  <button type="button" class="btn btn-info btn-raised" ng-if="file">
                    <i class="fa fa-upload"></i> @lang('upload.upload')
                  </button> 
                </span> 
              </div>
              
              <!-- Drop Zone -->
              <video class="video-js vjs-default-skin" ngf-src="file" controls preload="auto" width="640" height="264" vjs-video></video>
              <div class="upload-drop-zone" ngf-drop="setFile($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'">
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