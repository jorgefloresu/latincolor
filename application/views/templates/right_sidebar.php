<!-- START RIGHT SIDEBAR NAV-->
<aside id="right-sidebar-nav">
    <ul id="chat-out" class="side-nav rightside-navigation">
        <li>
            <a href="#" data-activates="chat-out" class="chat-close-collapse right" style="padding:0 0 0 12px;margin-right:-22px">
                <i class="material-icons">close</i>
            </a>
            <div class="user-profile" style="padding: 15px 0 0 15px">
                <p class="collections-title"><b><?=$user_data->fname?></b></p>
                <p class="email collections-content" style="font-weight: 300"><?=$user_data->email_address?></p>
                <p><a href="<?=base_url('main/user')?>">Ver mi cuenta</a></p>
            </div>
        </li>
        <li class="li-hover">
            <ul class="chat-collapsible" data-collapsible="expandable">
            <li id="user-purchases" data-url="<?php echo site_url('transactions/view_cart');?>">
                <div class="collapsible-header teal white-text active">
                    <i class="material-icons">whatshot</i>Imagenes Compradas
                </div>
                <div class="collapsible-body recent-activity">
                    <div class="image-list" style="padding-top: 15px"></div>
                </div>
            </li>
            <li id="user-payments" data-url="<?php echo site_url('transactions/view_cart');?>">
                <div class="collapsible-header light-blue white-text active">
                <i class="material-icons">attach_money</i>Sales Report</div>
                <div class="collapsible-body sales-repoart">
                    <div class="sales-repoart-list  chat-out-list row">
                        <div class="col s8">Target Salse</div>
                        <div class="col s4"><span id="sales-line-1"></span>
                        </div>
                    </div>
                    <div class="sales-repoart-list chat-out-list row">
                        <div class="col s8">Payment Due</div>
                        <div class="col s4"><span id="sales-bar-1"></span>
                        </div>
                    </div>
                    <div class="sales-repoart-list chat-out-list row">
                        <div class="col s8">Total Delivery</div>
                        <div class="col s4"><span id="sales-line-2"></span>
                        </div>
                    </div>
                    <div class="sales-repoart-list chat-out-list row">
                        <div class="col s8">Total Progress</div>
                        <div class="col s4"><span id="sales-bar-2"></span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="collapsible-header red white-text">
                <i class="material-icons">stars</i>Favorite Associates</div>
                <div class="collapsible-body favorite-associates">
                    <div class="favorite-associate-list chat-out-list row">
                        <div class="col s4"><img src="" alt="" class="circle responsive-img online-user valign profile-image">
                        </div>
                        <div class="col s8">
                            <p>Eileen Sideways</p>
                            <p class="place">Los Angeles, CA</p>
                        </div>
                    </div>
                    <div class="favorite-associate-list chat-out-list row">
                        <div class="col s4"><img src="" alt="" class="circle responsive-img online-user valign profile-image">
                        </div>
                        <div class="col s8">
                            <p>Zaham Sindil</p>
                            <p class="place">San Francisco, CA</p>
                        </div>
                    </div>
                    <div class="favorite-associate-list chat-out-list row">
                        <div class="col s4"><img src="" alt="" class="circle responsive-img offline-user valign profile-image">
                        </div>
                        <div class="col s8">
                            <p>Renov Leongal</p>
                            <p class="place">Cebu City, Philippines</p>
                        </div>
                    </div>
                    <div class="favorite-associate-list chat-out-list row">
                        <div class="col s4"><img src="" alt="" class="circle responsive-img online-user valign profile-image">
                        </div>
                        <div class="col s8">
                            <p>Weno Carasbong</p>
                            <p>Tokyo, Japan</p>
                        </div>
                    </div>
                    <div class="favorite-associate-list chat-out-list row">
                        <div class="col s4"><img src="" alt="" class="circle responsive-img offline-user valign profile-image">
                        </div>
                        <div class="col s8">
                            <p>Nusja Nawancali</p>
                            <p class="place">Bangkok, Thailand</p>
                        </div>
                    </div>
                </div>
            </li>
            </ul>
        </li>
    </ul>
  </aside>
  <!-- END RIGHT SIDEBAR NAV-->
