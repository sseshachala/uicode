@extends('orchestra/foundation::layouts.app')

@section('content')
<div class="row">
	<div class="col-md-8 col-xs-12">
    {{ $form }}
	</div>
	<div class="col-md-4 col-xs-12">
		@placeholder('orchestra.settings')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop


@push('orchestra.footer')
@include('orchestra/foundation::settings._script')
<script>
  var app = Platform.make('setting').$mount('body')
</script>
@endpush
