<?$this->load->view('templates/header')?>

  <header>

      <?$this->load->view('templates/ctrl/ctrl-nav')?>

  </header>

  <main class="consultor">

    <div class="container">

      <div class="row">
        <div class="col s12 m6 l6">
          <h4>Términos de la licencia de uso, Latin Color Images SAS</h4>
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
                    <p>Como distribuidor oficial de las marcas señaladas en el sitio web, en el momento de la compra usted recibe una licencia 
                        de derechos de uso que será recibida en el momento de la compra de acuerdo con las condiciones dadas por cada marca.</p>
                    <p>Cualquier recurso (fotografía, vector, video, música y/o after effects) son de modalidad Royalty Free, significa que 
                        usted puede utilizarlo en cualquier uso, por tiempo indefinido, en cualquier territorio del mundo sin exclusividad y 
                        lo mejor que la licencia nunca caduca.</p>
                    <p>Ofrecemos 2 tipos de licencias con todas las marcas, la cual es entregada de acuerdo a su compra:</p>
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th>Condiciones de uso</th>
                                <th>LICENCIA ESTÁNDAR</th>
                                <th>LICENCIA EXTENDIDA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Todo uso impreso</td>
                                <td>Hasta 500.000 copia por imagen</td>
                                <td>Ilimitados números de impresione</td>
                            </tr>
                            <tr>
                                <td>Uso digital y redes sociales</td>
                                <td>Ilimitadas (sin limitación de vistas)</td>
                                <td>Ilimitadas (sin limitación de vistas)</td>
                            </tr>
                            <tr>
                                <td>Archivos a perpetuidad</td>
                                <td>Aplica, no caduca la licencia</td>
                                <td>Aplica, no caduca la licencia</td>
                            </tr>
                            <tr>
                                <td>Países uso de los archivos</td>
                                <td>Todo el mundo</td>
                                <td>Todo el mundo</td>
                            </tr>
                            <tr>
                                <td>Imágenes en material merchandising</td>
                                <td>Entregadas de forma gratuita</td>
                                <td>Incluye la reventa de material merchandising</td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    <p>Adicionalmente usted debe saber:</p>
                    <ul class="browser-default">
                        <li>Uso digital de las imágenes ilimitadas, incluye: redes 
                            sociales, internet, apps, email marketing, software, ebooks, presentaciones etc.</li>
                        <li>Uso físico de las imágenes ilimitadas: revista, prensa, folletos, ayudaventas, 
                            catálogos, vallas, publicidad móvil, volantes, libros, rompetráfico, punto de 
                            venta, manuales, empaques, publicidad y marketing, entre otros.</li>
                        <li>Formato de las imágenes en JPG y vectores en EPS y JPG</li>
                        <li>Formato de los videos en .MOV o MP4</li>
                        <li>No tiene limitantes de visualizaciones en digital.</li>
                        <li>Uso permitido en comerciales, TV, streaming etc.</li>
                        <li>No se permite el uso de material fotográfico que atente con la integridad de las 
                            personas, temas sensibles como: político, religioso, condición sexual y pornográfico</li>
                        <li>Protección legal hasta US 10.000</li>
                        <li>Se entrega licencia correspondiente a la compra, junto con la factura</li>
                    </ul>
                    <p>De acuerdo con la anterior información, para la compra de imágenes individuales, en el sitio web usted tiene la posibilidad 
                        de escoger si desea obtener la Licencia Estándar o Extendida, de acuerdo a las necesidades de su empresa o de sus clientes, 
                        puede comprar en nombre propio o de un tercero.</p>
                    <p>Para los planes, suscripciones y paquete aplica solamente la Licencia Estándar que puede ser adquirida por una persona natural 
                        o jurídica, en nombre propio o de un tercero.</p>
                    <p>Ninguna licencia le permite revender cualquier recurso comprado en nuestro sitio web, ya que se prohíbe el sublicenciamiento 
                        de un recurso fotográfico; si una persona natural o jurídica recibe un cobro adicional diferente a los precios publicados 
                        en el sitio web por un tercero pierde todo el cubrimiento de la licencia.</p>
                    <p>Estamos comprometidos con las marcas oficiales por velar por el buen uso de las imágenes y evitar el uso ilegal de las mismas.</p>
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
