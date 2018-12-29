var Templates = (function(){
 var circleLoader = '<div class="circle-loader center" style="margin-left: 75px">'+
     '<div class="valign-wrapper" style="height:140px">'+
     '<div class="preloader-wrapper active">'+
     '<div class="spinner-layer spinner-green-only">'+
     '<div class="circle-clipper left">'+
     '<div class="circle"></div>'+
     '</div><div class="gap-patch">'+
     '<div class="circle"></div>'+
     '</div><div class="circle-clipper right">'+
     '<div class="circle"></div>'+
     '</div>'+
     '</div>'+
     '</div>'+
     '</div>'+
     '</div>';

 var cardTemplate = '<div class="thumb-card card hoverable">'+
     '<div class="card-image">'+
     circleLoader+
     '</div>'+
     '<div class="card-content">'+
     '<span class="card-title grey-text text-darken-4 center"></span>'+
     '</div>'+
     '<div class="card-action">'+
     '</div>'+
     '</div>';

var resultPanel = '<div class="card" style="padding:5%">'+
    '<span class="" style="font-weight:bolder"><span class="new badge blue" data-badge-caption="seg.">4</span>Resultados</span>'+
      '<p class="totalk grey-text" style="margin:0;line-height:1">First Line</p>'+
      '<p class="totaln" style="margin-top:10px;font-size: 25px;font-weight:bolder;">Second Line</p>'+
      '<div class="row">'+
        '<canvas id="myChart" width="200" height="100"></canvas>'+
      '</div>'+
      '</div>';

var _resultPanel = '<div class="center">'+
     '<p>Resultados para '+
     '<span style="background:#F1F1F1; padding: 5px; font-style: italic; '+
     'border-radius: 3px; font-weight: 100"></span></p>'+
     '<h5>Result</h5>'+
     '<span style="font-weight:300; font-size:12px">Imágenes encontradas en <span></span> seg.</span>'+
     '<p class="try-other"></p>'+
     /*'<div class="switch">'+
     '<label>Off'+
     '<input type="checkbox"><span class="lever"></span>'+
     'On</label>'+
     '</div>'+*/
     '</div>';

var menuSignIn = '<a id="login-menu" href="#sign-in" class="grey-text text-darken-3 modal-trigger" style="font-size:12px;text-transform:uppercase"></a>';

var iconLogin = '<i class="material-icons left" style="height:30px;line-height:30px">account_circle</i>';

var iconLoginDD = function(username){
     return iconLogin + '<i class="material-icons right" style="height:30px;line-height:30px">arrow_drop_down</i>'+username;
     }

var btnTryWith = function(tabUrl, keyword){
     return '<a class="btn waves-effect waves-light" href="'+tabUrl+'">'+
                              'Try with: '+keyword+'</a>';
     }

var cardAction = function(item, provider){
     return '<a href="'+item+'?provider='+provider+'">Preview</a>';
     }

var userImageList = '<div class="recent-activity-list chat-out-list row">'+
     '<div class="col s4 recent-activity-list-icon">'+
     '<div class="center-align">'+
     '<img src="" width="100%">'+
     '</div>'+
     '</div>'+
     '<div class="col s4 recent-activity-list-text" style="font-weight:300">'+
     '<b>Code</b>'+
     '<p style="margin-top:5px">Provider</p>'+
     '</div>'+
     '<div class="col s4">'+
     '<a class="btn-flat waves-effect waves-grey"><i class="material-icons">file_download</i></a>'+
     '</div>'+
     '</div>';

var planesResult = function(plan) {
      let row = "<li><div class='collapsible-header valign-wrapper "+plan.deal+"'>"+
                "<img src='"+location.origin+"/latincolor/img/"+plan.provider+".png'/>";
      if (plan.deal!='')
          row +="<span class='new badge red' data-badge-caption='oferta'>mejor</span>";

      row += "</div><div class='collapsible-body'>"+
              "<div class='row'><div class='col s6 m6 l6' style='border-right:1px #ccc solid'>"+
              "<strong>Tienes licencia para usar tus recursos en:</strong>"+
              "<p><i class='material-icons tiny pink-text'>check_circle</i><strong style='padding-left: 0.5em'>Comercial</strong><br/>"+
              "Publicidad, promoción, comercialización y otros usos comerciales.</span></p>"+
              "<p><i class='material-icons tiny pink-text'>check_circle</i><strong style='padding-left: 0.5em'>Editorial</strong><br/>"+
              "Interés periodístico o general. Uso no comercial.</span></p></div>"+
              "<div class='col s6 m6 l6 center'>"+
              "<div class='price' style='position: relative;font-size: 4rem;line-height: 1.6em;font-weight: 300;text-align: center;'>"+
                "<sup style='font-weight: 100;font-size: 1.42rem;line-height: 1.6em;top: -26px;'>$</sup>"+plan.por_imagen+
                "<sub style='font-weight: 100;font-size: 1.42rem;line-height: 1.6em;top: 0;'>c/imagen</sub>"+
              "</div>";
      row +=  "<span style='text-align: center;text-decoration: line-through;'>Precio regular: $4.00</span>"+
              "<p style='border-bottom: none;text-align: center;font-size: 1.07rem;line-height: 1.6em;'>"+
              plan.fotos_suscripcion+" IMÁGENES</p>"+
              "<p style='border-bottom: none;text-align: center;font-size: 1.07rem;line-height: 1.6em;'>"+
              "COSTO DEL PLAN "+plan.valor+"</p><a href='#!' data-id='"+plan.id+"' data-valor='"+plan.valor+"' data-iva='"+plan.iva+
              "' data-tco='"+plan.tco+"' data-provider='"+plan.provider+
                "' class='comprar-plan-btn btn waves-effect waves-light center'>Comprar ahora</a>"+
              "</ul></div></div></div>"+"</li>";
      return row;
    }

var fakeCounter = '<div style="height:118px" class="valign-wrapper">'+
     '<h5 class="timer" style="margin:0 auto"></h5></div>';

return {
     circleLoader: circleLoader,
     cardTemplate: cardTemplate,
     resultPanel: resultPanel,
     menuSignIn: menuSignIn,
     iconLogin: iconLogin,
     iconLoginDD: iconLoginDD,
     btnTryWith: btnTryWith,
     cardAction: cardAction,
     userImageList: userImageList,
     planesResult: planesResult,
     fakeCounter: fakeCounter
}

})();