<?php $this->load->view('templates/header')?>
<header>
  <nav class="top-content white-text z-depth-0" role="navigation">
    <div class="nav-wrapper">
      <div class="row">
        <div class="col m3">
          <!-- <a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta" target="_blank" class="phone"><i class="fab fa-whatsapp fa-2x"></i> + (57) 314 295 8463</a> -->
          <a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta"
            alt="Contáctanos" target="_blank" class="phone hide-on-small-only" style="padding-left: 30px;">
            <img src="<?php echo base_url('img/whatsapp-30.png')?>" style="margin: 10px -35px;position: absolute;"> + (57) 314
            295 8463</a>
        </div>
        <div class="col s12 m6 center">
          <div id="signuser" class="sesion-menu">
            <?php if ($logged): ?>
            <a href="<?php echo base_url('main/user')?>" class="sign-in modal-trigger">Hola,
              <?php echo $user_data->first_name?></a>
            <span><i class="fas fa-user-circle fa-lg"></i></span>
            <a href="<?php echo base_url('login/logout/noindex')?>" id="sign-out">Cerrar sesión</a>
            <a href="#signup-page" id="signup-user" class="modal-trigger" style="display:none">
              <?php echo _("Sign up")?></a>
            <?php else: ?>
            <a href="#sign-in" class="sign-in modal-trigger">
              <?php echo _("Login")?></a>
            <span><i class="fas fa-user-circle fa-lg"></i></span>
            <a href="<?php echo base_url('login/logout/noindex')?>" id="sign-out" style="display:none">Cerrar sesión</a>
            <a href="#signup-page" id="signup-user" class="modal-trigger">
              <?php echo _("Sign up")?></a>
            <?php endif; ?>
          </div>
        </div>
        <div class="col m3">
          <ul class="right hide-on-med-and-down">
            <span class="left">Síguenos</span>
            <li><a href="#"><i class="fab fa-facebook fa-lg"></i></a></li>
            <li><a href="#"><i class="fab fa-twitter fa-lg"></i></a></li>
            <li><a href="#"><i class="fab fa-instagram fa-lg"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <nav class="white-text z-depth-0 main-menu" role="navigation">
    <div class="nav-wrapper" style="top:5px">
      <div class="row">
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
        <div class="col s10">
          <a id="logo-container" alt="Latin Color Images - Banco de Imágenes" href="#" class="brand-logo" style="top:-5px"><img src="<?php echo base_url('img/LCI_LOGO_BLANCO_T.png')?>"></a>
          <ul class="hide-on-med-and-down" style="padding-left:260px">
            <li><a href="<?php echo base_url('main/planes')?>">PLANES & SUSCRIPCIONES</a></li>
            <li><a href="<?php echo base_url('main/planes#paquetes-promo')?>">PAQUETES</a></li>
            <li><a href="<?php echo base_url('main/servicios')?>">SERVICIOS</a></li>
            <!-- Dropdown Trigger -->
            <li><a class="dropdown-button" href="#!" data-activates="opciones">CONTÁCTANOS<i class="material-icons right">arrow_drop_down</i></a></li>
            <ul id="opciones" class="dropdown-homeopt dropdown-content">
              <li><a href="<?php echo base_url('main/consultor')?>">Mi Consultor</a></li>
              <li><a href="<?php echo base_url('main/nosotros')?>">Quienes somos</a></li>
              <li class="divider"></li>
              <li><a href="<?php echo base_url('main/contactanos')?>">Contáctanos</a></li>
            </ul>
          </ul>
        </div>
        <div class="col s2">
          <ul class="right hide-on-med-and-down">
            <li>
              <a id="menuCart" href="#" class="menu-cart white-text tooltipped" data-position="bottom" data-delay="50"
                data-tooltip="Lista vacía">
                <i class="material-icons left">shopping_cart</i>
                <label class="cart-count"></label>
              </a>
            </li>
          </ul>
          <!-- <ul id="nav-mobile" class="side-nav">
          <li><a href="#" class="waves-effect">Planes & Suscripciones</a></li>
          <li><a href="#" class="waves-effect">Paquetes</a></li>
          <li><a href="#" class="waves-effect">Servicios</a></li>
          <li><a class="dropdown-button" href="#!" data-activates="slide-out">Opciones<i class="material-icons right">arrow_drop_down</i></a></li>
        </ul> -->
        <ul id="slide-out" class="left-nav side-nav">
            <li>
              <div class="user-view">
                <!-- <div class="background">
                  <img src="">
                </div>
                <a href="#!user"><img class="circle" src="./img/lcilogo.png"></a> -->
                <a href="#!name"><span class="white-text name">
                    <?php echo $user_data->fname?></span></a>
                <a href="#!email"><span class="white-text email">
                    <?php echo $user_data->email_address?></span></a>
              </div>
            </li>
            <li><a href="<?php echo base_url('main/planes')?>">Planes & Suscripciones</a></li>
            <li><a href="<?php echo base_url('main/planes#paquetes-promo')?>">Paquetes</a></li>
            <li><a href="<?php echo base_url('main/servicios')?>">Servicios</a></li>
            <li>
              <ul class="collapsible collapsible-accordion">
                <li>
                  <a class="collapsible-header">Opciones</a>
                  <div class="collapsible-body">
                    <ul>
                      <li><a href="<?php echo base_url('main/consultor')?>">Mi Consultor</a></li>
                      <li><a href="<?php echo base_url('main/nosotros')?>">Quienes somos</a></li>
                      <li><a href="<?php echo base_url('main/contactanos')?>">Contáctanos</a></li>
                    </ul>
                  </div>
                </li>
              </ul>
            </li>
            <li><a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta"
              target="_blank" class="phone">Número de Contacto</a>
            </li>
            <li>
              <a id="sideCart" href="#" class="menu-cart tooltipped" data-position="left" data-delay="50"
                data-tooltip="Lista vacía">
                <i class="material-icons left">shopping_cart</i>Carrito de Compras
                <span class="new badge red right" data-badge-caption="items"></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>
