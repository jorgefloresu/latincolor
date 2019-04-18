<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>

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
          <div class="col s6">
            <div class="card">
              <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="<?=base_url('img/user-profile-bg.jpg')?>" alt="user background">
              </div>
              <div class="card-content">
                  <img src="<?=base_url('img/avatar.jpg')?>" alt="" class="circle responsive-img activator card-profile-image z-depth-1">
                  <a class="btn-floating activator btn-move-up waves-effect waves-light pink accent-2 right">
                    <i class="material-icons white-text">account_circle</i>
                  </a>
                  <span class="card-title activator grey-text text-darken-4">Latin Color Images</span>
                  <p><i class="material-icons small cyan-text text-darken-2">room</i> Calle 169 # 45A No. 66</p>
                  <p style="margin-left:34px"> Bogotá D.C., Colombia</p>
                  <p><i class="material-icons small cyan-text text-darken-2">phone</i> +57 (314) 295 8463</p>
                  <p><i class="material-icons small cyan-text text-darken-2">email</i> ventas@latincolorimages.com</p>
              </div>
            </div>
          </div>
          <div class="col s6">
            <ul class="collection">
              <li class="collection-item avatar">
                <i class="fab fa-skype fa-3x pink-text text-accent-2 brand-icon"></i>
                <span class="title">Title</span>
                <p>First Line <br>
                   Second Line
                </p>
                <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
              </li>
              <li class="collection-item avatar">
                <i class="fab fa-facebook fa-3x pink-text text-accent-2 brand-icon"></i>
                <span class="title">Title</span>
                <p>First Line <br>
                   Second Line
                </p>
                <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
              </li>
              <li class="collection-item avatar">
                <i class="fab fa-twitter fa-3x brand-icon pink-text text-accent-2"></i>
                <span class="title">Title</span>
                <p>First Line <br>
                   Second Line
                </p>
                <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
              </li>
              <li class="collection-item avatar">
                <i class="fab fa-instagram fa-3x brand-icon pink-text text-accent-2"></i>
                <span class="title">Title</span>
                <p>First Line <br>
                   Second Line
                </p>
                <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
              </li>
            </ul>
          </div>
        </div>

    </div>


    <?$this->load->view('cart')?>

    <?$this->load->view('templates/sign_in')?>

    <?$this->load->view('templates/right_sidebar')?>

  </main>

  <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div>

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
