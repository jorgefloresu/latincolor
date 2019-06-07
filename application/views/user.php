<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="user-background">
      <div id="profile-page-header" class="card">
          <div class="card-image waves-effect waves-block waves-light">
              <img class="activator" src="<?=base_url('img/user-profile-bg.jpg')?>" alt="user background">
          </div>
          <figure class="card-profile-image hide-on-small-only">
              <img src="<?=base_url('img/avatar.jpg')?>" alt="profile image" class="circle z-depth-2 responsive-img activator">
          </figure>
          <div class="card-content">
            <div class="row">
              <div class="col m2 hide-on-small-only"></div>
              <div class="col s4 m3">
                  <h4 class="card-title grey-text text-darken-4"><?=$user_data->fname?></h4>
                  <p class="medium-small grey-text"><?=$logged?></p>
              </div>
              <div class="col s4 m2 center-align">
                  <h4 class="card-title grey-text text-darken-4">$<?=$sum_downloads?></h4>
                  <p class="medium-small grey-text">Imágenes compradas</p>
              </div>
              <div class="col s4 m2 center-align">
                  <h4 class="card-title grey-text text-darken-4">$<?=$sum_planes?></h4>
                  <p class="medium-small grey-text">Planes comprados</p>
              </div>
              <!-- <div class="col s2 center-align">
                  <h4 class="card-title grey-text text-darken-4">$ 1,253,000</h4>
                  <p class="medium-small grey-text">Business Profit</p>
              </div>
              <div class="col s1 right-align" style="margin-top:-13px">
                <a class="btn-floating activator waves-effect waves-light darken-2 right">
                    <i class="material-icons">perm_identity</i>
                </a>
              </div> -->
            </div>
          </div>
          <div class="card-reveal">
              <p>
                <span class="card-title grey-text text-darken-4"><?=$user_data->fname?><i class="material-icons right">close</i></span>
                <span><i class="material-icons cyan-text text-darken-2">perm_identity</i> Project Manager</span>
              </p>

              <p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>

              <p><i class="material-icons cyan-text text-darken-2">perm_phone_msg</i> +1 (612) 222 8989</p>
              <p><i class="material-icons cyan-text text-darken-2">email</i><?=$user_data->email_address?></p>
              <p><i class="material-icons cyan-text text-darken-2">cake</i> 18th June 1990</p>
              <p><i class="material-icons cyan-text text-darken-2">airplanemode_on</i> BAR - AUS</p>
          </div>
      </div>
      <div id="profile-page-contents" class="row">
        <div class="col s12 m5 l6">
          <ul class="collection white">
            <li class="collection-item" style="border-bottom:none">
              <?=material_tabs("s3", ['tab-name'=>'Nombre',
                                      'tab-addr'=>'Dirección',
                                      'tab-pass'=>'Password'])?>
            </li>
          <form action="<?=base_url('login/set_userpref')?>" class="set-userpref" validate>
            <div id="tab-name" class="col s12">
              <div class="row tab-user">
                <?=material_input("s6", "first_name", "prefs[first_name]", "Nombre", TRUE)?>
                <?=material_input("s6", "last_name", "prefs[last_name]", "Apellido", TRUE)?>
                <?=material_input("s6", "empresa", "prefs[empresa]", "Empresa", TRUE)?>
                <?=material_input("s6", "nit", "prefs[nit]", "NIT", TRUE)?>
              </div>
            </div>
            <div id="tab-addr" class="col s12">
                <div class="row tab-user">
                  <?=material_select("s6", "country", "prefs[country]", "Selecciona el país")?>
                  <?=material_select("s6", "region", "prefs[state]", "Selecciona el departamento")?>
                  <?=material_select("s6", "city", "prefs[city]", "Selecciona la ciudad")?>
                  <?=material_input("s6", "address", "prefs[address]", "Calle, Avenida, Apto, Número", TRUE)?>
                  <?=material_input("s6", "zip", "prefs[zip]", "Código ZIP", TRUE)?>
                  <?=material_input("s6", "phone", "prefs[phone]", "Teléfono", TRUE)?>
                </div>
            </div>
            <div id="tab-pass" class="col s12">
              <div class="row tab-user">
                <?=material_password("s5", "password", "prefs[password]", "Nuevo password")?>
              </div>
            </div>
            <?=material_submit("s12")?>

        </form>
        </ul>
        </div>
        <div class="col s12 m7 l6">
          <ul class="collection">
            <li class="collection-item avatar">
              <i class="material-icons circle light-blue">folder</i>
              <span class="collection-header">Tus compras</span>
              <p>Lista de imágenes y planes comprados</p>
              <a href="#" class="secondary-content">
                <i class="material-icons">grade</i>
              </a>
            </li>
            <li class="collection-item" style="padding:0">
            <ul class="collapsible" style="border:0;margin:0">
              <li>
                <div class="collapsible-header valign-wrapper <?=$download_active?>">Imágenes</div>
                <div class="collapsible-body" style="padding:0">
                  <ul class="collection">
                    <div class="list-scroll">

                      <? foreach ($download_list as $item): ?>
                        <li class="collection-item">
                          <div class="row" style="margin-bottom:0">
                              <div class="image-item col s2"
                                style="background-image: url(<?=$item->img_url?>);">
                              </div>
                              <div class="col s2">
                                <? if ($item->img_provider == 'Depositphoto'): ?>
                                  <a href='' class="re-download btn blue waves-effect waves-grey" style="padding:0 10px" 
                                      data-url="<?=base_url('main/reDownload')?>" data-provider="<?=$item->img_provider?>"
                                      data-lid="<?=$item->license_id?>" data-id="<?=$item->img_code?>" data-type="<?=$item->type?>">
                                    <i class="material-icons">file_download</i>
                                  </a>
                                <? else: ?>
                                  <a href='#info' class="btn blue waves-effect waves-grey modal-trigger" style="padding:0 10px">
                                    <i class="material-icons">info_outline</i>
                                  </a>
                                <? endif ?>
                              </div>
                              <div class="col s3">
                                <p class="collections-title"><?=$item->img_code?></p>
                                <p class="collections-content"><?=$item->img_provider?></p>
                              </div>
                              <div class="col s3">
                                <? $dt = new DateTime($item->date) ?>
                                <p class="collections-title"><?=$dt->format('m/d/Y')?></p>
                                <p class="collections-content"><?=$dt->format('h:i:s A')?></p>
                              </div>
                              <div class="col s2">
                                <span class="right">$<?=number_format($item->img_price,2)?></span>
                              </div>
                          </div>
                          <div class="msg-redownload row" style="margin-bottom:0;display:none">
                            <div class="col s12">
                              <p class="collections-content red-text">Error al obtener el enlace de la descarga. Por favor contacta a <a href="<?=base_url('main/consultor')?>">Tu Consultor.</a></p>
                            </div>
                          </div>
                        </li>

                      <? endforeach ?>

                    </div>
                  </ul>
                </div>
              </li>

              <li>
                <div class="collapsible-header valign-wrapper <?=$planes_active?>">Planes</div>
                <div class="collapsible-body" style="padding:0">
                  <ul class="collection planes-collection">
                    <div class="list-scroll">
                      <? foreach ($planes_list as $item): ?>
                        <li class="collection-item">
                          <div class="row" style="margin-bottom:0">
                            <div class="col s3">Compra: <?=$item->img_code?></div>
                            <div class="col s4">
                              <span class="right"><?=$item->session_date?></span>
                            </div>
                            <div class="col s3">
                              <span class="right">$<?=$item->valor?></span>
                            </div>
                            <div class="col s2 right-align">
                              <a href="#!" class="plan-view" data-subaccountid="<?=$user_data->deposit_userid?>" data-plan="<?=$item->id?>"><i class="material-icons">info</i></a>
                            </div>
                          </div>
                        </li>
                      <? endforeach ?>
                      <!-- <li class="collection-item">
                        <div class="row" style="margin-bottom:0">
                          <div class="col s12">
                            <span class="right"><?//=$pags?></span>
                          </div>
                        </div>
                      </li> -->
                    </div>
                  </ul>
                </div>
              </li>

          </ul>
        </li>
        </ul>
        </div>
      </div>
  </main>

  <?$this->load->view('cart')?>

  <?$this->load->view('pages/payment_form'); ?>
  <?$this->load->view('templates/planes_view'); ?>
  <?$this->load->view('templates/info_view'); ?>
  <?$this->load->view('templates/info_bottom'); ?>

  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?=put_headers('js', $user_info)?>

</body>
</html>
