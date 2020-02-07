<aside id="left-sidebar-nav">
    <ul id="slide-out" class="side-nav fixed leftside-navigation">
        <li class="user-details">
          <div class="row">
            <div class="col s4 m4 l4">
              <img src="<?php echo base_url('img/avatar.jpg')?>" alt="" class="circle responsive-img valign profile-image">
            </div>
            <div class="col s8 m8 l8">
              <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown">
                <?php echo $user_data->fname?><i class="mdi-navigation-arrow-drop-down right" style="margin:0; width:1.5rem"></i></a>
              <ul id="profile-dropdown" class="dropdown-content" style="width: 128px; position: absolute; top: 57px; left: 101.234px; opacity: 1; display: none;">
                  <li><a href="#"><i class="mdi-action-face-unlock"></i> Profile</a>
                  </li>
                  <li><a href="#"><i class="mdi-action-settings"></i> Settings</a>
                  </li>
                  <li><a href="#"><i class="mdi-communication-live-help"></i> Help</a>
                  </li>
                  <li class="divider"></li>
                  <li><a href="#"><i class="mdi-action-lock-outline"></i> Lock</a>
                  </li>
                  <li><a id="sign-out" href="<?php echo site_url('login/logout/admin')?>"><i class="mdi-hardware-keyboard-tab"></i> Logout</a>
                  </li>
              </ul>
              <p class="user-roal">Administrador</p>
            </div>
          </div>
        </li>
        <!--<li class="bold"><a href="index.html" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Dashboard</a>
        </li>-->
        <li class="bold <?php echo ($page=='config')?'active':''?>">
          <a href="<?php echo base_url("admin/config")?>" class="waves-effect waves-cyan">
            <i class="mdi-action-settings"></i> Configuración
          </a>
        </li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold <?php echo (substr($page,-4)=='list')?'active':''?>">
                  <a class="collapsible-header waves-effect waves-cyan <?php echo (substr($page,-4)=='list')?'active':''?>">
                    <i class="material-icons">table_chart</i> Tablas</a>
                    <div class="collapsible-body">
                        <ul>
                            <li class="<?php echo ($page=='activities_list')?'active':''?>">
                              <a href="<?php echo base_url("admin?table=activities")?>">Actividades</a>
                            </li>
                            <li class="<?php echo ($page=='planes_list')?'active':''?>">
                              <a href="<?php echo base_url("admin?table=planes")?>">Planes</a>
                            </li>
                            <li class="<?php echo ($page=='downloads_list')?'active':''?>">
                              <a href="<?php echo base_url("admin?table=downloads")?>">Descargas</a>
                            </li>
                            <li class="<?php echo ($page=='usuarios_list')?'active':''?>">
                              <a href="<?php echo base_url("admin?table=membership")?>">Usuarios</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <!--<li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-image-palette"></i> UI Elements</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="ui-buttons.html">Buttons</a>
                            </li>
                            <li><a href="ui-badges.html">Badges</a>
                            </li>
                            <li><a href="ui-cards.html">Cards</a>
                            </li>
                            <li><a href="ui-collections.html">Collections</a>
                            </li>
                            <li><a href="ui-accordions.html">Accordian</a>
                            </li>
                            <li><a href="ui-navbar.html">Navbar</a>
                            </li>
                            <li><a href="ui-pagination.html">Pagination</a>
                            </li>
                            <li><a href="ui-preloader.html">Preloader</a>
                            </li>
                            <li><a href="ui-modals.html">Modals</a>
                            </li>
                            <li><a href="ui-media.html">Media</a>
                            </li>
                            <li><a href="ui-toasts.html">Toasts</a>
                            </li>
                            <li><a href="ui-tooltip.html">Tooltip</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="bold"><a href="app-widget.html" class="waves-effect waves-cyan"><i class="mdi-device-now-widgets"></i> Widgets <span class="new badge"></span></a>
                </li>
                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-editor-border-all"></i> Tables</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="table-basic.html">Basic Tables</a>
                            </li>
                            <li><a href="table-data.html">Data Tables</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-editor-insert-comment"></i> Forms</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="form-elements.html">Form Elements</a>
                            </li>
                            <li><a href="form-layouts.html">Form Layouts</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-social-pages"></i> Pages</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="page-login.html">Login</a>
                            </li>
                            <li><a href="page-register.html">Register</a>
                            </li>
                            <li><a href="page-lock-screen.html">Lock Screen</a>
                            </li>
                            <li><a href="page-invoice.html">Invoice</a>
                            </li>
                            <li><a href="page-404.html">404</a>
                            </li>
                            <li><a href="page-500.html">500</a>
                            </li>
                            <li><a href="page-blank.html">Blank</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class="mdi-editor-insert-chart"></i> Charts</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="charts-chartjs.html">Chart JS</a>
                            </li>
                            <li><a href="charts-chartist.html">Chartist</a>
                            </li>
                            <li><a href="charts-morris.html">Morris Charts</a>
                            </li>
                            <li><a href="charts-xcharts.html">xCharts</a>
                            </li>
                            <li><a href="charts-flotcharts.html">Flot Charts</a>
                            </li>
                            <li><a href="charts-sparklines.html">Sparkline Charts</a>
                            </li>
                        </ul>
                    </div>
                </li>-->
            </ul>
        </li>
        <li class="bold <?php echo ($page=='consultas')?'active':''?>">
          <a href="<?php echo base_url("admin/consultas")?>" class="waves-effect waves-cyan">
            <i class="material-icons">pan_tool</i> Consultas
            <?php  if ($new_consultas > 0): ?>
              <span class="new badge red"><?php echo $new_consultas?></span>
            <?php  endif ?>
          </a>
        </li>
        <li class="li-hover"><div class="divider"></div></li>
        <li class="li-hover"><p class="ultra-small margin more-text">Ordenes y ventas diarias</p></li>
        <li class="badge-ordenes bold <?php echo ($page=='ordenes')?'active':''?>">
          <a href="<?php echo base_url('admin/ordenes')?>" class="waves-effect waves-cyan">
            <i class="mdi-action-description"></i> Ordenes
            <?php  if ($new_ordenes->ord > 0): ?>
              <span class="new badge red"><?php echo $new_ordenes->ord?></span>
            <?php  endif ?>
          </a>
        </li>
        <li class="badge-ventas bold <?php echo ($page=='ventas')?'active':''?>">
          <a href="<?php echo base_url('admin/ventas')?>" class="waves-effect waves-cyan">
            <i class="material-icons">monetization_on</i> Ventas
            <?php  if ($new_ventas > 0): ?>
              <span class="new badge red"><?php echo $new_ventas?></span>
            <?php  else: ?>
              <span></span>
            <?php  endif ?>
          </a>
        </li>
        <li class="li-hover"><div class="divider"></div></li>
        <li class="li-hover"><p class="ultra-small margin more-text">Proveedores</p></li>
        <li class="depositphoto bold <?php echo ($page=='depositphoto')?'active':''?>">
          <a href="<?php echo base_url("admin/depositphoto")?>" class="waves-effect waves-cyan">
            <i class="material-icons">photo_camera</i> Depositphotos
          </a>
        </li>
        <li><a href="css-grid.html"><i class="mdi-image-grid-on"></i> Grid</a>
        </li>
        <!--<li><a href="css-color.html"><i class="mdi-editor-format-color-fill"></i> Color</a>
        </li>
        <li><a href="css-helpers.html"><i class="mdi-communication-live-help"></i> Helpers</a>
        </li>
        <li><a href="changelogs.html"><i class="mdi-action-swap-vert-circle"></i> Changelogs</a>
        </li>-->
        <li class="li-hover">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="sample-chart-wrapper">
                        <div class="ct-chart ct-golden-section" id="ct2-chart"></div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <a href="#" data-activates="slide-out" class="sidebar-collapse hide-on-large-only btn-floating btn-medium waves-effect waves-light darken-2"><i class="mdi-navigation-menu" ></i></a>
</aside>
