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

var userImageList = function(val) {
     let color = (val.subscription_id != ''?'color:white;background-color:green;padding:5px':'');
     let tooltip = (val.subscription_id != ''?'tooltipped':'');
     let dataTool = (val.subscription_id != ''?'data-position="top" data-delay="50" data-tooltip="Descargado con tu plan"':'');
     return '<div class="recent-activity-list chat-out-list row '+tooltip+'" '+dataTool+' style="margin-bottom:0">'+
     '<div class="col s4 recent-activity-list-icon">'+
     '<div class="center-align">'+
     '<img src="'+val.img_url+'" width="100%">'+
     '</div>'+
     '</div>'+
     '<div class="col s4 recent-activity-list-text" style="font-weight:300">'+
     '<b style="'+color+'">'+val.img_code+'</b>'+
     '<p style="margin-top:5px;">'+val.img_provider+'</p>'+
     '</div>'+
     '<div class="col s4">'+
     '<a class="redownload btn-flat waves-effect waves-grey" data-provider="'+val.img_provider+'" '+
     'data-lid="'+val.license_id+'" data-id="'+val.img_code+'" data-type="'+val.type+'">'+
     '<i class="material-icons">file_download</i></a>'+
     '</div>'+
     '</div>';
}

var userPlanList = function(val) {
     let logoProvider = ROOT+"img/"+val.provider+".png";
     return '<div class="recent-activity-list chat-out-list row">'+
     '<div class="col s4 recent-activity-list-icon">'+
     '<div class="center-align">'+
     "<img src='"+logoProvider+"' height='16'/>"+
     '</div>'+
     '</div>'+
     '<div class="col s4 recent-activity-list-text" style="font-weight:300">'+
     '<b>'+val.img_code+'</b>'+
     '</div>'+
     '<div class="col s4">'+
     val.session_date+
     '</div>'+
     '</div>';
}

var planInfo = function(val) {
     return '<div class="chat-out-list row">'+
     '<div class="col s7"><div>Limite de descargas</div></div>'+
     '<div class="col s5" style="font-weight:300"><b>'+val.totalAmount+'</b></div>'+
     '</div>'+
     '<div class="chat-out-list row">'+
     '<div class="col s7"><div>Descargas disponibles</div></div>'+
     '<div class="col s5" style="font-weight:300"><b>'+val.subscriptionAmount+'</b></div>'+
     '</div>'+
     '<div class="chat-out-list row">'+
     '<div class="col s7"><div>Fecha vencimiento</div></div>'+
     '<div class="col s5" style="font-weight:300"><b>'+val.fecha_fin+'</b></div>'+
     '</div>';
}

var providerPlanFeatures = function(provider, medio) {
     let commonFeatures = {
          'Fotos': [
               'Imágenes Royalty Free, libres de derechos',
               'Uso en cualquier territorio',
               'Por tiempo indefinido',
               'Perpetuidad uso de las imágenes',
               'Todo uso comercial y publicitario',
               'Todo uso digital y en redes sociales',
               'Material merchandising entregado de manera gratuita'
          ],
          'Videos': [
               'Videos Royalty Free, libres de derechos',
               'Uso en cualquier territorio',
               'Por tiempo indefinido',
               'Perpetuidad uso de los videos',
               'Todo uso digital y en redes sociales',
               'Presentaciones, Cine y TV',
               'No tiene limitante de reproducciones'
          ]
     };
     let particularFeatures = {
          'Depositphoto': {
               'Fotos': [
                    'Impresiones hasta 500.000 copias por imagen en cualquier medio',
               ],
               'Videos': []
          },
          'Dreamstime': {
               'Fotos': [
                    'No tiene limitantes de impresiones',
               ],
               'Videos': []
          },
          'Ingimages': {
               'Fotos': [
                    'Impresiones hasta 1.000.000 copias por imagen en cualquier medio',
               ],
               'Videos': []
          },
          'Adobe': {
               'Fotos':[],
               'Videos':[]
          }
     };
     medio = medio.substring(0,6);
     let allFeatures = commonFeatures[medio];
     console.log(medio);
     allFeatures.push.apply(allFeatures, particularFeatures[provider][medio]);
     return allFeatures;
}

