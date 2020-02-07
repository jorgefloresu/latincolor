<?php $this->load->view('templates/header')?>

  <header>

      <?php $this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="consultor">

    <div class="container">

      <div class="row">
        <div class="col s12 m6 l6">
          <h4>Nosotros</h4>
        </div>
        <div class="col s12 m6 l6">
          <p class="light">
            </p>
        </div>
      </div>

        <div class="row">
          <div class="col s12">
            <div class="card-panel">             
                <span>
                    <p>Somos una empresa joven e innovadora, con más de 12 años transmitiendo emociones por medio de la fotografía. 
                        Hemos decidido integrar millones y millones de imágenes por medio de desarrollo de API`s de nuestros proveedores, 
                        lo cual permite a todos nuestros usuarios encontrar en un solo sitio la imagen que necesitan, ya no tienen que 
                        estar en diferentes buscadores, ahora en Latin Color Images encontrarán fotos, videos, ilustraciones, vectores, 
                        íconos, infografías e imágenes en 3d en un solo lugar!</p>
                    <p>Nuestro desarrollo permitirá comprar desde una imagen hasta el plan o paquete que necesitan nuestros clientes para 
                        sus diseños, con la integración de la pasarela de pagos permitirá pagar los productos o servicios desde la cuenta 
                        de ahorros, tarjeta de crédito o en los establecimientos autorizados.</p>
                    <p>Ofrecemos un equipo especializado en la producción de imágenes y videos, tanto con cámara fija como con drone, 
                        solicita información aquí (direcciona a servicios)</p>
                    <p>Velamos por los derechos de uso de nuestras imágenes, trabajamos continuamente invitando a los usuarios a que 
                        legalicen los usos de las fotografías y protejamos la propiedad intelectual de nuestros fotógrafos, si tienes algún 
                        caso que contarnos o en lo que podamos ayudarte contáctanos aquí (direcciona a tu consultor</p>
                    <p>Comprometidos con nuestros clientes seguimos trabajando en la automatización y sistematización de nuestros procesos.</p>
                    <p>Cualquier inquietud o felicitación no dudes en escribirnos!</p>
                    <p>Cordialmente,</p>
                    <p>Carolina Arévalo Rodríguez<br>Directora General.</p>
                </span>
            </div>
          </div>
        </div>

    </div>


    <?php $this->load->view('cart')?>

    <?php $this->load->view('templates/sign_in')?>

    <?php $this->load->view('templates/right_sidebar')?>

  </main>

  <div id="downloading" class="modal bottom-sheet">
      <div class="modal-content">
        <p>Descargando...</p>
      </div>
  </div>

  <?php $this->load->view('pages/payment_form'); ?>
  <?php $this->load->view('pages/signup_form')?>
  <?php $this->load->view('pages/forgot_pass')?>
  <?php $this->load->view('templates/info_bottom')?>


  <!-- Error Structure -->
<div id="error" class="modal"></div>
<?php $this->load->view('templates/footer_tag') ?>

<?php echo put_headers('js', $user_info)?>

</body>
</html>
