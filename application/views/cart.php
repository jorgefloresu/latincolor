
  <div id="cart" class="modal modal-fixed-footer">
  <div class="modal-content">
      <h5>Lista de Compras</h5>
<!--       <input type="hidden" name="subaccountid" value="">
     subaccount:  -->
        <table class="shopping-cart responsive-table">
          <thead>
            <tr>
              <th class="responsive-header">Producto</th><th>Prod.ID</th><th>Descripción</th><th class="center">Tamaño</th>
              <th class="center">Licencia</th><th>Precio(US$)</th><th>Comprar</th><th>Quitar</th>
              <!-- <th style="width:6%"></th><th style="width:6%">Acciones</th> -->
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <p id="sub-total">
          <strong>Sub Total US$</strong>: <span id="stotal"></span>
        </p>
        <div id="downloading" class="card-panel teal hide" style="position: absolute;bottom:0;width:94%">
          <span class="white-text">Tu descarga ha iniciado. En caso que tu descarga esté incompleta
            háznoslo saber a <a href="<?=base_url('main/consultor?cons=Descarga+incompleta')?>" class="yellow-text">Tu Consultor</a>
            y escribe, en el detalle de la consulta, el código de las imágenes que no recibiste. Puedes cerrar esta ventana.
          </span>
        </div>
  </div>
  <div class="modal-footer">
    <a href="#!" id="close-cart" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
    <a href="#!" id="go-checkout" class="modal-action waves-effect waves-green btn-flat">Comprar todo</a>
    <a href="#!" id="empty-cart" class="modal-action waves-effect waves-green btn-flat">Vaciar lista</a>
  </div>
 </div>
