<nav id="topbar" class="grey lighten-3 z-depth-0">
    <div class="nav-wrapper">
      <ul class="left">
          <li><a href="#" id="top-images">TOP IMAGES</a></li>
          <li><a class="dropdown-menu" href="#">CATEGORIES<i class="mdi-navigation-arrow-drop-down right closer-right"></i></a></li>          
          <li><a class="dropdown-menu" href="#">COLOR<i class="mdi-image-color-lens right closer-right"></i></a></li>
          <li><a href="#" id="search-link" class="search">SEARCH<i class="mdi-action-search right closer-right"></i></a></li>         
        </ul>       
        <ul class="right hide-on-med-and-down">
          <li id="loguser">
            <?php if ($logged): ?>
              <ul id="user-dropdown" class="dropdown-content">
                  <li><a href="#" onclick="javascript: user_profile()"><i class="mdi-action-face-unlock"></i> Profile</a></li>
                  <li><a href="javascript: logout()">
                    <i class="mdi-hardware-keyboard-tab"></i> Logout</a>
                  </li>
              </ul>
              <a href="#" class="dropdown-button" data-activates="user-dropdown">
                <i class="mdi-social-person-outline left closer-left"></i>
                <span><?php echo $logged; ?></span>
                <i class="mdi-navigation-arrow-drop-down right closer-right"></i></a>
            <?php else: ?>
              <a href="#" id="login"><i class="mdi-action-lock-outline left closer-left"></i><span>LOGIN | SIGN UP</span></a>
            <?php endif; ?>
          </li>
          <li id="top-usercart"><a href="#">
            <?php if ($logged && $incart>0): ?>
            <i class="material-icons left closer-left red-text">cloud</i><div class="cart-total">
            <span class="task-cat"><?php echo $incart; ?></span></div>
            <?php else: ?>
            <i class="material-icons left closer-left tiny">cloud</i><div class="cart-total"></div>
            <?php endif; ?>
            MY CLOUD</a></li>
          <li id="top-options"><a href="#"><i class="fa fa-cog"></i></a></li>
      </ul>
    </div>
</nav>
