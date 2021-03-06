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
          reDownload: $('#user-purchases .image-list')
        }
        Login.init();
        this.shop = new $.Shop();
        this.setup();

  },

  setup: function() {
          let self = this;
          this.setMaterial();
          this.doAfterLogin();
          this.onRedownload();
          this.config.userNav.on('click', function(){
            self.config.rightNav.sideNav('hide');
          });
          this.config.searchOptions.on('click', 'a', function(){
            self.menuMedios(this, self);
          });
          this.config.iconSearch.on('click', function(){
            self.config.searchForm.submit();
          });
  },

  onRedownload: function() {
    let provider, imageId, type;
    this.config.reDownload.on('click', 'a.redownload', function(){
      provider = $(this).data('provider');
      imageId = $(this).data('id');
      type = $(this).data('type');
      let form = {
        url: ROOT + 'main/reDownload',
        inputs: {
          lid: $(this).data('lid'),
          provider: provider,
        }
      }
      $('#download-progress').modal('open');
      $.submitForm(form, getFile);
    });

    function getFile(data) {
      console.log(data);
      if (data.error) {
        $('.msg-redownload').show();
      } else {
          $('.cnt').text('1 de 1');
          $('#download-progress p').text(' Esperando a recibir el archivo...');
          $.loadFile(data.downloadLink, function (file) {
            let format = (type=='video'?'.mp4':'');
            $.saveFile(file, 'LCI-'+provider.substring(0,2)+'-'+imageId+'-rdw'+format);
          });
      }
      //window.location = data.downloadLink;
    }
  },


  doAfterLogin: function() {
          let self = this;
          $('body').off('login').on('login', function(e) {
                userData = $(this).data();
                Login.auth.refreshSettings();
      
                if ($.Auth.info('deposit_userid') == '') {
                  $('#plan-info').remove();
                } else {
                  let container = '<li id="plan-info" data-url="">'+
                      '<div class="collapsible-header green white-text active">'+
                      '<i class="material-icons">monetization_on</i>Datos de tu plan'+
                      '</div>'+
                      '<div class="collapsible-body recent-activity">'+
                      '<div class="image-list" style="padding-top: 15px"></div>'+
                      '</div>'+
                      '</li>';
                  $('.chat-collapsible').prepend(container);
                }
      
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
            $('.button-collapse').sideNav();
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