<main class="home valign-wrapper">
  <div id="index-banner" class="parallax-container">
    <!-- <div class="parallax"><img src="background1.jpg" alt="Unsplashed background img 1"></div> -->
    <div class="parallax">
      <video id="hero-vid" autoplay loop muted playsinline>
        <source src="./img/home.webm" type="video/webm">
        <source src="./img/home.mp4" type="video/mp4">
      </video>
    </div>

    <div class="section no-pad-bot">
      <div class="container">
        <h2 class="header center white-text">Fotografías en alta calidad <br /> para diseño publicitario</h2>
        <div class="row center">
          <h5 class="header col s12 light">Descarga imágenes para tus diseños con licencias completas de uso</h5>
        </div>
        <!-- <div class="row center">
        <a href="http://materializecss.com/getting-started.html" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Get Started</a>
      </div>
      <br><br> -->
        <div class="caption center-align">
          <nav id="slide-search-bar">
            <?php echo form_open(base_url().'main/search/1', 'id="home-search" method="get"')?>
            <div class="search-wrapper card">
              <div class="input-field">
                <input id="search" type="search" name="keyword" placeholder="Digita palabras claves en idioma inglés"
                  required>
                  <a class='escoge-marca dropdown-button btn blue' href='#' data-activates='lista-marcas'
                    style="position: absolute;right: 77px;top: 9px;padding: 0 10px 0 10px;line-height: 40px;display:none">
                    Buscar por ID
                  </a>

                  <!-- Dropdown Structure -->
                  <ul id='lista-marcas' class='dropdown-homeopt dropdown-content'>
                    <li><a class="busca-id" href="#!">Depositphoto</a></li>
                    <li><a class="busca-id" href="#!">Dreamstime</a></li>
                    <li><a class="busca-id" href="#!">Fotosearch</a></li>
                  </ul>
                    
                  <i class="material-icons" id="start_button" onclick="startButton(event)">
                    <img alt="Start" id="start_img" src="//www.google.com/intl/en/chrome/assets/common/images/content/mic.gif">
                  </i>

                <i class="material-icons icon-search red darken-4">search</i>
                <input type='hidden' id="medio" name='medio' />
                <input type='hidden' id="provider" name='provider' />
              </div>
            </div>
            <div class="buttons-filters">
              <a id="photos" class="btn-flat btn-fotos"><i class="material-icons icon-options left">check</i>Fotos</a>
              <a id="vectors" class="btn-flat btn-vectores">Vectores</a>
              <a id="videos" class="btn-flat btn-videos">Videos</a>
              <!-- <a class="btn-flat btn-audios">Audios</a> -->
            </div>

            <?$this->load->view('templates/voice_msgs')?>

            <?php echo form_close()?>
          </nav>
        </div>
      </div>
    </div>
</main>

<?php $this->load->view('cart')?>
<?php $this->load->view('templates/welcome_user')?>
<?php $this->load->view('pages/payment_form'); ?>

<?php $this->load->view('templates/sign_in')?>
<?php $this->load->view('pages/signup_form')?>
<?php $this->load->view('pages/forgot_pass')?>
<?php $this->load->view('templates/info_bottom')?>

<footer class="home-page-footer page-footer white hide-on-small-only">
  <div class="footer-copyright">
    <div class="footer-text grey-text text-lighten-3">
      <div class="row no-margin-bottom">
        <div class="col s12 m6 l6">
          &reg; 2019 Latin Color Images
          <a class="grey-text text-lighten-3" href="<?php echo base_url('main/privacidad')?>">Politicas de privacidad</a>
          <a class="grey-text text-lighten-3" href="<?php echo base_url('main/condiciones')?>">Términos de la licencia de uso <span id="wt"></span></a>
        </div>
        <div class="col s12 m6 l6">
          <div class="right"><a class="grey-text text-lighten-3" href="#">Idioma <i class="fas fa-globe fa-lg">globe</i></a></div>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- Error Structure -->
<div id="error" class="modal"></div>
<?php echo put_headers('js', $user_info)?>


</body>

</html>