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
        </div>
      </div>
      <div class="parallax"><img src="<?=base_url('img/servicios.jpg')?>" alt="Servicios"></div>
    </div>


    <div class="section">
      <div class="container">
          <div class="row">
            <div class="col s12 m6 l6">
              <h4>Nuestros servicios</h4>
            </div>
            <div class="col s12 m6 l6">
              <p class="light">Adquiere servicios fotográficos adaptados a tu proyecto.</p>
            </div>
          </div>
      </div>
      <div class="container">
          <div class="row">
            <div class="col s12 m12 l6">
                  <?=material_servicio_card('brown-layout','img/drone.gif',
                                            'IMÁGENES Y VIDEOS CON DRONE','',
                                            'Con nuestro Panthom 4 DJ Pro obtienes las mejores imágenes creativas en 300dpi y videos hasta en 4K, utiliza esta maravillosa tecnología para mostrar espacios y lugares aéreos; también disponible para cubrir eventos sociales.',
                                            'Ir a Mi Consultor')?>
            </div>
            <div class="col s12 m12 l6">
              <?=material_servicio_card('brown-layout','img/foto-producto.jpg',
                                        'FOTOGRAFÍA DE PRODUCTO','',
                                        'Con nuestro personal calificado realizamos tus fotografías para tu sitio web y redes sociales, tu tienda online, tu catálogo de productos y material impreso.',
                                        'Ir a Mi Consultor')?>
            </div>
          </div>
          <div class="row">
            <div class="col s12 m12 l6">
                  <?=material_servicio_card('brown-layout','img/foto-produce.jpg',
                                            'PRODUCCIÓN FOTOGRÁFICA','',
                                            'Realizamos la pre producción, producción y pos producción en imágenes y videos, con locaciones perfectas, modelos profesionales y un gran equipo productor.',
                                            'Ir a Mi Consultor')?>
            </div>
            <div class="col s12 m12 l6">
                  <?=material_servicio_card('brown-layout','img/cubrimiento.jpg',
                                            'CUBRIMIENTO DEPORTIVO','',
                                            'Con más de 10 años de experiencia en cubrimiento deportivo en Torneos Mundiales de Fútbol y de Confederaciones, Juegos Olímpicos Centroamericanos y Suramericanos.
                                            Cubrimientos para comités deportivos, patrocinadores, de carácter publicitario, editorial y de marca.
                                            Conoce nuestro material fotográfico <a href="http://jammedia.mediafiler.net" target="_blank">AQUI.</a>',
                                            'Ir a Mi Consultor')?>
            </div>
          </div>
          <div class="row">
            <div class="col s12">
              <?=material_servicio_card('brown-layout','img/360.gif',
                                        'FOTOGRAFÍA EN 360<sup>o</sup>','',
                                        'Por fin llegó la manera de mostrar cada detalle de los productos que vendes con equipos inteligentes, podrás mostrar todos tus artículos en 360° con los detalles que desees resaltar.'
                                        .'Además, cuentas con innovadoras mesas giratorias que permitirán mostrar en tu tienda física tus productos en 360°.',
                                        '')?>
            </div>
          </div>
          <div class="row">
            <div class="col s12 center" style="margin:20px 0">
                    <h5>Quieres saber más acerca de nuestros servicios?</h5>
                    <p>Contacta tu consultor</p>
                    <a href="<?=base_url('main/consultor')?>" class="btn-large waves-effect waves-light blue accent-3">Mi Consultor</a>
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
  <?$this->load->view('templates/info_bottom')?>


  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?$this->load->view('templates/footer_tag') ?>

<?=put_headers('js', $user_info)?>

</body>
</html>
