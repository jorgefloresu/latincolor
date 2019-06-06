
<div id="signup-page" class="modal" style="max-height:85%">
<div class="row">
<div class="col s12">

			<form action="<?=base_url('login/create_member')?>" id="signup-form" class="signup-form">
        <div class="row">
	        <div class="input-field col s12 center">
	            <h4>Registrarse</h4>
	            <p class="response center">Únete a nuestro selecto grupo de usuarios!</p>
	        </div>
        </div>
				<div class="row margin">
					<div class="input-field col s6">
						<i class="material-icons prefix">person</i>
						<input type="text" id="first_name" name="first_name" required="" aria-required="true">
						<label for="first_name">Nombre</label>
					</div>
					<div class="input-field col s6">
						<input type="text" id="last_name" name="last_name" required="" aria-required="true">
						<label for="last_name" style="width:100%">Apellido</label>
					</div>
     			</div>
				<div class="row margin">
					<div class="input-field col s6">
						<i class="material-icons prefix">account_circle</i>
						<input type="text" id="username" name="username" required="" aria-required="true">
						<label for="username">Nombre de usuario</label>
					</div>
					<div class="input-field col s6">
						<select class="country" name="country">
						<? foreach ($countries as $country) {
							$value = $country->code=='00'?'':$country->name;
							$disabled = $country->code=='00'?'disabled':'';
							$selected = $country->code=='CO'?'selected':'';
							echo "<option value='$value' $disabled $selected>".$country->name."</option>";
						}?>
						</select>
						<label>País donde te encuentras</label>
					</div>
				</div>
				<div class="row margin">
					<div class="input-field col s12">
						<i class="material-icons prefix">email</i>
						<input type="email" id="email_address" name="email_address" required="" aria-required="true">
						<label for="email_address">Dirección email</label>
					</div>
    		</div>
				<div class="row margin">
					<div class="input-field col s6">
						<i class="material-icons prefix">lock</i>
						<input type="password" name="password" id="password" required="" aria-required="true">
						<label for="password">Clave</label>
					</div>
					<div class="input-field col s6">
						<input type="password" name="password2" id="password2" required="" aria-required="true">
						<label for="password2" style="width:100%">Confirmar clave</label>
					</div>
    			</div>
    			<div class="row">
    				<div class="input-field col s4 offset-s4">
							<button class="btn waves-effect waves-light col s12 pink accent-2" type="submit">Crear cuenta
							</button>
						</div>
						<div class="input-field col s12">
	      			<p class="margin center medium-small sign-up">Ya tienes una cuenta?
	      				<a href="#" class="go-login">Inicia sesión</a></p>
	          </div>
				 </div>
			 </form>
</div>
</div>
</div>
