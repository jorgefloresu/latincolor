


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
                        <th>ID</th>
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
                    <?php foreach ($datares as $key => $value):?>
                    <tr>
                      <td><?php echo $value->id?></td>
                      <td><?php echo $value->first_name?></td>
                      <td><?php echo $value->last_name?></td>
                      <td><?php echo $value->username?></td>
                      <td><?php echo $value->email_address?></td>
                      <td><?php echo $value->address?></td>
                      <td><?php echo $value->city?></td>
                      <td><?php echo $value->state?></td>
                      <td><?php echo $value->country?></td>
                      <td><?php echo $value->phone?></td>
                      <td><?php echo $value->empresa?></td>
                      <td><?php echo $value->nit?></td>
                      <td><?php echo $value->deposit_userid?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>

                  </table>
              </div>
            </div>
          </div>
