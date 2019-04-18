<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="consultor">

    <div id="index-banner" class="parallax-container">
        <div class="section no-pad-bot">
          <div class="container">
            <br><br>
            <h1 class="header center white-text text-lighten-2">Mi Consultor</h1>
            <div class="row center">
              <h5 class="header col s12 light">Tu punto de atención centralizado</h5>
            </div>
            <div class="row center">
              <a href="#dinos-tu-consulta" id="consultor-btn" class="btn-large waves-effect waves-light blue accent-3">Dinos tu consulta</a>
            </div>

          </div>
        </div>
        <div class="parallax"><img src="<?=base_url('img/consultor.jpg')?>" alt="planes"></div>
    </div>

    <div id="dinos-tu-consulta" class="container">

        <div class="row">
          <div class="col s12 m6 l6">
            <h3>Contacta un consultor</h3>
          </div>
          <div class="col s12 m6 l6">
            <p class="light">
              <strong>¿Tienes alguna duda acerca de nuestros servicios o tienes alguna sugerencia? </strong>
              Déjanos saber tu duda u opinión, tus comentarios son muy valiosos para nosotros.</p>
          </div>
        </div>

        <div class="row">
          <div class="col s12">
            <form id="form-consultor" action="<?=base_url('main/send_consulta')?>">
              <input id="cons" type="hidden" name="cons" value="<?=$pre_consulta?>"/>
            <div class="card">
              <div class="card-content">
                <div class="section">
                  <h5>Paso 1 - Naturaleza de la consulta</h5>
                  <p>Este es un espacio para que puedas escribir tu consulta. Disponemos de una lista
                  de consultas frecuentes. Al momento de escribir te aparecerá una lista de consultas relacionadas.
                  Elige la más apropiada o ingresa una nueva consulta.</p>

                  <div class="row consultor-chips ">
                    <div class="input-field col s12">
                      <i class="material-icons prefix">mode_edit</i>
                      <div class="chips chips-autocomplete"></div>
                    </div>
                  </div>
                </div>
                <div class="section">
                  <h5>Paso 2 - Detalle de la consulta</h5>
                  <p>Si lo consideras, puedes agregar más detalles a tu consulta en este espacio opcional.</p>
                  <div class="row consultor-chips">
                    <div class="input-field col s12">
                      <i class="material-icons prefix">textsms</i>
                      <textarea id="detalle" class="materialize-textarea" name="detalle" data-length="120"></textarea>
                      <label for="detalle"><?=$pre_detalle_label?></label>
                    </div>
                  </div>
                </div>
                <div class="section">
                  <h5>Paso 3 - Ingresa tus datos de contacto</h5>
                  <p>Para dar un seguimiento personalizado, por favor ingresa tus datos.</p>
                  <div class="row">

                    <div class="input-field col s6">
                      <i class="material-icons prefix">account_circle</i>
                      <input id="name" type="text" name="name">
                      <label for="name">Nombre</label>
                    </div>
                    <div class="input-field col s6">
                      <i class="material-icons prefix">phone</i>
                      <input id="phone" type="text" name="phone">
                      <label for="phone">Teléfono</label>
                    </div>
                    <div class="input-field col s6">
                      <i class="material-icons prefix">email</i>
                      <input id="email" type="email" class="validate" name="email">
                      <label for="email">Email</label>
                    </div>

                  </div>


                  </div>
              </div>
              <div class="card-action">
                <button class="btn waves-effect waves-light blue acccent-3" type="submit">Enviar
                  <i class="material-icons right">send</i>
                </button>
              </div>
            </div>
            </form>
          </div>
        </div>

    </div>


    <?$this->load->view('cart')?>

    <?$this->load->view('templates/sign_in')?>

    <?$this->load->view('templates/right_sidebar')?>

  </main>

  <!-- <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div> -->
  <?$this->load->view('templates/welcome_user')?>

  <?$this->load->view('pages/payment_form'); ?>
  <?$this->load->view('pages/signup_form')?>
  <?$this->load->view('pages/forgot_pass')?>
  <?$this->load->view('templates/info_bottom')?>


  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?$this->load->view('templates/footer_tag') ?>

<?=put_headers('js', $user_info)?>

</body>
</html>
