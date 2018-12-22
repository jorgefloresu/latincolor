

          <div id="table-datatables">
            <h4 class="header">Planes</h4>
            <div class="row">
              <div class="col s12 m4 l3">
                <p>Lista de planes por proveedor</p>
              </div>
              <div class="col s12 m8 l9">

                  <table id="data-table-simple" class="display responsive nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th>Código</th>
                        <th>Proveedor</th>
                        <th>Frecuencia</th>
                        <th>Cantidad</th>
                        <th>Tiempo</th>
                        <th>Fotos</th>
                        <th>Valor</th>
                        <th>Especial</th>
                        <th>Ahorro</th>
                        <th>Descuento</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?foreach ($datares as $field):?>
                    <tr>
                      <td><?=$field->id?></td>
                      <td><?=$field->provider?></td>
                      <td><?=$field->frecuencia?></td>
                      <td><?=$field->cantidad?></td>
                      <td><?=$field->tiempo?></td>
                      <td><?=$field->fotos_suscripcion?></td>
                      <td><?=$field->valor?></td>
                      <td><?=$field->especial?>
                        <!-- <input type="text" id="<?=$field->id?>-especial" name="<?=$field->id?>-especial" value="<?=$field->especial?>"> -->
                      </td>
                      <td><?=$field->ahorro?></td>
                      <td><?=$field->descuento?></td>
                    </tr>
                    <?endforeach;?>
                  </tbody>

                  </table>
                  <!-- <button class="data-aplicar btn waves-effect waves-light" type="submit" name="modif">Aplicar</button> -->
                </div>
            </div>
            <h4 class="header">Actualizar planes</h4>
            <div class="row">
              <div class="col s12 m4 l3">
                <p>Actualiza la tabla de planes por medio de un archivo CSV</p>
              </div>
              <div class="col s12 m8 l9">

                <?=form_open_multipart('admin/do_upload')?>
                  <div class="file-field input-field">
                        <div class="btn">
                          <span>Archivo:</span>
                          <input type="file" name="userfile" size="20" />
                        </div>
                        <div class="file-path-wrapper">
                          <input class="file-path validate" type="text">
                        </div>
                  </div>
                  <button class="btn waves-effect waves-light" type="submit" value="upload" />
                    Enviar <i class="material-icons right">send</i>
                  </button>
                </form>
              </div>
            </div>
        </div>
