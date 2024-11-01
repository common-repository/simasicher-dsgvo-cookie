jQuery(document).ready(function($){
    //var monthNames =  ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
    //var currDate   = new Date();
    //var n = d.getMonth();
    if($('#myChart').length > 0) {
        var visitorsWithC    = visitorData.visitorsWith;
        var visitorsWithoutC = visitorData.visitorsWithout;
        var ctx = document.getElementById("myChart");
        ctx.height= 100;
        var myChart = new Chart(ctx, {
        type: 'bar',
        "data": {
            "labels": ["Seitenaufrufe insgesamt"],
            "datasets": [{
                "backgroundColor": ["rgba(75, 192, 192, 0.2)"],
                "borderColor": "rgba(75, 192, 192, 1)",
                "borderWidth": 1,
                "pointRadius": 3,
                "pointBackgroundColor": "rgba(220,220,220,1)",
                "pointBorderColor": "#fff",
                "pointBorderWidth": 1,
                "pointHoverBackgroundColor": "#fff",
                "pointHoverBorderColor": "rgba(220,220,220,1)",
                "pointHoverBorderWidth": 1,
                "data": [visitorsWithC],
                "label": "Cookies akzeptiert"
            }, {
                "backgroundColor": ["rgba(255, 99, 132, 0.2)"],
                "borderColor": "rgba(255, 99, 132, 1)",
                "borderWidth": 1,
                "pointRadius": 3,
                "pointBackgroundColor": "rgba(151,187,220,1)",
                "pointBorderColor": "#fff",
                "pointBorderWidth": 1,
                "pointHoverBackgroundColor": "#fff",
                "pointHoverBorderColor": "rgba(151,187,220,1)",
                "pointHoverBorderWidth": 1,
                "data": [visitorsWithoutC],
                "label": "Cookies nicht akzeptiert",
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    }
    

});
