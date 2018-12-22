<h2><?php echo $title ?></h2>
Images
<div class="row">
  <div class="col-sm-6 col-md-3">
    <a href="<?php echo base_url("gallery/podLiturgBPortada.jpg"); ?>" class="thumbnail">
      <img src="<?php echo base_url("gallery/podLiturgBPortada.jpg"); ?>" alt="image1">
    </a>
  </div>
</div>
<?php
$dir = "gallery"; // Your Path to folder
$map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */

 foreach ($map as $k): ?>
	<a href="<?php echo base_url($dir)."/".$k;?>" class="thumbnail">
    <img src="<?php echo base_url($dir)."/".$k;?>" alt="">
   
<?php endforeach ?>