<!DOCTYPE html>
<html lang="en">

  <head>
  <title>Images Uploading</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google-site-verification" content="gbiXTCGaj_ExlQzTaccfnaWv4RCI1ynFRSvIIEaoyII" />
    <link rel="shortcut icon" href="<?=base_url('favicon.ico')?>" type="image/x-icon">
    <link rel="icon" href="<?=base_url('favicon.ico')?>" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="57x57" href="<?=base_url('apple-icon-57x57.png')?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=base_url('apple-icon-60x60.png')?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=base_url('apple-icon-72x72.png')?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url('apple-icon-76x76.png')?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=base_url('apple-icon-114x114.png')?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url('apple-icon-120x120.png')?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=base_url('apple-icon-144x144.png')?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url('apple-icon-152x152.png')?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url('apple-icon-180x180.png')?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?=base_url('android-icon-192x192.png')?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=base_url('favicon-32x32.png')?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=base_url('favicon-96x96.png')?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('favicon-16x16.png')?>">
    <link rel="manifest" href="<?=base_url('manifest.json')?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?=base_url('ms-icon-144x144.png')?>">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?=base_url('css/dropzone.css');?>">
    <script src="<?=base_url('js/dropzone.js')?>"></script>
    <style>
        #dropzone {
            margin-bottom: 3rem; 
        }
        .dropzone .dz-message {
            font-weight: 400;
        }
        .dropzone {
            border: 2px dashed #0087F7;
            border-radius: 5px;
            background: white; 
        }
        .dropzone .dz-message {
                font-weight: 400; 
            }
        .dropzone .dz-message .note {
                font-size: 0.8em;
                font-weight: 200;
                display: block;
                margin-top: 1.4rem; 
            }

        *, *:before, *:after {
            box-sizing: border-box; 
        }

        html, body {
            height: 100%;
            font-family: Roboto, "Open Sans", sans-serif;
            font-size: 20px;
            font-weight: 300;
            line-height: 1.4rem;
            background: #F3F4F5;
            color: #646C7F;
            text-rendering: optimizeLegibility; 
        }
        @media (max-width: 600px) {
            html, body {
                font-size: 18px; 
            } 
        }
        @media (max-width: 400px) {
            html, body {
                font-size: 16px; 
            } 
        }

        h1, h2, h3, table th, table th .header {
            font-size: 1.8rem;
            color: #0087F7;
            -webkit-font-smoothing: antialiased;
            line-height: 2.2rem; 
        }

        h1, h2, h3 {
            margin-top: 2.8rem;
            margin-bottom: 1.4rem; 
        }

        h2 {
            font-size: 1.4rem; 
        }

        h1.anchor, h2.anchor {
            margin: 0;
            padding: 0;
            height: 1px;
            overflow: hidden;
            visibility: hidden; 
        }
    </style>
</head>
<body>

<div id="dropzone">
<?=form_open_multipart('upload/uploadImage', ['class'=>'dropzone'])?>
  <div class="fallback dz-message">
    <input name="userfile" type="file" multiple />
  </div>
</form>
</div>

<script>
/* var myDropzone = new Dropzone(document.body, {
    url: "http://localhost:8888/latincolor/upload/uploadImage",
    autoProcessQueue: true

}) */
</script>

<?//php echo $error;?>

<?//php echo form_open_multipart('upload/uploadImage');?>

<!--<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />-->

<?//php echo form_close(); ?>

</body>
</html>