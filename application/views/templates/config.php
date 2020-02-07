<h4 class="header">Configuración</h4>
<div class="row">
  <div class="col s12 m4 l3">
    <p>Variables globales del sistema</p>
  </div>
  <div class="col s12 m8 l9">
    <form action="<?php echo base_url('admin/set_config')?>" id="set-config" class="set-config">
    <div class="row">
        <?php foreach ($system as $key => $field):?>
          <div class="input-field col s12">
            <input type="hidden" name="features[<?php echo $key?>]" value="<?php echo $key?>">
            <input id="<?php echo $key?>" name="features[<?php echo $key?>]" value="<?php echo $field['value']?>" placeholder="Use valores decimales" type="text" class="validate">
            <label for="<?php echo $key?>"><?php echo $field['description']?></label>
          </div>
        <?php endforeach?>

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
