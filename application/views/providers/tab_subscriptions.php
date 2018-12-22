      <div class="row">
        <div class="col s3">
          <form id="subscriptionsForm" action="<?php echo site_url('deposit/subscriptions'); ?>" method="post">
            <div class="section">
              <div id="subsPeriod" class="input-field">
                <select>
                  <option value="" disabled selected>Choose your option</option>
                </select>
                <label>Subscriptions period</label>
              </div>
              <div id="subsCount" class="input-field">
                <select>
                  <option value="" disabled selected>Choose your option</option>
                </select>
                <label>Subscriptions count</label>
              </div>
            </div>
            <button id="subsBtn" class="btn waves-effect waves-light orange darken-2" type="submit" name="action">Get Subscriptions
            </button>
            <div class="section">
              <div id="subsPlans" class="input-field">
                <select>
                  <option value="" disabled selected>Choose your option</option>
                </select>
                <label>Subscriptions plans</label>
              </div>
            </div>
            <input id="subaccountid" type="hidden" name="subaccountid" value="<?=$subaccountid?>">
            <input id="u_sessionid" type="hidden" name="sessionid" value="<?=$sessionid?>">
          </form>
        </div>
        <div class="col s9" style="height: auto;">
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-3">
              <pre id="urlSubscriptions" style="line-height: 12px; font-size: 0.8em; word-break: break-word; overflow: hidden;"></pre>
            </div>
          </div>
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-5">
              <pre id="resultSubscriptions" style="line-height: 12px; font-size: 0.8em; max-height: 47vh;"></pre>
            </div>
          </div>
        </div>
      </div>
