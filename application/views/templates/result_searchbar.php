<nav id="search-bar" class="white">
    <div class="nav-wrapper row">
      <div class="col s3 m3">
        <ul class="left">
          <li><a href="#" data-activates="mobile-demo" class="button-collapse show-on-large">
            <i class="mdi-navigation-menu"></i></a></li>

        <!--  <li><a href="#" class="brand-logo left">Search</a></li> -->         
        </ul>
      </div>  
      <div class="col s6 m6">     
        <form action="<?php echo $form_action; ?>" id="navform">
        <div class="header-search-wrapper">
                <i class="mdi-action-search"></i>
                <input type="text" id="keywords" name="keywords" placeholder="Enter the keywords" 
                  value="<?php echo $keywords; ?>" class="header-search-input z-depth-2" />
                <a id="send-query" href="javascript: submitform()">
                <i class="mdi-content-send"></i></a>
<!--          <div id="loadwait" class="hide">
            <img src="<?php echo base_url('js/294.gif'); ?>">
          </div>
-->                       
        </div>
        </form>
      </div>
      <div class="col s3 m3">
        <ul class="right hide-on-med-and-down">
          <li id="loadwait" class="hide"><a href="#"><i class="fa fa-refresh fa-spin"></i></a></li>
          <li id="sort"><a href="#"><i class="small material-icons">toll</i></a></li>
          <li id="view-card" class="active"><a href="#"><i class="small material-icons">view_module</i></a></li>
          <li id="view-fit"><a href="#"><i class="small material-icons">view_quilt</i></a></li>
          <li><a href="#" data-activates="chat-out" class="chat-collapse"><i class="small material-icons">more_vert</i></a></li>
<!--          <li id="options"><a href="#"><i class="small material-icons">settings</i></a></li> -->
<!--          <li id="usercart"><a href="#">
            <i class="small mdi-action-shopping-cart"><?php if ($logged && $incart>0): ?><span class="task-cat red"><?php echo $incart; ?></span><?php endif; ?></i></a></li> -->
<!--          <li id="loguser">
          <?php if ($logged): ?>
              <ul id="profile-dropdown" class="dropdown-content">
                          <li><a href="#"><i class="mdi-action-face-unlock"></i> Profile</a></li>
                          <li><a href="#"><i class="mdi-action-settings"></i> Settings</a></li>
                          <li><a href="#"><i class="mdi-communication-live-help"></i> Help</a></li>
                          <li class="divider"></li>
                          <li><a href="#"><i class="mdi-action-lock-outline"></i> Lock</a></li>
                          <li><a href="javascript: logout()">
                            <i class="mdi-hardware-keyboard-tab"></i> Logout</a>
                          </li>
                    </ul>
            <a href="#" class="dropdown-button profile-btn" data-activates="profile-dropdown">
              <i class="mdi-social-person-outline"><span><?php echo $logged; ?></span>
                <i class="mdi-navigation-arrow-drop-down right" id="arrow-beside"></i></i>
            </a>
          <?php else: ?>
            <a href="#" id="login2">Log In</a>
          <?php endif; ?>

          </li>
-->          
        </ul>
      </div>
      </div>
</nav>
