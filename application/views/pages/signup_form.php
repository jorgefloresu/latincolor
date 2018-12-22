
<div id="signup-page" class="modal" style="max-height:85%">
<div class="row">
<div class="col s12">

			<form action="<?=base_url('login/create_member')?>" id="signup-form" class="signup-form">
        <div class="row">
	        <div class="input-field col s12 center">
	            <h4>Register</h4>
	            <p class="response center">Join to our community now !</p>
	        </div>
        </div>
				<div class="row margin">
					<div class="input-field col s6">
						<i class="material-icons prefix">person</i>
						<input type="text" id="first_name" name="first_name" required="" aria-required="true">
						<label for="first_name">First Name</label>
					</div>
					<div class="input-field col s6">
						<input type="text" id="last_name" name="last_name" required="" aria-required="true">
						<label for="last_name" style="width:100%">Last Name</label>
					</div>
     			</div>
				<div class="row margin">
					<div class="input-field col s12">
						<i class="material-icons prefix">account_circle</i>
						<input type="text" id="username" name="username" required="" aria-required="true">
						<label for="username">User Name</label>
					</div>
					<div class="input-field col s12">
						<i class="material-icons prefix">email</i>
						<input type="email" id="email_address" name="email_address" required="" aria-required="true">
						<label for="email_address">Email address</label>
					</div>
    			</div>
				<div class="row margin">
					<div class="input-field col s6">
						<i class="material-icons prefix">lock</i>
						<input type="password" name="password" id="password" required="" aria-required="true">
						<label for="password">Password</label>
					</div>
					<div class="input-field col s6">
						<input type="password" name="password2" id="password2" required="" aria-required="true">
						<label for="password2" style="width:100%">Password Confirmation</label>
					</div>
    			</div>
    			<div class="row">
    				<div class="input-field col s4 offset-s4">
							<button class="btn waves-effect waves-light col s12 pink accent-2" type="submit">Create Account
							</button>
						</div>
						<div class="input-field col s12">
	      			<p class="margin center medium-small sign-up">Already have an account?
	      				<a href="#" class="go-login">Login</a></p>
	          </div>
				 </div>
			 </form>
</div>
</div>
</div>
