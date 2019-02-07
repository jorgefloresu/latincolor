var Planes = {
  init: function() {
        Planes.config = {
          providersList: $("#providers-list"),
          selectedProvider: $(".selected-provider"),
          chipProvider: $(".chip-provider"),
          resultados: $(".resultados"),

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

        }
        Planes.setMaterial();
        Planes.setPlanes();

  },

  setPlanes: function() {
          Planes.setProvidersList();
          Planes.setFrecuenciaList();
          Planes.setCantidadList();
          Planes.setTiempoList();
          Planes.setArmaPlan();
          Planes.setPaquetes();
          Planes.setChipAction();

          Planes.config.resultados.on('calcular', function(){
            //if ($('.chip').length == 4) {
            if ($('.chip').length == 3) {
              let selection = [];
              $('.chip').each(function(index) {
                let str = $(this).text();
                selection[index] = str.substring(0,str.length-5);
              });
              let form = {
                url: location.origin+'/latincolor/main/calc_plan',
                inputs: {
                  //provider: selection[0],
                  frecuencia: selection[0],
                  cantidad: selection[1].split(" ")[0],
                  tiempo: selection[2].split(" ")[0]
                }
              }
              Planes.processSelectedPlan(form, selection);
            }
          });

          Planes.getPlanesParams();
  },

  processSelectedPlan: function (form, selection) {
          $.submitForm(form, function(res){
            Planes.config.planesResult.find('.popout').html('');
            if (res.length > 0) {
              let desc = 'Planes para descarga '+selection[0]+' de '+selection[1]+' durante '+selection[2];
              Planes.config.resultados.text(desc).show();
              $('body,html').animate({ scrollTop: Planes.config.resultados.offset().top-90 }, 500);
              $.each(res, function(index, plan){
                  let row = Templates.planesResult(plan, desc);
                  Planes.config.planesResult.find('.popout').append(row);
              });
              Planes.onComprarPlan();
              $('.collapsible').collapsible();

            } else {
              Planes.config.resultados.text("Sin resultados").show();
            }
          });
  },

  getPlanesParams: function () {
            $.getJSONfrom(location.origin+'/latincolor/main/planes_params')
              .then(function (res) {
                $.each(res, function(name, params) {
                  $("#"+name+"-list").html('');
                  $.each(params, function(index, param){
                    $("#"+name+"-list").append("<li><a href='#!'>"+param+"</a></li>");
                  })
                })
            });
  },

  onComprarPlan: function (plan) {
        Planes.config.comprarPlan.off('click');
        Planes.config.comprarPlan.on('click', 'a.comprar-plan-btn', function() {
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

  setProvidersList: function() {
        Planes.config.providersList.on('click','a',function() {
          Planes.config.selectedProvider.text($(this).text());
          //$(".selected-provider").css('display','inline-block');
          Planes.config.chipProvider.html('<div class="chip white-text">'+$(this).text()+'<i class="close material-icons">close</i></div>');
          Planes.config.resultados.trigger('calcular');
          //$('.chip').each(function () {
            //let str = $(this).text();
            //$(".resultados").text(str.substring(0,str.length-5));
            //selection.provider = $(this).text();
          //});
        });
  },

  setFrecuenciaList: function() {
          Planes.config.frecuenciaList.off('click');
          Planes.config.frecuenciaList.on('click','a',function() {
            Planes.config.frecuenciaSel.text($(this).text());
            let per = ($(this).text()=='Diaria' ? 'por día' :
                       $(this).text()=='Mensual'? 'por mes' : 'por año');
            Planes.config.periodo.text(per);
            Planes.config.frecuenciaChip.html('<div class="chip white-text">'+$(this).text()+'<i class="close material-icons">close</i></div>');
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

  setCantidadList: function() {
            Planes.config.cantidadList.off('click');
            Planes.config.cantidadList.on('click','a',function() {
              Planes.config.cantidadSel.text($(this).text());
              //$(".selected-cantidad").css('display','inline-block');
              Planes.config.cantidadChip.html('<div class="chip white-text">'+$(this).text()+'<i class="close material-icons">close</i></div>');
              Planes.config.resultados.trigger('calcular');
              //selection.cantidad = $(this).text();
            });
  },

  setTiempoList: function() {
            Planes.config.tiempoList.off('click');
            Planes.config.tiempoList.on('click','a',function() {
              Planes.config.tiempoSel.text($(this).text());
              //$(".selected-tiempo").css('display','inline-block');
              Planes.config.tiempoChip.html('<div class="chip white-text">'+$(this).text()+'<i class="close material-icons">close</i></div>');
              Planes.config.resultados.trigger('calcular');
              //selection.tiempo = $(this).text();
            });
  },

  setChipAction: function() {
            Planes.config.planChip.on('click','.chip i',function(){
              //$(".resultados").text("").hide();
              if ($(this).parent().parent().hasClass("chip-frecuencia"))
                Planes.config.periodo.text("");
            });
  },

  setArmaPlan: function() {
            Planes.config.armaPlanBtn.on('click', function() {
                //$('body,html').animate({ scrollTop: $('body').height() }, 1000);
                $('body,html').animate({ scrollTop: $('#planes-section').offset().top-30 }, 500);
                return false;
            });
  },

  setPaquetes: function() {
            Planes.config.paquetesBtn.on('click', function() {
                $('body,html').animate({ scrollTop: $('body').height() }, 1000);
                return false;
            });
  },

  setMaterial: function() {
            $('select').material_select();
            $(".chips").material_chip();
  },

}

var env = new $.Common();

$(document).ready(Planes.init);
