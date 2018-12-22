var MiConsultor = {

  init: function(){
    MiConsultor.config = {
      preConsulta: []
    }
    if ($('#cons').val()) {
      MiConsultor.config.preConsulta = [{ tag: $('#cons').val()}]
    }
    MiConsultor.onConsultorSend();
    MiConsultor.setMaterial();
  },

  onConsultorSend: function () {
    $('#form-consultor').on('submit', function (event) {
      event.preventDefault();
      let form = $(this).prepareData();
      form.inputs.consulta = JSON.stringify($('.chips-autocomplete').material_chip('data'));
      $.submitForm(form,function(res){
        console.log(res);
      })
    })
  },

  setMaterial: function(){
    $('.chips-autocomplete').material_chip({
      data: MiConsultor.config.preConsulta,
      placeholder: 'Escribe tu consulta',
      secondaryPlaceholder: '+ otra consulta?',
      autocompleteOptions: {
        data: {
          'Necesito una foto específica': null,
          'Quiero otra opción de plan': null,
          'Consultoría en banco de imágenes': null,
          'Consultoría en derechos de autor': null,
          'Necesito un servicio diferente': null,
          'Tengo una sugerencia': null,
          'Descarga incompleta': null,
        },
        limit: Infinity,
        minLength: 1,
      }
    });
  }
}

var env = new $.Common();

$(document).ready(MiConsultor.init);
