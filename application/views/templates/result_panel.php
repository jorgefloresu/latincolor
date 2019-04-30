        <div class="row">
          <div class="col m2 l2 panel" style="display:none">
            <div class="pinned hide-on-med-and-down"
              style="top: 170px; bottom: 0; height: auto; width: 16%; overflow-y: scroll;display: none;">

                <!-- <div class="toc-wrapper" >
                  <div class="card">
                    <div class="" style="padding:5%">
                      <span class="" style="font-weight:bolder;">
                        <span class="new badge blue" data-badge-caption="seg.">
                          Calculando
                        </span>
                        Resultados
                      </span>
                      <p class="totalk grey-text" style="margin:5px 0;line-height:1rem;font-size:13px"></p>
                      <p class="totaln" style="margin-top:10px;margin-bottom: 0;font-size: 25px;font-weight:bolder;"></p>
                    </div>
                    <div class="row" style="margin-right: -2px;">
                      <canvas id="myChart" width="200" height="78"></canvas>
                    </div>
                  </div> -->
                <!--
                <div class="card small green darken-1">
                  <div class="card-content white-text">
                    <span class="card-title">Resultados</span>
                    <p></p>
                  </div>
                  <div class="card-action green lighten-3">
                    <a href="#">This is a link</a>
                    <a href="#">This is a link</a>
                  </div>
                </div>
                -->
                <!-- </div> -->
                <ul class="collapsible grey lighten-4" style="width:100%">
                  <li class="white-text indigo darken-4" style="padding:13px 0;font-size: 0.98rem;font-weight: 100;text-align:center">FILTROS DE BÚSQUEDA</li>
                  <li class="grey lighten-4 center-align" style="padding:15px 10px;">
                    <img class="search-type-icon" src="<?=$icon?>">
                    <!-- <i class="material-icons medium">crop_original</i> -->
                    <span class="estas-buscando" style="display:block;"></span>
                    <span class="this-keyword" style="font-weight:900;text-transform:uppercase;">RESULTADOS</span>
                  </li>
                  <li>
                    <div class="collapsible-header valign-wrapper grey lighten-2">ORIENTACION</div>
                    <div class="collapsible-body" style="padding:0">
                      <ul class="collection orientacion-options grey lighten-4">
                        <!-- <li class="collection-item avatar">
                          <i class="material-icons circle green">insert_chart</i>
                          <span class="title">Imágenes por página</span>
                          <p>Cantidad mostrada: <span class="count-on-page"></span><br>
                        </li> -->
                        <? $data['options'] = array(
                                    'or-all' => array('title'=>'', 'name'=>'Todas'),
                                    'or-hor' => array('title'=>'', 'name'=>'Horizontal'),
                                    'or-ver' => array('title'=>'', 'name'=>'Vertical'),
                                    'or-sqa' => array('title'=>'', 'name'=>'Cuadrada')
                                  );
                           $data['field'] = $orientacion;
                           $data['option_class'] = 'or-options';
                           $this->load->view('templates/ctrl/ctrl-options',$data) ?>
                      </ul>
                    </div>
                  </li>
                  <li>
                    <div class="collapsible-header valign-wrapper grey lighten-2">COLOR</div>
                    <div class="collapsible-body grey lighten-3" style="padding:0">
                      <ul class="collection color-options">
                        <!-- <li class="collection-item avatar">
                          <i class="material-icons circle green">insert_chart</i>
                          <span class="title">Imágenes por página</span> -->
                          <!-- <p>Cantidad mostrada: <span class="count-on-page"></span><br> -->
                        <? $data['options'] = array(
                                    'co-all' => array('title'=>'', 'name'=>'Todos'),
                                    'co-byn' => array('title'=>'', 'name'=>'Blanco/Negro')
                                  );
                           $data['field'] = $color;
                           $data['option_class'] = 'co-options';
                           $this->load->view('templates/ctrl/ctrl-options',$data) ?>

                      </ul>
                    </div>
                  </li>
                  <!-- <li class="active">
                    <div class="collapsible-header valign-wrapper active grey lighten-2">MEDIOS</div>
                    <div class="collapsible-body grey lighten-3" style="padding:0">
                      <ul class="collection media-options">
                        <? //$data['options'] = array(
                           //         'md-fotos' => array('title'=>'', 'name'=>'Fotos'),
                           //         'md-vectores' => array('title'=>'', 'name'=>'Vectores'),
                           //         'md-videos' => array('title'=>'', 'name'=>'Videos')
                           //        );
                           //$data['field'] = $medio;
                           //$data['option_class'] = 'md-options';
                           //$this->load->view('templates/ctrl/ctrl-options',$data) ?>

                      </ul>
                    </div>
                  </li> -->
                  <li class="grey lighten-4 center-align">
                    <div style="padding:15px 0">
                        <span class="title" style="font-size:12px">Resultados por página</span>
                        <form action="#" style="margin-bottom:-10px">
                         <p class="range-field container">
                         <input type="range" id="test5" min="0" max="200" step="20" value="<?=$range?>" />
                         </p>
                        </form>
                        <span class="title" style="font-size:12px">0</span>
                        <span class="title" style="font-size:12px; margin-left:70%">200</span>
                    </div>
                  </li>
                  <ul class="collection grey lighten-2">
                    <li class="collection-item collection-options center-align">
                      <a id="aplicar-op" href="#!" class="btn-flat waves-effect waves-light"><i class="material-icons left">filter_list</i>Aplicar filtro</a>
                    </li>
                  </ul>
              </ul>
            </div>
          </div>
          <div class="col s12 m12 l10 result">
          <!-- <div class="col s12 m12 l10 result justified-gallery" style="margin-top: 60px"> -->
            <!-- <div id="result" style="overflow: auto; max-height: 80vh"></div> -->
          </div>
        </div>
