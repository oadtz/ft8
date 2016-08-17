@extends('_layouts.root')

@section('stylesheet')
  <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
  <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
@endsection

@section('javascript')
<script>
$(function () {
    $.material.init();
});
</script>
@endsection