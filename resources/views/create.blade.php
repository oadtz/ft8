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