@extends('_layouts.default')

@section('title', 'Easy VDO to Gif Convert')

@section('stylesheet')
@parent

<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="bs-component" ng-controller="GifGenerateController">
      <form class="form-horizontal" ng-submit="void(0)">
        <input type="hidden" ng-init="setGif({{htmlspecialchars(json_encode($gif))}})">
        <div class="jumbotron">
              <h1>Customize Your GIF</h1>

              <div class="row">
                <div class="col-sm-6">
                  <h4>Preview</h4>
                    <div ng-hide="$processing">
                      <div class="text-center upload-preview" ng-style="{ 'background-image': 'url(' + getUrl('api/gif/' + gif._id + '/upload/preview?aspect=' + gif.settings.aspectRatio) + ')' }">
                        <img ng-src="@{{getUrl('api/gif/' + gif._id + '/upload/preview-placeholder?aspect=' + gif.settings.aspectRatio)}}"  class="img-responsive">
                        <span ng-style="{'color': gif.settings.captionColor}">@{{gif.settings.caption}}</span>
                      </div>
                    </div>
                    <div ng-show="$processing">
                      <div class="text-center">@{{gif.statusName}}</div>
                      <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                          <span class="sr-only">
                            @{{gif.statusName}}
                          </span>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-sm-6">
                  <h4>Output</h4>

                    <div class="form-group">
                      <label class="col-md-2 control-label">Aspect Ratio</label>
                      <div class="col-md-10">
                        <div class="btn-group">
                            <label class="btn btn-default" ng-model="gif.settings.aspectRatio"  ng-disabled="$processing == true" uib-btn-radio="0">Same as Source</label>
                            <label class="btn btn-default" ng-model="gif.settings.aspectRatio"  ng-disabled="$processing == true" uib-btn-radio="1">Square</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label">Caption</label>
                      <div class="col-md-10">
                        <input type="text" class="form-control"  ng-disabled="$processing == true" ng-model="gif.settings.caption" maxlength="60">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-2 control-label">Font Color</label>
                      <div class="col-md-10">
                        <input type="text" class="form-control" colorpicker="hex"  ng-disabled="$processing == true" ng-model="gif.settings.captionColor" maxlength="7">
                      </div>
                    </div>
                </div>
                <div class="col-sm-12">
                  <button type="button" ng-click="generate()" class="btn btn-lg btn-primary btn-raised" ng-disabled="$processing == true">
                    Generate My GIF
                  </button>
                  <button type="button" ng-click="cancel()" class="btn btn-default" ng-disabled="$processing == true">
                    Cancel & Start Over
                  </button>
                </div>
              </div>
        </div>


      </form>
    </div>
  </div>
</div>

@endsection