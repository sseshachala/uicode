@extends('orchestra/foundation::layouts.app')

@section('content')
<div class="row">
  <div class="col-md-8 col-xs-12">
    {{ $form }}
  </div>
  <div class="col-md-4 col-xs-12">
    @placeholder('orchestra.account')
    @placeholder('orchestra.helps')
  </div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').nav('accounts/password').$mount('body')
</script>
@endpush
