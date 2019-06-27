


          <div id="table-datatables">
            <h4 class="header">Usuarios registrados</h4>
            <div class="row">
              <div class="col s12 m4 l3">
                <p>Lista de usuarios registrados</p>
              </div>
              <div class="col s12 m8 l9">

                  <table id="data-table-simple" class="display responsive nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th>Departamento</th>
                        <th>País</th>
                        <th>Teléfono</th>
                        <th>Empresa</th>
                        <th>Nit</th>
                        <th>Subaccount ID</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?foreach ($datares as $key => $value):?>
                    <tr>
                      <td><?=$value->first_name?></td>
                      <td><?=$value->last_name?></td>
                      <td><?=$value->username?></td>
                      <td><?=$value->email_address?></td>
                      <td><?=$value->address?></td>
                      <td><?=$value->city?></td>
                      <td><?=$value->state?></td>
                      <td><?=$value->country?></td>
                      <td><?=$value->phone?></td>
                      <td><?=$value->empresa?></td>
                      <td><?=$value->nit?></td>
                      <td><?=$value->deposit_userid?></td>
                    </tr>
                    <?endforeach;?>
                  </tbody>

                  </table>
              </div>
            </div>
          </div>
