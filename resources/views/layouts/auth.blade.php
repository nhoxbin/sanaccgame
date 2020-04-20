<!DOCTYPE html>
<html>
	<head>
		{{-- <meta charset="utf-8"> --}}
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
	    <meta name="csrf-token" content="{{ csrf_token() }}">

		<meta name="referrer" content="origin">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		{{-- <link href="http://starnhat.vn/icon.png" rel="shortcut icon" type="image/x-icon"> --}}
		<title>Sàn Acc Game - Sàn Mua Bán Tài Khoản Game</title>
		<meta name="viewport" content="width=device-width">
		<meta name="description" content="Website hỗ trợ game thủ mua bán tài khoản game an toàn tránh lừa đảo!">
		<meta name="keyword" content="giao dịch, mua acc game">

		<meta property="og:type" content="article">
		<meta property="og:url" content="">
		<meta property="og:site_name" content="Thành viên SanAccGame.Com">
		<meta property="og:image" content="">
		<meta property="og:image:type" content="image/jpeg">
		<meta property="og:image:width" content="300">
		<meta property="og:image:height" content="300">
		<meta property="og:title" content="Sàn mua và bán acc game hiệu quả nhất">
		<meta property="og:description" content="Hệ thống mua bán acc game SanAccGame.Com">

		<link href="{{ asset('web/client/css/auth/style.css') }}" rel="stylesheet">
		<link href="{{ asset('web/client/css/bootstrap.min.css') }}" rel="stylesheet">
		{{-- <link href="./login_files/owl.carousel.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/bootstrap-select.min.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/font-awesome.min.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/animate.min.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/responsive.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/googlefont.css" rel="stylesheet"> --}}
		<link href="{{ asset('web/client/css/auth/settings.css') }}" rel="stylesheet">
		{{-- <link href="./login_files/rs-plugin-styles.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/custom.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/jquery-ui.css" rel="stylesheet"> --}}
		{{-- <link href="./login_files/simplePagination.css" rel="stylesheet"> --}}
		
		<script src="{{ asset('web/client/js/auth/jquery.js') }}"></script>
		{{-- <script src="./login_files/jquery-ui.js.download"></script> --}}
		<script src="{{ asset('web/client/js/bootstrap.min.js') }}"></script>
		{{-- <script src="./login_files/app.js.download"></script> --}}
		{{-- <script src="./login_files/bootstrap-select.min.js.download"></script> --}}
		{{-- <script src="./login_files/smoothscroll.js.download"></script> --}}
		{{-- <script src="./login_files/owl.carousel.js.download"></script> --}}
		{{-- <script src="./login_files/scrollreveal.min.js.download"></script> --}}
		{{-- <script src="./login_files/home.js.download"></script> --}}
		{{-- <script src="./login_files/utils.js.download"></script> --}}
		{{-- <script src="./login_files/register.js.download"></script> --}}
		{{-- <script src="./login_files/profile.js.download"></script> --}}
	</head>
	<body class="popup grey-bg pushmenu-push">
		<div class="popup-all">
			<div class="container">
				<div class="row">
					@yield('content')
				</div> 
			</div> 
		</div>
	</body>
</html>