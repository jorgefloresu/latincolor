<?php $this->load->view('templates/header')?>

  <header>

      <?php $this->load->view('templates/ctrl/ctrl-nav')?>
      <div class="row" style="margin-top:120px;padding-left:13px;margin-bottom:0px;">
        <!-- <div class="col s2 valign-wrapper" style="height:30px;"> -->
          <!-- <span style="margin-left:-13px">Buscando...</span> -->
        <!-- </div> -->
        <div class="col l2 hide-on-med-and-down offset"></div>
        <div class="col m6 l6 valign-wrapper totaln hide-on-small-only" style="height:30px;"></div>
        <!-- <div class="col s3 valign-wrapper" style="height:30px;">
          <span class="current-page" style="border: 1px solid #ccc;padding: 4px 10px;"></span>
        </div> -->
        <div class="col s12 m6 l4 right-align pages">
          <div class="totaln hide-on-med-and-up"></div>
          <div class="paginator"></div>
        </div>
      </div>

  </header>

  <main class="search-result-background">
        <?php $this->load->view('templates/result_panel')?>
        <div class="fixed-action-btn fixed-btn-result">
          <a class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">fullscreen</i></a>
        </div>

        <div class="result-control row" style="margin-top:85px;padding-left:13px;margin-bottom:10px;">
          <div class="col l2 hide-on-med-and-down offset"></div>
          <div class="col s5 m7 l6 valign-wrapper totaln">
          </div>
          <div class="col s7 m5 l4 right-align pages">
            <div class="paginator"></div>
          </div>
        </div>

        <?php $this->load->view('preview')?>

        <?php $this->load->view('templates/right_sidebar')?>

  </main>

  <?php $this->load->view('cart')?>

  <?php $this->load->view('templates/sign_in')?>

  <?php $this->load->view('templates/welcome_user')?>

  <?php $this->load->view('pages/payment_form'); ?>
  <?php $this->load->view('pages/signup_form')?>
  <?php $this->load->view('pages/forgot_pass')?>
  <?php $this->load->view('templates/info_bottom')?>


  <!-- <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div> -->

  <!-- Error Structure -->
<div id="error" class="modal"></div>

<?php echo put_headers('js', $user_info)?>

</body>
</html>
