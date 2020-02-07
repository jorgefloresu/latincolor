<div class="navbar-fixed">
  <nav class="nav-extended white">
    <div class="nav-wrapper top-content">
        <!-- <a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta" target="_blank" class="phone"><i class="fab fa-whatsapp fa-2x"></i> + (57) 314 295 8463</a> -->
        <a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta" alt="Contáctanos" target="_blank" class="phone grey-text text-darken-3 tooltipped" style="padding-left: 30px;" data-position="right" data-delay="50" data-tooltip="Envíanos tu mensaje">
          <img src="<?php echo base_url('img/whatsapp-30.png')?>" style="margin: 2px -35px;position: absolute;"> + (57) 314 295 8463</a>
        <!-- <div id="signuser" class="sesion-menu">
          <?//php if ($logged): ?>
            <a href="<?//=site_url('main/user')?>" class="sign-in modal-trigger grey-text text-darken-3">Hola, <?//=$user_data->first_name?></a>
            <span><i class="fas fa-user-circle fa-lg"></i></span>
            <a href="<?//=site_url('login/logout/noindex')?>" id="sign-out" class="grey-text text-darken-3">Cerrar sesión</a>
            <a href="#signup-page" id="signup-user" class="modal-trigger grey-text text-darken-3" style="display:none"><?//=_("Sign up")?></a>
          <?//php else: ?>
            <a href="#sign-in" class="sign-in modal-trigger grey-text text-darken-3"><?//=_("Login")?></a>
            <span><i class="fas fa-user-circle fa-lg grey-text text-darken-3"></i></span>
            <a href="<?//=site_url('login/logout/noindex')?>" id="sign-out" class="grey-text text-darken-3" style="display:none">Cerrar sesión</a>
            <a href="#signup-page" id="signup-user" class="modal-trigger grey-text text-darken-3"><?//=_("Sign up")?></a>
          <?//php endif; ?>
        </div> -->
        <ul class="right hide-on-small-only">
          <span class="left">Síguenos</span>
          <li><a href="#"><i class="fab fa-facebook fa-lg grey-text text-darken-3"></i></a></li>
          <li><a href="#"><i class="fab fa-twitter fa-lg grey-text text-darken-3"></i></a></li>
          <li><a href="#"><i class="fab fa-instagram fa-lg grey-text text-darken-3"></i></a></li>
        </ul>
        <span class="grey-text right"><?php $this->load->view('templates/voice_msgs')?></span>
    </div>
      <div class="nav-wrapper">
        <div class="row valign-wrapper" style="margin-bottom: 0px;height: 80px">
          <div class="col s2 m3 l3 valign-wrapper">
            <a href="<?php echo base_url()?>" alt="Latin Color Images - Banco de Imágenes" class="brand-logo" style="margin-top:12px"><img src="<?php echo $logo?>"/></a>
            <a href="#" data-activates="mobile-demo" class="button-collapse grey-text text-darken-3">
              <i class="material-icons">menu</i>
            </a>
          </div>
          <div class="col s8 m6 l3">
          </div>
          <div class="col s2 m3 l6">
          <ul id="nav-mobile" class="right">
            <!-- Dropdown Trigger -->
            <!-- <li style="width: 160px">
              <a class="dropdown-button dropdown-providers grey-text text-darken-3" href="" data-activates="search-in">Buscar en
                <i class="material-icons right">arrow_drop_down</i></a>
            </li> -->
            <div class="hide-on-med-and-down" style="position:absolute; left:270px; height:35px; line-height:35px; top:23px">

              <li><a href="<?php echo base_url('main/planes')?>" class="grey-text text-darken-3" style="font-size:12px">PLANES & SUSCRIPCIONES</a></li>
              <li><a href="<?php echo base_url('main/planes#paquetes-promo')?>" class="grey-text text-darken-3" style="font-size:12px">PAQUETES</a></li>
              <li><a href="<?php echo base_url('main/servicios')?>" class="grey-text text-darken-3" style="font-size:12px">SERVICIOS</a></li>
              <!-- Dropdown Trigger -->
              <li><a class="dropdown-button grey-text text-darken-3" href="#!" data-activates="opciones" style="font-size:12px">CONTÁCTANOS<i class="material-icons right" style="height:30px;line-height:30px;margin-left:0">arrow_drop_down</i></a></li>
              <ul id="opciones" class="dropdown-homeopt dropdown-content">
                <li><a href="<?php echo base_url('main/consultor')?>">Mi Consultor</a></li>
                <li><a href="<?php echo base_url('main/nosotros')?>">Quienes somos</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo base_url('main/contactanos')?>">Contáctanos</a></li>
              </ul>

            </div>

            <div class="hide-on-small-only" style="position:absolute; height:35px; line-height:35px; top:23px; right:0; margin-right:30px">
            <li>
              <a id="menuCart" href="#" class="menu-cart grey-text text-darken-3 tooltipped valign-wrapper" data-position="left" data-delay="50" data-tooltip="Lista vacía" style="border-radius: 50%;width: 50px;height: 50px;margin-top: -10px;">
                <i class="material-icons left" style="height:30px;line-height:30px;margin-left:-9px">shopping_cart</i>
                <label class="cart-count"></label>
              </a>
            </li>
            <!--<li><a id="login-menu" href="#sign-in" class="grey-text text-darken-3 modal-trigger">
              <i class="material-icons left">account_circle</i>Sign in
              </a></li>-->

            <li id="loguser" style="margin-right: -15px;">
              <ul id="user-dropdown" class="dropdown-user dropdown-content">
                  <!-- <li><a href="#!">Opciones</a></li> -->
                  <li><a href="<?php echo base_url('main/user')?>" data-activates="chat-out" class="chat-collapse">Mi Cuenta</a></li>
                  <li class="divider"></li>
                  <li><a id="sign-out" href="<?php echo base_url('login/logout')?>">Cerrar sesión</a></li>
              </ul>
              <?php if ($logged): ?>
                <a id="login-menu" href="#sign-out" class="login-menu grey-text text-darken-3 dropdown-button" 
                  style="text-transform:capitalze" data-activates="user-dropdown">
                  <i class="material-icons left hide-on-med-and-down" style="height:30px;line-height:30px">account_circle</i>
                  <i class="material-icons right" style="height:30px;line-height:30px">arrow_drop_down</i>
                  <?php echo $user_data->first_name; ?>
                </a>
              <?php else: ?>
                <a id="login-menu" href="#sign-in" class="login-menu grey-text text-darken-3 modal-trigger" style="font-size:12px;text-transform:uppercase">
                <i class="material-icons left hide-on-med-and-down" style="height:30px;line-height:30px">account_circle</i><?php echo _("Login")?>
                </a>
              <?php endif; ?>
            </li>
          </div>
          </ul>
          </div>
        </div>
        <ul class="side-nav" id="mobile-demo">
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
            <a id="sideCart" href="#" class="menu-cart grey-text text-darken-3 tooltipped" data-position="left" data-delay="50" data-tooltip="Lista vacía">
              <i class="material-icons left">shopping_cart</i>Cart
              <span class="new badge red right" data-badge-caption="items"></span>
              <!-- <label class="cart-count"></label> -->
            </a>
          </li>
          <li class="loguser">
            <?php if ($logged): ?>
              <ul class="collapsible collapsible-accordion">
              <li>
                <a class="login-menu grey-text text-darken-3 collapsible-header">
                  <i class="material-icons left">account_circle</i>
                  <?php echo $user_data->first_name; ?>
                </a>
                <div class="collapsible-body">
                  <ul>
                    <!-- <li><a href="#!">Opciones</a></li> -->
                    <li><a href="<?php echo base_url('main/user')?>" data-activates="chat-out" class="chat-collapse">Mi Cuenta</a></li>
                    <li><a href="<?php echo base_url('login/logout')?>">Sign out</a></li>
                  </ul>
                </div>
              </li>
            </ul>
            <?php else: ?>
              <a href="#sign-in" class="login-menu grey-text text-darken-3 modal-trigger">
              <i class="material-icons left" style="margin: 0 32px 0 0">account_circle</i><?php echo _("Login")?>
              </a>
            <?php endif; ?>
          </li>
        </ul>
        <!-- <ul id="search-in" class="dropdown-content">
          <li><a href="">Depositphoto</a></li>
          <li><a href="">Fotosearch</a></li>
          <li class="divider"></li>
          <li><a href="#!">three</a></li> -->
        </ul>
      </div>
      <div class="nav-content">
        <div class="row grey lighten-3" style="margin-bottom:0; height:50px">
          <div class="col l6 hide-on-med-and-down">
            <nav class="transparent" style="height:50px; box-shadow:none; text-transform:uppercase;line-height:50px">
                <ul id="nav-mobile" class="menu-medios left">
                  <li <?php echo ($medio=='Fotos'?'class="active"':'')?>><a href="#" id="photos" class="grey-text text-darken-3" data-option="Fotos">
                    <!-- <i class="material-icons left">crop_original</i>Fotografías</a> -->
                    <img src="<?php echo base_url('img/Fotos-25.png')?>" style="margin: -7px 10px -7px 0px;">Fotos</a>
                  </li>
                  <li <?php echo ($medio=='Vectores'?'class="active"':'')?>><a href="#" id="vectors" class="grey-text text-darken-3" data-option="Vectores">
                    <!-- <i class="material-icons left">transform</i>Vectores</a> -->
                    <img src="<?php echo base_url('img/Vectores-25.png')?>" style="margin: -7px 10px -7px 0px;">Vectores</a>
                  </li>
                  <li <?php echo ($medio=='Videos'?'class="active"':'')?>><a href="#" id="videos" class="grey-text text-darken-3" data-option="Videos">
                    <!-- <i class="material-icons left">ondemand_video</i>Videos</a> -->
                    <img src="<?php echo base_url('img/Videos-25.png')?>" style="margin: -7px 10px -7px 0px;">Videos</a>
                  </li>
                  <!-- <li <?//=($medio=='Audios'?'class="active"':'')?>><a href="#" class="grey-text text-darken-3" data-option="Audios">
                    <img src="<?//=base_url('img/Audios-25.png')?>" style="margin: -7px 10px -7px 0px;">Audios</a>
                  </li> -->
                </ul>
            </nav>
          </div>
          <div class="col s12 m12 l6 grey darken-2" style="height:50px">

          <div class="row header-search-wrapper" style="top:10px; padding-right:15px">
            <form action="<?php echo base_url('main/search').'/'.$page; ?>" id="search" class="col s12">
                <div class="row">

                <!-- <div class="col s2" style="padding-top: 8px;"> -->
                  <!-- <span>BUSCAR</span> -->
                <!-- </div> -->
                <div class="col s12" style="padding: 0 7px 0 0;">
                    <!-- <i class="material-icons">search</i> -->
                    <span class="hide-on-small-only">BUSCAR</span>
                    <div class="input-field inline">
                      <i class="material-icons icon-search">search</i>
                      <input type="text" id="keyword" name="keyword" placeholder="Digita aquí palabras claves en idioma inglés"
                        value="<?php echo $keyword?>" class="header-search-input z-depth-2 grey-text" />
                      <!-- <input type="hidden" id="provider" name="provider" value="$provider" /> -->
                      <a class='escoge-marca dropdown-button btn blue' href='#' data-activates='lista-marcas'
                        style="position: absolute;right: 0;top: 3px;height: 25px;padding: 0 10px 0 10px;line-height: 30px;display:none">
                        Buscar por ID
                      </a>

                      <!-- Dropdown Structure -->
                      <ul id='lista-marcas' class='dropdown-homeopt dropdown-content'>
                        <li><a class="busca-id" href="#!">Depositphoto</a></li>
                        <li><a class="busca-id" href="#!">Dreamstime</a></li>
                        <li><a class="busca-id" href="#!">Fotosearch</a></li>
                      </ul>
                      <i class="mic" id="start_button" onclick="startButton(event)" style="position:absolute;right:-6px;top:-2px">
                        <img alt="Start" id="start_img" src="//www.google.com/intl/en/chrome/assets/common/images/content/mic.gif">
                      </i>

                    </div>
                    <input type='hidden' id="orientacion" name='orientacion' value='<?php echo $orientacion?>' />
                    <input type='hidden' id="color" name='color' value='<?php echo $color?>' />
                    <input type='hidden' id="medio" name='medio' value='<?php echo $medio?>' />
                    <input type="hidden" id="range" name="range" value="<?php echo $range?>" />
                    <input type="hidden" id="filter" name="filter" value="" />
          </div>
        </div>
      </form>

          </div>
          </div>
        </div>
      </div>
      <!-- <div class="nav-content <?//=($tabs=='')?'hide':''?>">
        <ul class="tabs tabs-transparent">
          <li class="tab" style="width: 160px"><a href="#Economicas"
          class="Economicas grey-text text-darken-3 <?//=($provider=='Economicas')?'active':''?>" data-url="">Economicas</a></li>
          <li class="tab" style="width: 160px"><a href="#Premium"
          class="Premium grey-text text-darken-3 <?//=($provider=='Premium')?'active':''?>" data-url="">Premium</a></li>
        </ul>
      </div> -->
  </nav>
  <div class="load-progress progress" style="margin: 0; top:166px; position: fixed; display: none;">
      <div class="indeterminate" style="width: 70%"></div>
  </div>
</div>
