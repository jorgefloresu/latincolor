
var Home  = {

    init: function () {
          Home.config = {
            medio:$('#medio'),
            sideMenu: $('.button-collapse'),
            sideMenuOpciones: $('.collapsible'),
            topMenuOpciones: $('.dropdown-button'),
            backgroundVideo: $('.parallax'),
            searchBar: $('#slide-search-bar'),
            optionButtons: $('.btn-flat'),
            searchField: $('input#search'),
            searchButton: $('.icon-search'),
            searchForm: $('#home-search'),
            searchWrapper: $('.search-wrapper'),

          }
          $('.btn-fotos').css('color','#FFF');
          Home.auth = new $.Auth();
          Home.shop = new $.Shop();

          Home.setup();
    },

    setup: function () {
          Login.init({auth:false});
          Home.setAuthentication();
          Home.setMaterial();
          Home.onWindowResize();
          Home.onSearchOptions();
          Home.onSearchField();
          Home.onSearchButton();
    },


    onWindowResize: function () {
          $(window).resize(function () {
            $("#wt").text($(this).width());
          })
    },

    setAuthentication: function() {
      Home.auth.setDefaults({
        onLogin: function (res) {
          if(res.status == 404) {
              Login.message('Usuario o password incorrecto');
          } else {
              Home.updateLoginElements(location.origin+'/latincolor/main/user', 'Hola, '+res.first_name, true);
              userData = res;
              Home.auth.init();
              Home.shop.displayCart();
          }
        },
        onLogout: function () {
          Home.updateLoginElements('#sign-in', 'Iniciar sesión', false);
        },
      });

    },

    updateLoginElements: function(url, text, activate) {
        Login.config.signin.attr('href', url)
                            .text(text);
        if (activate){
           Login.config.signUser.find('a#signup-user').hide();
           Login.config.signUser.find('a#sign-out').show();
           Login.close();
       } else {
           Login.config.signUser.find('a#sign-out').hide();
           Login.config.signUser.find('a#signup-user').show();
       }
    },

    onSearchOptions: function () {
          Home.config.searchBar.on("click", "a", function() {
            $(this).parent().find('a i').remove();
            Home.config.optionButtons.css("color","#CCC");
            Home.config.medio.val($(this).text());
            $(this).html('<i class="material-icons icon-options left">check</i>'+$(this).text());
            $(this).velocity({color:"#FFF"},{duration:600, queue: false}, "easeInSine");
          })
    },

    onSearchField: function () {
          Home.config.searchField.on({
            blur: function() {
                if (!$(this).val()) {
                  Home.config.searchWrapper.removeClass('focused');
                }
              },
            focus: function() {
                Home.config.searchWrapper.addClass('focused');
              }
          })
    },

    onSearchButton: function () {
          Home.config.searchButton.on({
            click: function () {
              Home.config.searchForm.submit();
            }
          })
    },

    setMaterial: function() {
              $(".modal").modal();
              Login.config.loginWindow.modal({
                ready: function(){
                  Home.shop.$element.modal('close');
                }
              });
              Home.config.sideMenu.sideNav();
              Home.config.sideMenuOpciones.collapsible();
              Home.config.backgroundVideo.parallax();
              Home.config.topMenuOpciones.dropdown({
                belowOrigin: true,
              })
    }

};

$(document).ready(Home.init);


  //   // $(window).on('load scroll', function () {
  //   //     var scrolled = $(this).scrollTop();
  //   //     $('#hero-vid').css('transform', 'translate3d(0, ' + -(scrolled * 0.25) + 'px, 0)'); // parallax (25% scroll rate)
  //   //   });
