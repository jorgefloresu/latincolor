var User = {
  init: function () {
    User.config = {
      cart: $("#cart"),
      saveToCart: $("#savetocart"),
      menuCart: $("#menuCart"),
      userPref: $('.set-userpref'),

      firstName: $('#first_name'),
      lastName: $('#last_name'),
      passwd: $('#password'),
      country: $('#country'),
      region: $('#region'),
      city: $('#city'),
      address: $('#address'),
      zip: $('#zip'),
      phone: $('#phone'),
      empresa: $('#empresa'),
      nit: $('#nit'),

      reDownload: $('.re-download'),
      planesView: $('#planes-view'),
      planesCollection: $('.planes-collection'),
    }

    User.auth = new $.Auth({
      logout: 'auto',
    });

    User.shop = new $.Shop(User.config.cart);

    User.setup()
  },

  setup: function () {
    User.setMaterial();
    User.setUserData();
    User.setRedownloads();
    User.savePrefs();
    User.setPlanesView();
  },

  setPlanesView: function () {
    User.config.planesCollection.on('click', 'a.plan-view', function (event) {
      event.preventDefault();
      if ($('.plan-ini').text() == 'Espere...') {
        let form = {
          url: location.origin + '/latincolor/main/get_plan_info',
          inputs: {
            subaccountId: $(this).data('subaccountid'),
            planId: $(this).data('plan')
          }
        }
        $.submitForm(form, function (res) {
          if (res.result == 'OK') {
            $('.plan-estado').text(res.estado).css({
              'color': function(){
                return res.estado == 'active' ? 'green' : 'red';
              },
              'font-weight': 'bold'
            });
            $('.plan-ini').text(res.fecha_ini);
            $('.plan-fin').text(res.fecha_fin);
            $('.plan-cantidad').text(res.cantidad);
            $('.plan-periodo').text(res.periodo+(res.periodo>1?' dias':' dia'));
            $('.plan-duracion').text(res.buyPeriod+' dias');
          }
        });
      }
      User.config.planesView.modal('open');
    })
  },

  setRedownloads: function () {
    let provider, imageId;
    User.config.reDownload.on('click', function (event) {
      event.preventDefault();
      provider = $(this).data('provider');
      imageId = $(this).data('id');
      let form = {
        url: $(this).data('url'),
        inputs: {
          lid: $(this).data('lid'),
          provider: provider,
        }
      }
      $('#download-progress').modal('open');
      $.submitForm(form, getFile);
    });

    function getFile(data) {
      console.log(data);
      if (data.error) {
        $('.msg-redownload').show();
      } else {
          $('.cnt').text('1 de 1');
          $('#download-progress p').text(' Esperando a recibir el archivo...');
          $.loadFile(data.downloadLink, function (file) {
            $.saveFile(file, 'image/jpeg', 'LCI-'+provider.substring(0,2)+'-'+imageId+'-rdw');
          });
      }
      //window.location = data.downloadLink;
    }

  },

  savePrefs: function () {
    User.config.userPref.on('submit', function (event) {
      User.config.passwd.attr('disabled', function (i, oldAttr) {
        return ($(this).val() == '')
      });

      event.preventDefault();

      if (User.config.country.val() != userData.country) {
        //User.shop.emptyCart();
      }

      User.config.country.val(function (index, value) {
        return value == '-Selecciona pais-' ? '' : value;
      });
      User.config.region.val(function (index, value) {
        return value == '-Selecciona estado o departamento-' ? '' : value;
      });
      User.config.city.val(function (index, value) {
        return value == '-Selecciona ciudad-' ? '' : value;
      });

      var formData = $(this).prepareData();
      console.log(formData);
      $(formData).getDataProvider()
        .then(function (response) {
          userData = response.userData;
          User.auth.init();
          User.shop.displayCart();
          $('#message').show().delay(1600).fadeOut('slow', 'linear');
          if ($.urlParam(location.href, 'back') == 'cart') {
            User.config.cart.modal('open');
          }
        })
    });
  },

  setUserData: function () {
    User.config.firstName.val(userData.first_name);
    User.config.lastName.val(userData.last_name);
    //User.config.country.val('Colombia');
    //User.config.region.val(userData.state);
    //User.config.city.val(userData.city);
    User.config.address.val(userData.address);
    User.config.zip.val(userData.zip);
    User.config.phone.val(userData.phone);
    User.config.empresa.val(userData.empresa);
    User.config.nit.val(userData.nit);

    var currentCountries = [],
      currentStates = [];
    var objCountry, countryCode,
      objState, stateCode;
    //let BATTUTA_KEY="2f77e7bc7a68481e29863fc57b0f4e0c";
    // Populate country select box from battuta API
    //url="https://battuta.medunes.net/api/country/all/?key="+BATTUTA_KEY+"&callback=?";
    $.getGeo('list=countries', User.setCountries)
      .then(function (res) {
        //currentCountries = [{"id":"0","code":"00","name":"-Seleccione país-"}];
        //currentCountries.push.apply(currentCountries, res);
        currentCountries = res;
        User.config.country.trigger("change");
      });

    User.config.country.on("change", function () {
      objCountry = currentCountries.find(country => country.name === $(this).val());
      countryCode = objCountry.id;
      $.getGeo('list=states&countryID=' + countryCode, User.setStates)
        .then(function (res) {
          //currentStates = [{"id":"0","name":"-Seleccione estado o departamento-"}];
          //currentStates.push.apply(currentStates, res);
          currentStates = res;
          User.config.region.trigger("change");
        });
    });

    User.config.region.on("change", function () {
      objState = currentStates.find(state => state.name === $(this).val());
      //console.log($(this).val(), objState);
      stateCode = objState.id;
      $.getGeo('list=cities&stateID=' + stateCode, User.setCities);
    });
  },

  setCountries: function (countries, currentCountries) {
    currentCountries = countries;
    User.config.country.material_select();
    $.each(countries, function (key, country) {
      $("<option></option>")
        .attr({
          value: country.name,
          selected: (country.name == userData.country ? true : false),
        })
        .append(country.name)
        .appendTo(User.config.country);
    });
    User.config.country.material_select('update');

  },

  setStates: function (states, currentStates) {
    currentStates = states;
    //console.log(userData.state, states);
    User.config.region.find("option").remove();
    $.each(states, function (key, state) {
      let selected = (state.name == userData.state ? true : false);
      $("<option></option>")
        .attr({
          value: state.name,
          selected: selected,
        })
        .append(state.name)
        .appendTo(User.config.region);
    });
    User.config.region.material_select('update');

  },

  setCities: function (cities) {
    User.config.city.find("option").remove();
    $.each(cities, function (key, city) {
      $("<option></option>")
        .attr({
          value: city.name,
          selected: (city.name == userData.city ? true : false),
        })
        .append(city.name)
        .appendTo(User.config.city);
    });
    User.config.city.material_select('update');
    //$("#city").trigger("change");

  },

  setMaterial: function () {
    $(".modal").modal();
    $('.dropdown-button').dropdown({
      belowOrigin: true,
    });
    $('.list-scroll').perfectScrollbar({
      suppressScrollX: !0
    });
    $('select').material_select();
    $(window).resize(function () {
      $("#list-scroll").height($(this).height() - 490);
    })

  },

}

$(document).ready(User.init);