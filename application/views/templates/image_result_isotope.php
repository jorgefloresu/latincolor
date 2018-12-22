
<header id="header" class="page-topbar">

  <div class="navbar-fixed">

    <?=$result_topbar?> 
    <?=$result_searchbar?>
    <?=$result_optionsbar?>

    <div class="progress">
      <div class="determinate hide" style=""></div>
    </div>

  </div>

</header>

<div class="container">

  <?=$result_breadcrumb?>

  <!--<div id="status"></div>-->

  <div class="grid"></div>

  <?=$result_imagedetail?>

  <!--    <div id="popup-preview" class="mfp-hide white-popup"></div> -->

  <?=$sign_in?>
  <?=$view_cart?>
  <?=$right_sidebar?>
  <?=$pay_window?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="<?php echo base_url("materialize/js/materialize.js"); ?>"></script>
<script src="<?php echo base_url("js/magnific-popup.js"); ?>"></script>
<script src="<?php echo base_url("js/jscolor/jscolor.js"); ?>"></script>
<script src="<?php echo base_url("js/perfect-scrollbar.min.js"); ?>"></script>
<script src="<?php echo base_url("js/plugins.js"); ?>"></script>
<script src="https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<!--<script src="<?php echo base_url("js/imagesloaded.pkgd.js"); ?>"></script>-->
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<!--<script src="<?php echo base_url("js/isotope.pkgd.js"); ?>"></script>-->
<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
<script src="<?php echo base_url("js/main.js"); ?>"></script>
