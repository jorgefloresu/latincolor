<div id="table-datatables">
  <h5>Ventas</h5>
  <div class="row">
    <div class="col s12 m9 l9 container">
      <?php  if(count($ventas) > 0): ?>
        <table id="data-ventas" class="display">
          <thead>
            <tr>
              <th></th>
            <?php  foreach ($ventas as $key => $columns):
                if ($key>0) break;
                foreach ($columns as $column => $value): ?>
                    <th><?php echo  $column ?></th>
            <?php   endforeach;
               endforeach ?>
             </tr>
          </thead>

          <tbody>

            <?php  foreach ($ventas as $venta): ?>
              <tr><td></td>
              <?php  foreach ($venta as $key => $value): ?>
                  <?php  if ($key == 'orderId'): ?>
                    <!-- <td><a class='modal-trigger modal-close view-tran' href='#pasarela-tran'><?php echo  substr($value, 0, strlen($value)-1); ?></a></td> -->
                    <td class="view-tran blue-text" style="cursor:pointer" data-order="<?php echo substr($value, 0, strlen($value)-1)?>"><?php echo $value?></td>
                  <?php  else: ?>
                    <td><?php echo  $value ?></td>
                  <?php  endif ?>
              <?php  endforeach ?>
              </tr>
            <?php  endforeach ?>
          </tbody>
        </table>
      <?php  else: ?>
        <p>No hay órdenes nuevas</p>
      <?php  endif ?>
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

<?php $this->load->view('templates/view_pasarela_tran')?>