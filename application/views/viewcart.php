<?$this->load->view('templates/header')?>

  <header style="margin-bottom: 15px;">
    <nav class="blue darken-3">
      <div class="nav-wrapper">
        <a href="#" class="brand-logo"><?echo _("Cart details")?></a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a href="">Sass</a></li>
          <li><a href="">Components</a></li>
          <li><a href="">JavaScript</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main>

    <div class="cart">
<!--       <input type="hidden" name="subaccountid" value="">
     subaccount:  -->
      <form id="shopping-cart" action="<?php echo site_url('main/viewcart'); ?>" method="post">
        <table class="shopping-cart">
          <thead>
            <tr>
              <th style="width: 120px">Media</th><th>Media ID</th><th>Description</th><th>Media Size</th>
              <th>Media License</th><th>Media Price</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <p id="sub-total">
          <strong>Sub Total</strong>: <span id="stotal"></span>
        </p>
        <input type="submit" name="update" id="update-cart" class="btn" value="Update Cart" />
        <input type="submit" name="delete" id="empty-cart" class="btn" value="Empty Cart" />
        <a href="#downloading" class="btn">Go To Checkout</a>
        <a href="" class="btn">Return</a>
      </form>
    </div>
    <!-- Error Structure -->
    <div id="error" class="modal"></div> 

    <div id="downloading" class="modal bottom-sheet">
        <div class="modal-content">
          <p>Descargando...</p>
        </div>
    </div>

  <?$this->load->view('pages/payment_form'); ?>
 
  </main>

<?$this->load->view('templates/footer')?>

<script type="text/javascript" src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script src="<?php echo base_url('js/pay.functions.js');?>"></script>
<script src="<?php echo base_url('js/shop.functions.js');?>"></script>


<script type="text/javascript">

  
  Api.setup.init({cart: $('.cart')});
  Pay.setup.init();

  var cart = Api.setup.config.cart;
  var shop = new $.Shop( cart ); // object's instance

  cart.on('click', '.download a', function(event){
    event.preventDefault();
    shop.actionItem(this);
  });

  Pay.setup.$CCWindow.modal({
    complete: function(modal) {
      if (Pay.setup.$message.html() == 'APROBADA')
        shop.setMessage('Descargando...');
    }
  });
$(function(){
  $('#downloading').modal();

});


</script>

</body>
</html>