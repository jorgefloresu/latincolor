      <div class="row">
        <div class="col s3">
          <form id="subaccountform" action="<?php echo site_url('deposit/subaccounts'); ?>" method="post">
            <button id="subaccountsbtn" class="btn waves-effect waves-light orange darken-2" type="submit" name="action">Get Subaccounts
            </button>
            <div class="section">
              <div id="subaccountsDiv" class="input-field">
                <select id="subaccounts">
                  <option value="" disabled selected>Choose your option</option>
                </select>
                <label>Subaccounts ID</label>
              </div>
            </div>
            <input id="s_sessionid" type="hidden" name="sessionid" value="<?=$sessionid?>">
          </form>
        </div>
        <div class="col s9" style="height: auto;">
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-3">
              <pre id="urlsubaccounts" style="line-height: 12px; font-size: 0.8em; word-break: break-word; overflow: hidden;"></pre>
            </div>
          </div>
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-5">
              <pre id="resultsubaccounts" style="line-height: 12px; font-size: 0.8em; max-height: 47vh;"></pre>
            </div>
          </div>
        </div>
      </div>
