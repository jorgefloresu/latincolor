      <div class="row">
        <div class="col s3">
          <form id="loginform" action="<?php echo site_url('deposit/login'); ?>" method="post">
            <div class="input-field">
              <input id="login" type="text" name="login" value="<?=$login?>">
              <label for="login" class="orange-text text-darken-2">Account login</label>
            </div>
            <div class="input-field">
              <input id="password" type="password" name="password" value="<?=$password?>">
              <label for="password" class="orange-text text-darken-2">Account password</label>
            </div>
            <button id="loginbtn" class="btn waves-effect waves-light orange darken-2" type="submit" name="action">Login
            </button>
            <input id="l_sessionid" type="hidden" name="sessionid" value="<?=$sessionid?>">
          </form>
        </div>
        <div class="col s9" style="height: auto;">
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-3">
              <pre id="" style="line-height: 12px; font-size: 0.8em; word-break: break-word; overflow: hidden;"></pre>
            </div>
          </div>
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-5">
              <pre id="resultlogin" style="line-height: 12px; font-size: 0.8em; max-height: 47vh;"></pre>
            </div>
          </div>
        </div>
      </div>
