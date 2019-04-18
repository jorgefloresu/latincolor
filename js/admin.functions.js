var Admin = {

    init: function () {
          Admin.config = {
            configForm: $('#set-config'),
            fullTable: $('#data-table-simple'),
            table: $('#data-table-row-grouping'),
            tableBody: $('#data-table-row-grouping tbody'),
            dataVentas: $('#data-ventas tbody'),
            aplicar: $('.data-aplicar'),
            topbar: $(".page-topbar"),
            counters: {},
            initCount: {},
            countOnce: true,
            groupColumn: 2,
          }
          $.fn.dataTable.moment('DD/MM/YYYY HH:mm');
          Admin.dataTable = $('#data-ventas').DataTable({
                  "dom": 'lfrtip<"toolbar">',
                  "language": {
                    "lengthMenu": "Display records _MENU_"
                  },
                  "autoWidth": false,
                  "columns": Admin.addColumnas(),
                  "order": [ [ $('.header').text()=='Consultas'?1:2, 'desc'] ],
                });
          //Admin.dataTable.columns.adjust().draw();
          Admin.auth = new $.Auth({ logout: 'auto' });
          Admin.setup();
    },

    setup: function () {
      Admin.setConfigData();
      Admin.setDataTables();
      Admin.setDataVentas();
      Admin.onDepositphoto();
      Admin.onDeleteSubaccount();
      Admin.setMaterial();
      Admin.getCounters();
      //setInterval(Admin.notify, 10000);

    },

    getCounters: function () {
      $.getJSON(location.origin+'/latincolor/admin/check_new')
              .done(function(res){
                Admin.config.counters = res;
                if (Admin.config.countOnce) {
                  Admin.config.initCount = res;
                  Admin.config.countOnce = false;
                }
              })
    },

    notify: function () {
      Admin.getCounters();
      console.log(Admin.config.initCount, Admin.config.counters);
      if (Admin.config.counters.new_ventas > Admin.config.initCount.new_ventas) {
        Materialize.toast('Aviso',4000);
        Admin.updateStatus('new','add');
      }
      if (Admin.config.counters.new_ordenes > Admin.config.initCount.new_ordenes) {
        Materialize.toast('Aviso',4000);
        Admin.updateStatus('ord','add');
      }
    },

    addColumnas: function() {

      let initialCols = [];

      if ( ! $('#data-ventas').hasClass('responsive') ) {
        initialCols = [{
          "class":          "details-control",
          "orderable":      false,
          "data":           null,
          "defaultContent": "",
        }]
      }

      initialCols.push.apply(initialCols, [{
                "class":   "",
                "data":    "new",
                "render":  function(data,type,row,meta){
                           return data == 'new' || data == 'ord' ?
                                  '<span class="status-badge new badge red"></span>' :
                                  data == 'pro' || data == 'g2p' ?
                                  '<span class="status-badge-pro new badge orange"></span>' : data
                           }
      }]);

      let columnas;
      switch ($('.header').text()) {
        case 'Consultas':
          columnas = [
            {"data": "fecha"},
            {"data": "nombre"},
            {"data": "email"},
            {"data": "phone"},
            {"data": "consulta"},
            {"data": "detalle"},
          ];
          break;
        default:
          columnas = [
            {"data": "fecha"},
            {"data": "orderId"},
            {"data": "activity"},
            {"data": "username"},
            {"data": "monto"},
          ];
      }
      initialCols.push.apply(initialCols, columnas);
      return initialCols;
    },

    setConfigData: function () {
      Admin.config.configForm.on('submit', function(event){
        event.preventDefault();
        let formData = $(this).prepareData();
        $(formData).getDataProvider()
                   .then(function(response){
                     $('#message').show().delay(1600).fadeOut('slow','linear');
                   })
      });

    },

    buyerData: function (tr) {
      let row = Admin.dataTable.row( tr ).data();
      $.getJSON(location.origin+'/latincolor/admin/get_buyer/'+row.username)
        .done(function(res){
          $('.buyer-user').text(res.username);
          $('.buyer-name').text(res.fname);
          $('.buyer-email').text(res.email_address);
          $('.buyer-direccion').text(res.address);
          $('.buyer-telefono').text(res.phone);
          $('.buyer-empresa').text(res.empresa);
          $('.buyer-nit').text(res.nit);
        })
        .fail(function(res) {
          console.log(res);
        });

    },

    format: function(row) {
      let message = row.new == 'g2p' ? 'Espere... Enviando notificacion a '+row.username : 'Cargando...';
      let div = $('<div/>').text(message);
      console.log(row);
      $.getJSON(location.origin+'/latincolor/admin/ventas_detalle/'+
                row.orderId+'/'+row.new+'/'+row.username+'/'+row.activity)
        .done(function(res){
          div.html(res.html_table);
        })
        .fail(function(res) {
          console.log(res);
        });
      return div;
    },

    updateStatus: function(labelNew, oper) {
      let badge = (labelNew == 'new' ? '.badge-ventas span' :
                   labelNew == 'ord' ? '.badge-ordenes span' : '');

      if (badge) {
        let numVentas = Number($(badge).text());
        if (oper == 'add') {
          let badgeText = (labelNew == 'new' ? Admin.config.counters.new_ventas :
                       labelNew == 'ord' ? Admin.config.counters.new_ordenes : '');
          $(badge).addClass('new badge red').text(badgeText);
          Admin.config.initCount = Admin.config.counters;
        }
        else {
          numVentas--;
          console.log(numVentas);
          if( numVentas == 0 ) {
            $(badge).removeClass('new badge red').text('');
            Admin.config.initCount = Admin.config.counters;
          } else {
            $(badge).text(numVentas);
          }
        }
      }

    },

    updateRowStatus: function(cell, status) {
      //let cellStatus = $(this).find('.badge').parent();
      switch (status) {
        case 'new':
          Admin.dataTable.cell(cell).data('').draw();
          break;
        case 'ord':
          Admin.dataTable.cell(cell).data('g2p').draw();
          break;
        case 'g2p':
          Admin.dataTable.cell(cell).data('pro').draw();
          break;
      }

    },

    updateRowSelected: function(tr) {
      //console.log(tr[0].cells[1]);
      if ( tr.hasClass('selected') ) {
           tr.removeClass('selected');
           $('div.toolbar .btn').addClass('disabled');
      }
      else {
          if ( ! $('#data-ventas').hasClass('responsive') ) {
            Admin.buyerData(tr);
          }
          Admin.dataTable.$('tr.selected').removeClass('selected');
          tr.addClass('selected');
          if (tr.find('span.status-badge').length > 0) {
            $('div.toolbar .btn').addClass('disabled');
          } else {
            $('div.toolbar .btn').removeClass('disabled');
          }
      }

    },

    onConfirmar: function () {
        $('button#ingresar').on('click', function(){
          Admin.sendUserPlan()
        })
    },

    sendUserPlan: function () {
        $('div.toolbar .btn').text('Enviando, espere...');
        let cellStatus = Admin.config.dataVentas.find('.selected .badge').parent();
        if ( ! $('div.toolbar .btn').hasClass('disabled') && cellStatus.length > 0) {
          let row = Admin.dataTable.row('.selected').data();
          // row.orderId+'/'+row.username+'/'+row.monto
          $.getJSON(location.origin+'/latincolor/admin/ventas_detalle/'+
                row.orderId+'/'+row.new+'/'+row.username+'/'+row.activity)
              .then(function (venta) {
                let orderData = {
                    orderId: row.orderId,
                    tranType: row.activity,
                    status: 'pro',
                    username: row.username,
                    description: venta.result[0].description,
                    items: JSON.stringify({
                      images: [],
                      planes: [{
                        orderId: row.orderId,
                        provider: venta.result[0].provider,
                        productId: venta.result[0].productId,
                        tranType: row.activity,
                        username: $('#plan-username').val(),
                        password: $('#plan-password').val()
                      }]
                    })
                };
                console.log(orderData);
                $.getJSON(location.origin+'/latincolor/order/confirmar_orden', orderData)
                  .done(function(res) {
                        if (res.process.planes.result == 'ok') {
                          Admin.dataTable.cell(cellStatus).data('').draw();
                        }
                        location.reload();
                  })
                  .fail(function(res) {
                    console.log(res)
                  });
              })  
          
        }
    },

    addOrderButton: function() {
      if ($('#data-ventas').hasClass('ordenes')) {
        $("div.toolbar").html('<a href="#plan-modal" class="btn modal-trigger waves-effect waves-light disabled">Confirmar</a>');
      }

    },

    setDataVentas: function() {
      // Array to track the ids of the details displayed rows
      //var detailRows = [];
      Admin.addOrderButton();
      Admin.onConfirmar();
      Admin.config.dataVentas.on( 'click', 'tr', function (event) {
          //let tr = $(this).closest('tr');
          let tr = $(this);
          Admin.updateRowSelected(tr);
          let row = Admin.dataTable.row( tr );
          let isMoreDetails = $(event.target).hasClass('details-control');
          //var idx = $.inArray( tr.attr('id'), detailRows );
          //console.log('more:'+$(event.target).hasClass('details-control'));

          if ( isMoreDetails ) {
            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();

                // Remove from the 'open' array
                //detailRows.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                Admin.updateStatus( row.data().new );
                Admin.updateRowStatus( $(this).find('.badge').parent(), row.data().new );
                row.child( Admin.format( row.data() )).show();

                // Add to the 'open' array
                //if ( idx === -1 ) {
                //    detailRows.push( tr.attr('id') );
                //}
            }
          }
          // else {
          // }
       });

       // On each draw, loop over the `detailRows` array and show any child rows
      // dt.on( 'draw', function () {
      //     $.each( detailRows, function ( i, id ) {
      //         $('#'+id+' td.details-control').trigger( 'click' );
      //     } );
      // });

    },

    setDataTables: function () {
      $.fn.dataTable.moment('DD/MM/YYYY');

      let table = Admin.config.fullTable.DataTable({
        "language": {
          "lengthMenu": "Display records _MENU_"
        },
        // "initComplete": function(settings, json) {
        //   var $table = $(this);

          // $table.closest('.dataTables_wrapper')
          //       .find('select')
          //       .addClass('browser-default');

        //   $table.closest('.dataTables_wrapper')
        //         .find('.dataTables_length label')
        //         .contents()
        //         .filter(function() {
        //           return ($(this).prop('tagName') !== 'SELECT');
        //   }).wrap('<span style="display:inline"></span>');
        //
      });

      // let table = Admin.config.table.DataTable({
      //     "columnDefs": [
      //         { "visible": false, "targets": 2 }
      //     ],
      //     "order": [[ 2, 'asc' ]],
      //     "displayLength": 25,
      //     "drawCallback": Admin.fillColumn,
      // });
      //
      // Admin.onTableBody(table);
      // Admin.onAplicar(Admin.config.fullTable.DataTable());
    },

    fillColumn: function ( settings ) {
        let api = this.api();
        let rows = api.rows( {page:'current'} ).nodes();
        let last=null;

        api.column(Admin.config.groupColumn, {page:'current'} ).data().each( function ( group, i ) {
            if ( last !== group ) {
                $(rows).eq( i ).before(
                    '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                );
                last = group;
            }
        });
    },

    /* onTableBody: function() {
      // Order by the grouping
      Admin.config.tableBody.on( 'click', 'tr.group', function () {
          let currentOrder = table.order()[0];
          if ( currentOrder[0] === Admin.config.groupColumn && currentOrder[1] === 'asc' ) {
              table.order( [ Admin.config.groupColumn, 'desc' ] ).draw();
          }
          else {
              table.order( [ Admin.config.groupColumn, 'asc' ] ).draw();
          }
      });
    }, */

    onAplicar: function(fullTable) {
      Admin.config.aplicar.click( function() {
          let data = fullTable.$('input[value=1]').serialize();
          console.log(fullTable.$('input[value=1]'));
          alert(
              "The following data would have been submitted to the server: \n\n"+
              data.substr( 0, 120 )+'...'
          );
          return false;
      } );

    },

    showSubaccount: function(el) {
      let url = $(el).data('url');
      $('li.active').find('.load').show();
      $.getJSONfrom(url).then(function(res){
        console.log(res);
        $(el).find('.chip img').attr('src', res.avatar)
                               .get(0).nextSibling.remove();
        $(el).find('.chip img').after(res.firstName+' '+res.lastName);
        $(el).find('.email').text(res.email);
        $(el).find('.since').text(res.createdAt);
        $(el).find('.load').hide();
      });
    },

    onDepositphoto: function() {
      $('.depositphoto').on('click', function(){
        let preload = "<i class='mdi-editor-insert-invitation'></i>Depositphotos "+
                  "<img src='"+location.origin+
                  "/latincolor/img/ajax-li.gif' class='right' style='margin-top:14px'/>";
        $(this).find('a').html(preload);
      })
    },

    onDeleteSubaccount: function() {
      $('.delete-user').on('click', function() {
        $(this).parent().parent().find('span').text('Eliminando...');
      })
    },

    setMaterial: function () {
      $('.modal').modal({
        dismissible: true
      });
      $('.dropdown-button').dropdown({
        inDuration: 300,
        outDuration: 125,
        constrain_width: true, // Does not change width of dropdown to that of the activator
        hover: false, // Activate on click
        alignment: 'left', // Aligns dropdown to left or right edge (works with constrain_width)
        gutter: 0, // Spacing from edge
        belowOrigin: true // Displays dropdown below the button
      });

      $('select').not('.disabled').material_select();
      //Main Left Sidebar Menu
      $('.sidebar-collapse').sideNav({
        edge: 'left', // Choose the horizontal origin
      });

      $('.subaccounts').collapsible({
        onOpen: Admin.showSubaccount
      });

      // let leftnav = Admin.config.topbar.height();
      // let leftnavHeight = window.innerHeight - leftnav;
      //
      // $('.leftside-navigation').height(leftnavHeight).perfectScrollbar({
      //   suppressScrollX: true
      // });


    },
}

$(document).ready(Admin.init);
