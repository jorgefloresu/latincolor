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
	<!-- <link href="<?php echo base_url('css/jquery.Jcrop.css');?>" type="text/css" rel="stylesheet" media="screen,projection"> -->
	<link href="<?php echo base_url('css/cropper.css');?>" type="text/css" rel="stylesheet" media="screen,projection">
    <title>Cloudinary Preview</title>
    <!-- <script src="https://media-library.cloudinary.com/global/all.js"></script> -->
  </head>

<body style="background: #e4ebf1">
    <nav class="white" style="height:70px">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo" style="margin-top:5px"><img src="<?php echo base_url('img/LCI-logo-Hi.png')?>"/></a>
    </div>
  </nav>
    <nav style="background: transparent;box-shadow: none;height: 50px; font-weight:400">
    <div class="nav-wrapper">
      <div class="col s12">
        <a href="<?php echo base_url('cloud') ?>" class="breadcrumb grey-text text-darken-3"><i class="material-icons left">folder</i>/</a>
        <?php if ($folder != ''): ?>
        <a href="<?php echo base_url('cloud/open_folder/'.$folder) ?>" class="breadcrumb grey-text text-darken-3"><?php echo $folder?></a>
        <?php endif ?>
      </div>
    </div>
  </nav>
<form id="fileform">
    <div class="row">
        <div class="col s5">
            <h4>Original</h4>
            <div><img id="original" class="materialboxed responsive-img" style="max-width: 100%" src="<?php echo $url?>"></div>
        </div>
        <div class="col s4">
            <h4>Transformaciones</h4>
            <h5>Dimensiones</h5>
            <div class="input-field col s3">
                <input id="width" type="text" class="validate" name="width" value="<?php echo $width ?>">
                <label for="width">Ancho</label>
            </div>
            <div class="input-field col s3">
                <input id="height" type="text" class="validate" name="height" value="<?php echo $height ?>">
                <label for="height">Alto</label>
            </div>
            <div class="input-field col s3">
                <input id="ymargin" type="text" class="validate" name="ymargin" value="0">
                <label for="ymargin">Margen Y</label>
            </div>
            <div class="input-field col s3">
                <input id="xmargin" type="text" class="validate" name="xmargin" value="0">
                <label for="xmargin">Margen X</label>
            </div>
            <div class="input-field col s3">
                <input id="bordes" type="text" class="validate" name="bordes" value="0">
                <label for="bordes">Borde curvo</label>
            </div>
            <div class="input-field col s3">
                <select name="format">
                <option value="jpg" selected>JPEG</option>
                <option value="png">PNG</option>
                <option value="gif">GIF</option>
                </select>
                <label>Formato de la imagen</label>
            </div>
            <div class="input-field col s6">
                <select name="crop">
                <option value="crop" selected>Crop</option>
                <option value="fill">Fill</option>
                </select>
                <label>Tipo de recorte</label>
            </div>
            <div class="col s6">
                <input type="checkbox" class="filled-in" id="filled-in-box" name="rostro" />
                <label for="filled-in-box">Detectar rostro</label>
            </div>

            <div class="col s12">
                <h5>Texto</h5>
            </div>

            <div class="input-field col s12">
                <input placeholder="Escribe aquí el texto" id="texto" type="text" class="validate" name="texto">
                <label for="texto">Texto</label>
            </div>            
            <div class="input-field col s3">
                <select name="fontfamily">
                <option value="Arial" selected>Arial</option>
                <option value="Helvetica">Helvetica</option>
                <option value="Neucha">Neucha</option>
                <option value="Palatino">Palatino</option>
                <option value="Parisienne">Parisienne</option>    
                <option value="Roboto">Roboto</option>
                <option value="Times">Times</option>
                <option value="Verdana">Verdana</option>
                </select>
                <label>Tipo de letra</label>
            </div>
            <div class="input-field col s3">
                <input id="fontsize" type="text" class="validate" name="fontsize" value="12">
                <label for="fontsize">Tamaño de letra</label>
            </div>
            <div class="input-field col s3">
                <input id="letterspacing" type="text" class="validate" name="letterspacing" value="0">
                <label for="letterspacing">Espaciado</label>
            </div>
            <div class="input-field col s3">
                <select name="fontweight">
                <option value="normal" selected>Normal</option>
                <option value="bold">Negrilla</option>
                <option value="thin">Delgada</option>
                <option value="light">Ligera</option>
                </select>
                <label>Grosor de la letra</label>
            </div>

            <div class="input-field col s3">
                <select name="textdecoration">
                <option value="normal" selected>Normal</option>
                <option value="underline">Subrayado</option>
                <option value="strikethrough">Tachado</option>
                </select>
                <label>Decoración del texto</label>
            </div>
                <div class="input-field col s3">
                <select name="ubicacion">
                <option value="center" selected>Centro</option>
                <option value="north">Superior</option>
                <option value="south">Inferior</option>
                </select>
                <label>Ubicación del texto</label>
            </div>
            <div class="input-field col s3">
                <select name="color">
                <option value="black" selected>Negro</option>
                <option value="white">Blanco</option>
                <option value="red">Rojo</option>
                </select>
                <label>Color del texto</label>
            </div> 
            <div class="input-field col s12">
                <input id="transform" type="hidden" name="transform" value="<?php echo $public_id ?>">
                <button type="submit" class="waves-effect waves-light btn">transformar</a>
            </div>

        </div>
        <div class="col s3">
            <h4>Preview</h4>
            <img id="transformed" class="materialboxed responsive-img" src="<?php echo $url?>">
        <!-- <div class="input-field col s2"> -->
            <input id="file" type="hidden" name="img" value="<?php echo $public_id ?>">
            <!-- <button type="submit" class="waves-effect waves-light btn">descargar</a>
        </div> -->
        <div class="input-field col s6">
            <!-- Dropdown Trigger -->
            <a class='dropdown-button btn' href='#' data-activates='dropdown1'>Descargar</a>

            <!-- Dropdown Structure -->
            <ul id='dropdown1' class='dropdown-content'>
                <li><a id="imodif" href="#!">Imagen modificada</a></li>
                <li class="divider"></li>
                <li><a id="iorig" href="#!">Imagen original</a></li>
            </ul>
        </div>            
        </div>

        <div class="row"></div>

        
    </div>
    <div class="row">
    </div>
