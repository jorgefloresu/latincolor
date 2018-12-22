<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>
      <div class="row" style="margin-top:120px;padding-left:13px;margin-bottom:10px;">
        <!-- <div class="col s2 valign-wrapper" style="height:30px;"> -->
          <!-- <span style="margin-left:-13px">Buscando...</span> -->
        <!-- </div> -->
        <div class="col s6 offset-s2 valign-wrapper totaln" style="height:30px;"></div>
        <!-- <div class="col s3 valign-wrapper" style="height:30px;">
          <span class="current-page" style="border: 1px solid #ccc;padding: 4px 10px;"></span>
        </div> -->
        <div class="col s4 right-align">
          <div class="paginator"></div>
        </div>
      </div>

  </header>

  <main class="search-result-background">
        <?$this->load->view('templates/result_panel')?>
        <div class="fixed-action-btn">
          <a class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">photo_filter</i></a>
        </div>

        <div class="result-control row" style="margin-top:85px;padding-left:13px;margin-bottom:10px;">
          <div class="col s6 offset-s2 valign-wrapper totaln">
          </div>
          <div class="col s4 right-align">
            <div class="paginator"></div>
          </div>
        </div>

        <?$this->load->view('preview')?>

        <?$this->load->view('templates/right_sidebar')?>

  </main>

  <?$this->load->view('cart')?>

  <?$this->load->view('templates/sign_in')?>

  <?$this->load->view('templates/welcome_user')?>

  <?$this->load->view('pages/payment_form'); ?>
  <?$this->load->view('pages/signup_form')?>
  <?$this->load->view('pages/forgot_pass')?>

  <!-- <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div> -->

  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?=put_headers('js', $user_info)?>



<script type="text/javascript">
      //subAccountForm.on("submit", {fillOut:Api.subaccountsFields}, Api.mainMethod);
      //subAccounts.on("change", Api.subAccountChange);

      //subscriptionsForm.on("submit", {fillOut:Api.subscriptionsFields}, Api.mainMethod);
// $(document).ready(function(){
//   Api.setup.init();
// })
</script>
</body>
</html>
