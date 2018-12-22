<div id="forgot-page" class="modal">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">

			<form action="<?=base_url('login/forgot_pass_phpmailer')?>" id="forgot-form" class="signup-form" method="post">
        <div class="row">
	        <div class="input-field col s12 center">
						<h5>Latin Color Images</h5>
	            <h4>Recover Password</h4>
	            <p class="response center">Your new password will be sent to this email</p>
	        </div>
        </div>
				<div class="row margin">
					<div class="input-field col s8 offset-s2">
						<i class="material-icons prefix">email</i>
						<input type="email" id="email_forgot" name="email_forgot" class="validate" required="" aria-required="true">
						<label for="email_forgot">Email address</label>
					</div>
    		</div>
  			<div class="row">
  				<div class="input-field col s4 offset-s4">
						<button class="btn waves-effect waves-light col s12 pink accent-2" type="submit">Get password
						</button>
					</div>
          <div id="message" style="display:none; color:red">
              <p class="error_msg center medium-small">Error sending email</p>
          </div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
	</div>
</div>
</body>
</html>
