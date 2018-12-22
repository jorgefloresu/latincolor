<div id="stream0">Part 1
</div>
<div id="stream1">Part 2
</div>
<div id="stream2">Part 3
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
$(document).ready(function() {
 var pro = ['PX', 'FS'];
 var key = 'business';
  // Run the loadtweets() function every 5 seconds.
  //window.setInterval(function() {
  for (var i = pro.length - 1; i >= 0; i--) {
    loadpix(i,pro[i],key);
  }
  //loadpix();
  //}, 5000);
  function loadpix(i,pro,key) {
    $.getJSON("http://localhost:1337/?callback=?", {q:key, pro:pro})
        .done(function(data) {
            $('#stream'+i).html(data.pro+': '+data.total);
            console.log(data);
        })
        .fail(function( jqxhr, textStatus, error ) {
            var err = textStatus + ', ' + error;
            console.log( "Request Failed: " + err);
        }); 
    }
});
</script>
