<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<!-- Meta Information -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title', config('app.name'))</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito:300,300i,400,400i,700" rel="stylesheet">
	<link rel="stylesheet" href="{{ mix('css/styles.css') }}">


	<!-- Global App Object -->
	<script>
      window.Jiri = <?php echo json_encode(array_merge(
          \Jiri\Configuration\JiriVariables::jiriVariables(), []
      )); ?>;
	</script>

</head>
<body class="@yield('custom-class')">
<el-container id="app" v-cloak>
	<!-- Navigation -->
	@guest
		<!-- Silence is golden -->
	@else
		<el-aside width="100px" class="nav__wrapper">
			@include('partials.nav')
		</el-aside>
	@endguest
	<!-- Main Content -->
	<el-container class="main-container">
		@guest
			<el-main>
				@yield('content')
			</el-main>
		@else
			<el-header class="header o-wrapper">
				@include('partials.header')
			</el-header>
			<el-main :class="{'pageevent':($route.meta.page === 'event')}">
				@yield('content')
			</el-main>
		@endguest
	</el-container>

</el-container>
<!-- JavaScript -->
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
