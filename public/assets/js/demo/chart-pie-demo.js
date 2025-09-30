// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily =
    'Nunito, -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["Present", "Late"], // Label tetap Present dan Late
        datasets: [{
            data: typeof attendancePieData !== 'undefined' ? attendancePieData : [50, 50],

            // Warna: Present = Biru, Late = Abu-abu
            backgroundColor: ['#4e73df', '#858796'],
            hoverBackgroundColor: ['#2e59d9', '#6e707e'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            callbacks: {
                label: function (tooltipItem, chart) {
                    var dataset = chart.datasets[tooltipItem.datasetIndex];
                    var total = dataset.data.reduce(function (prev, current) {
                        return prev + current;
                    });
                    var currentValue = dataset.data[tooltipItem.index];
                    var percentage = parseFloat((currentValue / total * 100).toFixed(2));
                    return chart.labels[tooltipItem.index] + ': ' + percentage + '%';
                }
            }
        },
        legend: {
            display: false
        },
        cutoutPercentage: 80,
    },
});
