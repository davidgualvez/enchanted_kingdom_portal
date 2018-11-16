<!DOCTYPE html>
<html>
<head> 
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="/favico.png" />
    <title>@yield('title') | Enchanted Kingdom</title>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/dist/semantic.min.css"> 
	<link rel="stylesheet" type="text/css" href="/css/config.css">
	<!-- CUSTOM CSS -->
	@yield('css')
</head>
<body> 

	<!-- navigation -->
	@include('layouts.customers.nav')

	<div class="ui container" id="main"> 
		<div class="ui equal width grid">  
			<!-- CONTENT -->
			<div class="column">
				<!-- SITE MAP --> 
			 	@yield('content')
			</div>
			<!-- END OF CONTENT --> 
		</div>
	</div>
	
	<!-- JS -->
	<script src="/js/app.js"></script>
	<script src="/dist/semantic.min.js"></script>
	<script src="/js/config.js"></script>
	<!-- CUSTOM JS -->
	@yield('js')
</body>
</html>