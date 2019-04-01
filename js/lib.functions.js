(function ( $ ) {

    // Guarda el url de cada link visitado
    var cache = {};
    var valProgress;

    $.createCache = function( requestFunction ) {
                    return function( key, callback ) {
                        if ( !cache[ key ] ) {
                            cache[ key ] = $.Deferred(function( defer ) {
                                requestFunction( defer, key );
                            }).promise();
                        }
                        return cache[ key ].done( callback );
                    };
                };

    $.loadPage = function(url) {
                  var formData = $('<a href="'+url+"></a>").prepareData();
                  Api.setup.config.progress.toggle();
                  $.getJSON(url)
                     .then(Api.showResult)
                     .done(Api.isGood)
                     .fail(Api.notGood);
                };

    // Submit the form data using Ajax
    $.submitForm = function(form, populate) {
                    $('.load-progress').toggle(true);
                    $.ajax({
                        url: form.url,
                        data: form.inputs,
                        method: "POST",
                        dataType: "json"
                    }).done(function(res){
                      $('.load-progress').toggle(false);
                      populate(res);
                    }).fail(function(res){
                      $('.load-progress').toggle(false);
                      populate(res);
                      //alert("Error from url: "+form.url);
                    });
                }

    $.download = function(items, callback) {
            $('#download-progress').modal();
            $('#download-progress').modal('open');
            let downloadItems = {
              items: []
            }
            $.each(items, function (index, selCartItem) {
              downloadItems.items.push(JSON.stringify({
                  id: selCartItem.productId,
                  size: selCartItem.size,
                  price: Number(selCartItem.price).toFixed(2),
                  license: selCartItem.license_type,
                  provider: selCartItem.provider,
                  username: selCartItem.username,
                  thumb: selCartItem.thumb,
                  result: ''
                })
              )
            });

            $.post(location.origin+'/latincolor/main/download', downloadItems)
              .done(getFile);

            /* let result = items;
            $.each(items, function(index, selCartItem) {
                let lresult = selCartItem;
                let form = {
                  url: location.origin+'/latincolor/main/download',
                  inputs: {
                    id: selCartItem.productId,
                    size: selCartItem.size,
                    price: Number(selCartItem.price).toFixed(2),
                    license: selCartItem.license_type,
                    provider: selCartItem.provider,
                    username: selCartItem.username,
                    thumb: selCartItem.thumb,
                  }
                }
                console.log(form);
                $.submitForm(form, getFile) */
                  /* function(data) {
                    console.log(data);
                    window.location = data.url;
                    $('.progress').toggle(false);
                    lresult.result = 'success';
                    result[index] = lresult;
                    window.onunload = function() {
                      $('#downloading').modal('close');
                    }
                  }) */
              /* })
              callback(result); */

              function getFile(response) {
                let data = JSON.parse(response);
                if (data.error) {
                  $('.msg-redownload').show();
                } else {
                  valProgress = 0;
                  $.each(data, function(index, item){
                    if (item.result == 'success') {
                      $('#download-progress p').text(' Esperando a recibir el archivo...');
                      console.log(item.url);
                      $.loadFile(item.url, function (file) {
                        if (item.url.substr(-3) == 'zip') {
                          let hoy = new Date();
                          $.saveFile(file, 'LCI-'+hoy.getTime()+'.zip');
                        } else {
                          $.saveFile(file, 'LCI-'+item.provider.substring(0,2)+'-'+item.id);
                        }
                      })
                    }
                  });
                  callback(data);
                }
              }
                    
            }

    $.loadFile = function (file, response) {
                let request = new XMLHttpRequest();
                request.responseType = 'blob';
                request.open('GET', file);
                request.addEventListener('progress', updateProgress);
                request.addEventListener('load', function () {
                  response(request.response);
                });
                //request.setRequestHeader('Access-Control-Allow-Origin', '*');
                request.send();

                function updateProgress(evt){
                  if (evt.lengthComputable){
                    let percentComplete;
                    if (evt.loaded > valProgress) {
                      console.log(evt.loaded+' '+valProgress);
                      valProgress = evt.loaded;  
                    }
                    percentComplete = (valProgress / evt.total)*100;
                    let percent = Math.ceil(percentComplete)+'%';
                    $('.determinate').css('width', percent);
                    $('#download-progress p').text(' Recibiendo...');
                    $('.pct').text('('+percent+')');
                    if (percentComplete == 100) {
                      $('#download-progress i').show();
                      $('#download-progress p').text(' 100% Completado');
                    }
                  }
                }
            }

    $.saveFile = function (object, name) {
              let a = document.createElement('a');
              let url = URL.createObjectURL(object);
              a.href = url;
              a.download = name;
              a.click();
            }

    $.getPurchases = function(el) {
              el.find('.image-list').html('');
              let url = el.data('url')+'?username='+$.Auth.info('username');
              let html = '';
              $.getJSONfrom(url).then(function(res) {
                  $.each(res, function(index, val) {
                    if (el.attr('id')=='user-purchases') {
                      html += Templates.userImageList.replace('src=""','src="'+val.img_url+'"')
                                                      .replace('Code', val.img_code)
                                                      .replace('Provider', val.img_provider);
                    } else {
                      html += Templates.userPlanList.replace('Provider', val.provider)
                                                    .replace('Code', val.img_code)
                                                    .replace('Date', val.session_date);
                    }
                  })
                  el.find('.image-list').html(html);
                })
            }
            
    $.getJSONfrom = $.createCache(function(dfd, url, formData) {
                    //console.log(url);
                    $.getJSON(url,formData).then(dfd.resolve, dfd.reject);
                });

    $.loadImage = $.createCache(function( defer, url ) {
                    var image = new Image();
                    function cleanUp() {
                        image.onload = image.onerror = null;
                    }
                    defer.then( cleanUp, cleanUp );
                    image.onload = function() {
                        defer.resolve( url );
                    };
                    image.onerror = defer.reject;
                    image.src = url;
                });

    $.getGeo = $.createCache(function(dfd, params, doFunc) {
                    let url = location.origin + "/latincolor/countries?" + params;
                    $.getJSON(url, doFunc)
                     .then(function(res){
                        dfd.resolve(res);
                      }, dfd.reject);
                });

    $.hasValidSubscription = function () {
                  let url = location.origin+'/latincolor/main/check_subscriptions/';
                  return $.getJSON(url+$.Auth.info('deposit_userid'));
          }
        
    $.setValidador = function(callback) {
                  $.validator.setDefaults({
                      errorClass: 'invalid',
                      validClass: "valid",
                      errorPlacement: function (error, element) {
                          $(element)
                              .closest("form")
                              .find("label[for='" + element.attr("id") + "']")
                              .attr('data-error', error.text());
                      },
                      submitHandler: function (form) {
                          //console.log($(form).attr('id'));
                          let formData = $(form).prepareData();
                          $.post(formData.url, formData.inputs)
                            .then(function(res){
                                    callback('success', res, $(form).attr('id'));
                                  },
                                  function(res){
                                    callback('error', res, $(form).attr('id'));
                                  }
                            )
                      }
                  })
                }

    $.validator.methods.email = function( value, element ) {
                    return this.optional( element ) || /[a-z0-9]+@[a-z]+\.[a-z]+/.test( value );
                }

    $.fn.validar = function () {
                    $(this).validate({
                        rules: {
                            password: {
                                minlength: 4
                            },
                            password2: {
                              equalTo: "#password"
                            }
                        },
                        messages: {
                          email_address: 'Favor digite un email correcto',
                          email_forgot: 'Favor digite un email correcto',
                          password2: 'Ambas claves deben coincidir'
                        }
                    });
                }

    // Ejecuta la llamada al servidor para obtener el set de datos
    $.fn.getDataProvider = function() {
                    var url = this[0].url;
                    var formData = this[0].inputs;
                    if ( formData )
                      url += '?' + $.param(formData);

                    return $.getJSONfrom(url, formData);
                }


    // Organiza el action y los inputs del form en un solo array
    $.fn.prepareData = function() {
                    var form = {};
                    if (this.is("form")) {
                      form.url = $(this).attr("action");
                      form.inputs = $(this).formInputs();
                    } else {
                      form.url = $(this).attr('href');
                      form.inputs = null;
                    }
                    return form;
                }

    // Organiza los inputs del form en un solo array
    $.fn.formInputs = function() {
                    var $form_inputs = $(this).find(':input:not([disabled])');
                    var form_data = {};
                    $form_inputs.each(function() {
                      form_data[this.name] = $(this).val();
                    });
                    return form_data;
                }

    //$.fn.turnsTo = function()

    $.fn.forSubAccount = function(subAccountId) {
                    $(this).attr('href', function(){
                      return $(this).attr('href').split('?')[0] + '?subaccountid=' + subAccountId;
                    });
                }

    $.fn.getUsername = function() {
                  return $.trim($(this).contents().filter(function(){
                                  return this.nodeType == 3
                                }).text())
                }

    $.fn.jGallery = function(options, data, callback) {
                console.log(data);
                let defaults = {
                    rowHeight : 73,
                    lastRow : 'nojustify',
                    margins : 5,
                    captions : true,
                    maxRowHeight : 80,
                };
                $.extend(defaults, options);
                $(this).html(data);
                $(this).justifiedGallery(defaults)
                       .on('jg.complete', callback)
              }

    $.currentPage = function(str) {
                        let rstr = new RegExp(str);
                        return (rstr.test(location.href));
                        //return (location.href.match(/search/gi) != null);
              }

    $.urlParam = function(url, field) {
              let exp = new RegExp( field + "=([^&]+)" );
              let str = exp.exec(url);
              console.log(str);
              if (str == null)
                return '';
              else
                return exp.exec(url)[1].replace(/\+/g," ");
            }

    $(window).on('popstate', function(e) {
                  var state = e.originalEvent.state;
                  //console.log(state);
                  if (state != undefined ) {
                      $.loadPage(state.url);
                  }
                  /*else {
                      $(".container").empty();
                      $("#paginator").empty();
                  }*/
              });

    // $.fn.isotopeImagesReveal = function( items ) {
    //               var iso = this.data('isotope');
    //               var itemSelector = iso.options.itemSelector;
    //               // hide by default
    //               items.hide();
    //               // append to container
    //               this.append( items );
    //               items.imagesLoaded().progress( function( imgLoad, image ) {
    //                   // get item
    //                   // image is imagesLoaded class, not <img>, <img> is image.img
    //                   var item = $( image.img ).parents( itemSelector );
    //                   // if ($(image.img)[0].naturalHeight > $(image.img)[0].naturalWidth) {
    //                   //   console.log(item.hasClass("grid-item"));
    //                   //   item.toggleClass("grid-item--width2");
    //                   //
    //                   // }
    //                   // un-hide item
    //                   item.show();
    //                   // isotope does its thing
    //                   iso.appended( item );
    //               });
    //
    //               return this;
    //           };

}(jQuery))
