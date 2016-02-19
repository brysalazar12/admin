<!DOCTYPE html>
<html>
	<head>
		<title>Test</title>
		<script src="{{ url('themes/admin/startbootstrap-sb-admin/assets/js/jquery.js') }}" type="text/javascript"></script>
		<script src="{{ url('themes/admin/startbootstrap-sb-admin/assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<link rel="stylesheet" href="{{ url('themes/admin/startbootstrap-sb-admin/assets/css/bootstrap.min.css') }}" type="text/css">
		<link href="{{ url('themes/admin/startbootstrap-sb-admin/assets/css/sb-admin.css') }}" rel="stylesheet">
		<link href="{{ url('themes/admin/startbootstrap-sb-admin/assets/css/plugins/morris.css') }}" rel="stylesheet">
		<link href="{{ url('themes/admin/startbootstrap-sb-admin/assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
		<!--<script src="{{ url('themes/admin/startbootstrap-sb-admin/assets/js/plugins/morris/raphael.min.js') }}" type="text/javascript"></script>-->
		<!--<script src="{{ url('themes/admin/startbootstrap-sb-admin/assets/js/plugins/morris/morris.min.js') }}" type="text/javascript"></script>-->
		<!--<script src="{{ url('themes/admin/startbootstrap-sb-admin/assets/js/plugins/morris/morris-data.js') }}" type="text/javascript"></script>-->

		{{-- start load easyui css --}}
		<link rel="stylesheet" href="{{ url('themes/admin/startbootstrap-sb-admin/assets/easyui/themes/default/easyui.css') }}" type="text/css">
		<link rel="stylesheet" href="{{ url('themes/admin/startbootstrap-sb-admin/assets/easyui/themes/icon.css') }}" type="text/css">
		{{-- end load easyui css --}}

		{{-- start load easyui js --}}
		<script src="{{ url('themes/admin/startbootstrap-sb-admin/assets/easyui/jquery.easyui.min.js') }}" type="text/javascript"></script>
		{{-- end load easyui js --}}


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	</head>
	<body>
		<div id="wrapper">
			<!-- Navigation -->
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

				<!-- Brand and toggle get grouped for better mobile display -->
				@include('partial.toggle')

				<!-- Top Menu Items -->
				@include('partial.top-menu')

				<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
				@include('partial.left-menu')
				<!-- /.navbar-collapse -->
			</nav>
			@yield('main')
		</div>

		@section('footerJs')
		myfooter
		@show
	</body>
</html>