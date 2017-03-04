<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>ATLAS</title>
		@include('layouts.head')
		@yield('style_content')
		<!-- Custom styling plus plugins -->
		{!! Html::style('build/css/custom.min.css') !!}
		<style>
			.loading {
				background: #fff url("{{asset('imgs/ajax-loader.gif')}}") no-repeat center center !important;
			}

			.esconda {
				display: none;
			}
		</style>
	</head>
	<body class="nav-md">
		{!! Html::script('js/nprogress.js') !!}
		<script>
			NProgress.start();
		</script>
		<!---- Visualização de popup ----->
		{{--		@include('layouts.alerts.popup')--}}
		{{--@include('layouts.alerts.notifications')--}}
		{{--@include('layouts.modals.notifications')--}}
		@yield('modals_content')
		@include('layouts.modals.remove')
		@include('layouts.modals.add')

		<div class="container body">
			<div class="main_container">
				<!---- Visualização de erros ----->
			@include('layouts.menu')
			<!-- page content -->
				<div class="right_col" role="main">
					<div class="loading loading-page"></div>
					@yield('modal_content')
					@if (count($errors) > 0)
						@include('layouts.alerts.erros')
					@endif
					@if (session()->has('mensagem'))
						@include('layouts.alerts.success')
					@endif
					@yield('page_content')
					<!-- /page content -->
				</div>
			</div>
		</div>
		@include('layouts.foot')
		@yield('scripts_content')
	</body>
</html>
