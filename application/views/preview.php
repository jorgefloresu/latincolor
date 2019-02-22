<div class="imgdetail modal">

  <div class="imgdetail-title row" style="">
    <div style="float:left;margin:8px;color:#757575">
      <img id="icon-type" src="<?=base_url('img/Fotos-50.png')?>">
      <!-- <i id="icon-type" class="fas fa-image fa-2x"></i> -->
    </div>
    <div style="padding-top:10px; /*width:95%;float:left*/">
      <a href="#" class="imgdetail-close right"><i class="material-icons">cancel</i></a>
      <h5 id="title"></h5>
    </div>
  </div>
  <div class="imgdetail-subtitle row">
    <div class="col s12 m6 l6">
      <!-- <a href="#" class="right" style="padding-right:90px"><i class='material-icons'>file_download</i></a>
        <a href="#" class="right" style="padding-right:10px"><i class='material-icons'>add_shopping_cart</i></a> -->
      <span id="subtitle"></span>
    </div>
  </div>
  <div class="row" style="width:97%">

    <div id="imgheight-container" class="imgheight col s12 m7 center">
      <div id="loading" class="">
        <a class="popup-link" href="" title=""><img src="" class="responsive-img" /></a>
        <video class="responsive-video" controls poster="" width="600" height="300">
          <source class="webm" src="" type="video/webm">
          <source class="mp4" src="" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>

    </div>

    <div class="section col s12 m5 l5 right">
      <!-- <div class="divider" style="background-color: #606060"></div> -->

      <div id="prices" class="card" style="margin:0 0 20px 0">
        <!-- <div class="card-image" style="height: 50px;border-bottom: 1px solid #CCC;background-color:#e0e0e0">
                    <span class="card-title black-text" style="padding:7px">Nuestros precios</span>
                  </div> -->
        <div class="card-content" style="padding: 0 0 10px 0">
          <table>
            <thead>
              <tr style="background-color:#CCC">
                <!-- <th>Size</th>
                              <th>Dimension</th>
                              <th>Price</th> -->
                <th class="center-align">ESCOGE EL TAMAÑO</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        <div class="card-action">
          <!-- Dropdown Trigger -->
          <a class='dropdown-button dropdown-plan waves-effect waves-light white btn tooltipped' 
            data-position="top" href='#' data-activates='plan-activo' 
            style="border: .12rem solid blue;color: blue; padding:0 1rem">Elige Plan</a>

          <!-- Dropdown Structure -->
          <ul id='plan-activo' class='dropdown-content'>
          </ul>
          <a href="<?=site_url('main/planes')?>" class="waves-effect waves-light btn" style="background-color: transparent;border: .12rem solid #f12088;color: #f12088;padding:0 1rem">
            Ver planes</a>
          <a href="#" class="direct-download waves-effect waves-light btn blue" style="padding:0 12px; display:none"><i class="material-icons">file_download</i></a>
          <a href="#" class="menu-cart waves-effect waves-light tooltipped btn right" data-tooltip="Carrito vacío" style="background-color:#1c9414; padding:0 12px">
            <i class="material-icons">shopping_cart</i></a>
        </div>
      </div>

    </div>

  </div>
  <div class="row container similar-container">
    <div>
      <h5>Imágenes relacionadas</h5>
    </div>
    <div class="row similar">
    </div>
  </div>
  <div class="row container similar-container">
    <table>
      <thead>
        <tr>
          <td>Palabras clave</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <p id="keywords-preview" class="grey-text" style="font-size: 11px;line-height:1rem"></p>
          </td>
        </tr>
      </tbody>
    </table>

  </div>
</div>

</div>