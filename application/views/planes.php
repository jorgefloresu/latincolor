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
            <h5 class="header col s12 light">Representamos las mejores marcas a nivel mundial</h5>
          </div>
          <div class="row center">
            <a href="#planes-section" id="arma-plan-btn" class="btn-large waves-effect waves-light blue accent-3">Arma tu plan</a>
            <a href="#paquetes-promo" id="paquetes-btn" class="btn-large waves-effect waves-light blue accent-3">Escoge tu paquete</a>
          </div>
          <br><br>

        </div>
      </div>
      <div class="parallax"><img src="<?=base_url('img/planes.jpg')?>" alt="planes"></div>
    </div>


    <div class="container">
      <div id="planes-section" class="section">
        <div class="row">
          <div class="col s12 m6 l6">
            <h4>Escoge y arma tu plan</h4>
          </div>
          <div class="col s12 m6 l6">
            <p class="light">Selecciona los parámetros necesarios para obtener tu mejor plan (Estos planes incluyen: fotos, ilustraciones, vectores, íconos, infografías e imágenes en 3d).</p>
          </div>

        </div>
        <!-- <div class="row">          
          <ul id="tabs-swipe-demo" class="tabs tabs-transparent">
            <li class="tab col s3 m3 l3 offset-l3"><a class="grey-text text-darken-3 active" href="#planes-imagenes">Imágenes</a></li>
            <li class="tab col s3 m3 l3"><a class="grey-text text-darken-3" href="#planes-videos">Videos</a></li>
          </ul>
        </div> -->
        <div style='border:1px solid #CCC' class="row">
        <div id="planes-imagenes" class="col s12" style="background-color:#d6d6d6">

          <div class="row" style="margin-bottom:0">

            <?=material_plan_card("s12 m3 l3",
            [ 'MEDIO'     =>['image',
                             '',
                             '',
                             'medio', 'Medio'
                            ],
              'FRECUENCIA'=>['schedule',
                            'Disponemos de tanta variedad que vas a obtener muchas ventajas.',
                            '¿Con qué frecuencia deseas descargar imágenes?',
                            'frecuencia', 'Frecuencia'
                           ],
             'CANTIDAD'  =>['collections',
                            'Porque una sola imagen no siempre dice todo lo que deseas.',
                            '¿Qué cantidad de imágenes <span class="periodo"></span> necesitas?',
                            'cantidad', 'Cantidad'
                           ],
             'TIEMPO'    =>['timelapse',
                            'Manten productivo tu ingenio creativo por mucho más tiempo.',
                            '¿Por cuánto tiempo quieres usar la suscripción?',
                            'tiempo', 'Tiempo'
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
        <div class="col s12 m12 l12">
          <div class="planes-result container">
            <ul class='plan-option collapsible popout' data-collapsible='accordion'>
            </ul>
            <p class='center' style='padding-top:25px'>Tienes alguna inquietud sobre la licencia, contacta <a href="<?=base_url('main/consultor')?>">TU CONSULTOR</a></p>
          </div>
        </div>
      </div>
      </div>
      
      </div>
    </div>

    <div class="parallax-container valign-wrapper">
      <div class="section no-pad-bot">
        <div class="container">
          <h1 class="header center white-text text-lighten-2">Paquetes</h1>
          <div class="row center">
            <h5 class="header col s12 light">Las mejores marcas a un solo clic!</h5>
          </div>
        </div>
      </div>
      <div class="parallax"><img src="<?=base_url('img/paquetes.jpg')?>" alt="paquetes"></div>
    </div>

    <div class="container">
      <div id="paquetes-promo" class="section">
        <div class="row">
          <div class="col s12 m6 l6">
            <h4>Conoce nuestras opciones</h4>
          </div>
          <div class="col s12 m6 l6">
            <p class="light">Tenemos las mejores opciones en recursos gráficos para tu publicidad (Estos paquetes incluyen: fotos, ilustraciones, vectores, íconos, infografías e imágenes en 3d).</p>
          </div>

        </div>
        <div class="paquete-option row">
          <?=material_paquete_card('s12 m6 l3', 'indigo',
                                    'SMALL', 'US$', '59', 'Microempresarios',
                                    ['50 imágenes para descargar durante un mes', 'Todas las categorías en fotos, ilustraciones, vectores, iconos, infografías y 3D',
                                     'Tamaño aprox: 5000x3000 300 dpi, JPG y PSD',
                                     'Hasta 500.000 impresiones por fotografía'], 
                                     $plan['small'])?>

          <?=material_paquete_card('s12 m6 l3', 'grey',
                                    'MEDIUM', 'US$', '999', 'PYMES',
                                    ['500 imágenes mensuales', '6.000 imágenes al año', 'A sólo US$0.16 por foto',
                                     'Tamaño aprox: 5000x3000 300 dpi, JPG y PSD',
                                     'Hasta 300.000 impresiones por fotografía'],
                                     $plan['medium'])?>

          <?=material_paquete_card('s12 m6 l3', 'orange',
                                    'LARGE', 'US$', '1.299', 'Medianas',
                                    ['100 imágenes mensuales acumulativas', '1.200 imágenes al año',
                                     '10 Videos HD para descargar durante 1 año', 'Licencia para 10 usuarios',
                                     'Videos 1920x1080 @25FPS / MOV / 79.9MB / H.264'],
                                     $plan['large'])?>

          <?=material_paquete_card('s12 m6 l3', 'green',
                                    'X-LARGE', 'US$', '2.279', 'Plan PREMIUM',
                                     ['750 imágenes mensuales (sin limitación diaria ni mensual)',
                                      'Con integración en Adobe CC: PhotoShop, Illustrator, InDesing, Dimension PremierPro y Adobe Spark',
                                      'Licencia para 10 usuarios', 'Hasta 500.000 impresiones por fotografía'],
                                      $plan['xlarge'])?>
        </div>
        <div class="row">
          <div class="col s12">
            <!-- <div class="card-panel" style="background-color:#eee;"> -->
              <!-- <span>
              En todos los paquetes obtienes:
              </span> -->
              <ul>
              <li class="center" style="margin-bottom:10px">En todos los paquetes obtienes:</li>
              <li class="blue darken-4" style="height: 2px;"></li>
              <?=material_collection(['Imágenes a perpetuidad','Uso en medios digitales y redes sociales','Todo uso comercial, publicitario y editorial'])?>
              <li class="indigo" style="height: 2px;"></li>
              </ul>              
              <p class="center" style="font-size:12px">No es permitido usar para la venta de material merchandising ni templates</p>
              <p class="center">¨Si estos planes no aplican, no te preocupes! tienes más de 300 opciones con nuestros <a id="back-to-planes" href="#planes-section">Planes y Suscripciones</a>¨</p>
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
  <?$this->load->view('templates/info_bottom')?>


  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?$this->load->view('templates/footer_tag') ?>
<?=put_headers('js', $user_info)?>

</body>
</html>
