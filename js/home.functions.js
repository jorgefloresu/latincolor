
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
          Home.onSearchById();
          Home.onSearchKeyword();
          Home.changeHeader();
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
              Home.updateLoginElements(ROOT+'main/user', 'Hola, '+res.first_name, true);
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
          Home.config.searchBar.on("click", "a.btn-flat", function() {
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

    onSearchById: function() {
        $('#home-search .busca-id').on('click', function(event) {
          event.preventDefault();
          $('#provider').val($(this).text());
          Home.config.searchForm.submit();
          //let previewUrl = ROOT + 'main/preview/'+findStr+'/?provider='+provider;
          //$('<a href="'+previewUrl+'"></a>').appendTo(setup.config.resultSearch).click();

          //findByImageId(setup.config.keyword.val(), $(this).text())                
        });

    },

    onSearchKeyword: function() {
      Home.config.searchField.on('keyup', function(){
        let val = $(this).val()
        if ( /^\d+$/.test(val) ) {
          $('#home-search .escoge-marca').show()
        } else {
          $('#home-search .escoge-marca').hide()                  
        }
      });

    },

    changeHeader: function () {
      let counter = 0;
      let headers = [
                     'Videos en todas las resoluciones <br/> para todo uso Web, HD y 4K', 
                     'Imágenes vectoriales para expresar <br/> tus ideas creativas con calidad',
                     'Fotografías en alta calidad <br/> para diseño publicitario'
                    ];
      let maxCounter = headers.length - 1;
      setInterval(function() {
        $('.home h2').velocity(
          {opacity:0},
          {complete: function(el){
            $('.home h2').html(headers[counter]);
            if (counter == maxCounter) {
              counter = 0; //reset to start
            } else {
                ++counter; //iterate to next image
            }
          }
        });
        $('.home h2').velocity(
          {opacity:1},
          "easeInSine"
        )
      }, 4000)
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
