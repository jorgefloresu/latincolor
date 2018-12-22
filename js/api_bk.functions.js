var Api = ( function() {

  var setup = {

      setPreview: function(settings) {
              setup.preview = {
                title: $('#title'),
                subtitle: $('#subtitle'),
                image: $('#loading img'),
                prices: $('#prices table tbody'),
                keywords: $('#keywords-preview')
              }
              $.extend(setup.preview, settings);
            },

      init: function(settings) {
            setup.config = {
              // Search controls
              searchForm: $("#search"),
              searchPanel: $(".pinned"),
              searchTotal: $(".toc-wrapper"),
              resultSearch: $(".result"),             // Contenedor del JSON de la respuesta de search
              fullUrlSearch: $("#url"),               // Contenedor del url que se ejecuta del search
              provider: $('#provider'),
              searchIn: $('#search-in'),
              keyword: $('#keyword'),
              range: $('#range'),
              providersList: $('.dropdown-providers'),

              previewWindow: $('.imgdetail'),
              previewClose: $('.imgdetail-close'),

              mediaForm: $("#media"),
              resultMedia: $("#resultMedia"),         // Contenedor del JSON de la respuesta de getMediaData
              fullUrlMedia: $("#urlMedia"),           // Contenedor del url que se ejecuta del getMediaData
              mediaSizes: $("#media-sizes"),
              mediaPrice: $("#mediaPrice"),

              loginWindow: $('#sign-in'),
              loginForm: $(".login-form"),
              resultLogin: $("#resultlogin"),         // Contenedor del JSON de la respuesta de login
              sessionId: $("input[name=sessionid]"),  // Muestra el ID de la session
              sessionIdText: $("#sessionidtext"),
              loginBtn: $(".loginform button"),       // Contenedor del boton login
              loginMenu: $("#login-menu"),
              logout: $("#logout"),
              logUser: $("#loguser"),
              userAccount: $(".chat-collapsible"),
              userProfile: $(".user-profile"),

              subAccountForm: $("#subaccountform"),
              subAccounts: $("#subaccounts"),
              resultSubaccounts: $("#resultsubaccounts"),
              subAccountId: $("input[name=subaccountid]"),
              subAccountIdText: $("#subAccountIdText"),

              subscriptionsForm: $("#subscriptionsForm"),
              resultSubscriptions: $("#resultSubscriptions"),
              subsPeriod: $("#subsPeriod"),
              subsCount: $("#subsCount"),
              subsBtn: $("#subsBtn"),
              subsPlans: $("#subsPlans"),

              cart: $("#cart"),
              saveToCart: $("#savetocart"),

              paginator: $("#paginator"),             // Contenedor del HTML del paginador
              progress: $(".progress"),
              error: $("#error"),

              tabs: $('ul.tabs')
            };
            $.extend(setup.config, settings);
          },

  }

  var collapsibleOpts = {
            accordion : true,
            onOpen : function(el) {
                      if (el.attr('id')=='user-purchases')
                        getPurchases(el);
                    }
          }

  var getPurchases = function(el) {
            el.find('.image-list').empty();
            var url = el.data('url')+'?username='+setup.config.loginMenu.getUsername();
            $.getJSONfrom(url).then(function(res) {
                $.each(res, function(index, val) {
                  var html = Templates.userImageList.replace('src=""','src="'+val.img_url+'"')
                                                    .replace('Code', val.img_code)
                                                    .replace('Provider', val.img_provider);
                  el.find('.image-list').append(html)
                })
            })
          }

  var getTotalPayments =  function(el) {

  }

  // Asigna el contenido a los contenedores relacionados con el search
  var searchFields = function(data) {
              //setup.config.resultSearch.html(JSON.stringify(data.source, null, 3));
              //$('#result').html(JSON.stringify(data.source, null, 3));
              setup.config.fullUrlSearch.html(data.fullurl);
              setup.config.paginator.html(data.pags);
              setup.config.resultSearch.html('');
              //if (!data.fullurl) {
              setup.config.searchTotal.html(Templates.resultPanel);
              setup.config.searchTotal.find('p span').html(data.keyword);
              setup.config.searchTotal.find('span span').html(data.time);
              setup.config.searchTotal.find('h5').html(data.total);
              $('.count-on-page').text(data.count);
              var tabUrl = $('.'+setup.config.provider.val()).data('url');
              var keywordUrl = /keyword=([^&]+)/.exec(tabUrl)[1].replace(/\+/g," ");
              if (keywordUrl != setup.config.keyword.val()){
                tabUrl = tabUrl.replace(keywordUrl, setup.config.keyword.val());
                setup.config.searchTotal.find('p.try-other')
                                        .html(Templates.btnTryWith(tabUrl, setup.config.keyword.val()));
              }
              //}
              //console.log(data.source);
              document.body.scrollTop = 0; // For Chrome, Safari and Opera
              document.documentElement.scrollTop = 0; // For IE and Firefox
          }

  // Asigna el contenido a los contenedores relacionados con el getMediaData
  var mediaFields = function(data) {
              setup.config.resultMedia.html(JSON.stringify(data.source, null, 3));
              setup.config.fullUrlMedia.html(data.fullurl);
              setup.config.mediaSizes.html(data.sizes);
              var price = setup.config.mediaSizes.val().split('-')[1];
              setup.config.mediaPrice.val(price);
              setup.config.saveToCart.toggleClass('disabled',false);
              setup.config.saveToCart.data('desc',data.source.title);

              //$("select").material_select();
              //Materialize.updateTextFields();
          }

  var previewFields = function(data) {
              //setup.config.previewWindow.html(data.page);
              console.log(data);
              if (data) {
                setup.setPreview();
                setup.preview.title.html(data.id);
                setup.preview.subtitle.html(data.title);
                setup.preview.image.attr('src', data.image);
                setup.preview.prices.html(data.sizes);
                setup.preview.keywords.html(data.keywords);
                setup.preview.image.load(function(){
                  if (setup.preview.image.height() > setup.preview.image.width()) {
                    setup.preview.image.attr('width','60%');
                  }else{
                    setup.preview.image.attr('width','90%');
                  }
                  $("#loading").css('margin-top', ($(".imgdetail").height() - $(this).height()) / 2 );
                })
              }
              setup.config.previewWindow.velocity({top:"90px", opacity:1}, {duration:600, queue: false}, "easeInSine");
          }

  var mediaSizesChange = function() {
              var size = setup.config.mediaSizes.val();
              var price = size.split('-')[1];
              setup.config.mediaPrice.val(price);

              Materialize.updateTextFields();
          }

  // Asigna el contenido a los contenedores relacionados con el login
  var loginFields = function(data) {
              //setup.config.loginForm.attr('action', data.action);
              //setup.config.resultLogin.html(JSON.stringify(data.source, null, 3));
              //var _sessionid = (data.sessionid ? data.sessionid : String.fromCharCode(160));
              //setup.config.sessionIdText.text(_sessionid);
              //setup.config.sessionId.val(data.sessionid);
              //setup.config.loginBtn.html(data.loglabel);
              //setup.config.logged.toggle();
              setup.config.userProfile.find('b').text(data.first_name+' '+data.last_name);
              setup.config.userProfile.find('.email').text(data.email_address);
              setup.config.loginWindow.modal('close');

              setup.config.loginMenu.attr({
                'href': '#sign-out',
                'class': 'grey-text text-darken-3 dropdown-button',
                'data-activates': 'user-dropdown'
              });
              setup.config.loginMenu.html(Templates.iconLoginDD(data.username));
              $(".dropdown-button").dropdown();

          }

  var logout = function(data){
            $("body").click();
            setup.config.loginMenu.remove();
            setup.config.logUser.append(Templates.menuSignIn);
            setup.init();
            setup.config.loginMenu.html(Templates.iconLogin+'Sign in');

          }

  var subaccountsFields = function(data) {
              setup.config.resultSubaccounts.html(JSON.stringify(data.source, null, 3));
              setup.config.subAccounts.html(data.subaccounts);
              setup.config.subAccountId.val(setup.config.subAccounts.val());
              setup.config.subAccountIdText.text(setup.config.subAccounts.val());

              $("select").material_select();
          }

  var subAccountChange = function() {
              setup.config.subAccountId.val($(this).val());
              setup.config.subAccountIdText.text($(this).val());
              setup.config.cart.forSubAccount($(this).val());

          }

  var subscriptionsFields = function(data) {
              setup.config.resultSubscriptions.html(JSON.stringify(data.source, null, 3));
              setup.config.subsPeriod.html(data.subsPeriod);
              setup.config.subsCount.html(data.subsCount);
              setup.config.subsPlans.html(data.subsPlans);

              $("select").material_select();
          }

  var cartFields = function(storage) {
              //setup.config.subAccountId.val(data.subAccountId);
              var media = this.storage.get('media');
              alert(media.id);
          }

  var loginMethod = function(event) {
              event.preventDefault();
              var formData = $(this).prepareData();
              if ($(this).attr('id') == 'sign-out')
                  $.submitForm(formData, logout);
              else
                  $.submitForm(formData, loginFields);
          }

  var showResult = function(data) {
            console.log(data);
            searchFields(data);
            $.when(addImageCards(data)).done(function(){
              if (data.result.length == 0)
                $('#paginator').hide();
              else {
                $('#paginator').show();
              }
            });

          }

  var complement = function (ipp) {
            var loc = location.href;
            var strpage = loc.substring(loc.lastIndexOf("/")+1,loc.lastIndexOf("?"));
            //var page = parseInt(strpage) + parseInt(setup.config.range.val());
            var page = parseInt(strpage) + 1;
            var form = {
              url: location.origin+'/latincolor/main/search/'+strpage,
              inputs: {
                keyword: setup.config.keyword.val(),
                provider: setup.config.provider.val(),
                orientacion: '',
                range: setup.config.range.val(),
                np: ipp
              }
            }
            $(form).getDataProvider().done(addImageCards).done(function(data) {
                setup.config.paginator.html(data.pags);
                $('.count-on-page').text(function(){
                  return $(this).text()+'+'+data.count;
                });
            });
          }

  var addImageCards = function(data){
            return $.Deferred(function(defer){
              $.each(data.result, function(index, item) {
                $(Templates.cardTemplate).attr('id', item.id).appendTo(setup.config.resultSearch);
                $.loadImage(item.thumb).done(function(res){
                  var card = setup.config.resultSearch.find('#'+item.id);
                  var img = '<img src="'+res+'">';
                  card.find('.card-title').text(index+' - '+item.id);
                  card.find('.card-image').html(img);
                  card.find('.card-action').html(Templates.cardAction(data.preview+item.id, item.provider));
                  //card.find('.card-action').html(Templates.cardAction(data.preview+item.id, setup.config.provider.val()));
                });
              });
              defer.resolve();
            }).promise();
          };

  var dismissPreview = function(event) {
            event.preventDefault();
            setup.config.previewWindow.velocity({top:"-800px", opacity:0}, {duration:600, queue: false}, "easeInSine");
            setup.preview.image.attr('src', '');

          }

  var mainMethod = function(event) {
            event.preventDefault();
            if ($(this).parent().hasClass('active')) return false;
            if (setup.config.keyword.val()){
              setup.config.searchPanel.show();
              /*if ($(this).is("form")) {
                setup.config.searchTotal.html(Templates.fakeCounter);
                $('.timer').countTo({
                    from: 50,
                    to: 2500,
                    speed: 5000,
                    refreshInterval: 50,
                    onComplete: function(value) {
                        console.debug(this);
                    }
                });
              }*/
              setup.config.progress.toggle();
              var formData = $(this).prepareData();
              setHistory(this);
              $(formData).getDataProvider()
                         .then(event.data.fillOut)
                         .done(isGood)
                         .fail(notGood);
            }
          }

  var providerMethod = function(event){
              event.preventDefault();
              setup.config.provider.val($(this).text());
              $(this).attr('href', $(this).data('url'));
              $(this).trigger('populate');
          }

  var setHistory = function(source) {
              if ($(source).is("form"))
                url = $(source).attr("action")+ '?' + $(source).serialize();
              else
                url = $(source).attr('href');


              if (url.match(/preview/gi) == null) {
                setTabLink(url);
                window.history.pushState({url: "" + url + ""}, "", url);
              }
          }

  var setTabLink =  function(url){
              if (!$('.Premium').data('url')) {
                $('.Premium').data('url', url.replace('Economicas', 'Premium'));
                $('.Economicas').data('url', url.replace('Premium', 'Economicas'));
              } else {
                $('.'+setup.config.provider.val()).data('url', url);
              }
          }

  var download = function(selCartItem) {
            //alert(selCartItem.provider.val());
            //$('#downloading').modal('open');
              var form = {
                url: location.origin+'/latincolor/main/download',
                inputs: {
                  id: selCartItem.id.val(),
                  size: selCartItem.size.val(),
                  license: selCartItem.license.val(),
                  provider: selCartItem.provider.val(),
                  username: selCartItem.username.val()
                }
              }
            /*  var form = {
                url: location.origin+'/CentralStock/main/download',
                inputs: CCForm.formInputs()
              }*/
              $.submitForm(form, getFile);
          }

  var getFile = function(data) {
            window.location = data.url;
            //window.onunload = function() {
            //  $('#downloading').modal('close');
            //}
            //alert(data.licenseid);
            //alert('media:'+data.media+' size:'+data.size+' license:'+
            //      data.license+' username:'+data.username+' provider:'+data.provider);
          }

  var notGood = function(res){
            setup.config.error.html(res.responseText);
            setup.config.error.modal('open');
            setup.config.progress.toggle();
          }

  var isGood = function(res) {
            setup.config.progress.toggle();

          }

  return {
           setup: setup,
           searchFields: searchFields,
           previewFields: previewFields,
           mediaFields: mediaFields,
           mediaSizesChange: mediaSizesChange,
           loginFields: loginFields,
           subaccountsFields: subaccountsFields,
           subAccountChange: subAccountChange,
           subscriptionsFields: subscriptionsFields,
           cartFields: cartFields,
           notGood: notGood,
           isGood: isGood,
           mainMethod: mainMethod,
           loginMethod: loginMethod,
           providerMethod: providerMethod,
           showResult: showResult,
           dismissPreview: dismissPreview,
           download: download,
           collapsibleOpts: collapsibleOpts,
         }

})();
