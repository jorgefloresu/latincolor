var Pay = (function () {

	var setup = {
		init: function () {
			var values = {
				factDescript: $('.fact-descript'),
				factSubtotal: $('.fact-subtotal'),
				factIVA: $('.fact-iva'),
				factTCO: $('.fact-tco'),
				factTotal: $('.fact-total'),

				$CCForm: $('#myCCForm'),
				$orderId: $('#orderId'),
				$totalId: $('#totalId'),
				tranType: '',
				$orderNumber: $('#orderNumber'),
				cartItems: {
					images:[],
					planes:[]
				},
				userName: $('#pay_username'),
				/* selCartItem: {
					id: $('#imageCode'),
					description: $('#description'),
					size: $('#size'),
					price: $('#totalId'),
					license: $('#license'),
					provider: $('#pay_provider'),
					tran_type: '',
					username: $('#pay_username'),
					idplan: ''
				}, */
				resCartItems: {
					images:[],
					planes:[]
				},
				$aviso: $('#aviso'),
				$message: $('#pay-message p'),
				$CCWindow: $('#buy'),

				tipoTarjeta: $('#tipo-tarjeta'),
				$CCNumber: $('#ccNo'),
				$CCVC: $('#cvv'),
				$CCMonth: $("#expMonth"),
				$CCYear: $("#expYear"),
				token: '',
				processed: false,

				tabsPayMethods: $('ul.tabs')
			};
			
			$.extend(setup, values);

			//TCO.loadPubKey('sandbox');
			setup.$CCForm.submit(function (e) {
			//$('.login-form').submit(function(e) {
				e.preventDefault();
				setup.$message.html('Enviando información...');
				//console.log(this);
				//tokenRequest();
				//tokenRequestPayU();
				//payWithToken();
				payWithPayU();
			})

			/* setup.$CCForm.submit(function(event) {
				event.preventDefault();
				console.log(setup.cartItems);
				let form_data = {
					token: 'prueba',
					orderId: setup.$orderId.val(),
					totalId: setup.factTotal.text().replace(",", "."),
					tranType: setup.tranType,
					username: setup.userName.val(),
					items: JSON.stringify(setup.cartItems)
				};
				processOrder(form_data);
				// $.download(setup.cartItems.images, function(res){
				// 	setup.resCartItems.images = res;
				// 	setup.$CCWindow.modal('close');
				// });
			}) */

		}
	}

	var payWithPayU = function() {
		let args = {
			url: setup.$CCForm.prop('action'), 
			//location.origin + '/latincolor/order/PayU_pay',
			inputs: {
				//orderId: setup.$orderId.val(),
				totalId: setup.factTotal.text().replace(",", "."),
				tranType: setup.tranType,
				username: setup.userName.val(),
				tipoTarjeta: setup.tipoTarjeta.val(),
				ccNo: setup.$CCNumber.val(),
				expMonth: setup.$CCMonth.val(),
				expYear: setup.$CCYear.val(),
				cvv: setup.$CCVC.val(),
				items: JSON.stringify(setup.cartItems)
			}
		};

		$.submitForm(args, function(res){
			console.log(res);
			if (res.state == "APPROVED") {
				let textResult = 'APROBADA. Procesando, espere...';
				setup.$message.html(textResult);
				setup.token = res.transactionId;
				args.inputs.orderId = res.merchantOrderId;
				args.inputs.token = res.transactionId;
				console.log(args.inputs);
				processOrder(args.inputs);
			} else if (res.state == "DECLINED") {
				console.log(res.responseMessage);
				setup.$message.html('<span style="color:red">'+res.responseMessage+'</span>');
			} else {
				console.log(res);
				setup.$message.html('<span style="color:red">'+res+'</span>');
			}
		})
	}

	var successTokenCC = function (datatoken) {
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
		setup.token = datatoken.response.token.token;

		let form_data = {
			token: datatoken.response.token.token,
			//orderId: setup.$orderId.val(),
			orderId: 0,
			totalId: setup.factTotal.text().replace(",", "."),
			tranType: setup.tranType,
			username: setup.userName.val(),

			//orderNumber: setup.$orderNumber.val(),

			/* productId: setup.cartItems[0].productId,
			provider: setup.cartItems[0].provider,
			description: setup.cartItems[0].description,
			tranType: setup.cartItems[0].tranType,
			username: setup.cartItems[0].username, */
			/* order: JSON.stringify({
				orderId: setup.$orderId.val(),
				tranType: setup.cartItems[0].tranType,
				idplan: setup.cartItems[0].idplan,
				provider: setup.cartItems[0].provider,
				username: setup.cartItems[0].username,
				description: setup.cartItems[0].description,
			}), */
			items: JSON.stringify(setup.cartItems)
		};
		setup.$message.html('Verificando tarjeta...');
		$.ajax({
			url: setup.$CCForm.prop('action'),
			type: 'POST',
			data: form_data,
			dataType: 'json'
		}).done(function (data) {
			if (data.response.responseCode == 'APPROVED') {
				//setup.$CCWindow.modal('close');
				let textResult = 'APROBADA. Procesando, espere...';
				setup.$message.html(textResult);
				form_data.orderId = data.response.merchantOrderId;
				//-- before: Api.download(setup.selCartItem);
				console.log(form_data);
				//processOrder(form_data);
			}
		}).fail(function (data) {
			console.log(data);
			if (data.responseText == 'Ya existe login') {
				setup.$message.html('<span style="color:red">Problema con la suscripción. Contáctenos</span>');
			} else {
				setup.$message.html('<span style="color:red">Tarjeta rechazada. Intente con otra</span>');
			}
		});
	}

	// Called when token creation fails.
	var errorTokenCC = function (data) {
		if (data.errorCode === 200) {
			tokenRequest();
		} else {
			alert(data.errorMsg);
		}
	}

	var tokenRequest = function () {
		// Setup token request arguments
		let args = {
			sellerId: "901331385",
			publishableKey: "9D266415-16A9-49D0-AD84-AC8A1A735EB2",
			ccNo: setup.$CCNumber.val(),
			cvv: setup.$CCVC.val(),
			expMonth: setup.$CCMonth.val(),
			expYear: setup.$CCYear.val()
		};
		// Make the token request
		//console.log(args);
		TCO.requestToken(successTokenCC, errorTokenCC, args);
	}

	var tokenRequestPayU = function () {
		let args = {
			url: location.origin + '/latincolor/order/PayU_token',
			inputs: {
				username: setup.userName.val(),
				tipoTarjeta: setup.tipoTarjeta.val(),
				ccNo: setup.$CCNumber.val(),
				expMonth: setup.$CCMonth.val(),
				expYear: setup.$CCYear.val()
			}
		};
		$.submitForm(args, function(res){
			console.log(res);
			if (res.status != 500) {
				if (res.code == 'SUCCESS') {
					//payWithToken(res);
					console.log(res);
				} else {
					console.log(res);
				}
			} else {
				console.log('Ha ocurrido un error interno');
			}
		})
	}

	var payWithToken = function () {
		let form_data = {
			url: location.origin + '/latincolor/order/PayU_pay_token',
			inputs: {
				//token: datatoken.creditCardToken.creditCardTokenId,
				orderId: 0,
				totalId: setup.factTotal.text().replace(",", "."),
				tranType: setup.tranType,
				username: setup.userName.val()
			}
		}
		$.submitForm(form_data, function(res){
			console.log(res)
		})
	}

	var processOrder = function (form) {
		$.getJSON(location.origin + '/latincolor/order/confirmar_orden', form)
				.then(function (res) {
					if (res.process.images.result !== 'ok') {						
						console.log('error al enviar correo de orden de las imagenes');
						Materialize.toast('No fue posible enviar notificación al correo',4000);
					}
					if (res.process.planes.result !== 'ok') {
						console.log('error al enviar correo de orden del plan');
						Materialize.toast('No fue posible enviar notificación al correo',4000);
					}
				})
				.done(function () {
					setup.processed = true;
					setup.$CCWindow.modal('close');
				})
				.fail(function (res) {
					console.log(res);
				})
	}

	return {
		setup: setup
	}

})();

Pay.setup.init();