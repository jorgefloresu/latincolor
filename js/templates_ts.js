var Templates = (function(){
 var circleLoader = '<div class="center" style="margin-left: 75px">'+
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

 var cardTemplate = '<div class="grid-item"><img src="" /></div>';

 var _cardTemplate = '<div class="thumb-card card hoverable">'+
     '<div class="card-image">'+
     circleLoader+
     '</div>'+
     '<div class="card-content">'+
     '<span class="card-title grey-text text-darken-4 center"></span>'+
     '</div>'+
     '<div class="card-action">'+
     '</div>'+
     '</div>';

var resultPanel = '<div class="center">'+
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

var menuSignIn = '<a id="login-menu" href="#sign-in" class="grey-text text-darken-3 modal-trigger"></a>';

var iconLogin = '<i class="material-icons left">account_circle</i>';

var iconLoginDD = function(username){
     return iconLogin + '<i class="material-icons right">arrow_drop_down</i>'+username;
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
     '<a class="btn-flat waves-effect waves-light"><i class="material-icons">file_download</i></a>'+
     '</div>'+
     '</div>';

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
     fakeCounter: fakeCounter
}

})();
