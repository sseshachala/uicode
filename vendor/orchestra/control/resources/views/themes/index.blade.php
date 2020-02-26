@extends('orchestra/foundation::layouts.page')

@php
$collection = collect([
  'frontend' => ['link' => handles('orchestra::control/themes/frontend'), 'title' => 'Frontend Theme'],
  'backend' => ['link' => handles('orchestra::control/themes/backend'), 'title' => 'Backend Theme'],
]);
@endphp

@section('header::right')
<btndrop id="theme-menu" current="{{ $type }}" pull="right" :items="dropmenu"></btndrop>
@stop

@section('content')
<div class="row">
  @include('orchestra/control::themes._list')
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').nav('control-themes')
  app.$set('dropmenu', {!! $collection->toJson() !!})
  app.$mount('body')
</script>
@endpush
