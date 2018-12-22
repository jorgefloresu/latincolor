      <div class="row">
        <div id="cart" class="col s3">
          <form id="media" action="<?php echo site_url('deposit/imgdetail'); ?>">
            <div class="input-field">
              <input id="mediaid" type="text" class="validate" name="mediaid">
              <label for="mediaid" class="orange-text text-darken-2">Media ID</label>
            </div>
            <button class="btn waves-effect waves-light orange darken-2" type="submit" name="action">Get data
              <i class="material-icons right">send</i>
            </button>
            <button class="btn waves-effect waves-light orange darken-2" type="button" name="savetocart"><i class="material-icons">shopping_cart</i>
            </button>
            <div class="divider" style="margin-top: 20px;"></div>
            <div class="section">
              <div id="mediasizes" class="input-field">
                <select id="media-sizes">
                  <option value="" disabled selected>Choose your option</option>
                </select>
                <label>Media Sizes</label>
              </div>
            </div>
            <div class="input-field">
              <input id="mediaPrice" type="text" name="price" placeholder="Select media" value="">
              <label for="mediaPrice" class="orange-text text-darken-2">Price</label>
            </div>
            <p>
              <input name="license" type="radio" id="standard" value="standard" checked />
              <label for="standard">Standard</label>
            </p>
            <p>
              <input class="orange darken-2" name="license" type="radio" id="extended" value="extended" />
              <label for="extended">Extended</label>
            </p>
          </form>
        </div>
        <div class="col s9" style="height: auto;">
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-3">
              <pre id="urlMedia" style="line-height: 12px; font-size: 0.8em; word-break: break-word; overflow: hidden;"></pre>
            </div>
          </div>
          <div class="row" style="margin-bottom: 0px;">
            <div class="col s12 card-panel grey lighten-5">
              <pre id="resultMedia" style="line-height: 12px; font-size: 0.8em; max-height: 47vh;"></pre>
            </div>
          </div>
        </div>
      </div>