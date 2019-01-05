<!DOCTYPE html>
<html lang="en" style="display: table;margin: auto;height:100%">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo base_url('materialize/admin/css/materialize.min.css'); ?>" media="screen, projection"/>
		<!-- <link rel="stylesheet" href="<?php echo base_url('materialize/css/materialize.min.css'); ?>" media="screen, projection"/> -->
		<link rel="stylesheet" href="<?php echo base_url('css/materialize-plus.css'); ?>" />
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<title>Latincol Panel</title>

	</head>

  <body class="blue" style="display: table-cell;vertical-align: middle;height:100%">
		<div id="sign-in" class="row" style="width:300px">
		<div class="col s12 z-depth-4 card-panel">

					<?php echo form_open('login/validate_credentials/redir', 'id="login-form" class="login-form" novalidate'); ?>
				        <div class="row">
					        <div class="input-field col s12 center">
					            <img src="<?php echo base_url('img/avatar.jpg'); ?>" alt="" class="responsive-img valign profile-image-login">
					            <p class="center login-form-text">Ingresa tus credenciales</p>
					        </div>
				        </div>
						<div class="row margin">
							<div class="input-field col s12">
								<i class="mdi-social-person-outline prefix"></i>
								<input type="text" id="username" name="username" class="validate" required="" aria-required="true">
								<label for="username">Username</label>
							</div>
		    			</div>
						<div class="row margin">
							<div class="input-field col s12">
								<i class="mdi-action-lock-outline prefix"></i>
								<input type="password" name="password" id="password" class="validate" required="" aria-required="true">
								<label for="password">Password</label>
							</div>
		    			</div>
						<div class="row">
							<div class="input-field col s12 m12 l12 login-text">
						      	<input type="checkbox" id="rememberMe">
						      	<label for="rememberMe">Remember Me</label>
						    </div>
		    			</div>
		    			<div class="row">
		    				<div class="input-field col s12">
								<button class="btn waves-effect waves-light col s12 pink accent-2" type="submit">Sign in
								</button>
							</div>
							<div id="message" style="display:none; color:red">
								<p class="error_msg center medium-small">Your submitted login details are incorrect.</p>
							</div>
						</div>
					<?php echo form_close(); ?>
		</div>
		</div>

		<script src="<?php echo base_url('js/jquery-3.3.1.min.js');?>"></script>
		<script src="<?php echo base_url('materialize/admin/js/materialize.min.js');?>"></script>
		<!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
		<script src="http://localhost:8888/CI/materialize/js/materialize.min.js"></script> -->
		<script src="<?php echo base_url('js/js.storage.min.js'); ?>"></script>
		<script src="<?php echo base_url('js/jquery.validate.js'); ?>"></script>
		<script src="<?php echo base_url('js/lib.functions.js');?>"></script>
		<script src="<?php echo base_url('js/auth.functions.js');?>"></script>
		<script src="<?php echo base_url('js/login.functions.js');?>"></script>

	</body>
</html>
