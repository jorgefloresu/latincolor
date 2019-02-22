$( document ).ready(function() {

var Api = ( function() {

  var preview = {}

  var shop = {}

  var setup = {

      setPreview: function(settings) {
              preview = {
                icon: $('#icon-type'),
                title: $('#title'),
                subtitle: $('#subtitle'),
                imgContainer: $('#loading'),
                image: $('#loading img'),
                prices: $('#prices table tbody'),
                keywords: $('#keywords-preview'),
                similar: $('.similar'),
                similarCont: $('.similar-container'),
                imageTitle: $('.imgdetail-title'),
                video: $("#loading video"),
                webm: $("#loading .webm"),
                mp4: $("#loading .mp4"),
                saveToCart: $("#savetocart"),
                _minHeight: '450px', //$('#loading img').css('min-height'),
              }
              $.extend(preview, settings);
            },

      init: function(settings) {
            if ($('#myChart').length)
              setup.chart = new $.Chart("myChart");

            setup.config = {
              // Search controls
              searchForm: $("#search"),
              searchIcon: $('.icon-search'),
              searchOptions: $(".menu-medios li"),
              searchPanel: $(".pinned"),
              searchTotal: $(".toc-wrapper"),
              panel: $(".panel"),
              orientacion: $(".or-options"),
              medio: $(".md-options"),
              color: $(".co-options"),
              setFilters: $("#aplicar-op"),
              floatingBtn: $('.btn-floating'),

              resultSearch: $(".result"),             // Contenedor del JSON de la respuesta de search
              fullUrlSearch: $("#url"),               // Contenedor del url que se ejecuta del search
              provider: $('#provider'),
              searchIn: $('#search-in'),
              keyword: $('#keyword'),
              range: $('#range'),

              previewWindow: $('.imgdetail'),
              previewClose: $('.imgdetail-close'),
              previewPrices: $('#prices .card-content'),
              previewSimilar: $(".similar"),

              mediaForm: $("#media"),
              resultMedia: $("#resultMedia"),         // Contenedor del JSON de la respuesta de getMediaData
              fullUrlMedia: $("#urlMedia"),           // Contenedor del url que se ejecuta del getMediaData
              mediaSizes: $("#media-sizes"),
              mediaPrice: $("#mediaPrice"),

              loginMenu: $("#login-menu"),
              userAccount: $(".chat-collapsible"),

              resultControl: $('.result-control'),
              paginator: $(".paginator"),             // Contenedor del HTML del paginador
              progress: $(".progress"),
              progressPos: '166px',
              error: $("#error"),

              tabs: $('ul.tabs'),
              navbar: $('.nav-extended'),
              userNav: $(".chat-close-collapse"),
              rightNav: $(".chat-collapse"),
              openCart: $('a.open-cart'),
              directDownload: $('.direct-download'),

              avisoPlan: true,
              validSubscription: false,

            };
            $.extend(setup.config, settings);


            locateProgress();
            Login.init();
            shop = new $.Shop();
            setup.setPreview();
            setSearch();
            setMaterial();
            // Login.doAfterLogin( Login.auth.init, shop.displayCart );
            doAfterLogin();

            setup.config.paginator.hide();
            setup.config.resultControl.hide();
          },
  }

  var doAfterLogin = function() {
          $('body').on('login', function(e) {
              setup.config.avisoPlan = true;
              userData = $(this).data();
              Login.auth.init();
              shop.displayCart();
          });
  }


  var collapsibleOpts = {
            accordion : true,
            onOpen : function(el) {
                      if (el.attr('id')=='user-purchases')
                        getPurchases(el);
                    }
          }

  var getPurchases = function(el) {
            el.find('.image-list').html('');
            let url = el.data('url')+'?username='+$.Auth.info('username');
            $.getJSONfrom(url).then(function(res) {
                $.each(res, function(index, val) {
                  let html = Templates.userImageList.replace('src=""','src="'+val.img_url+'"')
                                                    .replace('Code', val.img_code)
                                                    .replace('Provider', val.img_provider);
                  el.find('.image-list').append(html)
                })
            })
          }

  var setSearch = function() {
            //if (location.href.match(/search/gi) != null) {
            //  locateProgress('110px');
            //  $(".chat-collapse").sideNav({
            //    edge: 'right'
            //  });
            //  $('.chat-collapsible').collapsible(collapsibleOpts);
              setup.config.searchForm.on("submit", {fillOut: showResult}, mainMethod);
              setup.config.searchIcon.on('click', function(){
                setup.config.searchForm.submit();
              });

              setup.config.searchOptions.on('click', 'a', menuMedios);
              setup.config.resultSearch.on('click', 'a', {fillOut: previewFields}, mainMethod);
              setup.config.searchTotal.on("click", "a", {fillOut: showResult}, mainMethod);
              setup.config.orientacion.on('click', setSwitch);
              setup.config.color.on('click', setSwitch);
              setup.config.medio.on('click', setSwitch);
              setup.config.setFilters.on('click', applyOptions);
              setup.config.floatingBtn.on('click', floatButton);
              setup.config.paginator.on("click", "a", {fillOut: showResult}, mainMethod);
              setup.config.userNav.on('click', function(){
                setup.config.rightNav.sideNav('hide');
              });

              setup.config.previewClose.on('click', dismissPreview);
              setup.config.previewWindow.on('scroll', scrollPreview);
              setup.config.previewSimilar.on('click', 'a', {fillOut: previewFields}, mainMethod);

              setup.config.directDownload.on('click', doDownload);

              setup.config.previewPrices.on("click", "a", function(event){
                  event.preventDefault();
                  //$('.size-info').html($(this).data('width')+' x '+$(this).data('height')+
                  //                     ' | Licencia: '+$(this).data('license'));
                  if ( ! setup.config.validSubscription || $(this).data('license') == 'extended') {
                    $('.direct-download').hide();

                    let subscriptionid = $('.dropdown-plan').text() == 'Elige Plan' ? '' : $('.dropdown-plan').text();
                    $(this).data('subscriptionid', subscriptionid);
  
                    let sizeClicked = shop.addToCart(this);
                    if ( sizeClicked.status == 'added' ) {
                      preview.prices.find('a.active').removeClass('active')
                                    .find('.size-option').removeClass('white-text').addClass('blue-text');
                      $(this).addClass('active');
                      $(this).find('.size-option').removeClass('blue-text').addClass('white-text');
                    } else if ( sizeClicked.status == 'deleted' ) {
                      shop.$tableCartBody
                        .find(".delete a[data-item='" +$(sizeClicked.item).data('img')+ "']")
                        .click();
                        $(this).removeClass('active');
                        $(this).find('.size-option').removeClass('white-text').addClass('blue-text');
                    }
                    shop.displayCart();      
                  } else {
                    preview.prices.find('a.active').removeClass('active')
                                  .find('.size-option').removeClass('white-text').addClass('blue-text');
                    $(this).addClass('active');
                    $(this).find('.size-option').removeClass('blue-text').addClass('white-text');

                    $('.direct-download').show();
                  }
              });
              onPlanSelected();
              // setup.config.openCart.on('click', function(event){
              //   event.preventDefault();
              //   setup.config.previewClose.click();
              //   shop.displayCart();
              // });
            //setup.config.tabs.on('click', 'a', providerMethod);
              //setup.config.tabs.on('populate', 'a', {fillOut: showResult}, mainMethod);
              setup.config.searchForm.submit();
          //  }
          }

  var doDownload = function() {
              let selected = preview.prices.find('a.active');
              let item = [{
                'productId': $(selected).data('img'),
                'size': $(selected).data('size'),
                'license_type': $(selected).data('license'),
                'provider': $(selected).data('provider'),
                'thumb': $(selected).data('thumb'),
                'username': $.Auth.info('username'),
              }];
              $.download(item, function (res) {
                console.log(res);
              })
  }

  // Asigna el contenido a los contenedores relacionados con el search
  var searchFields = function(data) {
              //setup.config.fullUrlSearch.html(data.fullurl);
              setup.config.paginator.html(data.pags);
              if ($('#myChart').length)
                setup.chart.updateData(data.totalProv);
              setup.config.searchTotal.find('.totalk').html(data.keyword);
              setup.config.searchTotal.find('.badge').html(data.time);
              //setup.config.searchTotal.find('.totaln').html(new Intl.NumberFormat().format(data.total));
              $('.totaln').html('RESULTADOS DE BÚSQUEDA: <span style="font-weight:900; padding:0 10px;">'+
                                new Intl.NumberFormat().format(data.total)+'</span> coincidencias');
              $('.estas-buscando').text('Estás buscando '+getFromUrl(location.href,'medio').toLowerCase());
              $('.this-keyword').text(setup.config.keyword.val());
              $('.search-type-icon').attr('src', location.origin+'/latincolor/img/'+getFromUrl(location.href,'medio')+'-50.png');
              $('.current-page').text(data.cur_page);
              $('.num-pages').text(data.num_pages+(data.num_pages==1?' pagina':' paginas'));
              //let tabUrl = $('.'+setup.config.provider.val()).data('url');
              //console.log(tabUrl);
              //let keywordUrl = getFromUrl(tabUrl, 'keyword');
              // if (keywordUrl != setup.config.keyword.val()){
              //   tabUrl = tabUrl.replace(keywordUrl, setup.config.keyword.val());
              //   setup.config.searchTotal.find('p.try-other')
              //                           .html(Templates.btnTryWith(tabUrl, setup.config.keyword.val()));
              // }
              //setup.config.searchTotal.toggle(true);
              setup.config.panel.toggle(true);
              //}
              //console.log(data.source);
              document.body.scrollTop = 0; // For Chrome, Safari and Opera
              document.documentElement.scrollTop = 0; // For IE and Firefox
          }

  var hasValidSubscription = function () {
          let url = location.origin+'/latincolor/main/check_subscriptions/';
          return $.getJSON(url+$.Auth.info('deposit_userid'));
  }

  var previewFields = function(data) {
              if (data.source == 'preview') {
                  preview.imgContainer.toggleClass("is-loading");
                  //$("#loading").append(Templates.circleLoader);
                  preview.image.attr('src', '');
                  console.log(data);
                  if (data) {
                    preview.title.html(data.title);
                    preview.subtitle.html("No. de referencia: #"+data.id);
                    if (data.type == 'video') {
                      preview.image.css('min-height', 0);
                      setup.config.previewPrices.css('min-height', '270px');
                      //preview.icon.removeClass("fa-image").addClass("fa-video");
                      preview.icon.attr('src', location.origin+'/latincolor/img/Videos-50.png');
                      preview.similarCont.find('h5').text('Videos relacionados')
                      preview.video.attr('poster', data.image);
                      $(".material-placeholder").hide();
                      preview.webm.attr('src', data.webm);
                      preview.mp4.attr('src', data.mp4);
                      preview.video.show();
                      preview.video.get(0).load();
                    } else {
                      preview.image.css('min-height', preview._minHeight);
                      preview.similarCont.find('h5').text('Imágenes relacionadas')
                      //preview.icon.removeClass("fa-video").addClass("fa-image");
                      preview.icon.attr('src', function() {
                        return location.origin+'/latincolor/img/'+
                               (data.type == 'vector'?'Vectores-50.png':'Fotos-50.png');
                      });
                      preview.video.hide();
                      preview.image.attr('src', data.image);
                      preview.image.parent().attr('href', data.image);
                      preview.image.parent().attr('title', data.title);
                      $(".material-placeholder").show();
                    }
                    preview.prices.html(data.sizes);
                    if ($.Auth.status() !== 'loggedIn' || 
                        $.Auth.info('deposit_userid') == '' ||
                        preview.prices.find('a:first').data('provider') !== 'Depositphoto') {
                      $('.dropdown-plan').hide();
                      $('.size-price').css({'text-decoration': 'none', 'color':'grey'});
                      preview.prices.find('i').each(function(){
                        $(this).remove();
                      })
                    } else {
                        console.log($.Auth.info('deposit_userid'));
                        preview.prices.append("<tr><td style='padding:0 30px'><i class='tiny material-icons green-text'>flag</i> Aplica descarga con subscripción</td></tr>");
                        let html = "";
                        let disponibles = 0;
                        $('.dropdown-plan').show();
                      
                        hasValidSubscription()
                          .then(function(res){
                            if (res.length == 0) {
                              $('.dropdown-plan').tooltip({
                                tooltip: "Renueva tu plan o adquiere uno nuevo"
                              })
                            }
                            $.each(res, function(key, plan){
                              html += "<li><a href='#!' class='selected-plan blue-text text-darken-4' data-disponible='"+plan.amount+"' data-id='"+plan.id+"'>"+plan.id+"</a></li>";
                              disponibles += Number(plan.amount);
                            })
                          })
                          .done(function(){
                            if (html != "") {
                              html = "<li><a href='#!' class='blue-text text-darken-4'>Ninguno</a></li>" + html;
                              $('#plan-activo').html(html);
                              $('.size-price').css({'text-decoration': 'line-through', 'color':'#d3d3d3'});
                              if (setup.config.avisoPlan) {
                                Materialize.toast('Tienes '+disponibles+' descargas disponibles con tu suscripción', 5000);
                                setup.config.avisoPlan = false;
                              }
                              setup.config.validSubscription = true;
                              $('.dropdown-plan').tooltip({
                                tooltip: "Elige Plan"
                              })
                            }
                          })
                    }
                    setup.config.previewPrices.find('.size-option div').css('width',function(){
                      return (data.type == 'video' ? '90px' : '38px')
                    });
                    //var sizeOption = preview.prices.find('.size-option');
                    //var sizeInfo = preview.prices.find('.size-info');
                    //sizeOption.on('click', 'a', function() {
                    //  console.log(this);
                    //});
                    preview.keywords.html(data.keywords);
                    //preview.image.load(function(){
                    preview.image.imagesLoaded()
                      .done(function(){

                        //if (preview.image.height() >= preview.image.width()) {
                          preview.imgContainer.toggleClass("is-loading");
                          setup.config.previewPrices.css('min-height', function(){
                            let mh = preview.image.height() - 70;
                            return mh+'px';
                          });
                          //$("#loading circle-loader").remove();
                          //preview.image.attr({width: 'auto', height: '100%'});
                          //if (preview.image.parent().width() <= preview.image.width()) {
                          //  preview.image.attr({width: '100%', height: 'auto'});
                          //}
                        //} else
                        // {
                        //   preview.image.attr({width: '100%', height: 'auto'});
                        // }
                        // if (preview.image.height() >= 400)
                          //preview.image.attr({width: '100%', height: 'auto'});

                        //$("#loading").css('margin-top', ($(".imgdetail").height() - $(this).height()) / 2 );
                      })
                  }
                  //setup.config.previewWindow.height('auto');
                  //setup.config.previewWindow.velocity({top:"112px", opacity:1}, {duration:600, queue: false}, "easeOutBack");
                  setup.config.previewWindow.modal("open");
                  preview.similarCont.toggle(false);
                  if (data.similar_url == 1 && data.similar.length > 0) {
                      console.log(data.similar);
                      preview.similarCont.toggle(true);
                      preview.similar.html('Loading related images...');
                      preview.similar.jGallery({}, data.similar);
                  } else if (data.similar) {
                        let provider = getFromUrl( $(this)[0].url, 'provider' );
                        preview.similarCont.toggle(true);
                        preview.similar.html('Loading related images...');
                        $.getJSONfrom(location.origin+'/latincolor/main/find_similar/'+data.id+'/'+provider)
                          .done(function(res){
                            preview.similar.jGallery({}, res.thumb);
                        })
                  }
                  setup.config.previewWindow.animate({
                      scrollTop : 0  // Scroll to top of window
                  }, 500);
            } else {
                //let shop = new $.Shop( setup.config.cart );
                shop.addToCart($(data.tag));
                shop.displayCart();
            }

          }
  
  var onPlanSelected = function () {
              $('#plan-activo').on('click', 'a', function () {
                /* let text = '',
                    tiptext = '';
                if ($(this).text() == 'Ninguno') {
                  text = tiptext = 'Elegir Plan';                  
                } else {
                  text = $(this).text();
                } */
                let text = $(this).text() == 'Ninguno' ? 'Elegir Plan' : $(this).text();
                $('.dropdown-plan').text(text);
                if ($(this).data('disponible')!==undefined) {
                  $('.dropdown-plan').tooltip({
                    tooltip: "Disponibles: "+ $(this).data('disponible')
                  })
                }
              })
  }

  var showResult = function(data) {
              console.log(data);
              searchFields(data);
              setup.config.resultSearch.html("<p>Preparing the layout...</p>");
              let items = '';
              for(let i=0; i<data.result.length; i++){
                items += data.result[i].html;
              }
              //setup.config.resultSearch.imagesLoaded(function(){
                setup.config.resultSearch.jGallery({
                    rowHeight : 170,
                    margins : 10,
                    maxRowHeight : 300
                }, $(items), function (e) {
                  if (data.result.length == 0){
                    setup.config.resultControl.hide();
                    setup.config.paginator.hide();
                  } else {
                    setup.config.paginator.show();
                    setup.config.resultControl.show();
                  }
                })
              //})
          }

  var dismissPreview = function(event) {
            event.preventDefault();
            setup.config.previewWindow.modal("close");
            //setup.config.previewWindow.velocity({top:"-4000px", opacity:0, height:0}, {duration:600, queue: false}, "easeInSine");
            preview.image.attr('src', '');

          }

  var scrollPreview = function(){
            if ($(this).scrollTop()>0)
              preview.imageTitle.css('box-shadow', '0px 3px 7px -4px rgba(0,0,0,0.4)');
            else {
              preview.imageTitle.css('box-shadow', '');

            }
          }

  var mainMethod = function(event) {
        //  if (location.href.match(/search/gi) == null) {
            event.preventDefault();
            ///console.log($(this));
            if ($(this).parent().hasClass('active')) return false;
            if (setup.config.keyword.val() || !$(this).is("form")){
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
              let url = '';
              setup.config.progress.toggle();
              if ($(this).is("form")) {
                if (setup.config.keyword.val() != getFromUrl(location.href, 'keyword')) {
                  $(this).attr("action", location.origin+'/latincolor/main/search/1');
                }
                url = $(this).attr("action");
              }
              else
                url = $(this).attr('href');
              if ( url.match(/preview/gi) == null && url.match(/instant/gi) == null ) {
                //setup.config.resultSearch.html("<p>Loading images...</p>");
                let w = (setup.config.panel.is(":hidden") ? "90%" : "80%");
                setup.config.resultSearch.html("<div style='position:fixed;opacity:.5;height:50%;width:"+w+"'><span style='position:absolute;font-size:12px;top:50%;left:50%'><img src='"+location.origin+
                "/latincolor/img/Cube-1s-50px.gif' style='opacity:1'/><br/>Buscando...</span></div>");
              }

              var formData = $(this).prepareData();
              setHistory(this);
              $(formData).getDataProvider()
                         .then(event.data.fillOut)
                         .done(isGood)
                         .fail(notGood);
            }
          //}
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
              let url = '';
              if ($(source).is("form"))
                url = $(source).attr("action")+ '?' + $(source).serialize();
              else
                url = $(source).attr('href');


              if (url.match(/preview/gi) == null && url.match(/instant/gi) == null) {
                //setTabLink(url);
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
          setup.config.progress.css('top', setup.config.navbar.height());

          }

  // var download = function(items) {
  //         $.each(items, function(index, selCartItem) {
  //             let form = {
  //               url: location.origin+'/latincolor/main/download',
  //               inputs: {
  //                 id: selCartItem.id,
  //                 size: selCartItem.size,
  //                 price: Number(selCartItem.price).toFixed(2),
  //                 license: selCartItem.license,
  //                 provider: selCartItem.provider,
  //                 username: selCartItem.username
  //               }
  //             }
  //             console.log(form);
  //             $.submitForm(form, getFile);
  //           })
  //         }
  //
  // var getFile = function(data) {
  //           console.log(data);
  //           window.location = data.url;
  //           setup.config.progress.toggle(false);
  //           //window.onunload = function() {
  //           //  $('#downloading').modal('close');
  //           //}
  //         }

  var notGood = function(res, status, error){
            console.log(res);
            if (error == "Not Found") {
              Materialize.toast('Datos no disponibles', 4000)
            }
            setup.config.progress.toggle(false);
          }

  var isGood = function(res) {
            setup.config.progress.toggle(false);
          }

  var getFromUrl = function(url, field) {
            let exp = new RegExp( field + "=([^&]+)" );
            return exp.exec(url)[1].replace(/\+/g," ");
          }

 var menuMedios = function () {
            $("#medio").val($(this).data('option'));
            $('.menu-medios li').removeClass('active');
            $(this).parent().addClass('active');
            setup.config.searchForm.submit();
          }

  var setSwitch = function (event) {
            if ($(this).is(':checked')) {
              let items, field;
              let opt = "#"+$(this).attr("id");
              switch ($(this).attr('class')) {
                case 'or-options':
                  items = ['#or-all','#or-hor','#or-ver','#or-sqa'];
                  field = "#orientacion";
                  break;
                case 'co-options':
                  items = ['#co-all','#co-byn'];
                  field = "#color";
                  break;
                case 'md-options':
                  items = ['#md-fotos','#md-vectores','#md-videos'];
                  field = "#medio";
              }

              let selectedItem = $(this).parent().parent().prev().text();
              $(field).val( selectedItem.substring(0,3)=='Tod' ? '': selectedItem );
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
                      $('.totaln').toggleClass('s6 offset-s2', false).toggleClass('s8',true)
                  }
              });
            } else {
              setup.config.panel.velocity(
                { opacity: 1 },
                { begin: function(el) {
                      setup.config.resultSearch.toggleClass('l10',true).toggleClass('l12',false);
                      $('.totaln').toggleClass('s8',false).toggleClass('s6 offset-s2', true);
                  }
              });
            }

          }

  var setMaterial = function () {
          $('.popup-link').magnificPopup({
            type: 'image',
          });
          $('.materialboxed').materialbox();
          $(".modal").modal();
          Login.config.loginWindow.modal({
            ready: function(){
              shop.$element.modal('close');
            }
          });
          $('.dropdown-button').dropdown({
            belowOrigin: true,
          });
          $('.dropdown-plan').dropdown();
          $('.chat-collapsible').collapsible(collapsibleOpts);
          $(".chat-collapse").sideNav({
            edge: 'right'
          });
         $('.button-collapse').sideNav();
         $('.tooltipped').tooltip();
         $('.dropdown-plan').tooltip('remove');
         if ($('#stotal').text() != '0')
            $('.tooltipped').tooltip('remove');
  }

  return {
           setup: setup,
        //   download: download,
         }

})();

Api.setup.init();

});
