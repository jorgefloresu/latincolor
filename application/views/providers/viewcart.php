<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/css/materialize.min.css">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 -->
    <link rel="stylesheet" href="<?php echo base_url('materialize/0.98.1/css/materialize.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('materialize/css/providers.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('materialize/material-icons/iconfont/material-icons.css');?>">
    <title>Get Images from API</title>

  </head>
  <body>

  <header style="margin-bottom: 15px;">
    <nav class="blue darken-3">
      <div class="nav-wrapper">
        <a href="#" class="brand-logo">Cart details</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a href="sass.html">Sass</a></li>
          <li><a href="badges.html">Components</a></li>
          <li><a href="collapsible.html">JavaScript</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main>

    <div class="cart">
      <input type="hidden" name="subaccountid" value="<?=$subaccountid?>">
      subaccount: <?=$subaccountid?>
      <form id="shopping-cart" action="<?php echo site_url('deposit/viewcart'); ?>" method="post">
        <table class="shopping-cart">
          <thead>
          <tr>
            <th>Media ID</th>
            <th>Description</th>
            <th>Media Size</th>
            <th>Media License</th>
            <th>Media Price</th>
          </tr>
          </thead>
          <tbody>
          
          </tbody>
        </table>
        <p id="sub-total">
          <strong>Sub Total</strong>: <span id="stotal"></span>
        </p>
        <input type="submit" name="update" id="update-cart" class="btn" value="Update Cart" />
        <input type="submit" name="delete" id="empty-cart" class="btn" value="Empty Cart" />
        <a href="index.html" class="btn">Continue Shopping</a>
        <a href="checkout.html" class="btn">Go To Checkout</a>
      </form>
    </div>
    <!-- Error Structure -->
    <div id="error" class="modal"></div> 

  </main>

  <?=$footer?>

  </body>

 <script src="<?php echo base_url('js/jquery-2.2.4.min.js');?>"></script>
 <script src="<?php echo base_url('materialize/0.98.1/js/materialize.min.js');?>"></script>
 <script src="<?php echo base_url('js/js.storage.min.js');?>"></script>
 <script src="<?php echo base_url('js/api.functions.js');?>"></script>
 <script type="text/javascript">

  $(document).ready(function(){

    Api.setup.init();
    //Api.cartFields(storage);
    var shop = new $.Shop( ".cart" ); // object's instance

  });

</script>
</html>