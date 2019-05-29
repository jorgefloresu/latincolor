<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="consultor">

    <div class="container">

      <div class="row">
        <div class="col s12 m6 l6">
          <h4>Políticas de Privacidad</h4>
        </div>
        <div class="col s12 m6 l6">
          <p class="light">Mayo 2019
            </p>
        </div>
      </div>

        <div class="row">
          <div class="col s12">
            <div class="card-panel">             
                <span>
                    <p>Latin Color Images se compromete a proteger su privacidad en cumplimiento por las normas legales de Protección de Datos 
                        Personales, la Ley 1581 de 2012 (Habeas Data), su decreto reglamentario 1377 de 2013 y demás disposiciones y normas 
                        complementarias, hemos desarrollado y adoptado la Política para el Tratamiento de Datos, con apego a lo dispuesto en la 
                        ley colombiana; por lo anterior toda información que ha sido enviada y continuaremos circulando será con respecto al 
                        desarrollo de nuestro objeto social.</p>
                    <p>Usamos su información personal para proporcionarle los productos y servicios que ofrecemos y la información que usted 
                        considere necesaria.</p>
                    <p>También recibirá información por medio de sitios de redes sociales como Facebook e Instagram; así mismo recibirá 
                        información si ha colocado sus datos por medio de landing pages, formularios de contacto o algún otro mecanismo digital.</p>
                    <p>Con el fin de mantener la información actualizada y proporcionarle información de gran interés es posible que combinemos 
                        la información facilitada por usted con fuentes de terceros.</p>
                    <p>La información enviada al correo electrónico incluye: productos y servicios, precios y promociones, información importante 
                        de uso y derechos de autor, licenciamiento y la demás que se considere importante para el uso de material fotográfico en 
                        piezas publicitarias.</p>
                    <p>Nos comprometemos a no entregar su información a otras personas ni empresas, compartiremos su información con las líneas 
                        de negocio pertenecientes a Latin Color Images; su información personal no será compartida a ningún tercero ni en ningún 
                        sitio.</p>
                    <p>Proporcionaremos todos los controles de seguridad de su información personal y la registrada en las cuentas de el sitio web, 
                        en los servidores de Latin Color Images será depositada toda la información recolectada.</p>
                    <p>Conforme a las leyes vigentes de cada país, usted tiene el derecho de actualizar su información personal, como corregir o 
                        eliminar toda información depositada en Latin Color Images en el momento que lo considere necesario.</p>
                    <p>Cualquier inquietud adicional o si desea ponerse en contacto con nosotros no dude escribirnos a 
                        <a href="mailto:comercial@latincolorimages.com">comercial@latincolorimages.com</a> 
                        o a los números de teléfono indicados en el sitio web.</p>
                    <p>Cordialmente,</p>
                    <p>Gerencia.</p>
                </span>
            </div>
          </div>
        </div>

    </div>


    <?$this->load->view('cart')?>

    <?$this->load->view('templates/sign_in')?>

    <?$this->load->view('templates/right_sidebar')?>

  </main>

  <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div>

  <?$this->load->view('pages/payment_form'); ?>
  <?$this->load->view('pages/signup_form')?>
  <?$this->load->view('pages/forgot_pass')?>
  <?$this->load->view('templates/info_bottom')?>


  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?$this->load->view('templates/footer_tag') ?>

<?=put_headers('js', $user_info)?>

</body>
</html>
