require.config({
  baseUrl: '../',
  paths: {
    jquery: 'js/jquery-3.3.1.min',
    auth: 'auth.functions'
  }
});
  (function($){
    $.fn.hello = function() {
      return $(this).text("Hello");
    }

  }(jQuery))
