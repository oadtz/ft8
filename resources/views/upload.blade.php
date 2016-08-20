@extends('_layouts.default')

@section('title', trans('site.title'))


@section('content')

<div class="row" ng-controller="UploadController">
  <div class="col-md-12">
    <div class="bs-component">
      <form class="form-horizontal">
      <div class="row" ng-switch="step">
          <div ng-switch-when="2">

            <div class="col-sm-2">
              <fieldset>
                <legend>Progress</legend>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span class="sr-only">Uploading</span>
                  </div>
                </div>
                <div class="text-center">
                  Uploading...
                </div>
              </fieldset>
            </div>

            <div class="col-sm-7">
              <fieldset>
                <legend>Target File Settings</legend>
                <div class="well">

                  <div class="form-group">
                    <label class="col-md-2 control-label">Preset</label>

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
                    <label class="col-md-2 control-label">Resolution</label>
                    <div class="col-md-10">
                      <select class="form-control">
                        <option>Same as uploaded</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-2 control-label">Encoding</label>
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
                    <label class="col-md-2 control-label">Overlay / Watermark</label>
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
                      <button type="submit" class="btn btn-primary">Save Settings<div class="ripple-container"></div></button>
                    </div>
                  </div>

                </div>
              </fieldset>
            </div>

            <div class="col-sm-3">
              <fieldset>
                <legend>Post Action</legend>
                <button type="button" class="btn btn-default btn-raised btn-block">
                  <i class="fa fa-envelope"></i> Send Link to Email...
                </button>
              </fieldset>
            </div>

          </div>
          <div ng-switch-default>

            <div class="jumbotron">
              <h1>@lang('site.upload_title')</h1>
              <div class="input-group image-preview">
                <span class="input-group-btn">
                  <!-- image-preview-input --> 
                  <button type="button" class="btn btn-default btn-raised" ng-click="setStep(2)"> 
                    <i class="fa fa-folder-open"></i> @lang('site.browse')
                  </button>
                  <!-- image-preview-clear button -->
                  <button type="button" class="btn btn-default btn-raised"> 
                    <i class="fa fa-remove"></i> @lang('site.clear') 
                  </button>
                </span> 
              </div>
              
              <!-- Drop Zone -->
              <div class="upload-drop-zone" id="drop-zone">@lang('site.drop_zone')</div>

            </div>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>

@endsection