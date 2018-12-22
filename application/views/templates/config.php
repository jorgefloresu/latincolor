<h4 class="header">Configuración</h4>
<div class="row">
  <div class="col s12 m4 l3">
    <p>Variables globales del sistema</p>
  </div>
  <div class="col s12 m8 l9">
    <form action="<?=base_url('admin/set_config')?>" id="set-config" class="set-config">
    <div class="row">
        <?foreach ($system as $key => $field):?>
          <div class="input-field col s12">
            <input type="hidden" name="features[<?=$key?>]" value="<?=$key?>">
            <input id="<?=$key?>" name="features[<?=$key?>]" value="<?=$field['value']?>" placeholder="Use valores decimales" type="text" class="validate">
            <label for="<?=$key?>"><?=$field['description']?></label>
          </div>
        <?endforeach?>
        <!-- <div class="input-field col s12">
          <input id="comision" name="comision" value="<?=$comision?>" placeholder="Use decimales, ej: digite 0.4 para representar 40%" type="text" class="validate">
          <label for="comision">Porcentaje de Comisión</label>
        </div>
        <div class="input-field col s12">
          <input id="iva" name="iva" value="<?=$iva?>" placeholder="Use decimales, ej: digite 0.16 para representar 16%" type="text" class="validate">
          <label for="iva">IVA</label>
        </div> -->

    </div>
    <div class="row">
      <div class="input-field col s12">
        <button class="btn waves-effect waves-light" type="submit" name="action">Submit
            <i class="material-icons right">send</i>
        </button>
      </div>
    </div>
    <br/>
    <div id="message" style="display: none; color:teal">
      <p class="error_msg medium-small">Datos guardados</p>
    </div>
    </form>
  </div>
</div>