</form>

</body>
<script src="<?php echo base_url('js/jquery-2.2.4.min.js');?>" ></script>
<script src="<?php echo base_url('materialize/js/materialize.min.js');?>" ></script>
<!-- <script src="<?php echo base_url('js/jquery.Jcrop.min.js');?>" ></script> -->
<script src="<?php echo base_url('js/cropper.js');?>" ></script>
<script src="<?php echo base_url('js/jquery-cropper.js');?>" ></script>
<script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>  

<script type="text/javascript">
var $image = $('#original');

$image.cropper({
  crop: function(event) {
    $('#width').val(event.detail.width.toFixed());
    $('#height').val(event.detail.height.toFixed());
    $('#ymargin').val(event.detail.y.toFixed());
    $('#xmargin').val(event.detail.x.toFixed());
  }
});

// Get the Cropper.js instance after initialized
var cropper = $image.data('cropper'); 

var ratio = 0;

$(document).ready(function(){
    $('select').material_select();
    $('.materialboxed').materialbox();
    /* $('#original').Jcrop({
        onSelect: function(c){
            $('#width').val(c.w*ratio);
            $('#height').val(c.h*ratio);
            $('#ymargin').val(c.y);
            $('#xmargin').val(c.x);
        }
    }); */
});


$('#dropdown1 a').on('click', function(evt){
    evt.preventDefault();
    $.ajax({
        type: 'POST',
        data: $('#fileform').serialize() + '&res=' + $(this).attr('id'),
        url: 'http://localhost:8888/latincolor/cloud/descargar',
        dataType: 'JSON'
    }).done(function(res){
        console.log(res);
        loadFile(res.file, function(response){
            let a = document.createElement('a');
            let url = URL.createObjectURL(response);
            a.href = url;
            a.download = res.realname;
            a.click();
        });
    })
});

$('#fileform button').on('click', function(evt){
    evt.preventDefault();
    var proc = ($(this).text()).trim();
    $.ajax({
        type: 'POST',
        data: $('#fileform').serialize(),
        url: 'http://localhost:8888/latincolor/cloud/'+proc,
        dataType: 'JSON'
    }).done(function(res){
        console.log(res);
        if (proc=='transformar') {
            $('#transformed').attr('src', res.file);
            $('#file').val(res.file);
        } else {
            loadFile(res.file, function(response){
                let a = document.createElement('a');
                let url = URL.createObjectURL(response);
                a.href = url;
                a.download = 'foto.jpg';
                a.click();
            });
        }
    })
});


$('#transformed').load(function(){
    $('<img>').attr('src',$(this).attr('src')).load(function(){
         ratio = this.width/this.height;
    })
});

/* $('#width').change(function(){
    let data = {
        width: $(this).val().toFixed(),
        height: $('#height').val().toFixed()
    }
    cropper.setData(data)
}) */
/* $('#width').keyup(function(){
    $('#height').val(($(this).val() / ratio).toFixed());
}); */

function loadFile(file, response) {
    let request = new XMLHttpRequest();
    console.log(file);
    request.responseType = 'blob';
    request.open('GET', file);
    request.addEventListener('load', function () {
        response(request.response);
    });
    //request.setRequestHeader('Access-Control-Allow-Origin', '*');
    request.send();
}



</script>

</html>

