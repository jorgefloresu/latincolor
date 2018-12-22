(function( $ ) {

    $.Auth = function( options ) {
      this.settings = {
        form: $('#login-form'),
        logout: $('#sign-out'),
      }
      this.settings = (options!==undefined ? options : this.settings);
      this.settings.info = {};
      this.settings.formId = '';
      this.settings.userInfoUrl = location.origin+'/latincolor/login/user_info';
      this.init();
    }

    $.extend($.Auth, {

      defaults:{},

      prototype: {

        init: function(){

              let self = this;

              //$.getJSON(this.settings.userInfoUrl)
              //    .then(function(response){
                  self.settings.info = userData; //response;
                  self.settings.status = (self.settings.info.username !== undefined ? "loggedIn" : "");
                  Storages.sessionStorage.set('status',self.settings.status);

                  if ('form' in self.settings) {
                    self.settings.form.on('submit', logMethod);
                    self.settings.formId = self.settings.form.attr('id');
                  }

                  if (self.settings.logout != 'auto') {
                    self.settings.logout.on('click', logMethod);
                  }
                  console.log(self.settings);
                  $.extend(true, $.Auth.defaults, self.settings);
              //  });


              function logMethod(event) {

                 if ($.Auth.defaults.logout != 'auto') {
                  event.preventDefault();

                  if ($(this).attr('id') == $.Auth.defaults.formId ||
                      $(this).attr('id') == $.Auth.defaults.logout.attr('id')) {

                        $.Auth.defaults.info = {};
                        let formData = $(this).prepareData();

                        if ($(this).attr('id') == $.Auth.defaults.logout.attr('id')) {
                          $.Auth.defaults.status = '';
                          $.submitForm(formData, $.Auth.defaults.onLogout);

                        } else {
                          $.Auth.defaults.status = 'loggedIn';
                          $.submitForm(formData, function(res){
                            $.extend(true, $.Auth.defaults.info, res);
                            $.Auth.defaults.onLogin(res);
                          })
                        }
                  }
                } else {
                  $.Auth.defaults.status = '';
                }
                Storages.sessionStorage.set('status', $.Auth.defaults.status);
              }
        },

        setDefaults: function(options) {
          $.extend($.Auth.defaults, options)
        },

      },

      status: function() {
        return Storages.sessionStorage.get('status');
      },

      enabled: function() {
        let info = $.Auth.defaults.info;
        console.log(info);
        return (info.address!=='' && info.zip!=='' && info.phone!=='' && info.country!=='');
      },

      info: function(prop) {
        if ($.Auth.defaults.info === undefined || $.isEmptyObject($.Auth.defaults.info))
          return null
        else
          return $.Auth.defaults.info[prop];
      },

    });

}(jQuery));
