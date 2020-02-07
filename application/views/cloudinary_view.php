<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="<?php echo base_url('materialize/css/materialize.min.css');?>" media="screen,projection">
    <link rel="stylesheet" href="<?php echo base_url('materialize/material-icons/iconfont/material-icons.css');?>">

    <link href="<?php echo base_url('css/styles.css');?>" type="text/css" rel="stylesheet" media="screen,projection">
		<link href="<?php echo base_url('css/home-style.css');?>" type="text/css" rel="stylesheet" media="screen,projection">
    <title>Cloudinary Test</title>
    <!-- <script src="https://media-library.cloudinary.com/global/all.js"></script> -->
  </head>

<body style="background: #e4ebf1">
  <nav class="white" style="height:70px">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo" style="margin-top:5px"><img src="<?php echo base_url('img/LCI-logo-Hi.png')?>"/></a>
      <ul id="nav-mobile" class="right">
        <li><a id="upload_widget" class="cloudinary-button">Upload files</a></li>
      </ul>
    </div>
  </nav>
<!-- <button id="upload_widget" class="cloudinary-button">Upload files</button> -->
    <!-- <button id="open-btn" class="myBtn">Media Library</button> -->
<div class="row">
    <?php if(! $root): ?>
      <article class="col s1" style="margin-top:7px">
        <div class="card-panel" style="padding:13px">
          <span style="font-size: 18px"><a href="<?php echo base_url('cloud')?>"><i class="material-icons left">folder</i>/</a>
          </span>
        </div>
      </article>
    <?php endif ?>
  <!-- <nav>
    <div class="nav-wrapper">
      <div class="col s12">
        <a href="<?php echo base_url('cloud') ?>" class="breadcrumb">...</a>
        <?php if (isset($folders)): ?>
        <?php foreach ($folders as $folder): ?>

        <a href="<?php echo base_url('cloud/open_folder/'.$folder['path']) ?>" class="breadcrumb"><?php echo $folder['path']?></a>
        <?php endforeach ?>
        <?php endif ?>

      </div>
    </div>
  </nav> -->

<?php if (isset($folders)): ?>
<?php foreach ($folders as $folder): ?>
  <!-- <div class="row"> -->
      <article class="col s12 m4 l2" style="margin-top:7px">
        <div class="card-panel hoverable" style="padding:13px">
          <span style="font-size: 18px; font-weight:400"><a href="<?php echo base_url('cloud/open_folder/'.$folder['path'])?>"><i class="material-icons left">folder</i><?php echo $folder['path'] ?></a>
          </span>
        </div>
      </article>
    <!-- </div> -->
<?php endforeach ?>
<?php endif ?>
<div class="row"></div>
<?php foreach ($res as $val): ?>

    <article class="col s12 m6 l3">
    <div class="card">
      <div class="card-image waves-effect waves-block waves-light" style="height:auto">
        <img class="activator" src="<?php echo $val['image']?>">
      </div>
      <div class="card-content" style="padding:18px">
        <span class="card-title activator grey-text text-darken-4"><?php echo basename($val['id']) ?><i class="material-icons right">more_vert</i></span>
        <p><a href="<?php echo base_url('cloud/open_image/?img='.$val['id'].'&folder='.$val['folder']) ?>">Abrir imagen</a></p>
      </div>
      <div class="card-reveal">
        <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i>Card Title</span>
        <p>Here is some more information about this product that is only revealed once clicked on.</p>
      </div>
</article>
<?php endforeach ?>
</div>

</body>
<script src="<?php echo base_url('js/jquery-2.2.4.min.js');?>" ></script>
<script src="<?php echo base_url('materialize/js/materialize.min.js');?>" ></script>
<script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>  

<script type="text/javascript">  
  /* window.ml = cloudinary.createMediaLibrary({
   cloud_name: 'latincolor',
   api_key: '238135328693765',
   username: 'jorge@latincolorimages.com',
   button_class: 'myBtn',
   button_caption: 'Select Image or Video',
 }, {
     insertHandler: function (data) {
       data.assets.forEach(asset => { console.log("Inserted asset:",
       JSON.stringify(asset, null, 2)) })
       }
    },
    document.getElementById("open-btn")
)
 */
var myWidget = cloudinary.createUploadWidget({
  cloudName: 'latincolor', 
  uploadPreset: 'sample_a53a48bbe4'}, (error, result) => { 
    if (!error && result && result.event === "success") { 
      console.log('Done! Here is the image info: ', result.info); 
    }
  }
)

document.getElementById("upload_widget").addEventListener("click", function(){
    myWidget.open();
  }, false);

$(document).on('cloudinarywidgetsuccess', function(e, data) {
  console.log("Global success", e, data);
  location.reload();
});

$(document).on('cloudinarywidgetfileuploadsuccess', function(e, data) {
  console.log("Single file success", e, data);
  location.reload();
});


</script>

</html>

