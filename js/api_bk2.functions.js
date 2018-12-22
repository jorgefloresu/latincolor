var Api = ( function() {

  var setup = {

      setPreview: function(settings) {
              setup.preview = {
                icon: $('#icon-type'),
                title: $('#title'),
                subtitle: $('#subtitle'),
                image: $('#loading img'),
                prices: $('#prices table tbody'),
                keywords: $('#keywords-preview'),
                similar: $('.similar'),
                similarCont: $('.similar-container'),
                imageTitle: $('.imgdetail-title')
              }
              $.extend(setup.preview, settings);
            },

      init: function(settings) {
            setup.config = {
              // Search controls
              searchForm: $("#search"),
              searchPanel: $(".pinned"),
              searchTotal: $(".toc-wrapper"),
              panel: $(".panel"),
              orientacion: $(".or-options"),
              medio: $(".md-options"),
              setFilters: $("#aplicar-op"),
              floatingBtn: $('.btn-floating'),

              resultSearch: $(".result"),             // Contenedor del JSON de la respuesta de search
              fullUrlSearch: $("#url"),               // Contenedor del url que se ejecuta del search
              provider: $('#provider'),
              searchIn: $('#search-in'),
              keyword: $('#keyword'),
              range: $('#range'),
              providersList: $('.dropdown-providers'),

              previewWindow: $('.imgdetail'),
              previewClose: $('.imgdetail-close'),
              previewPrices: $('#prices .card-content'),
              previewSimilar: $(".similar"),

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
              menuCart: $("#menuCart"),

              paginator: $("#paginator"),             // Contenedor del HTML del paginador
              progress: $(".progress"),
              progressPos: '110px',
              error: $("#error"),

              tabs: $('ul.tabs')
            };
            setup.config.paginator.hide();
            $.extend(setup.config, settings);
            locateProgress(setup.config.progressPos);
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
              //setup.config.resultSearch.html("<h5>Loading images...please wait</h5>");
              //setup.config.resultSearch.html(JSON.stringify(data.source, null, 3));
              //$('#result').html(JSON.stringify(data.source, null, 3));
              setup.config.fullUrlSearch.html(data.fullurl);
              setup.config.paginator.html(data.pags);
              //if (!data.fullurl) {
              //setup.config.searchTotal.html(Templates.resultPanel);
              // setup.config.searchTotal.find('p span').html(data.keyword);
              // setup.config.searchTotal.find('span span').html(data.time);
              // setup.config.searchTotal.find('h5').html(data.total);
              chart.updateData(data.totalProv);
              setup.config.searchTotal.find('.totalk').html(data.keyword);
              setup.config.searchTotal.find('.badge').html(data.time);
              setup.config.searchTotal.find('.totaln').html(new Intl.NumberFormat().format(data.total));
              // $('.count-on-page').text(data.count);
              let tabUrl = $('.'+setup.config.provider.val()).data('url');
              //console.log(tabUrl);
              //var keywordUrl = /keyword=([^&]+)/.exec(tabUrl)[1].replace(/\+/g," ");
              let keywordUrl = getFromUrl(tabUrl, 'keyword');
              if (keywordUrl != setup.config.keyword.val()){
                tabUrl = tabUrl.replace(keywordUrl, setup.config.keyword.val());
                setup.config.searchTotal.find('p.try-other')
                                        .html(Templates.btnTryWith(tabUrl, setup.config.keyword.val()));
              }
              //setup.config.searchTotal.toggle(true);
              setup.config.panel.toggle(true);
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
              let price = setup.config.mediaSizes.val().split('-')[1];
              setup.config.mediaPrice.val(price);
              setup.config.saveToCart.toggleClass('disabled',false);
              setup.config.saveToCart.data('desc',data.source.title);

              //$("select").material_select();
              //Materialize.updateTextFields();
          }

  var jGallery = function(data) {
              // let similar = '';
              // for (let i=0; i<data.length; i++) {
              //   similar += '<div><img src="'+data[i]+'" />'+
              //              '<div class="caption"><a href="">Link</a></div></div>';
              // }
              console.log(data);
              let similar = data;
              setup.preview.similar.html(similar);
              setup.preview.similar.justifiedGallery({
                  rowHeight : 73,
                  lastRow : 'nojustify',
                  margins : 5,
                  captions : true,
                  maxRowHeight : 80
              })
  }

  var previewFields = function(data) {
              //setup.config.previewWindow.html(data.page);
              setup.setPreview();
              $("#loading").toggleClass("is-loading");
              //$("#loading").append(Templates.circleLoader);
              setup.preview.image.attr('src', '');
              console.log(data);
              if (data) {
                setup.preview.title.html(data.title);
                setup.preview.subtitle.html("No. de referencia: #"+data.id);
                if (data.type == 'video') {
                  setup.preview.icon.removeClass("fa-image").addClass("fa-video");
                  setup.preview.similarCont.find('h5').text('Videos relacionados')
                  $("#loading video").attr('poster', data.image);
                  $(".material-placeholder").hide();
                  $("#loading .webm").attr('src', data.webm);
                  $("#loading .mp4").attr('src', data.mp4);
                  $("#loading video").show();
                  $("#loading video").get(0).load();
                } else {
                  setup.preview.similarCont.find('h5').text('Imágenes relacionadas')
                  setup.preview.icon.removeClass("fa-video").addClass("fa-image");
                  $("#loading video").hide();
                  setup.preview.image.attr('src', data.image);
                  $(".material-placeholder").show();
                  //$("#loading").html('<img src="'+data.image+'" class="materialboxed"/>')
                }
                setup.preview.prices.html(data.sizes);
                setup.preview.keywords.html(data.keywords);
                //setup.preview.image.load(function(){
                setup.preview.image.imagesLoaded()
                  .done(function(){

                    //if (setup.preview.image.height() >= setup.preview.image.width()) {
                      $("#loading").toggleClass("is-loading");
                      //$("#loading circle-loader").remove();
                      setup.preview.image.attr({width: 'auto', height: '100%'});
                      if (setup.preview.image.parent().width() <= setup.preview.image.width()) {
                        setup.preview.image.attr({width: '100%', height: 'auto'});
                      }
                    //} else
                    // {
                    //   setup.preview.image.attr({width: '100%', height: 'auto'});
                    // }
                    // if (setup.preview.image.height() >= 400)
                      //setup.preview.image.attr({width: '100%', height: 'auto'});

                    //$("#loading").css('margin-top', ($(".imgdetail").height() - $(this).height()) / 2 );
                  })
              }
              //setup.config.previewWindow.height('auto');
              //setup.config.previewWindow.velocity({top:"112px", opacity:1}, {duration:600, queue: false}, "easeOutBack");
              setup.config.previewWindow.modal("open");
              setup.preview.similarCont.toggle(false);
              if (data.similar_url == 1 && data.similar.length > 0) {
                  console.log(data.similar);
                  setup.preview.similarCont.toggle(true);
                  setup.preview.similar.html('Loading related images...');
                  jGallery(data.similar);
              } else if (data.similar) {
                    let provider = getFromUrl( $(this)[0].url, 'provider' );
                    setup.preview.similarCont.toggle(true);
                    setup.preview.similar.html('Loading related images...');
                    $.getJSON(location.origin+'/latincolor/main/find_similar/'+data.id+'/'+provider)
                      .done(function(res){
                        jGallery(res.thumb);
                    })
              }
              setup.config.previewWindow.animate({
                  scrollTop : 0  // Scroll to top of window
              }, 500);

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

              let items = '';
              for(let i=0; i<data.result.length; i++){
                items += data.result[i].html;
              }
              setup.config.resultSearch.html($(items));
              setup.config.resultSearch.justifiedGallery({
                  rowHeight : 170,
                  lastRow : 'nojustify',
                  margins : 10,
                  captions : true,
                  maxRowHeight : 300
              }).on('jg.complete', function (e) {
                if (data.result.length == 0)
                  $('#paginator').hide();
                else {
                  $('#paginator').show();
                }
              });
          }

  var dismissPreview = function(event) {
            event.preventDefault();
            setup.config.previewWindow.modal("close");
            //setup.config.previewWindow.velocity({top:"-4000px", opacity:0, height:0}, {duration:600, queue: false}, "easeInSine");
            setup.preview.image.attr('src', '');

          }

  var scrollPreview = function(){
            if ($(this).scrollTop()>0)
              setup.preview.imageTitle.css('box-shadow', '0px 3px 7px -4px rgba(0,0,0,0.4)');
            else {
              setup.preview.imageTitle.css('box-shadow', '');

            }
          }

  var mainMethod = function(event) {
            event.preventDefault();
            console.log($(this));
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
              if ($(this).is("form"))
                url = $(this).attr("action");
              else
                url = $(this).attr('href');
              if (url.match(/preview/gi) == null) {
                setup.config.resultSearch.html("<p>Loading images...</p>");
              }
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
              if ($(this).text()=="Economicas") {
                setup.config.provider.val($(this).text());
                $(this).attr('href', $(this).data('url'));
                $(this).trigger('populate');
              }
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

  var locateProgress = function(pos) {
          setup.config.progress.css('top', pos);

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

  var notGood = function(res, status, error){
            console.log(res);
            if (error == "Not Found") {
              Materialize.toast('Datos no disponibles', 4000)
              //setup.config.error.html(res.status);
              //setup.config.error.modal('open');
            }
            setup.config.progress.toggle();
          }

  var isGood = function(res) {
            setup.config.progress.toggle();
          }

  var getFromUrl = function(url, field) {
            let exp = new RegExp( field + "=([^&]+)" );
            return exp.exec(url)[1].replace(/\+/g," ");
            // /provider=([^&]+)/.exec(url)[1].replace(/\+/g," ");
          }

  var setSwitch = function (event) {
            if ($(this).is(':checked')) {
              let items, field;
              let opt = "#"+$(this).attr("id");
              if ($(this).hasClass("or-options")){
                items = ['#or-all','#or-hor','#or-ver','#or-sqa'];
                field = "#orientacion";
              }else{
                items = ['#md-fotos','#md-vectores','#md-videos'];
                field = "#medio";
              }
              let selectedItem = $(this).parent().parent().prev().text();
              $(field).val( selectedItem=='Todas' ? '': selectedItem );
              let setOff = $.grep(items, function(item){
                return item != opt;
              });
              //console.log(opt,setOff);
              for (let i=0; i<setOff.length; i++) {
                if ($(setOff[i]).is(':checked'))
                  $(setOff[i]).click();
              }
            }
          }

  var applyOptions = function(event) {
              event.preventDefault();
              let range = $("p.range-field").find("span.value").text();
              setup.config.range.val(range);

              let fotosChecked = 0;
              $(".orientacion-options li span:not(.lever):lt(4)").each(function(){
                if ($(this).next().find('input').is(':checked')) {
                  fotosChecked++;
                } else {
                  fotosChecked--;
                }
              });
              if (fotosChecked == -4)
                alert("Debe chequear al menos una orientación");
              else {
                setup.config.searchForm.submit();
              }
          }

  var floatButton = function(){
            if (setup.config.panel.css('opacity') == '1') {
              setup.config.panel.velocity(
                { opacity: 0 },
                { complete: function(el) {
                      setup.config.resultSearch.toggleClass('l10',false).toggleClass('l12',true);
                  }
              });
            } else {
              setup.config.panel.velocity(
                { opacity: 1 },
                { begin: function(el) {
                      setup.config.resultSearch.toggleClass('l10',true).toggleClass('l12',false);
                  }
              });
            }

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
           setSwitch: setSwitch,
           applyOptions: applyOptions,
           floatButton: floatButton,
           scrollPreview: scrollPreview
         }

})();
