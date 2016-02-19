<!DOCTYPE html>
<html>
	<head>
		<script src="{{ url('themes/admin/startbootstrap-sb-admin/assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<link rel="stylesheet" href="{{ url('themes/admin/startbootstrap-sb-admin/assets/css/bootstrap.min.css') }}" type="text/css">

		<style>
			@import url(http://fonts.googleapis.com/css?family=Roboto:400);
			body {
			  background-color:#fff;
			  -webkit-font-smoothing: antialiased;
			  font: normal 14px Roboto,arial,sans-serif;
			}

			.container {
				padding: 25px;
				position: fixed;
			}

			.form-login {
				background-color: #EDEDED;
				padding-top: 10px;
				padding-bottom: 20px;
				padding-left: 20px;
				padding-right: 20px;
				border-radius: 15px;
				border-color:#d2d2d2;
				border-width: 5px;
				box-shadow:0 1px 0 #cfcfcf;
			}

			h4 {
			 border:0 solid #fff;
			 border-bottom-width:1px;
			 padding-bottom:10px;
			 text-align: center;
			}

			.form-control {
				border-radius: 10px;
			}

			.wrapper {
				text-align: center;
			}
		</style>
	</head>
	<body>
		<!--Pulling Awesome Font -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

		<div class="container">
			<div class="row">
				<div class="col-md-offset-5 col-md-3">
					<div class="form-login">
						<form method="POST">
							<h4>Login</h4>
							{!! csrf_field() !!}
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<input type="email" name="email" class="form-control input-sm chat-input" placeholder="Email" />
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>
							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<input type="password" name="password" class="form-control input-sm chat-input" placeholder="password" />
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
							</div>
							</br>
							<div class="wrapper">
							<span class="group-btn">
								<button type="submit" class="btn btn-primary btn-md">login <i class="fa fa-sign-in"></i></button>
							</span>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</body>
</html>
