<div id="table-datatables">

  <h4 class="header">Subcuentas</h4>
  <div class="row">
    <div class="col s12 m4 l3">
      <p>Datos de la API</p>
    </div>
    <div class="col s12 m8 l9">
      <ul class="subaccounts collapsible" data-collapsible="accordion">
        <? foreach ($subaccounts->subaccounts as $key => $value): ?>
          <li data-url='<?=base_url("admin/get_subaccount_data/{$value}")?>'>
            <div class="collapsible-header"><i class="material-icons">filter_drama</i>
              Sub Cuenta # <?=$value?> <span class="load" style="color:#CCC;display:none">- Extrayendo datos...</span>
            </div>
            <div class="collapsible-body">
              <table>
                <tbody>
                  <tr>
                    <td>
                      <div class="chip">
                        <img src="<?=base_url('img/ajax-loader.gif')?>" alt="Contact Person">
                      </div>
                    </td>
                    <td>
                      <span class="email">Cargando...</span>
                    </td>
                    <td>
                      <span class="since">Cargando...</span>
                    </td>
                    <td>
                      <a class='delete-user' href='<?=base_url("admin/delete_subaccount/{$value}")?>'><i class="material-icons red-text">delete</i></a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </li>
        <? endforeach ?>
        </ul>
      </div>
  </div>
  <h4 class="header">Planes</h4>
  <div class="row">
    <div class="col s12 m4 l3">
      <p>Lista de planes</p>
    </div>
    <div class="col s12 m8 l9">
      <table id="data-table-simple" class="display responsive nowrap" style="width:100%">
        <thead>
          <tr>
          <? foreach ($subscriptions->offers as $key => $properties):
              if ($key>0) break;
              foreach ($properties as $property => $value): ?>
                  <th><?= $property ?></th>
          <?  endforeach;
             endforeach ?>
           </tr>
        </thead>

        <tbody>
          <? foreach ($subscriptions->offers as $property): ?>
            <tr>
            <? foreach ($property as $key => $value): ?>
                  <td><?= $value ?></td>
            <? endforeach ?>
            </tr>
          <? endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
