


          <div id="table-datatables">
            <h4 class="header">Descargas de usuarios</h4>
            <div class="row">
              <div class="col s12 m4 l3">
                <p>Lista de descargas realizadas por los usuarios registrados</p>
              </div>
              <div class="col s12 m8 l9">

                  <table id="data-table-simple" class="display responsive nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Usuario</th>
                        <th>Proveedor</th>
                        <th>Código</th>
                        <th>License ID</th>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Fecha</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?foreach ($datares as $key => $value):?>
                    <tr>
                      <td><img src="<?=$value->img_url?>" style="max-height:50px; max-width:50px"/></td>
                      <td><?=$value->username?></td>
                      <td><?=$value->img_provider?></td>
                      <td><?=$value->img_code?></td>
                      <td><?=$value->license_id?></td>
                      <td><?=$value->size?></td>
                      <td><?=$value->img_price?></td>
                      <td><?=$value->date?></td>
                    </tr>
                    <?endforeach;?>
                  </tbody>

                  </table>
              </div>
            </div>
          </div>
