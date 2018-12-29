var Pay = (function () {

    var setup = {
    	init: function() {
	        values = {
            factDescript: $('.fact-descript'),
            factSubtotal: $('.fact-subtotal'),
            factIVA: $('.fact-iva'),
            factTCO: $('.fact-tco'),
            factTotal: $('.fact-total'),

	        	$CCForm: $('#myCCForm'),
		        $orderId: $('#orderId'),
		        $totalId: $('#totalId'),
		        $orderNumber: $('#orderNumber'),
            cartItems: [],
            userName: $('#pay_username'),
		        selCartItem: {
			        id: $('#imageCode'),
			        description: $('#description'),
			        size: $('#size'),
              price: $('#totalId'),
			        license: $('#license'),
			        provider: $('#pay_provider'),
              tran_type: '',
			        username: $('#pay_username')
		        },
            resCartItems: [],
            $aviso: $('#aviso'),
		        $message: $('#pay-message p'),
		        $CCWindow: $('#buy'),

		        $CCNumber: $('#ccNo'),
		        $CCVC: $('#cvv'),
		        $CCMonth: $("#expMonth"),
		        $CCYear: $("#expYear")
	    	};

	    	$.extend(setup, values);

  			TCO.loadPubKey('sandbox');
  			setup.$CCForm.submit(function(e) {
  			  e.preventDefault();
  			  tokenRequest();
  			})

  			// setup.$CCForm.submit(function(event) {
  			// 	event.preventDefault();
        //   $.download(setup.cartItems, function(res){
        //     setup.resCartItems = res;
        //     setup.$CCWindow.modal('close');
        //   });
  			// })

	    }
	}

	var	successTokenCC = function(datatoken) {
		  // var form_data = {
		  //     token: datatoken.response.token.token,
		  //     orderId: setup.$orderId.val(),
		  //     totalId: Number(setup.$totalId.val()).toFixed(2),
		  //     orderNumber: setup.$orderNumber.val(),
      //
		  //     imageCode: setup.selCartItem.id.val(),
		  //     description: setup.selCartItem.description.val(),
		  //     username: setup.selCartItem.username.val(),
      //     items: JSON.stringify(setup.cartItems)
		  // };
      let form_data = {
		      token: datatoken.response.token.token,
		      orderId: setup.$orderId.val(),
		      totalId: Number(setup.factTotal.text()).toFixed(2),
		      orderNumber: setup.$orderNumber.val(),

		      productId: setup.cartItems[0].productId,
          provider: setup.cartItems[0].provider,
		      description: setup.cartItems[0].description,
          tranType: setup.cartItems[0].tranType,
		      username: setup.cartItems[0].username,
          order: JSON.stringify({
            orderId: setup.$orderId.val(),
            tranType: setup.cartItems[0].tranType,
            provider: setup.cartItems[0].provider,
            username: setup.cartItems[0].username,
            description: setup.cartItems[0].description,
          }),
          items: JSON.stringify(setup.cartItems)
		  };
		  setup.$message.html('Verificando tarjeta...');
      console.log(form_data);
		  $.ajax({
		      url: setup.$CCForm.prop('action'),
		      type: 'POST',
		      data: form_data,
		      dataType: 'json'
		  }).done(function(data){
		      if (data.response.responseCode == 'APPROVED'){
		          setup.$CCWindow.modal('close');
		          setup.$message.html('APROBADA');
		          //-- before: Api.download(setup.selCartItem);
              processOrder(form_data);
		      }
		  }).fail(function(data){
          console.log(data);
		      setup.$message.html('<span style="color:red">Tarjeta rechazada. Intente con otra</span>');
		 });
	}

		// Called when token creation fails.
	var	errorTokenCC = function(data) {
		  if (data.errorCode === 200) {
		      tokenRequest();
		  } else {
		      alert(data.errorMsg);
		  }
	}

	var tokenRequest = function() {
		  // Setup token request arguments
		  var args = {
		      sellerId: "901331385",
		      publishableKey: "9D266415-16A9-49D0-AD84-AC8A1A735EB2",
		      ccNo: setup.$CCNumber.val(),
		      cvv: setup.$CCVC.val(),
		      expMonth: setup.$CCMonth.val(),
		      expYear: setup.$CCYear.val()
		  };
		  // Make the token request
		  TCO.requestToken(successTokenCC, errorTokenCC, args);
	}

  var processOrder = function(form) {
      //let status = ( form.provider=='Depositphoto' ? '' : 'ord' );
      //+form.orderId+'/'+form.username+'/'+form.totalId+'/'+form.description+'/'+form.tranType+'/'+status)
      $.getJSON(location.origin+'/latincolor/order/confirmar_orden', form)
          .done(function(res){
            if (res.process == 'ok') {
              if (form.tranType == 'compra_img' || form.tranType == 'compra_imgs') {
                $.download(setup.cartItems, function(res){
                  setup.resCartItems = res;
                  setup.$CCWindow.modal('close');
                });
              }
            }
          })
          .fail(function(res){
            console.log(res);
          });
  }

	return {
		setup: setup
	}

})();