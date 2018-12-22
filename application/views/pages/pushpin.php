<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="<?php echo base_url('materialize/0.98.1/css/materialize.min.css');?>">

    <style type="text/css">
       html, body, .block {
        height: 100%;
      }
      nav ul li a:hover, nav ul li a.active {
            background-color: rgba(0,0,0,.1);
      }
    </style>
</head>
<div id="blue" class="block blue scrollspy">
  <nav class="pushpin-demo-nav" data-target="blue">
    <div class="nav-wrapper light-blue">
      <div class="container">
        <a href="#" class="brand-logo">LatinColorImages Blue</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a href="#red">Blue Link 1</a></li>
          <li><a href="#!">Blue Link 2</a></li>
          <li><a href="#!">Blue Link 3</a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>

<div id="red" class="block red lighten-1 scrollspy">
  <nav class="pushpin-demo-nav" data-target="red">
    <div class="nav-wrapper red">
      <div class="container">
        <a href="#" class="brand-logo">LatinColorImages Red</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a href="#green">Red Link 1</a></li>
          <li><a href="#!">Red Link 2</a></li>
          <li><a href="#!">Red Link 3</a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>

<div id="green" class="block green lighten-1 scrollspy">
  <nav class="pushpin-demo-nav" data-target="green">
    <div class="nav-wrapper green">
      <div class="container">
        <a href="#" class="brand-logo">LatinColorImages Green</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a href="#blue">Green Link 1</a></li>
          <li><a href="#!">Green Link 2</a></li>
          <li><a href="#!">Green Link 3</a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>

<?$this->load->view('templates/footer')?>
<script type="text/javascript">
(function($){
  $(function(){

    $('.scrollspy').scrollSpy({scrollOffset: 0});

    if ($('.pushpin-demo-nav').length) {
          $('.pushpin-demo-nav').each(function() {
            var $this = $(this);
            var $target = $('#' + $(this).attr('data-target'));
            $this.pushpin({
              top: $target.offset().top,
              bottom: $target.offset().top + $target.outerHeight() - $this.height()
            });
          });
        }
  }); // end of document ready
})(jQuery); // end of jQuery name space

</script>

</body>
</html>