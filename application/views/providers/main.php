
  <?=$header?>

  <main>

    <div id="tab-search" class="col s12">
      <?=$tabsearch?>
    </div>
    <div id="tab-media" class="col s12">
      <?=$tabmedia?>
    </div>
    <div id="tab-login" class="col s12">
      <?=$tablogin?>
    </div>
    <div id="tab-subaccounts" class="col s12">
      <?=$tabsubaccounts?>
    </div>
    <div id="tab-subscriptions" class="col s12">
      <?=$tabsubscriptions?>
    </div>
    <!-- Error Structure -->
    <div id="error" class="modal"></div>

  </main>

  <?=$footer?>

  </body>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/js/materialize.min.js"></script>
 -->
 <script src="<?php echo base_url('js/jquery-2.2.4.min.js');?>"></script>
 <script src="<?php echo base_url('materialize/0.98.1/js/materialize.min.js');?>"></script>
 <script src="<?php echo base_url('js/js.storage.min.js');?>"></script>
 <script src="<?php echo base_url('js/templates.js');?>"></script>
 <script src="<?php echo base_url('js/api.functions.js');?>"></script>
 <script src="<?php echo base_url('js/shop.functions.js');?>"></script>
 <script type="text/javascript">

      $(document).ready(function(){
          $(".button-collapse").sideNav();
          $("select").material_select();
          $('.modal').modal();
      });

      /**
      Defina el contenido de las variables por el id o class del objeto:
      p.e.: Api.setup.init({searchForm: $("#id"), paginator: $(".class")})
      **/
      Api.setup.init();
      var shop = new $.Shop( "#cart" ); // object's instance

      // Define todas los forms usados en todos los tabs
      var searchForm = Api.setup.config.searchForm;
      var mediaForm = Api.setup.config.mediaForm;
      var loginForm = Api.setup.config.loginForm;
      var subAccountForm = Api.setup.config.subAccountForm;
      var subscriptionsForm = Api.setup.config.subscriptionsForm;

      // Select de subaccounts y subscriptions
      var mediaSizes = Api.setup.config.mediaSizes;
      var subAccounts = Api.setup.config.subAccounts;
      var subsPeriod = Api.setup.config.subsPeriod;
      var subsCount = Api.setup.config.subsCount;

      var saveToCart = Api.setup.config.saveToCart;

      // Define el contenedor del paginador que se usa para las busquedas
      var paginator = Api.setup.config.paginator;

      // Solicita ejecutar el search al servidor indicando sus contenedores
      searchForm.on("submit", {fillOut:Api.searchFields}, Api.mainMethod);
      paginator.on("click", "a", {fillOut:Api.searchFields}, Api.mainMethod);

      // Solicita ejecutar el getMediaData al servidor indicando sus contenedores
      mediaForm.on("submit", {fillOut:Api.mediaFields}, Api.mainMethod);
      mediaSizes.on("change", Api.mediaSizesChange);

      loginForm.on("submit", Api.loginMethod);

      subAccountForm.on("submit", {fillOut:Api.subaccountsFields}, Api.mainMethod);
      subAccounts.on("change", Api.subAccountChange);

      subscriptionsForm.on("submit", {fillOut:Api.subscriptionsFields}, Api.mainMethod);

      saveToCart.on("click", function(){
        shop.addToCart($(this).parent());
      });


</script>
</html>
