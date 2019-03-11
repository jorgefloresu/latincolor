(function ( $ ){

$.Common = function(setup) {
  this.init();
  if (setup !== undefined)
    setup();
}

$.Common.prototype = {
  init: function() {
        this.config = {
          userNav: $(".chat-close-collapse"),
          rightNav: $(".chat-collapse"),
          iconSearch: $('.icon-search'),
          searchForm: $('#search'),
          searchOptions: $(".menu-medios li"),
          keyword: $('#keyword'),
        }
        Login.init();
        this.shop = new $.Shop();
        this.setup();

  },

  setup: function() {
          let self = this;
          this.setMaterial();
          this.doAfterLogin();
          this.config.userNav.on('click', function(){
            self.config.rightNav.sideNav('hide');
          });
          this.config.searchOptions.on('click', 'a', function(){
            self.menuMedios(this, self);
          });
          this.config.iconSearch.on('click', function(){
            self.config.searchForm.submit();
          });
          $('.chat-collapsible').on('click','li', function(){
            $.getPurchases($(this));
          })
  },


  doAfterLogin: function() {
          let self = this;
          $('body').on('login', function(e) {
              userData = $(this).data();
              Login.auth.init();
              $('.chat-collapsible').collapsible(self.collapsibleOpts);
              self.shop.displayCart();
          });
  },

  menuMedios: function (current, main) {
           $("#medio").val($(current).data('option'));
           $('.menu-medios li').removeClass('active');
           $(current).parent().addClass('active');
           if (main.config.keyword.val()) {
               main.config.searchForm.submit();
           }
  },

  collapsibleOpts: {
    accordion : true,
    onOpen : function(el) {
              if (el.attr('id')=='user-purchases')
                $.getPurchases(el);
            }
  },

  setMaterial: function() {
            let self = this;
            $(".modal").modal();
            Login.config.loginWindow.modal({
              ready: function(){
                self.shop.$element.modal('close');
              }
            });
            $(".chat-collapse").sideNav({
              edge: 'right'
            });
            $(".dropdown-trigger").dropdown();
            $('.chat-collapsible').collapsible(self.collapsibleOpts);
            $('.dropdown-button').dropdown({
              belowOrigin: true,
            });
            $('.parallax').parallax();
  },

}
}(jQuery));

//$(document).ready(this.init);
