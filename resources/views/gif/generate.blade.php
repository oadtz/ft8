@extends('_layouts.default')

@section('title', trans('site.slogan'))

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
        <div class="well">
              <h1>Ready to Create You GIF</h1>

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
              <div class="row" ng-hide="$processing">
                <div class="col-sm-6">
                  <h4>Output</h4>

                    <div class="form-group">
                      <label class="col-md-2 control-label">Crop</label>
                      <div class="col-md-10">
                        <div class="btn-group">
                            <label class="btn btn-default" ng-model="gif.settings.aspectRatio"  ng-disabled="$processing == true" uib-btn-radio="0">Original</label>
                            <label class="btn btn-default" ng-model="gif.settings.aspectRatio"  ng-disabled="$processing == true" uib-btn-radio="1">Center</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label">Subtitle</label>
                      <div class="col-md-10">
                        <input type="text" class="form-control" ng-disabled="$processing == true" ng-model="gif.settings.caption" ng-model-options="{ debounce: 1000 }" maxlength="60">
                      </div>
                    </div>

                    <div class="form-group" ng-show="gif.settings.caption">
                      <label class="col-md-2 control-label">Font Color</label>
                      <div class="col-md-10">
                        <!--input type="text" class="form-control" colorpicker="hex" ng-disabled="$processing == true" ng-model="gif.settings.captionColor" ng-model-options="{ debounce: 1000 }" maxlength="7"-->
                        <div class="btn-group">
                            <label class="btn btn-default" ng-model="gif.settings.captionColor"  ng-disabled="$processing == true" uib-btn-radio="'#FFFFFF'"><div class="swatch" style="background-color: #FFFFFF"></div></label>
                            <label class="btn btn-default" ng-model="gif.settings.captionColor"  ng-disabled="$processing == true" uib-btn-radio="'#888888'"><div class="swatch" style="background-color: #888888"></div></label>
                            <label class="btn btn-default" ng-model="gif.settings.captionColor"  ng-disabled="$processing == true" uib-btn-radio="'#000000'"><div class="swatch" style="background-color: #000000"></div></label>
                            <label class="btn btn-default" ng-model="gif.settings.captionColor"  ng-disabled="$processing == true" uib-btn-radio="'#FF0000'"><div class="swatch" style="background-color: #FF0000"></div></label>
                            <label class="btn btn-default" ng-model="gif.settings.captionColor"  ng-disabled="$processing == true" uib-btn-radio="'#00FF00'"><div class="swatch" style="background-color: #00FF00"></div></label>
                            <label class="btn btn-default" ng-model="gif.settings.captionColor"  ng-disabled="$processing == true" uib-btn-radio="'#0000FF'"><div class="swatch" style="background-color: #0000FF"></div></label>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-sm-6">
                  <h4>Preview</h4>
                      <!--div class="text-center upload-preview" ng-style="{ 'background-image': 'url(' + getUrl('api/gif/' + gif._id + '/upload/preview?aspect=' + gif.settings.aspectRatio + '&caption=' + gif.settings.caption + '&color=' + gif.settings.color) + ')' }">
                        <img ng-src="@{{getUrl('api/gif/' + gif._id + '/upload/preview-placeholder?aspect=' + gif.settings.aspectRatio)}}"  class="img-responsive">
                        <span ng-style="{'color': gif.settings.captionColor}">@{{gif.settings.caption}}</span>
                      </div-->
                      <img ng-show="gif" ng-src="@{{getPreviewUrl()}}" class="img-responsive">
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