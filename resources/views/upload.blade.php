@extends('_layouts.default')

@section('title', 'Easy VDO to Gif Convert')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="bs-component">
      <form class="form-horizontal" ng-submit="void(0)">


     		<!-- Step 1 -->
            <div class="jumbotron">
              <h1>Upload Video</h1>

              <div class="input-group">
                <span class="input-group-btn">

                  <button type="button" class="btn btn-info btn-raised">
                    <i class="fa fa-upload"></i> Upload this file
                  </button> 
                  
                  <!-- image-preview-input --> 
                  <span class="btn btn-default btn-raised" ngf-select="setFile($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'"> 
                    <i class="fa fa-folder-open"></i> Choose...
                  </span>
                  <!-- image-preview-clear button -->
                  <button type="button" class="btn btn-default btn-raised" ng-click="setFile(null)"> 
                    <i class="fa fa-remove"></i> Cancel 
                  </button>
                </span> 
                <div class="pull-right">
                  <a href class="btn btn-link btn-success">Login</a>
                </div>
              </div>
              <div class="upload-drop-zone" ngf-drop="setFile($file)" ngf-pattern="'video/*'" ngf-accept="'video/*'" ngf-allow-dir="false">
                <i class="fa fa-film"></i> You can drag n drop your video here.
                <br>
              </div>
              <p>House rules:</p>
              <ul>
              	<li>Maximum video size is 200 MB.</li>
              	<li>For best result, your GIF will be limited to 15 seconds long.</li>
              </ul>
            </div>

     		<!-- Step 1 -->
            <div class="jumbotron">
              <h1>Customize Your GIF</h1>

              <div class="row">
              	<div class="col-sm-6">
              		<h4>Output</h4>

	                  <div class="form-group">
	                    <label class="col-md-2 control-label">Aspect Ratio</label>
	                    <div class="col-md-10">
	                      <div class="btn-group">
	                          <label class="btn btn-default" ng-model="video.resolution" uib-btn-radio="0">Same as Source</label>
	                          <label class="btn btn-default" ng-model="video.resolution" uib-btn-radio="1">Square</label>
	                      </div>
	                    </div>
	                  </div>
	                  <div class="form-group">
	                    <label class="col-md-2 control-label">Caption</label>
	                    <div class="col-md-10">
	                      <input type="text" class="form-control" ng-model="video.caption" maxlength="60">
	                    </div>
	                  </div>

	                  <div class="form-group">
	                    <label class="col-md-2 control-label">Font Color</label>
	                    <div class="col-md-10">
	                      <input type="text" class="form-control" colorpicker="hex"  ng-model="video.captionColor" maxlength="7">
	                    </div>
	                  </div>
              	</div>
              	<div class="col-sm-6">
              		<h4>Preview</h4>
              		<div class="text-center" style="background-image: url('{{url('uploads/out.gif')}}'); background-size: 100%; background-repeat: no-repeat; width: 540px; height: 300px; vertical-align: bottom; display: table-cell;">
              			<span ng-style="{'color': video.captionColor}">@{{video.caption}}</span>
              		</div>
              	</div>
              	<div class="col-sm-12">
                  <button type="button" ng-click="saveSetting()" class="btn btn-lg btn-primary btn-raised" ng-disabled="$saving == true">
                  	Generate My GIF
                  </button>
                  <button type="button" ng-click="saveSetting()" class="btn btn-default" ng-disabled="$saving == true">
                  	Go Back
                  </button>
              	</div>
              </div>
            </div>

      </form>
    </div>
  </div>
</div>

@endsection