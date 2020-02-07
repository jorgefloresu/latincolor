<?php $this->load->view('templates/header')?>

  <header>

      <?php $this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="consultor">

    <div class="container">

      <div class="row">
        <div class="col s12 m6 l6">
          <h4>Contáctanos</h4>
        </div>
        <div class="col s12 m6 l6">
          <p class="light">
            </p>
        </div>
      </div>

        <div class="row">
          <div class="col s12 m6">
            <div class="card">
              <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="<?php echo base_url('img/user-profile-bg.jpg')?>" alt="user background">
              </div>
              <div class="card-content">
                  <img src="<?php echo base_url('img/avatar.jpg')?>" alt="" class="circle responsive-img activator card-profile-image z-depth-1 hide-on-med-and-down">
                  <a class="btn-floating activator btn-move-up waves-effect waves-light pink accent-2 right">
                    <i class="material-icons white-text">account_circle</i>
                  </a>
                  <span class="card-title activator grey-text text-darken-4">Latin Color Images</span>
                  <p class="valign-wrapper"><i class="material-icons small cyan-text text-darken-2">room</i> Calle 169<sup>a</sup> # 56 - 57 Oficina 504</p>
                  <p class="valign-wrapper" style="padding:10px 0"><i class="material-icons small cyan-text text-darken-2">phone</i> +57 1 694 05 60</p>
                  <p class="valign-wrapper"><i class="material-icons small cyan-text text-darken-2">email</i> ventas@latincolorimages.com</p>
                  <p style="margin-top:10px"> Bogotá D.C., Colombia</p>
                  <p style="margin-top:34px"><b>Para ventas y soporte en Colombia y Latinoamérica</b></p>
                  <p class="valign-wrapper" style="padding:10px 0"><i class="material-icons small cyan-text text-darken-2">email</i> carolina@latincolorimages.com</p>
                  <p><a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta"
                    target="_blank" class="phone hide-on-small-only" style="padding-left: 30px;">
                    <img src="<?php echo base_url('img/whatsapp-30.png')?>" style="margin:0 -30px;position: absolute;"> +57 1 314
                    295 8463</a>
                  </p>
                  <p style="margin-top:34px"><b>Para ventas y soporte en Centroamérica</b></p>
                  <p class="valign-wrapper" style="padding:10px 0"><i class="material-icons small cyan-text text-darken-2">email</i> jorge@latincolorimages.com</p>
                  <p><a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta"
                      target="_blank" class="phone hide-on-small-only" style="padding-left: 30px;">
                      <img src="<?php echo base_url('img/whatsapp-30.png')?>" style="margin:0 -30px;position: absolute;"> +503 7920 2025</a>
                  </p>
              </div>
            </div>
          </div>
          <div class="col s12 m6">
            <ul class="collection">
              <li class="collection-item avatar">
                <i class="fab fa-skype fa-3x pink-text text-accent-2 brand-icon"></i>
                <span class="title">Skype</span>
                <p><a href="skype:latincolorimages?chat">latincolorimages</a>
                </p>
                <!-- <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a> -->
              </li>
              <li class="collection-item avatar">
                <i class="fab fa-facebook fa-3x pink-text text-accent-2 brand-icon"></i>
                <span class="title">Facebook</span>
                <p><a href="https://www.facebook.com/LatinColorImages/">LatinColorImages</a>
                </p>
                <!-- <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a> -->
              </li>
              <li class="collection-item avatar">
                <i class="fab fa-instagram fa-3x brand-icon pink-text text-accent-2"></i>
                <span class="title">Instagram</span>
                <p><a href="https://www.instagram.com/latincolorimages/?hl=es-la">latincolorimages</a> 
                </p>
                <!-- <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a> -->
              </li>
            </ul>
          </div>
        </div>

    </div>


    <?php $this->load->view('cart')?>

    <?php $this->load->view('templates/sign_in')?>

    <?php $this->load->view('templates/right_sidebar')?>

  </main>

  <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div>

  <?php $this->load->view('pages/payment_form'); ?>
  <?php $this->load->view('pages/signup_form')?>
  <?php $this->load->view('pages/forgot_pass')?>
  <?php $this->load->view('templates/info_bottom')?>


  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?php $this->load->view('templates/footer_tag') ?>

<?php echo put_headers('js', $user_info)?>

</body>
</html>
