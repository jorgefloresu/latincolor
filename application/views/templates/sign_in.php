<div id="sign-in" class="modal">
    <div class="modal-content">
    <div class="row">
    <div class="col s12">
    <?php echo form_open('login/validate_credentials', 'id="login-form" class="login-form" novalidate'); ?>
        <div class="input-field col s12 center">
          <h4>Iniciar Sesión</h4>
        </div>
      <div class="row margin">
        <div class="input-field col s12">
          <i class="material-icons prefix">account_circle</i>
          <input type="text" id="login-username" name="username" class="validate" required="" aria-required="true">
          <label for="username">Usuario</label>
        </div>
        </div>
      <div class="row margin">
        <div class="input-field col s12">
          <i class="material-icons prefix">vpn_key</i>
          <input type="password" name="password" id="login-password" class="validate" required="" aria-required="true">
          <label for="password">Clave</label>
        </div>
      </div>
      <div class="row margin">
        <div class="input-field col s12">
          <button class="btn waves-effect waves-light col s12 pink accent-2" type="submit">Ingresar
          </button>
        </div>
      </div>
      <!-- <div class="row margin">
        <div class="input-field col s6">
        <div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false"></div>
          <fb:login-button scope="public_profile,email" size="large" onlogin="checkLoginState();"></fb:login-button>
        </div>
        <div id="my-signin2" class="input-field col s6"></div>
      </div> -->
      <div id="message" style="display:none; color:red">
        <p class="error_msg center medium-small">Tus credenciales de usuario no son correctas</p>
      </div>
      <div class="row margin">
        <div class="input-field col s6 m6 l6">
          <p class="margin medium-small"><a href="#" class="create-account">Crear cuenta</a></p>
        </div>
        <div class="input-field col s6 m6 l6">
          <p class="margin right-align medium-small"><a href="#" class="forgot-password">Recuperar clave</a></p>
        </div>
      </div>
    <?php echo form_close(); ?>
    </div>
    </div>
    </div>
</div>
