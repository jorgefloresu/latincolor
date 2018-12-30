(function ( $ ){

    $.Shop = function( element ) {
      this.$element = (element!==undefined ? element : $('#cart')); // top-level element
      this.init();
    };

    $.Shop.prototype = {
      init: function() {
        // initializes properties and methods
        this.$tableCart = $(".shopping-cart");
        this.$tableCartBody = this.$tableCart.find( "tbody" );
        this.$subTotal = $("#stotal");
        this.storage = Storages.localStorage;
        this.cartName = "CI-cart";
        this.total = "total";
        this.iva = 0;
        this.tco = 0;
        this.$emptyCartBtn = $("#empty-cart");
        this.navCartBtn = $(".menu-cart");
        this.sideCartBtn= $(".menu-cart .badge");
        this.$cartCount = $(".cart-count");
        this.$emptyCart = $("#empty-cart");
        this.$checkout  = $("#go-checkout");
        this.$closeCart = $("#close-cart");

        this.objOriginal = '';
        this.objId = '';

        this.$element.data(userData);

        var checkCart = this.storage.get(this.cartName);
        if (checkCart == null || checkCart.length == 0)
          this.$cartCount.toggle(false);
        else
          this.$cartCount.css('display','inline');

        this.actionCart();
        this.createCart();
        this.displayCart();
        this.deleteItem();
        this.downloadItem();
        this.setCartButton();
        this.setShopAndPay();
      },

      setCartButton: function() {
            self = this;
            this.navCartBtn.on("mouseover", function () {
              let cart = self.storage.get(self.cartName);
              if (cart.length > 0)
                $(this).tooltip('remove');
            })
      },

      setShopAndPay: function() {
            let self = this;
            this.navCartBtn.on('click', function(event) {
                event.preventDefault();
                if ($('#stotal').text() != '0.00') {
                  self.$emptyCart.show();
                  self.$checkout.show();
                  self.$element.modal({
                    complete: function() {
                      self.$tableCartBody.find('.remove a').click();
                    }
                  });
                  self.$element.modal("open");
                }
            });
            Pay.setup.init();
            Pay.setup.$CCWindow.modal({
                complete: function(modal) {
                  if (Pay.setup.$message.html() == 'APROBADA')
                    self.setMessage('Descargando...');
                }
            })
      },

      actionCart: function() {
          self = this;
          this.$emptyCart.on('click', function(event){
            event.preventDefault();
            self.emptyCart();
          });
          this.$checkout.on('click', function(event){
            event.preventDefault();
            let total = self._convertString(self.$subTotal.text());
            if ($.Auth.status() == 'loggedIn') {
              if (total == 0) {
                self._toast("empty");
              } else if ($.Auth.enabled()) {
                self.payTotal();
              } else {
                self._toast("perfil");
              }
            } else {
              self._toast("sesion");
            }
          });
          this.$closeCart.on('click', function() {
              Materialize.Toast.removeAll();
              $('#downloading').addClass('hide');
          })
      },

      createCart: function() {
        if( this.storage.get( this.cartName ) == null ) {
          //console.log('init cart');
          let cart = [];
          this.storage.set( this.cartName, cart);
          this.storage.set( this.total, "0" );
        }
      },

      displayCart: function() {
        let cart = this.storage.get(this.cartName);
        let country = $.Auth.info('country');
        if (cart == null)
            this.$tableCartBody.html("");
        else {
          let total = this.storage.get(this.total);
          let cartLength = cart.length;
          if (cartLength == 0){
            this.updateCount(this, 'removeAll');
          }
          else {
            let html = "";
            let iva = 0, tco = 0;
            for (let i=0; i<cartLength; i++){
              let item = cart[i];
              iva += (country=='Colombia' || country==null ? item.iva : 0);
              tco += item.tco;
              html += "<tr><td><img src='"+item.thumb+"' height='auto' style='max-height:110px;width: auto;min-width: 110px;max-width:110px'/></td><td>" + item.id + "</td>";
              html += "<td>" + item.desc    + "</td><td>" + item.sizelbl  + "</td>";
              html += "<td>" + item.license + "</td><td class='right-align'>" + item.price.toFixed(2).toLocaleString() + "</td>";
              html += "<td class='download center'><a href='' data-item='" + item.id + "' class='btn-flat'><i class='material-icons'>file_download</i></a></a></td>";
              html += "<td class='delete center'><a href='' data-item='" + item.id + "' class='btn-flat'><i class='material-icons'>delete</i></a></td></tr>";
            }
            this.$tableCartBody.html( html );
            this.$cartCount.text(cartLength).toggle(true);
            this.sideCartBtn.text(cartLength);
            this.sideCartBtn.attr('data-badge-caption','items');
            this.sideCartBtn.addClass('new red');
            this.iva = iva;
            this.tco = tco;
          }
          this.$subTotal.text(total.toFixed(2).toLocaleString());
        }
      },

      addToCart: function(item){
        let added = {
          status: '',
          item: null
        };
        var cart = this.storage.get(this.cartName);
        if (cart == null)
           this.init();
        let id = $(item).data('img');
        if (id != "") {
          $(item).data('item',$(item).data('img'));
          let foundItem = this._found(item);
          if (foundItem == null) {
            let size = $(item).data('size');
            let sizelbl = $(item).data('sizelbl');
            let license = $(item).data('license');
            let price = this._convertString($(item).data('price'));
            let desc = $(item).data('desc');
            let thumb = $(item).data('thumb');
            let provider = $(item).data('provider');
            let iva = this._convertString($(item).data('iva'));
            let tco = this._convertString($(item).data('tco'));

            let total = this.storage.get(this.total);
            cart.push({'id':id, 'desc':desc, 'size':size, 'sizelbl':sizelbl, 'license':license,
                        'price':price, 'iva':iva, 'tco':tco, 'thumb':thumb, 'provider':provider});
            total = total + price;
            console.log("add total:"+total);
            this.storage.set(this.total, total);
            this.storage.set(this.cartName, cart);
            this.$cartCount.text(cart.length);
            this.sideCartBtn.text(cart.length);
            this.$cartCount.css('display','inline');
            added.status = 'added';
            Materialize.toast('Item '+id+', tamaño '+size+' ha sido agregado',2000);
          } else {
            if (foundItem.element.size == $(item).data('size')) {
              added.status = 'deleted';
              Materialize.toast('Item '+id+', tamaño '+$(item).data('size')+' fue removido de la lista de compras',2000);
            } else {
              added.status = 'exists';
              Materialize.toast('Item '+id+', ya está agregado en la lista de compras',2000);
            }
          }
          added.item = item;
        }
        //}
        // var count = storage.get('media');
        return added;
      },

      deleteItem: function() {
        var self = this;
        this.$element.on("click", ".delete a", function(event){
            event.preventDefault();
            var cart = self.storage.get(self.cartName);
            var total = self.storage.get(self.total);
            var item = self._found(this);
            if (item != null) {
              console.log("total antes:"+total);
              total = total - self._convertString(item.element.price);
              cart.splice(item.index,1);
              console.log(cart.length);
              self.$cartCount.text(cart.length);
              self.sideCartBtn.text(cart.length);
              self.storage.set(self.cartName, cart);
              self.storage.set(self.total, total);
              $(this).parents('tr').remove();
              self.$subTotal.text(total.toFixed(2));
              if (cart.length == 0){
                  self.updateCount(self, 'removeAll');
              }
              console.log("total despues:"+total);
            }
        });
      },

      downloadItem: function() {
        let self = this;
        this.$element.on('click', '.download a', function(event){
          event.preventDefault();
          // if (typeof Api !== 'undefined') {
          //   console.log('con api');
          //   if ( Api.isLogged() ) {
          //     self.actionItem(this);
          //   }
          // } else {
            if ($.Auth.status() == 'loggedIn') {
              if ( $.Auth.enabled() )
                self.actionItem(this);
              else {
                self._toast("perfil");
              }
            } else {
              self._toast("sesion");
            }
          //}
        });

      },

      setMessage: function(mess) {
        if (mess == 'restore'){
          $(this.objId).html(this.objOriginal);
        }
        else
          $(this.objId).html(mess);

      },

      payItem: function(item) {
        var order = Math.floor((Math.random()*1000000)+1);
        let userLogged = '';
        //if (typeof Api !== 'undefined')
        //  userLogged = Api.whoIsLogged();
        //else {
          userLogged = $.Auth.info('username');
        //}
        Pay.setup.factDescript.text(item.desc+', tamaño: '+item.size);
        Pay.setup.factSubtotal.text(this._convertString(item.price).toFixed(2).toLocaleString());
        Pay.setup.factIVA.text(this._convertString(item.iva).toFixed(2).toLocaleString());
        Pay.setup.factTCO.text(this._convertString(item.tco).toFixed(2).toLocaleString());
        Pay.setup.factTotal.text((this._convertString(item.price)+
                                  this._convertString(item.iva)+
                                  this._convertString(item.tco)).toFixed(2).toLocaleString());

        Pay.setup.$CCForm.trigger('reset');
        Pay.setup.$orderId.val(order);
        Pay.setup.$totalId.val(item.price);
        Pay.setup.$orderNumber.val(order);
        Pay.setup.selCartItem.id.val(item.id);
        Pay.setup.selCartItem.description.val(item.desc);
        Pay.setup.selCartItem.size.val(item.size);
        Pay.setup.selCartItem.license.val(item.license);
        Pay.setup.selCartItem.provider.val(item.provider);
        Pay.setup.selCartItem.username.val(userLogged);
        Pay.setup.cartItems = [];
        Pay.setup.cartItems.push({'orderId':order, 'productId':item.id, 'size':item.size, 'license_type':item.license,
                                  'price':item.price, 'iva':item.iva, 'tco':item.tco, 'provider':item.provider, 'thumb':item.thumb,
                                  'description':item.desc, 'tranType':'compra_img', 'username':userLogged});

        Pay.setup.$aviso.addClass('hide');
        Pay.setup.$message.html('');
        this.openPayWindow();

      },

      actionItem: function(objSelected) {
          var item = this._found(objSelected);
          if (item != null) {
            let country = $.Auth.info('country');
            item.element.iva = (country=='Colombia' || country==null ? item.element.iva : 0);

            this.objOriginal = objSelected;
            $(objSelected).parent().attr('id', item.element.id);
            this.objId = '#'+item.element.id;
            this.payItem(item.element);
            //Api.download(Pay.setup.selCartItem);
            //console.log(item);
            //alert('id:'+item.element.id+' size:'+item.element.size+' price:'+
            //    item.element.price+' provider:'+item.element.provider);

          }
      },

      payPlan: function(plan) {
        if ($.Auth.status() == 'loggedIn') {
          if ( $.Auth.enabled() ) {

            let order = Math.floor((Math.random()*1000000)+1);
            let userLogged = $.Auth.info('username');
            let country = $.Auth.info('country');
            plan.iva = (country=='Colombia' || country==null ? plan.iva : 0 );
            Pay.setup.factDescript.text(plan.desc);
            Pay.setup.factSubtotal.text(this._convertString(plan.price).toFixed(2).toLocaleString());
            Pay.setup.factIVA.text(this._convertString(plan.iva).toFixed(2).toLocaleString());
            Pay.setup.factTCO.text(this._convertString(plan.tco).toFixed(2).toLocaleString());
            Pay.setup.factTotal.text((this._convertString(plan.price)+
                                      this._convertString(plan.iva)+
                                      this._convertString(plan.tco)).toFixed(2).toLocaleString());

            Pay.setup.$CCForm.trigger('reset');
            Pay.setup.$orderId.val(order);
            Pay.setup.$totalId.val(this._convertString(plan.price));
            Pay.setup.$orderNumber.val(order);
            Pay.setup.$message.html('');
            Pay.setup.$aviso.addClass('hide');
            Pay.setup.$CCWindow.modal('open');
            Pay.setup.userName.val(userLogged);
            Pay.setup.cartItems = [];
            Pay.setup.cartItems.push({'orderId':order, 'productId':plan.id,
                                      'price':plan.price,
                                      'iva':plan.iva, 'tco':plan.tco,
                                      'provider':plan.provider, 'description':plan.desc,
                                      'tranType':'compra_plan', 'username':userLogged});
          } else {
              self._toast("perfil");
          }

        } else {
            this._toast("sesion");

        }
      },

      payTotal: function() {
        console.log(this.$element.find('.download a').length);
        let self = this;
        let order = Math.floor((Math.random()*1000000)+1);
        //let userLogged = '';
        //if (typeof Api !== 'undefined')
        //  userLogged = Api.whoIsLogged();
        //else {
        let userLogged = $.Auth.info('username');
        //}
        Pay.setup.factSubtotal.text(this._convertString(this.$subTotal.text()).toFixed(2).toLocaleString());
        Pay.setup.factIVA.text(this.iva.toFixed(2).toLocaleString());
        Pay.setup.factTCO.text(this.tco.toFixed(2).toLocaleString());
        Pay.setup.factTotal.text((this._convertString(this.$subTotal.text())+
                                  this.iva+
                                  this.tco).toFixed(2).toLocaleString());

        Pay.setup.$CCForm.trigger('reset');
        Pay.setup.$orderId.val(order);
        Pay.setup.$totalId.val(this.$subTotal.text());
        Pay.setup.$orderNumber.val(order);
        Pay.setup.$message.html('');
        Pay.setup.userName.val(userLogged);
        Pay.setup.cartItems = [];
        let textItems = '';
        this.$element.find('.download a').each(function(index){
          let item = self._found(this);
          let country = $.Auth.info('country');
          textItems += item.element.id + ', ' + item.element.size + '; ';
          item.element.iva = (country=='Colombia' || country==null ? item.element.iva : 0);
          Pay.setup.cartItems.push({'orderId':order, 'productId':item.element.id, 'size':item.element.size, 'license_type':item.element.license,
                                    'price':item.element.price.toFixed(2).toLocaleString(), 'iva':item.element.iva.toFixed(2).toLocaleString(),
                                    'tco':item.element.tco.toFixed(2).toLocaleString(), 'provider':item.element.provider, 'thumb':item.element.thumb,
                                    'description':item.element.desc, 'tranType':'compra_imgs', 'username':userLogged});
        });
        Pay.setup.factDescript.text(textItems);

        if (Pay.setup.cartItems.length > 1) {
          Pay.setup.$aviso.removeClass('hide');
        } else {
          Pay.setup.$aviso.addClass('hide');
        }

        this.openPayWindow();

        //console.log(Pay.setup.cartItems.length, Pay.setup.cartItems);
      },

      openPayWindow: function() {
        Pay.setup.$CCWindow.modal({
          complete: function(){
            console.log(Pay.setup.resCartItems);
            $.each(Pay.setup.resCartItems, function(index, item){
              if (item.result == 'success') {
                $('.delete a[data-item="'+item.productId+'"]')
                    .html('')
                    .parent()
                    .addClass('remove');
                $('.download a[data-item="'+item.productId+'"]')
                    .html("<i class='material-icons green-text'>check_circle</i>");
              }
            });
            self.$emptyCart.hide();
            self.$checkout.hide();
            $('#downloading').removeClass('hide');
          }
        });

        Pay.setup.$CCWindow.modal('open');

      },

      emptyCart: function() {
        if (this.storage != undefined)
          this.storage.remove([this.cartName, this.total]);
          this.$tableCartBody.html("");
          this.$subTotal.html('0.00');
          this.navCartBtn.tooltip();
          this.init();

      },

      updateCount: function (obj, action) {
        if (action == 'removeAll') {
          obj.$cartCount.text('');
          obj.$subTotal.html('0.00');
          obj.$cartCount.toggle(false);
          obj.navCartBtn.tooltip();
          obj.sideCartBtn.text('');
          obj.sideCartBtn.attr('data-badge-caption','');
          obj.sideCartBtn.removeClass('new red');
        } else {

        }

      },

      _toast: function(type){
          let text, link;
          let close = '<a class="toast-action" onclick="Materialize.Toast.removeAll()"><i class="material-icons white-text" style="padding-right:10px">clear</i></a>';
          switch (type) {
            case 'empty':
              text = "No tienes elementos en tu lista de compra";
              link = close;
              break;
            case 'perfil':
              text = "Debes completar tu perfil";
              link = '<a href="'+location.origin+'/latincolor/main/user'+'" class="btn-flat toast-action modal-trigger" onclick="Materialize.Toast.removeAll()">Ir a tu perfil</a>';
              break;
            case 'sesion':
              text = "Debes iniciar sesión";
              link = '<a href="#sign-in" class="btn-flat toast-action modal-trigger" onclick="Materialize.Toast.removeAll()">Iniciar Sesión</a>';
              break;
          }
          let toast = $('<span>'+text+'</span>')
                      .add($(link))
                      .add($(close));

          Materialize.toast(toast, 30000);

      },

      _found: function(objSelected) {
        var cart = this.storage.get(this.cartName);
        let cartLength = cart.length;
        for (let i=0; i<cartLength; i++) {
            let item = cart[i];
            let selected = $(objSelected).data('item');
            if (item.id == selected) {
              return {index:i, element:cart[i]};
            }
          }
        return null;
      },

      _convertString: function( numStr ) {
        var num;
        numStr = numStr.toString().replace(",", ".");
        if( /^[-+]?[0-9]+\.[0-9]+$/.test( numStr ) ) {
          num = parseFloat( numStr );
        } else if( /^\d+$/.test( numStr ) ) {
          num = parseInt( numStr, 10 );
        } else {
          num = Number( numStr );
        }

        if( !isNaN( num ) ) {
          return num;
        } else {
          console.warn( numStr + " cannot be converted into a number" );
          return false;
        }
      }

    };

}(jQuery));
