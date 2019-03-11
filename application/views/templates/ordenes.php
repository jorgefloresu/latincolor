<div id="table-datatables">
  <h5>Ordenes</h5>
  <div class="row">
    <div class="col s12 m9 l9">
      <? if($new_ordenes->ord > 0 || $new_ordenes->pro > 0): ?>
        <table id="data-ventas" class="ordenes display">
          <thead>
            <tr>
              <th></th>
            <? foreach ($ordenes as $key => $columns):
                if ($key>0) break;
                foreach ($columns as $column => $value): ?>
                    <th><?= $column ?></th>
            <?  endforeach;
               endforeach ?>
             </tr>
          </thead>

          <tbody>

            <? foreach ($ordenes as $orden): ?>
              <tr><td></td>
              <? foreach ($orden as $key => $value): ?>
                    <td><?= $value ?></td>
              <? endforeach ?>
              </tr>
            <? endforeach ?>
          </tbody>
        </table>
      <? else: ?>
        <p>No hay órdenes nuevas</p>
      <? endif ?>
    </div>
    <div class="col s12 m3 l3">
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
