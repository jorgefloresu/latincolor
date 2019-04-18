var Planes = {
  init: function () {
    Planes.config = {
      providersList: $("#providers-list"),
      selectedProvider: $(".selected-provider"),
      chipProvider: $(".chip-provider"),
      resultados: $(".resultados"),

      medioList: $("#medio-list"),
      medioSel: $(".selected-medio"),
      medioChip: $(".chip-medio"),
      medio: $(".medio"),

      frecuenciaList: $("#frecuencia-list"),
      frecuenciaSel: $(".selected-frecuencia"),
      frecuenciaChip: $(".chip-frecuencia"),
      periodo: $(".periodo"),

      cantidadList: $("#cantidad-list"),
      cantidadSel: $(".cantidad-frecuencia"),
      cantidadChip: $(".chip-cantidad"),

      tiempoList: $("#tiempo-list"),
      tiempoSel: $(".tiempo-frecuencia"),
      tiempoChip: $(".chip-tiempo"),

      armaPlanBtn: $("#arma-plan-btn"),
      paquetesBtn: $("#paquetes-btn"),
      planChip: $(".chip-plan"),

      planesResult: $(".planes-result"),
      comprarPlan: $(".plan-option"),
      comprarPaquete: $(".paquete-option"),
      backToPlanes: $('#back-to-planes'),

    }
    Planes.setMaterial();
    Planes.setPlanes();

  },

  setPlanes: function () {
    Planes.setMedioList();
    //Planes.setProvidersList();
    Planes.setFrecuenciaList();
    Planes.setCantidadList();
    Planes.setTiempoList();
    Planes.setArmaPlan();
    Planes.setPaquetes();
    Planes.setBackToPlanes();
    Planes.setChipAction();
    Planes.onComprarPaquete();

    Planes.config.resultados.on('calcular', function () {
      //if ($('.chip').length == 4) {
      if ($('.chip').length == 4) {
        let selection = [];
        $('.chip').each(function (index) {
          let str = $(this).text();
          selection[index] = str.substring(0, str.length - 5);
        });
        let form = {
          url: location.origin + '/latincolor/main/calc_plan',
          inputs: {
            //provider: selection[0],
            medio: selection[0],
            frecuencia: selection[1],
            cantidad: selection[2].split(" ")[0],
            tiempo: selection[3].split(" ")[0]
          }
        }
        Planes.processSelectedPlan(form, selection);
      }
    });

    //Planes.getPlanesParams('Fotos');
  },

  processSelectedPlan: function (form, selection) {
    $.submitForm(form, function (res) {
      Planes.config.planesResult.find('.popout').html('');
      let icon = function (color, text) {
        return "<i class='material-icons "+color+"-text' style='font-size:40px'>" + text + "</i>";
      }
      if (res.length > 0) {
        let good = icon('green', 'check_circle');
        let intro = "Hemos encontrado " + res.length + (res.length>1?" opciones":" opción")+" de ";
        let desc = 'Planes de '+selection[0]+' para descarga ' + selection[1] + ' de ' + selection[2] + ' durante ' + selection[3];
        $('.result-icon').html(good);
        Planes.config.resultados.html(intro + desc.toLowerCase() + ' con:<br>').show();
        //$('body,html').animate({ scrollTop: Planes.config.resultados.offset().top-90 }, 500);
        $.each(res, function (index, plan) {
          Planes.config.resultados.append(function () {
            return plan.provider + (plan.deal!=''?'<span style="color:red;font-size:11px;vertical-align:super"> * mejor oferta</span>':'') + (index == res.length - 1 ? '' : ', ');
          });
          let row = Templates.planesResult(plan, desc);
          Planes.config.planesResult.find('.popout').append(row);
        });
        Planes.onComprarPlan();
        $('.collapsible').collapsible();

      } else {
        let fail = icon('red', 'cancel');
        $('.result-icon').html(fail);
        Planes.config.resultados.html(" Sin resultados. Intenta con otra selección.").show();
      }
    });
  },

  getPlanesParams: function (medio) {
    $.getJSONfrom(location.origin + '/latincolor/main/planes_params/'+medio)
      .then(function (res) {
        $.each(res, function (name, params) {
          $("#" + name + "-list").html('');
          $.each(params, function (index, param) {
            $("#" + name + "-list").append("<li><a href='#!'>" + param + "</a></li>");
          })
        })
      });
  },

  onComprarPlan: function (plan) {
    Planes.config.comprarPlan.off('click');
    Planes.config.comprarPlan.on('click', 'a.comprar-plan-btn', function () {
      let planSelected = {
        id: $(this).data('id'),
        provider: $(this).data('provider'),
        desc: Planes.config.resultados.text(),
        price: $(this).data('price'),
        iva: $(this).data('iva'),
        tco: $(this).data('tco')
      }
      //env.shop.payPlan(planSelected);
      env.shop.addToCart(this);
      env.shop.displayCart();
    })
  },

  onComprarPaquete: function (paquete) {
    Planes.config.comprarPaquete.on('click', 'a.comprar-paquete-btn', function () {
      env.shop.addToCart(this);
      env.shop.displayCart();
    })
  },

  setProvidersList: function () {
    Planes.config.providersList.on('click', 'a', function () {
      Planes.config.selectedProvider.text($(this).text());
      //$(".selected-provider").css('display','inline-block');
      Planes.config.chipProvider.html('<div class="chip white-text">' + $(this).text() + '<i class="close material-icons">close</i></div>');
      Planes.config.resultados.trigger('calcular');
      //$('.chip').each(function () {
      //let str = $(this).text();
      //$(".resultados").text(str.substring(0,str.length-5));
      //selection.provider = $(this).text();
      //});
    });
  },

  setMedioList: function() {
    Planes.config.medioList.html(Templates.videoTypes());
    Planes.config.medioList.off('click');
    Planes.config.medioList.on('click', 'a', function() {
      //Planes.config.medioSel.text($(this).text());
      let option = $(this).textOnly();
      Planes.config.medioChip.html('<div class="chip white-text">' + option + '<i class="close material-icons">close</i></div>');
      Planes.getPlanesParams(option);
      Planes.config.resultados.trigger('calcular');
    })
  },

  setFrecuenciaList: function () {
    Planes.config.frecuenciaList.off('click');
    Planes.config.frecuenciaList.on('click', 'a', function () {
      Planes.config.frecuenciaSel.text($(this).text());
      let per = ($(this).text() == 'Diaria' ? 'por día' :
        $(this).text() == 'Mensual' ? 'por mes' : 'por año');
      Planes.config.periodo.text(per);
      Planes.config.frecuenciaChip.html('<div class="chip white-text">' + $(this).text() + '<i class="close material-icons">close</i></div>');
      Planes.config.resultados.trigger('calcular');
      // $("#frecuencia-list").on('click','a',function() {
      //   console.log('entra');
      //   $(".selected-frecuencia").text($(this).text());
      //   let per = ($(this).text()=='Diaria' ? 'por día' :
      //              $(this).text()=='Mensual'? 'por mes' : 'por año');
      //   $(".periodo").text(per);
      //   $(".chip-frecuencia").html('<div class="chip white-text">'+$(this).text()+'<i class="close material-icons">close</i></div>');
      //   $(".resultados").trigger('calcular');
    });
  },

  setCantidadList: function () {
    Planes.config.cantidadList.off('click');
    Planes.config.cantidadList.on('click', 'a', function () {
      Planes.config.cantidadSel.text($(this).text());
      //$(".selected-cantidad").css('display','inline-block');
      Planes.config.cantidadChip.html('<div class="chip white-text">' + $(this).text() + '<i class="close material-icons">close</i></div>');
      Planes.config.resultados.trigger('calcular');
      //selection.cantidad = $(this).text();
    });
  },

  setTiempoList: function () {
    Planes.config.tiempoList.off('click');
    Planes.config.tiempoList.on('click', 'a', function () {
      Planes.config.tiempoSel.text($(this).text());
      //$(".selected-tiempo").css('display','inline-block');
      Planes.config.tiempoChip.html('<div class="chip white-text">' + $(this).text() + '<i class="close material-icons">close</i></div>');
      Planes.config.resultados.trigger('calcular');
      //selection.tiempo = $(this).text();
    });
  },

  setChipAction: function () {
    Planes.config.planChip.on('click', '.chip i', function () {
      //$(".resultados").text("").hide();
      if ($(this).parent().parent().hasClass("chip-frecuencia"))
        Planes.config.periodo.text("");
    });
  },

  setArmaPlan: function () {
    Planes.config.armaPlanBtn.on('click', function(){
      Planes.desplazar('#planes-section');
      return false;
    });
  },

  setPaquetes: function () {
    Planes.config.paquetesBtn.on('click', function() {
      Planes.desplazar('#paquetes-promo');
      return false;
    });
  },

  setBackToPlanes: function() {
    Planes.config.backToPlanes.on('click', function() {
      Planes.desplazar('#planes-section');
      return false;
    })
  },

  desplazar: function(tag) {
    $('body,html').animate({
      scrollTop: $(tag).offset().top - 140
    }, 500);
  },

  setMaterial: function () {
    $('select').material_select();
    $(".chips").material_chip();
    $(".dropdown-trigger").dropdown({
      constrainWidth: false
    })
  },

}

var env = new $.Common();

$(document).ready(Planes.init);