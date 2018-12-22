<script src="<?php echo base_url('js/jquery-2.2.4.min.js');?>" ></script>
<script src="<?php echo base_url('materialize/js/materialize.min.js');?>" ></script>
<script src="<?php echo base_url('js/jquery.validate.js'); ?>" ></script>
<script src="<?php echo base_url('js/js.storage.min.js');?>" ></script>
<script src="<?php echo base_url('js/lib.functions.js');?>" ></script>
<script src="<?php echo base_url('js/auth.functions.js');?>" ></script>
<script type="text/javascript">

//jQuery(document).ready(function($) {
$(function(){
  $('.modal').modal();
  var auth = new $.Auth({
    form: $('#login-form'),
    logout: $('#sign-out'),
  });
  console.log(auth);
  auth.setDefaults({
        onLogin: function(res) {
                    console.log(res);
                    $('span').text(res.first_name);
                 },
        onLogout: function(res) {
                    console.log(res);
                    $('span').text(res.status);
                  }

  });
  $('#status').on('click', function(){
      alert($.Auth.status());
  })

//   $('#login-form').Auth({
//     onLogin: function(res) {
//                 console.log(res);
//                 $('span').text(res.first_name);
//              },
//     onLogout: function(res) {
//                 console.log(res);
//                 $('span').text(res.status);
//               }
//   });
})

</script>
