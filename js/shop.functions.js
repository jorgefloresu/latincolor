(function ($) {

  $.Shop = function (element) {
    this.$element = (element !== undefined ? element : $('#cart')); // top-level element
    this.init();
  };

  $.Shop.prototype = {
    init: function () {
      // initializes properties and methods
      this.$tableCart = $(".shopping-cart");
      this.$tableCartBody = $(".shopping-cart tbody"); //this.$tableCart.find("tbody");
      this.$subTotal = $("#stotal");
      this.storage = Storages.localStorage;
      this.cartName = "CI-cart";
      this.total = "total";
      this.iva = 0;
      this.tco = 0;
      this.$emptyCartBtn = $("#empty-cart");
      this.navCartBtn = $(".menu-cart");
      this.sideCartBtn = $(".menu-cart .badge");
      this.$cartCount = $(".cart-count");
      this.$emptyCart = $("#empty-cart");
      this.$checkout = $("#go-checkout");
      this.$closeCart = $("#close-cart");

      this.objOriginal = '';
      this.objId = '';

      this.$element.data(userData);

      var checkCart = this.storage.get(this.cartName);
      if (checkCart == null || checkCart.length == 0)
        this.$cartCount.toggle(false);
      else
        this.$cartCount.css('display', 'inline');

      this.actionCart();
      this.createCart();
      this.displayCart();
      this.deleteItem();
      this.downloadItem();
      this.setCartButton();
      this.setShopAndPay();
    },

    setCartButton: function () {
      let self = this;
      this.navCartBtn.on("mouseover", function () {
        let cart = self.storage.get(self.cartName);
        if (cart.length > 0)
          $(this).tooltip('remove');
      })
    },

    setShopAndPay: function () {
      let self = this;
      this.navCartBtn.on('click', function (event) {
        event.preventDefault();
        if ($('#stotal').text() != '0') {
          self.$emptyCart.show();
          self.$checkout.show();
          self.$element.modal({
            complete: function () {
              self.$tableCartBody.find('.remove a').click();
            }
          });
          self.$element.modal("open");
        }
      });
      //Pay.setup.init();
      //this.openPayWindow();
      /*Pay.setup.$CCWindow.modal({
        complete: function (modal) {
          if (Pay.setup.$message.html() == 'Transacción aprobada!... preparando compra')
            self.setMessage('Descargando...');
        }
      })*/
    },

    actionCart: function () {
      let self = this;
      self.$checkout.off('click');
      this.$emptyCart.on('click', function (event) {
        event.preventDefault();
        self.$element.off('click', '.download a');
        self.emptyCart();
      });
      this.$checkout.on('click', function (event) {
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
      this.$closeCart.on('click', function () {
        Materialize.Toast.removeAll();
        $('#downloading').addClass('hide');
      })
    },

    createCart: function () {
      if (this.storage.get(this.cartName) == null) {
        //console.log('init cart');
        let cart = [];
        this.storage.set(this.cartName, cart);
        this.storage.set(this.total, "0");
      }
    },

    displayCart: function () {
      let cart = this.storage.get(this.cartName);
      let country = $.Auth.info('country');
      if (cart == null)
        this.$tableCartBody.html("");
      else {
        let total = this.storage.get(this.total);
        let cartLength = cart.length;
        if (cartLength == 0) {
          this.updateCount(this, 'removeAll');
        } else {
          let html = "";
          let iva = 0,
            tco = 0;
          for (let i = 0; i < cartLength; i++) {
            let item = cart[i];
            let icon = 'file_download';
            if (item.size == 'N/A') {
              icon = 'credit_card';
            }
            iva += (country == 'Colombia' || country == null ? item.iva : 0);
            tco += item.tco;
            html += "<tr><td><img src='" + item.thumb + "' height='auto' style='max-height:90px;width: auto;max-width:90px'/></td><td>" + item.id + "</td>";
            html += "<td>" + item.desc + "</td><td class='center'>" + item.sizelbl + "</td>";
            html += "<td class='center'>" + item.license + "</td><td class='right-align'>" + this._price(item.price) + "</td>";
            html += "<td class='download center'><a href='' class='btn blue' id='"+item.id +"' data-item='" + item.id + "' style='padding:0 10px'><i class='material-icons'>" + icon + "</i></a></a></td>";
            html += "<td class='delete center'><a href='' class='btn red' id='"+item.id +"' data-item='" + item.id + "' style='padding:0 10px'><i class='material-icons'>delete</i></a></td></tr>";
          }
          this.$tableCartBody.html(html);
          // this.$tableCartBody.imagesLoaded().progress(function(instance, image){
          //   console.log(image.img.src+'size: '+image.img.width);
          //   $(image.img).css('min-width',0);
          // });
          this.$cartCount.text(cartLength).toggle(true);
          this.sideCartBtn.text(cartLength);
          this.sideCartBtn.attr('data-badge-caption', 'items');
          this.sideCartBtn.addClass('new red');
          this.iva = iva;
          this.tco = tco;
        }
        this.$subTotal.text(this._price(total));
      }
    },

    addToCart: function (item) {
      let added = {
        status: '',
        item: null
      };
      var cart = this.storage.get(this.cartName);
      if (cart == null)
        this.init();
      let id = $(item).data('img');
      if (id != "") {
        $(item).data('item', $(item).data('img'));
        let foundItem = this._found(item);
        if (foundItem == null) {
          let size = $(item).data('size');
          let sizelbl = $(item).data('sizelbl');
          let license = $(item).data('license');
          let desc = $(item).data('desc');
          let thumb = $(item).data('thumb');
          let type = $(item).data('type');
          let provider = $(item).data('provider');
          let tranType = $(item).data('trantype');
          let idplan = $(item).data('idplan') == null ? 0 : this._convertString($(item).data('idplan'));
          let price = this._convertString($(item).data('price'));
          let iva = this._convertString($(item).data('iva'));
          let tco = this._convertString($(item).data('tco'));
          let subscriptionId = $(item).data('subscriptionid');
          if (subscriptionId !== '') {
            //price = iva = tco = 0;
          }

          let total = this.storage.get(this.total);
          cart.push({
            'id': id,
            'desc': desc,
            'size': size,
            'sizelbl': sizelbl,
            'license': license,
            'price': price,
            'iva': iva,
            'tco': tco,
            'thumb': thumb,
            'type': type,
            'provider': provider,
            'tranType': tranType,
            'idplan': idplan,
            'subscriptionid': subscriptionId
          });
          total = total + price;
          console.log("add total:" + total);
          this.storage.set(this.total, total);
          this.storage.set(this.cartName, cart);
          this.$cartCount.text(cart.length);
          this.sideCartBtn.text(cart.length);
          this.$cartCount.css('display', 'inline');
          added.status = 'added';
          this._toast('info', 'Item ' + id + (size == 'N/A' ? '' : ', tamaño ' + size) + ' ha sido agregado');
        } else {
          if ($(item).data('size') != 'N/A') {
            if (foundItem.element.size == $(item).data('size')) {
              added.status = 'deleted';
              this._toast('info', 'Item ' + id + ', tamaño ' + $(item).data('size') + ' fue removido de la lista de compras');
            } else {
              added.status = 'exists';
              this._toast('info', 'Item ' + id + ', ya está agregado en la lista de compras');
            }
          } else {
            if (foundItem.element.size == $(item).data('size')) {
              this._toast('info', 'Item ' + id + ', ya está agregado en la lista de compras');
            }
          }
        }
        added.item = item;
      }
      //}
      // var count = storage.get('media');
      return added;
    },

    deleteItem: function () {
      let self = this;
      this.$element.on("click", ".delete a", function (event) {
        event.preventDefault();
        let cart = self.storage.get(self.cartName);
        let total = self.storage.get(self.total);
        let item = self._found(this);
        if (item != null) {
          console.log("total antes:" + total);
          total = total - self._convertString(item.element.price.toString());
          self.iva = self.iva - self._convertString(item.element.iva.toString());
          self.tco = self.tco - self._convertString(item.element.tco.toString());
          cart.splice(item.index, 1);
          console.log(cart.length);
          self.$cartCount.text(cart.length);
          self.sideCartBtn.text(cart.length);
          self.storage.set(self.cartName, cart);
          self.storage.set(self.total, total);
          self.$element.off('click', '.download a');
          $(this).parents('tr').remove();
          self.$subTotal.text(total.toFixed(2));
          if (cart.length == 0) {
            console.log("cart length 0");
            self.emptyCart();
            self.updateCount(self, 'removeAll');
          }
          console.log("total despues:" + total);
        }
      });
    },

    downloadItem: function () {
      let self = this;
      this.$element.on('click', '.download a', function (event) {
        event.preventDefault();
        // if (typeof Api !== 'undefined') {
        //   console.log('con api');
        //   if ( Api.isLogged() ) {
        //     self.actionItem(this);
        //   }
        // } else {
          console.log($(this));
          if ($.Auth.status() == 'loggedIn') {
            if ($.Auth.enabled())
              self.actionItem(this);
            else {
              self._toast("perfil");
              self.openLoginWindow();
            }
        } else {
          self._toast("sesion");
          self.openLoginWindow();
        }
        //}
      });

    },

    setMessage: function (mess) {
      if (mess == 'restore') {
        $(this.objId).html(this.objOriginal);
      } else
        $(this.objId).html(mess);

    },

    payItem: function (item) {
      console.log(item);
      var order = Math.floor((Math.random() * 1000000) + 1);
      let userLogged = '';
      //if (typeof Api !== 'undefined')
      //  userLogged = Api.whoIsLogged();
      //else {
      userLogged = $.Auth.info('username');
      //}
      Pay.setup.factDescript.text(item.desc + ', tamaño: ' + item.size);
      Pay.setup.factSubtotal.text(this._price(this._convertString(item.price)));
      Pay.setup.factIVA.text(this._price(this._convertString(item.iva)));
      Pay.setup.factTCO.text(this._price(this._convertString(item.tco)));
      Pay.setup.factTotal.text(this._price(this._convertString(item.price) +
        this._convertString(item.iva) +
        this._convertString(item.tco)));

      Pay.setup.$CCForm.trigger('reset');
      Pay.setup.$orderId.val(order);
      Pay.setup.$totalId.val(item.price);
      Pay.setup.tranType = 'Compra de imagen';
      Pay.setup.userName.val(userLogged);
      Pay.setup.$orderNumber.val(order);
      /* Pay.setup.selCartItem.id.val(item.id);
      Pay.setup.selCartItem.description.val(item.desc);
      Pay.setup.selCartItem.size.val(item.size);
      Pay.setup.selCartItem.license.val(item.license);
      Pay.setup.selCartItem.provider.val(item.provider);
      Pay.setup.selCartItem.username.val(userLogged); */
      Pay.setup.cartItems.images = [];
      Pay.setup.cartItems.planes = [];
      Pay.setup.cartItems.images.push({
        'orderId': order,
        'productId': item.id,
        'size': item.size,
        'license_type': item.license,
        'price': item.price,
        'iva': item.iva,
        'tco': item.tco,
        'provider': item.provider,
        'thumb': item.thumb,
        'type': item.type,
        'description': item.desc,
        'tranType': item.tranType,
        'username': userLogged,
        'idplan': '',
        'subscriptionId': item.subscriptionId
      });

      Pay.setup.$aviso.addClass('hide');
      Pay.setup.$message.html('');
      //this.openPayWindow();

    },

    actionItem: function (objSelected) {
      let item = this._found(objSelected);
      if (item != null) {
        let country = $.Auth.info('country');
        item.element.iva = (country == 'Colombia' || country == null ? item.element.iva : 0);

        this.objOriginal = objSelected;
        $(objSelected).parent().attr('id', item.element.id);
        this.objId = '#' + item.element.id;
        if (item.element.tranType == 'compra_img') {
          this.payItem(item.element);
        }
        else if (item.element.tranType == 'compra_plan') {
            console.log("compra plan");
            this.payPlan(item.element);
        }
        
        this.openPayWindow();
        Pay.setup.$CCWindow.modal('open');

        //Api.download(Pay.setup.selCartItem);
        //console.log(item);
        //alert('id:'+item.element.id+' size:'+item.element.size+' price:'+
        //    item.element.price+' provider:'+item.element.provider);

      }
    },

    payPlan: function (plan) {
    //  if ($.Auth.status() == 'loggedIn') {
    //    if ($.Auth.enabled()) {

          let order = Math.floor((Math.random() * 1000000) + 1);
          let userLogged = $.Auth.info('username');
          let country = $.Auth.info('country');
          plan.iva = (country == 'Colombia' || country == null ? plan.iva : 0);
          Pay.setup.factDescript.text(plan.desc);
          Pay.setup.factSubtotal.text(this._convertString(plan.price).toFixed(2).toLocaleString());
          Pay.setup.factIVA.text(this._convertString(plan.iva).toFixed(2).toLocaleString());
          Pay.setup.factTCO.text(this._convertString(plan.tco).toFixed(2).toLocaleString());
          Pay.setup.factTotal.text((this._convertString(plan.price) +
            this._convertString(plan.iva) +
            this._convertString(plan.tco)).toFixed(2).toLocaleString());

          Pay.setup.$CCForm.trigger('reset');
          Pay.setup.$orderId.val(order);
          Pay.setup.$totalId.val(this._convertString(plan.price));
          Pay.setup.tranType = 'Compra de plan';
          Pay.setup.$orderNumber.val(order);
          Pay.setup.$message.html('');
          Pay.setup.$aviso.addClass('hide');
          Pay.setup.$CCWindow.modal('open');
          Pay.setup.userName.val(userLogged);
          Pay.setup.cartItems.images = [];
          Pay.setup.cartItems.planes = [];
          Pay.setup.cartItems.planes.push({
            'orderId': order,
            'productId': plan.id,
            'price': plan.price,
            'iva': plan.iva,
            'tco': plan.tco,
            'provider': plan.provider,
            'description': plan.desc,
            'tranType': plan.tranType,
            'username': userLogged,
            'idplan': plan.idplan,
            'subscriptionId': ''
          });
    /*    } else {
          self._toast("perfil");
        }

      } else {
        this._toast("sesion");

      }*/
    },

    payTotal: function () {
      if ($.Auth.status() == 'loggedIn') {
        if ($.Auth.enabled()) {

          this.openPayWindow();
          Pay.setup.$CCWindow.modal('open');
  
          console.log(this.$element.find('.download a').length);
          let self = this;
          let order = Math.floor((Math.random() * 1000000) + 1);
          //let userLogged = '';
          //if (typeof Api !== 'undefined')
          //  userLogged = Api.whoIsLogged();
          //else {
          let userLogged = $.Auth.info('username');
          //}
          Pay.setup.factSubtotal.text(this._convertString(this.$subTotal.text()).toFixed(2).toLocaleString());
          Pay.setup.factIVA.text(this.iva.toFixed(2).toLocaleString());
          Pay.setup.factTCO.text(this.tco.toFixed(2).toLocaleString());
          Pay.setup.factTotal.text((this._convertString(this.$subTotal.text()) +
            this.iva +
            this.tco).toFixed(2).toLocaleString());

          Pay.setup.$CCForm.trigger('reset');
          Pay.setup.$orderId.val(order);
          Pay.setup.$totalId.val(this.$subTotal.text());
          Pay.setup.tranType = 'compra_imgs';
          Pay.setup.$orderNumber.val(order);
          Pay.setup.$message.html('');
          Pay.setup.userName.val(userLogged);
          Pay.setup.cartItems.images = [];
          Pay.setup.cartItems.planes = [];
          let itemsCount = 0;
          this.$element.find('.download a').each(function (index) {
            let item = self._found(this);
            let country = $.Auth.info('country');
            itemsCount += 1;
            item.element.iva = (country == 'Colombia' || country == null ? item.element.iva : 0);
            let itemToPush = {
              'orderId': order,
              'productId': item.element.id.toString(),
              'size': item.element.size,
              'license_type': item.element.license,
              'price': item.element.price.toFixed(2).toLocaleString(),
              'iva': item.element.iva.toFixed(2).toLocaleString(),
              'tco': item.element.tco.toFixed(2).toLocaleString(),
              'provider': item.element.provider,
              'thumb': item.element.thumb,
              'type': item.element.type,
              'description': item.element.desc,
              'tranType': item.element.tranType,
              'username': userLogged,
              'idplan': item.element.idplan,
              'subscriptionId': item.subscriptionId
            }
            if (item.element.tranType == 'compra_img') {
              Pay.setup.cartItems.images.push(itemToPush);
            } else {
              Pay.setup.cartItems.planes.push(itemToPush);
            }
          });
          Pay.setup.factDescript.text('Compra de '+itemsCount+ (itemsCount>1?' items':' item'));

          if (Pay.setup.cartItems.images.length + Pay.setup.cartItems.planes.length > 1) {
            Pay.setup.$aviso.removeClass('hide');
          } else {
            Pay.setup.$aviso.addClass('hide');
          }

          Pay.setup.$CCWindow.modal('open');
          //this.openPayWindow();

        } else {
          self._toast("perfil");
        }
      } else {
        this._toast("sesion");

      }

      //console.log(Pay.setup.cartItems.length, Pay.setup.cartItems);
    },

    openLoginWindow: function () {
      let self = this;
      Login.config.loginWindow.modal({
        complete: function () {
          if ($.Auth.status() == 'loggedIn') {
            self._toast('info', 'Sesión iniciada');
          }
        }
      })
    },

    openPayWindow: function () {
      console.log(Pay.setup.factTotal.text().replace(",", "."));
      if (! $('#downloading').hasClass('hide') ) {
        $('#downloading').addClass('hide')
      }
      let self = this;
      Pay.setup.$CCWindow.modal({
        /* ready: function() {
            Pay.setup.tabsPayMethods.tabs();
        }, */
        complete: function () {
          if (Pay.setup.processed) {
            Pay.setup.processed = false;
            if (Pay.setup.cartItems.planes.length > 0) {
              Pay.setup.resCartItems.planes = Pay.setup.cartItems.planes;
              Pay.setup.resCartItems.planes[0].result = 'success';
              console.log(Pay.setup.resCartItems);
            }
            if (Pay.setup.cartItems.images.length > 0) {
              $.download(Pay.setup.cartItems.images, function (res2) {
                Pay.setup.resCartItems.images = res2;
                console.log(Pay.setup.resCartItems);
                //setup.$CCWindow.modal('close');
                let allItems = [];
                allItems.push.apply(allItems, Pay.setup.resCartItems.images);
                allItems.push.apply(allItems, Pay.setup.resCartItems.planes);
                console.log(allItems);
                if (allItems.length > 0) {

                  if (Pay.setup.token != '') {
                    //let cart = self.storage.get(self.cartName);

                    $.each(allItems, function (index, item) {
                      if (item.result == 'success') {
                        let objDelete = '.delete a[data-item="' + item.productId + '"]';
                        $(objDelete).html('')
                          .removeClass('btn red')
                          .parent()
                          .addClass('remove');
                        //let cartItem = self._found(objDelete);
                        //cart.splice(cartItem.index, 1);
                        $('.download a[data-item="' + item.productId + '"]')
                          .html("<i class='small material-icons green-text'>check_circle</i>")
                          .removeClass('btn blue');
                      }
                    });

                    //self.storage.set(self.cartName, cart);
                    self.$emptyCart.hide();
                    self.$checkout.hide();
                    if (Pay.setup.resCartItems.images.length > 0) {
                      $('#downloading').removeClass('hide');
                      Pay.setup.$message.html() == 'Transacción aprobada!... preparando compra';
                      //self.setMessage('Descargado');
                    }
                    //Pay.setup.resCartItems = [];
                  }
                }          
              })
            }
          }
        }
      })
      //Pay.setup.$CCWindow.modal('open');

    },

    emptyCart: function () {
      if (this.storage != undefined)
        this.storage.remove([this.cartName, this.total]);
      this.$tableCartBody.html("");
      this.$tableCartBody.empty();
      this.$subTotal.html('0');
      this.navCartBtn.tooltip();
      this.init();
      //this.createCart();
      //this.displayCart();
      //Pay.setup.init();

    },

    updateCount: function (obj, action) {
      if (action == 'removeAll') {
        obj.$cartCount.text('');
        obj.$subTotal.html('0');
        obj.$cartCount.toggle(false);
        obj.navCartBtn.tooltip();
        obj.sideCartBtn.text('');
        obj.sideCartBtn.attr('data-badge-caption', '');
        obj.sideCartBtn.removeClass('new red');
      } 

    },

    _toast: function (type, info) {
      let text, link;
      let time = 30000;
      let close = '<a class="toast-action" onclick="Materialize.Toast.removeAll()"><i class="material-icons white-text" style="padding-right:10px">clear</i></a>';
      switch (type) {
        case 'info':
          text = info;
          time = 4000;
          break
        case 'empty':
          text = "No tienes elementos en tu lista de compra";
          link = '';
          break;
        case 'perfil':
          text = "Debes completar tu perfil";
          link = '<a href="' + location.origin + '/latincolor/main/user?back=cart' + '" class="btn-flat toast-action" onclick="Materialize.Toast.removeAll()">Ir a tu perfil</a>';
          break;
        case 'sesion':
          text = "Debes iniciar sesión";
          link = '<a href="#sign-in" class="btn-flat toast-action modal-trigger" onclick="Materialize.Toast.removeAll()">Iniciar Sesión</a>';
          break;
      }
      let toast = $('<span>' + text + '</span>')
        .add($(link))
        .add($(close));

      Materialize.toast(toast, time);

    },

    _found: function (objSelected) {
      var cart = this.storage.get(this.cartName);
      let cartLength = cart.length;
      for (let i = 0; i < cartLength; i++) {
        let item = cart[i];
        let selected = $(objSelected).data('item');
        if (item.id == selected) {
          return {
            index: i,
            element: cart[i]
          };
        }
      }
      return null;
    },

    _price: function (valor) {
      let local = $.Auth.info('country') == 'Colombia' || $.Auth.info('country') == null ? 'es-CO' : 'en-US';
      return valor.toLocaleString(local, {
        maximumFractionDigits: 2
      })
    },

    _convertString: function (numStr) {
      var num;
      if (typeof numStr == 'number') {
        num = numStr;
      } else if (/^\d{1,3}(,\d{3})*(\.\d{1,2})?$/.test(numStr)) {
        numStr = numStr.replace(/,/g, "");
        num = parseFloat(numStr);
      } else if (/^\d{1,3}(\.\d{3})*(,\d{1,2})?$/.test(numStr)) {
        numStr = numStr.replace(/\./g, "");
        numStr = numStr.replace(",", ".");
        num = parseFloat(numStr);
      } else if (/^\d+$/.test(numStr)) {
        num = parseInt(numStr, 10);
      } else {
        num = Number(numStr);
      }

      if (!isNaN(num)) {
        return num;
      } else {
        console.warn(numStr + " cannot be converted into a number");
        return false;
      }
    }

  };

}(jQuery));