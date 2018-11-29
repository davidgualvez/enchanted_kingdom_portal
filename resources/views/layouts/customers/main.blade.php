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
	<link rel="stylesheet" type="text/css" href="/css/plugins/iziToast.min.css">
	<link rel="stylesheet" type="text/css" href="/css/plugins/jquery-confirm.min.css">
	<link rel="stylesheet" type="text/css" href="/css/config.css">
	
	<!-- JS -->
	<script src="/js/app.js"></script>
	<script src="/dist/semantic.min.js"></script>
	<script src="/js/plugins/iziToast.min.js"></script>
	<script src="/js/plugins/jquery-confirm.min.js"></script>
	<script src="/js/config.js"></script>

	<!-- CUSTOM CSS -->
	<style type="text/css">
		/*.main.container {
		    margin-top: 2em;
		  }*/

		/*  .main.menu {
		    margin-top: 4em;
		    border-radius: 0;
		    border: none;
		    box-shadow: none;
		    transition:
		      box-shadow 0.5s ease,
		      padding 0.5s ease
		    ;
		  }
		  
	
		  .main.menu .item img.logo {
		    margin-right: 1.5em;
		  }

		  .overlay {
		    float: left;
		    margin: 0em 3em 1em 0em;
		  }
		  
		  .overlay .menu {
		    position: relative;
		    left: 0;
		    transition: left 0.5s ease;
		  }

		  .main.menu.fixed {
		    background-color: #FFFFFF;
		    border: 1px solid #DDD;
		    box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
		  }*/
		  /*.overlay.fixed .menu {
		    left: 800px;
		  }*/

	</style>
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
	<!-- <script src="/js/app.js"></script>
	<script src="/dist/semantic.min.js"></script>
	<script src="/js/plugins/iziToast.min.js"></script>
	<script src="/js/config.js"></script> -->
	<!-- CUSTOM JS -->
	@yield('js')
</body>
</html>