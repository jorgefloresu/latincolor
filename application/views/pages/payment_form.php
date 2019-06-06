<div id="buy" class="modal">
  <div class="modal-content" style="padding:10px">
    <!-- <div id="aviso" class="row hide" style="margin-bottom:0">
      <div class="col s12">
        <div class="card-panel yellow no-shadow" style="padding: 15px">
          <span><i><strong>Importante:</strong> Ten en cuenta que para descargar varias imágenes, debes asegurarte
              que tu navegador tenga habilitada la opción de descargas multiples.</i></span>
        </div>
      </div>
    </div> -->
    <div class="row" style="margin-top: 10px">
      <div class="col s12">
        <h5>Selecciona método de pago</h5>
      </div>
      
      <!-- <ul class="tabs" style="margin-bottom:10px; background-color:transparent">
            <li class="tab col s6"><a href="#pay-tarjeta">Tarjeta de Crédito</a></li>
            <li class="tab col s6"><a href="#pay-transfer">Transferencia Bancaria</a></li>
        </ul> -->
    </div>
    <div class="row" style="margin-bottom:0;">
      <div class="col s6">
        <?php echo form_open('order/testPay', 'id="myCCForm" class="login-form" novalidate'); ?>
        <input id="token" name="token" type="hidden" value="TokenCode">
        <input id="orderId" name="orderId" type="hidden" value="">
        <input id="totalId" name="totalId" type="hidden" value="">
        <input id="orderNumber" name="orderNumber" type="hidden" value="">
        <input id="imageCode" name="imageCode" type="hidden" value="">
        <input id="size" name="size" type="hidden" value="">
        <input id="license" name="license" type="hidden" value="">
        <input id="description" name="description" type="hidden" value="">
        <input id="pay_provider" name="provider" type="hidden" value="">
        <input id="pay_username" name="username" type="hidden" value="">
        <div class="row margin">
          <div class="col s6">
            <label for="tipo-tarjeta">TIPO DE TARJETA</label>
            <select class="browser-default" id="tipo-tarjeta">
              <option value="" disabled selected>- Seleccione -</option>
              <?foreach ($tarjeta as $t):?>
              <?echo '<option value="'.$t['code'].'">'.$t['tipo'].'</option>'?>
              <?endforeach?>
            </select>
            <div class="row" style="margin-bottom:0;">
              <img src="<?=base_url('img/co-credito.png')?>" style="height:45px">
            </div>
          </div>
          </div>
          <div class="row margin">
          <div class="input-field col s9">
            <i class="material-icons prefix">credit_card</i>
            <input type="text" id="ccNo" size="25" value="" class="validate" required="" aria-required="true"
              autocomplete="off">
            <label>Número de la tarjeta</label>
          </div>
        </div>
        <!-- <div class="row margin" style="padding: 0 .75rem;">
          <label>FECHA DE EXPIRACION</label>
        </div> -->
        <div class="row">
          <div class="col s1">
            <i class="material-icons small" style="padding-top: 1.5rem">event_note</i>
          </div>
          <div class="col s3" style="margin-left: 1rem">
            <label>Mes</label>
            <select class="browser-default" id="expMonth">
              <option value="" disabled selected>MM</option>
              <?php 
                      for ($i=1; $i < 13 ; $i++) {
                        $val = str_pad($i, 2, "0", STR_PAD_LEFT);
                        echo '<option value="'.$val.'">'.$val.'</option>';
                      } 
                    ?>
            </select>
          </div>
          <div class="col s4">
            <label>Año</label>
            <select class="browser-default" id="expYear">
              <option value="" disabled selected>YYYY</option>
              <?php 
                      for ($i=date("Y"); $i < date("Y")+10 ; $i++) {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                    ?>
            </select>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s4">
            <i class="material-icons prefix">vpn_key</i>
            <input type="text" id="cvv" size="4" value="" class="validate" required="" aria-required="true"
              autocomplete="off">
            <label>CVC</label>
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <button class="btn waves-effect waves-light pink accent-2" type="submit">Enviar pago
            </button>
            <div id="pay-message">
              <p class="error_msg medium-small green-text" style="font-size:12px; line-height:18px"></p>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
      </div>
      <div class="col s6">
        <?$this->load->view('templates/pre_factura')?>
      </div>
    </div>
    <!-- <div id="pay-transfer">
          <div class="row" style="margin-bottom:0; padding-left:66%">
              <img src="<?//=base_url('img/co-transferencia.png')?>" style="height:45px">
              </div>
              <div class="col s4">
                <?//$this->load->view('templates/pre_factura')?>
              </div>
              <div class="col s1"></div>
              <div class="col s7">
                <?php //echo form_open('order/test_payu', 'id="myTBForm" class="login-form" novalidate'); ?>
    
                <label for="pse-bank">BANCO</label>
                <select class="browser-default" id="pse-bank">
                    <option value="" disabled selected>- Seleccione -</option>
                    <?//foreach ($banks as $bank):?>
                        <?//echo '<option value="'.$bank->pseCode.'">'.$bank->description.'</option>'?>
                    <?//endforeach?>
                </select>
                
                <label for="pse-nombre">NOMBRE DEL TITULAR</label>
                <input type="text" id="pse-nombre" size="40" value="" class="validate flat-field" required="" aria-required="true" autocomplete="off">
                
                <label for="pse-persona">TIPO DE CLIENTE</label>
                <select class="browser-default" id="pse-persona">
                    <option value="" disabled selected>- Seleccione -</option>
                    <?//foreach ($persona as $p):?>
                        <?//echo '<option value="'.$p['code'].'">'.$p['tipo'].'</option>'?>
                    <?//endforeach?>
                </select>

                <label for="pse-documento">DOCUMENTO DE IDENTIFICACIÓN</label>
                <select class="browser-default" id="pse-documento">
                    <option value="" disabled selected>- Seleccione -</option>
                    <?//foreach ($documento as $d):?>
                        <?//echo '<option value="'.$d['code'].'">'.$d['tipo'].'</option>'?>
                    <?//endforeach?>
                </select>

                <label for="pse-dni">NUMERO DE IDENTIDAD</label>
                <input type="text" id="pse-dni" size="20" value="" class="validate flat-field" required="" aria-required="true" autocomplete="off">

                        <button class="btn waves-effect waves-light pink accent-2" type="submit">Enviar pago
                        </button>
                    <div id="pay-message">
                        <p class="error_msg center medium-small green-text"></p>
                    </div>

                <?php //echo form_close(); ?>
              </div>
          </div>
        </div> -->
  </div>
</div>