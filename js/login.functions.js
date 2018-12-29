var Login = {

  init: function (settings) {
      Login.config = {
        loginWindow: $('#sign-in'),
        linkCreateAccount: $('.create-account'),
        linkForgotPassword: $('.forgot-password'),
        registerWindow: $('#signup-page'),
        forgotWindow: $('#forgot-page'),
        linkLogin: $('.go-login'),
        signUpForm: $('#signup-form'),
        forgotForm: $('#forgot-form'),
        //loginForm: $('#sign-in'),
        optionsAuth: {
          form: $('#login-form'),
          logout: $('#sign-out'),
        },

        signin: $(".sign-in"),
        signUser: $("#signuser"),
        welcomeUser: $('#welcome-user'),
        welcomeBtn: $('.welcome-agree'),
        userProfile: $(".user-profile"),
        loginWindow: $('#sign-in'),
        loginMenu: $("#login-menu"),
        logUser: $("#loguser"),
        message: $('#message'),
        auth: true,

      };
      $.extend(true, Login.config, settings);

      if (Login.config.auth)
        Login.auth = new $.Auth( Login.config.optionsAuth );

      Login.setup();
  },

  setup: function () {
      Login.onCreateAccount();
      Login.onLogin();
      Login.onForgotPassword();
      Login.setMaterial();

      $.setValidador( Login.newAccount );
      Login.config.signUpForm.validar();
      Login.config.forgotForm.validar();

      if (Login.config.auth)
        Login.auth.setDefaults({
            onLogin: function (res) {
                if(res.status == 404) {
                  //alert(res.responseJSON.response);
                  $('#message').show();
                } else {
                  //setup.auth.init();
                  Login.loginFields(res);
                }
            },
            onLogout: function (res) {
              Login.logout(res);
            }
        })
  },

  // welcomeAgree: function(event) {
  //   event.preventDefault();
  //   $('#login-username').val( $('#username').val() );
  //   $('#login-password').val( $('#password').val() );
  //   Login.config.optionsAuth.form.submit();
  //
  // },

  newAccount: function(status, res, formId) {
    if (formId == 'signup-form') {
        if (status == 'success') {
          Login.config.welcomeUser.find('h4').text('Bienvenido '+$('#first_name').val()+'!');
          Login.config.registerWindow.modal('close');
          Login.config.welcomeUser.modal('open');
          $('.response').css('color','green').text(res);
          $('#login-username').val( $('#username').val() );
          $('#login-password').val( $('#password').val() );
          Login.config.optionsAuth.form.submit();
        } else {
          $('.response').css('color','red').text(res.responseText);
        }

    } else {
        console.log(res);
        $('.response').css({'color':'green','background-color':'yellow'}).text(res);

    }
  },

  loginFields: function(data) {
              Login.config.signin.text('Mi Cuenta');
              Login.config.signin.attr('href', location.origin+'/latincolor/main/user');
              Login.config.signUser.find('a#sign-out')
                                   .attr('href',location.origin+'/latincolor/login/logout/noindex')
                                   .attr('class','')
                                   .text('Cerrar sesión');
              Login.config.userProfile.find('b').text(data.first_name+' '+data.last_name);
              Login.config.userProfile.find('.email').text(data.email_address);
              Login.config.loginWindow.modal('close');

              Login.config.loginMenu.attr({
                'href': '#sign-out',
                'class': 'grey-text text-darken-3 dropdown-button',
                'data-activates': 'user-dropdown'
              });
              Login.config.loginMenu.html(Templates.iconLoginDD(data.first_name));
              Login.setMaterial();

              $('body').data(data).trigger('login');
  },

  logout: function(data){
              $("body").click();
              //Storages.localStorage.set('status','');
              Login.config.loginMenu.remove();
              Login.config.logUser.append(Templates.menuSignIn);
              Login.config.loginMenu = $('#login-menu');
              Login.config.loginMenu.html(Templates.iconLogin+'Iniciar sesión');
              Login.config.signin.attr('href','#sign-in')
                                 .text("Iniciar sesión");
              Login.config.signUser.find('a#sign-out')
                                   .attr('href','#signup-page')
                                   .attr('class','modal-trigger')
                                   .text('Registrarse');
              if ($.currentPage('user'))
                location.href = location.origin+'/latincolor';
              Login.setMaterial();

  },


  onCreateAccount: function () {
      Login.config.linkCreateAccount.on('click', function(){
        Login.config.loginWindow.modal('close');
        Login.config.registerWindow.modal('open');
      });

      //Login.config.welcomeBtn.on('click', Login.welcomeAgree)
  },

  onLogin: function () {
      Login.config.linkLogin.on('click', function(){
        Login.config.registerWindow.modal('close');
        Login.config.loginWindow.modal('open');
      })
  },

  onForgotPassword: function () {
      Login.config.linkForgotPassword.on('click', function(){
        Login.config.forgotForm[0].reset();
        $('.response').css('color','black').text('Tu nueva clave será enviada a email que ingresas');
        Login.config.loginWindow.modal('close');
        Login.config.forgotWindow.modal('open');
      })
  },

  message: function(msg) {
      Login.config.message.show().children('p').text(msg);
  },

  close: function() {
    Login.config.loginWindow.modal('close');
  },

  setMaterial: function() {
    $('.dropdown-button').dropdown({
        belowOrigin: true,
    });
  },

}
