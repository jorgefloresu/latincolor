<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="planes">
    <div id="index-banner" class="parallax-container">
      <div class="section no-pad-bot">
        <div class="container">
          <br><br>
          <h1 class="header center white-text text-lighten-2">Planes y suscripciones</h1>
          <div class="row center">
            <h5 class="header col s12 light">Te ofrecemos las mejores marcas a nivel mundial</h5>
          </div>
          <div class="row center">
            <a href="#tabs-swipe-demo" id="arma-plan-btn" class="btn-large waves-effect waves-light blue accent-3">Arma tu plan</a>
            <a href="#paquetes-promo" id="paquetes-btn" class="btn-large waves-effect waves-light blue accent-3">Escoge tu paquete</a>
          </div>
          <br><br>

        </div>
      </div>
      <div class="parallax"><img src="<?=base_url('img/imagebkg_ciudad.jpg')?>" alt="Unsplashed background img 1"></div>
    </div>


    <div class="container">
      <div id="planes-section" class="section">
        <div class="row">
          <div class="col s12 m6 l6">
            <h4>Escoge y arma tu plan</h4>
          </div>
          <div class="col s12 m6 l6">
            <p class="light">Elige la característica que más se acerca a tu preferencia y obtendrás el plan que mejor se ajusta a tu necesidad.</p>
          </div>

        </div>
        <div class="row">
          <ul id="tabs-swipe-demo" class="tabs tabs-transparent">
            <li class="tab col s3 m3 l3 offset-l3"><a class="grey-text text-darken-3 active" href="#planes-imagenes">Imágenes</a></li>
            <li class="tab col s3 m3 l3"><a class="grey-text text-darken-3" href="#planes-videos">Videos</a></li>
          </ul>
        </div>
        <div style='border:1px solid #CCC' class="row">
        <div id="planes-imagenes" class="col s12" style="background-color:#d6d6d6">

          <div class="row" style="margin-bottom:0">

            <?=material_plan_card("s12 m4 l4",
            ['FRECUENCIA'=>['schedule',
                            'Disponemos de tanta variedad que vas a obtener muchas ventajas.',
                            '¿Con qué frecuencia deseas descargar imágenes?',
                            'frecuencia', 'Selecciona Frecuencia'
                           ],
             'CANTIDAD'  =>['collections',
                            'Porque una sola imagen no siempre dice todo lo que deseas.',
                            '¿Qué cantidad de imágenes <span class="periodo"></span> necesitas?',
                            'cantidad', 'Selecciona Cantidad'
                           ],
             'TIEMPO'    =>['timelapse',
                            'Manten productivo tu ingenio creativo por mucho más tiempo.',
                            '¿Por cuánto tiempo quieres usar la suscripción?',
                            'tiempo', 'Selecciona Tiempo'
                           ]
            ])?>

          </div>
      </div>

      <div class="row">
        <div class="col s12 m12 l12">
          <div class="resultados-text">
            <div class="result-icon"></div>
            <span class="resultados"><span style="font-weight:100">Tus resultados aparecerán aquí</span></span>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- <div class="col s2 m2 l2">
          <h5 class="plan-proveedor"></h5>
        </div> -->
        <div id="paquetes-promo"></div>
        <div class="col s12 m12 l12">
          <div class="planes-result container">
            <ul class='plan-option collapsible popout' data-collapsible='accordion'>
            </ul>
          </div>
        </div>
      </div>
      </div>
      <div id="planes-videos" class="col s12">
        <div class="row">
          <div class="col s12 m6 l3">
            <div class="plan-pref">
              <div class="icon-block">
                <h1 class="center brown-text"><i class="material-icons">flash_on</i></h1>
                <h5 class="center">Marca de los videos</h5>

                <p class="light">We did most of the heavy lifting for you to provide a default stylings that incorporate our custom components. Additionally, we refined animations and transitions to provide a smoother experience for developers.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>

    <div class="parallax-container valign-wrapper">
      <div class="section no-pad-bot">
        <div class="container">
          <h1 class="header center white-text text-lighten-2">Paquetes y promociones</h1>
          <div class="row center">
            <h5 class="header col s12 light">Te ofrecemos paquetes de fotos, ilutraciones y vectores</h5>
          </div>
        </div>
      </div>
      <div class="parallax"><img src="<?=base_url('img/imagebkg_barco.jpg')?>" alt="Unsplashed background img 2"></div>
    </div>

    <div class="container">
      <div class="section">
        <div class="row">
          <div class="col s12 m6 l6">
            <h4>Conoce nuestras opciones</h4>
          </div>
          <div class="col s12 m6 l6">
            <p class="light">Elige la característica que más se acerca a tu preferencia y obtendrás el plan que mejor se ajusta a tu necesidad.</p>
          </div>

        </div>
        <div class="row">
          <?=material_paquete_card('s12 m6 l3', 'light-blue',
                                    'PROMOCIONAL + COL', '$', '420', 'Royalty Free',
                                    ['50 fotos de paquete', '10 fotos colombianas',
                                     'Medios digitales', 'Editorial y revistas',
                                     'Uso no comercial'])?>

          <?=material_paquete_card('s12 m6 l3', 'red',
                                    'PAQUETE COLOMBIA', '$', '420', 'Royalty Free',
                                    ['50 fotos de paquete', '10 fotos colombianas',
                                     'Medios digitales', 'Editorial y revistas',
                                     'Uso no comercial'])?>

          <?=material_paquete_card('s12 m6 l3', 'deep-orange',
                                    'SOLO MOCKUPS', '$', '420', 'Royalty Free',
                                    ['50 fotos de paquete', '10 fotos colombianas',
                                     'Medios digitales', 'Editorial y revistas',
                                     'Uso no comercial'])?>

          <?=material_paquete_card('s12 m6 l3', 'teal',
                                    'PAQUETE PREMIUM', '$', '420', 'Royalty Free',
                                     ['50 fotos de paquete', '10 fotos colombianas',
                                      'Medios digitales', 'Editorial y revistas',
                                      'Uso no comercial'])?>
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
<?=put_headers('js', $user_info)?>

</body>
</html>
