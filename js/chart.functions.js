(function ( $ ){

    $.Chart = function( element ) {
      let ctx = document.getElementById(element).getContext("2d");

      let gradient = ctx.createLinearGradient(0, 0, 200, 0);
      gradient.addColorStop(0, '#2962FF');
      gradient.addColorStop(1, '#3949ab');

      //this.$element = $( element ); // top-level element
      this.init(ctx, gradient);
    };

    $.Chart.prototype = {
      init: function(ctx, gradient) {
        this.chart = new Chart(ctx, {
            type: 'line',
            data:{
              labels: [],
              datasets: [{
                  backgroundColor: gradient,
                  strokeColor: "rgba(255,82,82,0.1)",
                  pointColor: "#fff",
                  pointStrokeColor: "#0288d1",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "#0288d1",
                  data: []
                }]
            },
            options: {
                scales: {
                  xAxes: [{
                    display: false
                  }],
                  yAxes: [{
                    display: false,
                  }]
                },
                legend: {
                  display: false,
                },
                tooltips:{
                  intersect: false,
                  mode: 'nearest',
                  position: 'nearest',
                  caretPadding: 2
                }
            }
          })
        },

        updateData: function(data) {
          this.chart.data.labels = data.names;
          this.chart.data.datasets[0].data = data.count;
          this.chart.update();
        }

      }
}(jQuery));
