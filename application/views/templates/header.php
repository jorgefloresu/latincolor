<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google-site-verification" content="gbiXTCGaj_ExlQzTaccfnaWv4RCI1ynFRSvIIEaoyII" />
    <link rel="shortcut icon" href="<?=base_url('favicon.ico')?>" type="image/x-icon">
    <link rel="icon" href="<?=base_url('favicon.ico')?>" type="image/x-icon">
    <? if ($this->input->get('keyword')): ?>
      <link rel="canonical" href="<?=base_url('main/search')?>">
    <? endif ?>
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

    <script defer="defer" src="<?php echo base_url('js/fa-brands.min.js');?>"></script>
    <script defer="defer" src="<?php echo base_url('js/fa-regular.min.js');?>"></script>
    <script defer="defer" src="<?php echo base_url('js/fa-solid.min.js');?>"></script>
    <script src="<?php echo base_url('js/fontawesome.min.js');?>" defer="defer"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-39846111-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-39846111-1');
    </script>

    <!--[if IE]>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <?=put_headers('css')?>
    <![endif]-->

    <title><?echo _("Title")?></title>
    <meta name="title" content="Latin Color Images - Banco de imágenes en Colombia" />
    <meta name="description" content="Banco de imágenes, videos y vectores. Operamos desde Colombia para toda latinoamérica. Promovemos la venta de imágenes y videos individuales o por medio de suscripciones, planes y paquetes. Brindamos además un servicio personalizado de asesoría en la búsqueda de imágenes. También somos representantes de diversos bancos de imágenes internacionales.">
    <meta name="keywords" content="banco de imágenes,banco de imagenes en colombia,banco de imagenes y videos en colombia,banco de imagenes videos y vectores en colombia,banco de fotos en colombia,banco de fotografía en colombia,stock de imágenes en colombia,producción de imágenes a la medida en colombia,photoresearcher,búsqueda de imágenes en colombia,búsqueda de imágenes y videos en colombia, banco de imágenes royalty free en colombia,banco de imágenes con licencia extendida en colombia,banco de imágenes con todas las licencias en colombia,banco de imagenes para uso publicitario en colombia,banco de imagenes para uso editorial en colombia,venta de fotos en colombia,venta de fotografías en colombia,imagenes y videos de alimentos,imagenes y videos de animales,imagenes y videos de naturaleza,imagenes y videos de arquitectura,imagenes y videos de industria,imagenes y videos de gente,imagenes y videos de costumbres,imagenes y videos de paisajes,imagenes y videos de objetos,imagenes y videos de texturas,imagenes y videos de ciudades,imagenes y videos de pueblos,stock images of colombia,stock images and videos in colombia,images producing in colombia,image search in colombia,images with all licenses in colombia,royalty free images in colombia,colombia advertising usage of images,colombia images editorial use,stock photos in colombia,stock photography in colombia,colombian image stock,colombian image bank,images plan and packages in colombia,image selling in colombia,food images and videos,animals images and videos,nature images and videos,architecture images and videos,industry images and videos,people images and videos,customs images and videos,landscapes images and videos,objects images and videos,textures images and videos,cities images and,towns images and videos,planes y suscripciones,planes paquetes y suscripciones,planes y suscripciones de imagenes y videos en colombia,planes paquetes y suscripciones de imagenes y videos en colombia" />

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "https://latincolorimages.com",
      "logo": "https://latincolorimages.com/img/LCI-logo-Hi.png",
      "contactPoint": [{
        "@type": "ContactPoint",
        "telephone": "+57-1-694-05-60",
        "contactType": "sales"
      }]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "https://latincolorimages.com/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://latincolorimages.com/main/search?keyword={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Person",
      "name": "Latin Color Images",
      "url": "http://latincolorimages.com",
      "sameAs": [
        "http://www.facebook.com/LatinColorImages",
        "http://instagram.com/latincolorimages"
      ]
    }
    </script>
  </head>

  <body>
    <!--[if !IE]> -->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" lazyload="1">
      <?=put_headers('css')?>
    <!-- <![endif]-->
