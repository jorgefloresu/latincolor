<div id="table-datatables">
  <h5>Ventas</h5>
  <div class="row">
    <div class="col s12 m9 l9 container">
      <? if(count($ventas) > 0): ?>
        <table id="data-ventas" class="display">
          <thead>
            <tr>
              <th></th>
            <? foreach ($ventas as $key => $columns):
                if ($key>0) break;
                foreach ($columns as $column => $value): ?>
                    <th><?= $column ?></th>
            <?  endforeach;
               endforeach ?>
             </tr>
          </thead>

          <tbody>

            <? foreach ($ventas as $venta): ?>
              <tr><td></td>
              <? foreach ($venta as $key => $value): ?>
                  <? if ($key == 'orderId'): ?>
                    <!-- <td><a class='modal-trigger modal-close view-tran' href='#pasarela-tran'><?= substr($value, 0, strlen($value)-1); ?></a></td> -->
                    <td class="view-tran blue-text" style="cursor:pointer" data-order="<?=substr($value, 0, strlen($value)-1)?>"><?=$value?></td>
                  <? else: ?>
                    <td><?= $value ?></td>
                  <? endif ?>
              <? endforeach ?>
              </tr>
            <? endforeach ?>
          </tbody>
        </table>
      <? else: ?>
        <p>No hay órdenes nuevas</p>
      <? endif ?>
    </div>
    <div class="col s12 m3 l3 container">
      <h6>Datos del usuario</h6>
      <div class="buyer">
        <blockquote>
          <label>Usuario</label>
          <p class="buyer-user"></p>
          <label>Nombre</label>
          <p class="buyer-name"></p>
          <label>Email</label>
          <p class="buyer-email"></p>
          <label>Dirección</label>
          <p class="buyer-direccion"></p>
          <label>Teléfono</label>
          <p class="buyer-telefono"></p>
          <label>Empresa</label>
          <p class="buyer-empresa"></p>
          <label>NIT</label>
          <p class="buyer-nit"></p>
      </blockquote>
      </div>
    </div>
  </div>

</div>

<?$this->load->view('templates/view_pasarela_tran')?>