var planesResult = function(plan, desc) {
      let logoProvider = ROOT+"img/"+plan.provider+".png";
      //let row = "<li><div class='collapsible-header valign-wrapper "+plan.deal+"'>"+
      let row = "<li><div class='collapsible-header valign-wrapper' "+ (plan.deal!=''?"style='border-left:5px solid red'":"")+">"+
                "<img src='"+logoProvider+"' height='23'/>";
      //if (plan.deal!='')
          //row +="<span class='new badge red' style='position:absolute;right:38%' data-badge-caption='oferta'>mejor</span>";

      row += "</div><div class='collapsible-body' style='background-color:#eee'>"+
              "<div class='row'><div class='col s12 m7 l7'>"+
              "<strong>Tienes licencia estándar para usar tus recursos fotográficos en:</strong>"+
              planFeatures(providerPlanFeatures(plan.provider, plan.medio))+
              "<p style='font-size:12px'>No incluye permiso para material merchandising destinado para la venta.</p>"+
              "<p style='font-size:12px'>Las fotografías de uso Editorial no pueden ser usadas en medios publicitarios.</p>"+
              "</div>"+
              "<div class='col s12 m5 l5 center'>"+
              "<div class='price'>"+
                "<sup class='currency'>US$</sup>"+plan.por_imagen.toLocaleString()+
                "<sub class='millar'>c/"+(plan.medio=='Fotos'?'foto':'video')+"</sub>"+
              "</div>";
      row +=  "<span style='text-align: center;text-decoration: line-through;'>Precio regular: $4.00</span>"+
              "<p style='border-bottom: none;text-align: center;font-size: 1.07rem;line-height: 1.6em;'>"+
              plan.fotos_suscripcion+" "+plan.medio+"</p>"+
              "<p style='border-bottom: none;text-align: center;font-size: 1.07rem;line-height: 1.6em;color:red'>"+
              "COSTO DEL PLAN US$"+plan.valor.toLocaleString()+"</p>"+planButton(plan,desc)+
              "</ul></div></div></div>"+"</li>";
      return row;
    }

var providerPreviewFeatures = function(provider) {
     let particularFeatures = {
          'Depositphoto': [
               'Todo uso digital y en redes sociales',
               'Impresiones hasta 500.000 copias por imagen en cualquier medio',
               'Todo uso comercial y publicitario',
               'Material merchandising entregado de manera gratuita'
          ],
          'Dreamstime': [
               'Todo uso digital y en redes sociales',
               'No tiene limitante de impresiones',
               'Todo uso comercial y publicitario',
               'Material merchandising entregado de manera gratuita'
          ],
          'Fotosearch': [
               'Todo uso digital y en redes sociales',
               'Impresiones hasta 1.000.000 copias por imagen en cualquier medio',
               'Todo uso comercial y publicitario',
               'Material merchandising entregado de manera gratuita'
          ],
          'Adobe': []
     }
     return particularFeatures[provider];
}

var planFeatures = function(features) {
     let checkIcon = "<i class='material-icons tiny green-text'>check_circle</i><span style='padding-left: 0.5em'>";
     let item = '';
     $.each(features, function(index, feature){
          item += '<p>' + checkIcon + feature + '</span></p>';
     });
     return item;
}

var previewFeatures = function(provider) {
     let features = providerPreviewFeatures(provider);
     let tag = '<ul>'+
                '<li class="center" style="margin-bottom:10px"></li>'+
                '<li class="blue darken-4" style="height: 2px;"></li>'+
                '<ul class="collection" style="border:none; padding-left:2.6em; list-style-image:url('+ "'" + ROOT+'img/check_circle.png' + "')" + '">';

     $.each(features, function(index, feature) {
          tag += "<li class='collection-item' style='border-bottom:none; background-color:transparent; padding:7px 20px'>"+
                 "<span style='top:-7px; position:relative'>"+feature+"</span></li>";
     });
     tag += '</ul><li class="indigo" style="height: 2px;"></li></ul>';
     return tag;
}

var planButton = function(plan, desc) {
     let logoProvider = ROOT+"img/"+plan.provider+".png";
     return "<a data-id='"+plan.id+"' data-img='"+plan.id+
               "' data-price='"+plan.valor+"' data-iva='"+plan.iva+"' data-thumb='"+logoProvider+
               "' data-tco='"+plan.tco+"' data-provider='"+plan.provider+"' data-desc='"+desc+"' data-type='plan'"+
               "' data-size='N/A' data-sizelbl='-' data-license='standard' data-trantype='compra_plan' data-idplan='"+plan.offerId+
               "' class='comprar-plan-btn btn waves-effect waves-light blue'><i class='material-icons tiny'>add_shopping_cart</i></a>";
}

var videoTypes = function() {
     return "<li><a href='#!'><i class='material-icons'>image</i>Fotos</a></li>"+
            "<li class='divider'></li>"+
            "<li><a href='#!'><i class='material-icons'>high_quality</i>Videos Web</a></li>"+
            "<li><a href='#!'><i class='material-icons'>hd</i>Videos 720</a></li>"+
            "<li><a href='#!'><i class='material-icons'>hd</i>Videos 1080</a></li>"+
            "<li><a href='#!'><i class='material-icons'>4k</i>Videos 4K</a></li>";
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
     userPlanList: userPlanList,
     planInfo: planInfo,
     planesResult: planesResult,
     videoTypes: videoTypes,
     previewFeatures: previewFeatures,
     fakeCounter: fakeCounter
}

})();
