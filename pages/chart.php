<html>
<head>
        <script type="text/javascript">
            window.onload = function () {
                document.getElementById("demo").innerHTML = 5 + 6;
                var dataLength = 0;
                var data = [];
                var updateInterval = 500;
                updateChart();
                function updateChart() {
                    $.getJSON("jsonchar.php", function (result) {
                        if (dataLength !== result.length) {
                            for (var i = dataLength; i < result.length; i++) {
                                data.push({
                                    label: (result[i].valorx),
                                    y: parseInt(result[i].valory)
                                });
                            }
                            dataLength = result.length;
                            chart.render();
                        }
                    });
                }
                var chart = new CanvasJS.Chart("chart", {
                    zoomEnabled: true,
                    title: {
                        text: "DAU Temperature"
                    },
                    axisX: {
                        title: "chart updates every " + updateInterval / 1000 + " secs"
                    },
                    axisY: {
                        title: "Temperature",
                        suffix: " °C",

                    },
                    data: [{type: "spline",
                            toolTipContent: "{label} : {y} °C",
                             lineColor: "red", 
                            dataPoints: data}],
                });
                setInterval(function () {
                    updateChart()
                }, updateInterval);
            }
        </script>
        <script src="../backend/js/jquery.min.js"></script>
        <script src="../backend/js/canvasjs.min.js"></script>
    </head>
    <body>
        <div id="chart">
        </div>
    </body>
</html>