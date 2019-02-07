<div id="buy" class="modal">
    <div class="modal-content" style="padding:10px">
      <div id="aviso" class="row hide" style="margin-bottom:0">
        <div class="col s12">
          <div class="card-panel yellow no-shadow" style="padding: 15px">
            <span><i><strong>Importante:</strong> Ten en cuenta que para descargar varias imágenes, debes asegurarte
            que tu navegador tenga habilitada la opción de descargas multiples.</i></span>
          </div>
        </div>
      </div>
    <div class="row" style="margin-top: 10px">
      <div class="col s7">
        <div class="row margin">
            <div class="col s8">
              <h5>Factura</h5>
              <table>
              <tbody>
                <tr>
                  <td colspan="2" class="fact-descript"></td>
                </tr>
                <tr>
                  <td>SubTotal</td>
                  <td class="fact-subtotal right">100.00</td>
                </tr>
                <tr>
                  <td>IVA</td>
                  <td class="fact-iva right">13.00</td>
                </tr>
                <tr>
                  <td>TCO</td>
                  <td class="fact-tco right">3.90</td>
                </tr>
                <tr>
                  <td>Total</td>
                  <td class="fact-total right">113.00</td>
                </tr>
              </tbody>
              </table>
            </div>
        </div>
    </div>
    <div class="col s5">

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
            <div class="col s12">
              <h5>Datos de la Tarjeta</h5>
            </div>
            <div class="row margin">
                <div class="col s12">
                    <label>Número de la Tarjeta</label>
                    <input type="text" id="ccNo" size="25" value="" class="validate flat-field" required="" aria-required="true" autocomplete="off">
                </div>
            </div>
            <div class="row margin">
                <div class="col s12">
                    <label>FECHA DE EXPIRACION</label>
                </div>
            </div>
            <div class="row margin">
               <div class="col s3">
                    <label>Mes</label>
                    <select class="browser-default" id="expMonth">
                        <option value="" disabled selected>MM</option>
                        <?php for ($i=1; $i < 13 ; $i++) {
                            $val = str_pad($i, 2, "0", STR_PAD_LEFT);
                            echo '<option value="'.$val.'">'.$val.'</option>';
                        }; ?>
                    </select>
                </div>
                <div class="col s9">
                    <label>Año</label>
                    <select class="browser-default" id="expYear">
                        <option value="" disabled selected>YYYY</option>
                        <?php for ($i=2019; $i < 2029 ; $i++) {
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }; ?>
                    </select>
                </div>
            </div>
            <div class="row margin">
                <div class="col s6">
                    <label>CVC</label>
                    <input type="text" id="cvv" size="4" value="" class="validate flat-field" required="" aria-required="true" autocomplete="off">
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light col s12 pink accent-2" type="submit">Enviar pago
                    </button>
                </div>
                <div id="pay-message">
                    <p class="error_msg center medium-small green-text"></p>
                </div>
            </div>

    <?php echo form_close(); ?>
    </div>
    </div>
    </div>
</div>
