


          <div id="table-datatables">
            <h4 class="header">Actividades de usuarios</h4>
            <div class="row">
              <div class="col s12 m4 l3">
                <p>Lista de actividades realizadas por los usuarios registrados</p>
              </div>
              <div class="col s12 m8 l9">

                  <table id="data-table-simple" class="display responsive nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Transaccion</th>
                        <th>Fecha y hora</th>
                        <th>Imagen</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($datares as $key => $value):?>
                    <tr>
                      <td><?php echo $value->username?></td>
                      <td><?php echo $value->activity_type?></td>
                      <td><?php echo $value->session_date?></td>
                      <td><?php echo $value->img_code?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>

                  </table>
              </div>
            </div>
          </div>
