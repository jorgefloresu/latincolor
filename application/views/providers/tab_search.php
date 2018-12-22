      <div class="row">
        <div class="col s3">
          <form id="search" action="<?php echo site_url('deposit/search/0'); ?>">
            <div class="input-field">
              <input id="keyword" type="text" class="validate" name="keyword">
              <label for="keyword" class="orange-text text-darken-2">Keyword</label>
            </div>
            <div class="input-field">
              <input id="items" type="text" name="items" value="<?=$items?>" size="15">
              <label for="items" class="orange-text text-darken-2">Items por pagina</label>
            </div>
            <button class="btn waves-effect waves-light orange darken-2" type="submit" name="action">Search
              <i class="material-icons right">send</i>
            </button>
          </form>
        </div>
        <div class="col s9" style="height: auto;">
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-3">
              <pre id="url" style="line-height: 12px; font-size: 0.8em; word-break: break-word; overflow: hidden;"></pre>
            </div>
          </div>
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-5">
              <pre id="result" style="line-height: 12px; font-size: 0.8em; max-height: 43vh;"></pre>
            </div>
          </div>
          <div id="paginator">
          <!-- <a href="http://localhost:8888/CI/Deposit/search/0?keyword=business&items=5">Test</a> -->
          </div>
        </div>
      </div>