<div id="plan-modal" class="modal" style="max-height:85%">
    <div class="row">
        <div class="col s12">

            <!-- <form action="<?=base_url('admin/plan_credenciales')?>" class="signup-form"> -->
                <div class="input-field col s12 center">
                    <h5>Datos del Plan</h5>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input type="text" id="plan-username" name="username" class="validate" required=""
                            aria-required="true">
                        <label for="username">Usuario</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">vpn_key</i>
                        <input type="password" name="password" id="plan-password" class="validate" required=""
                            aria-required="true">
                        <label for="password">Clave</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <button id="ingresar" class="modal-action modal-close btn waves-effect waves-light col s12 pink accent-2" type="submit">Ingresar
                        </button>
                    </div>
                </div>               
            <!-- </form> -->
        </div>
    </div>
</div>