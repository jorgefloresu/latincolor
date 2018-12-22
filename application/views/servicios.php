<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="servicios">
    <div id="index-banner" class="parallax-container">
      <div class="section no-pad-bot">
        <div class="container">
          <br><br>
          <h1 class="header center white-text text-lighten-2">Servicios</h1>
          <div class="row center">
            <h5 class="header col s12 light">Te ofrecemos servicios a la medida de tus necesidades</h5>
          </div>
          <div class="row center">
            <a href="#" id="making-off-btn" class="btn-large waves-effect waves-light blue accent-3">Solicitar Making Off</a>
          </div>
        </div>
      </div>
      <div class="parallax"><img src="<?=base_url('img/imagebkg_aerial.jpg')?>" alt="Unsplashed background img 1"></div>
    </div>


    <div class="section">
    <div class="container">
        <div class="row">
          <div class="col s12 m6 l6">
            <h4>Nuestros servicios</h4>
          </div>
          <div class="col s12 m6 l6">
            <p class="light">Te ofrecemos servicios fotográficos adaptados a tu proyecto. Consulta todos nuestros servicios.</p>
          </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
          <div class="col s12 m4 l4">
            <?=material_servicio_card('gradient-layout','img/drone.jpg',
                                      'IMAGENES <br/> Y VIDEOS CON DRONE','',
                                      'Here is some more information about this product that is only revealed once clicked on.',
                                      'Consultar un asesor')?>
          </div>

          <div class="col s12 m8 l8">
            <div class="row" style="margin-bottom:0">
              <div class="col s12 m6 l6">
                <?=material_servicio_card('brown-layout','img/bailarina.jpg',
                                          'PRODUCE <br/> TUS PROPIAS <br/> FOTOGRAFIAS','medium-title',
                                          'Here is some more information about this product that is only revealed once clicked on.',
                                          'Consultar un asesor')?>
              </div>
              <div class="col s12 m6 l6">
                <?=material_servicio_card('grey-layout','img/sala.jpg',
                                          'FOTOGRAFIA <br/> DE PRODUCTO','medium-title',
                                          'Here is some more information about this product that is only revealed once clicked on.',
                                          'Consultar un asesor')?>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m12 l12">
                <?=material_servicio_card('brown-layout','img/360.jpg',
                                          'IMAGENES <br/> EN 360 GRADOS','medium-title',
                                          'Here is some more information about this product that is only revealed once clicked on.',
                                          'Consultar un asesor')?>
              </div>
            </div>
          </div>
        </div>
    </div>
    </div>



    <!-- <div class="parallax-container valign-wrapper">
      <div class="section no-pad-bot">
        <div class="container">
          <div class="row center">
            <h5 class="header col s12 light">A modern responsive front-end framework based on Material Design</h5>
          </div>
        </div>
      </div>
      <div class="parallax"><img src="<?=base_url('img/imagebkg_ciudad.jpg')?>" alt="Unsplashed background img 3"></div>
    </div> -->

    <?$this->load->view('cart')?>

    <?$this->load->view('templates/sign_in')?>

    <?$this->load->view('templates/right_sidebar')?>

  </main>

  <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div>
  <?$this->load->view('templates/welcome_user')?>

  <?$this->load->view('pages/payment_form'); ?>
  <?$this->load->view('pages/signup_form')?>
  <?$this->load->view('pages/forgot_pass')?>


  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?=put_headers('js', $user_info)?>

</body>
</html>
