$( document ).ready(function() {
    
    // CounterUp Plugin
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    /*var flot2 = function () {

        // We use an inline data source in the example, usually data would
        // be fetched from a server

        var data = [],
            totalPoints = 100;

        function getRandomData() {

            if (data.length > 0)
                data = data.slice(1);

            // Do a random walk

            while (data.length < totalPoints) {

                var prev = data.length > 0 ? data[data.length - 1] : 50,
                    y = prev + Math.random() * 10 - 5;

                if (y < 0) {
                    y = 0;
                } else if (y > 100) {
                    y = 100;
                }

                data.push(y);
            }

            // Zip the generated y values with the x values

            var res = [];
            for (var i = 0; i < data.length; ++i) {
                res.push([i, data[i]])
            }

            return res;
        }

        var plot2 = $.plot("#flotchart2", [ getRandomData() ], {
            series: {
                shadowSize: 0	// Drawing is faster without shadows
            },
            yaxis: {
                min: 0,
                max: 100
            },
            xaxis: {
                show: false
            },
            colors: ["#22BAA0"],
            legend: {
                show: false
            },
            grid: {
                color: "rgba(120,130,140,1)",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: 'transparent'
            },
            tooltip: true,
            tooltipOpts: {
                content: "Y: %y",
                defaultTheme: false
            }
        });

        function update() {
            plot2.setData([getRandomData()]);

            plot2.draw();
            setTimeout(update, 30);
        }

        update();

    };

    flot2();*/
    /*var flot1 = function () {
        var data = [[0, 65], [1, 60], [2, 80], [3, 80], [4, 55], [5, 55], [6, 40]];
        var data2 = [[0, 30], [1, 50], [2, 40], [3, 20], [4, 85], [5, 25], [6, 90]];
        var dataset =  [
            {
                data: data,
                color: "#f25656",
                lines: {
                    show: true,
                    fill: 0,
                },
                shadowSize: 0,
            }, {
                data: data,
                color: "#fff",
                lines: {
                    show: false,
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "#f25656",
                    lineWidth: 2
                },
                curvedLines: {
                    apply: false,
                },
                shadowSize: 0
            }, {
                data: data2,
                color: "rgba(34,186,160,1)",
                lines: {
                    show: true,
                    fill: 0,
                },
                shadowSize: 0,
            },{
                data: data2,
                color: "#fff",
                lines: {
                    show: false,
                },
                curvedLines: {
                    apply: false,
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "rgba(34,186,160,1)",
                    lineWidth: 2
                },
                shadowSize: 0
            }
        ];

        var ticks = [[0, "1"], [1, "2"], [2, "3"], [3, "4"], [4, "5"], [5, "6"], [6, "7"], [7, "8"]];

        var plot1 = $.plot("#flotchart1", dataset, {
            series: {
                color: "#14D1BD",
                lines: {
                    show: true,
                    fill: 0.2
                },
                shadowSize: 0,
                curvedLines: {
                    apply: true,
                    active: true
                }
            },
            yaxis: {
                tickSize: 25
            },
            xaxis: {
                ticks: ticks
            },
            legend: {
                show: false
            },
            grid: {
                color: "rgba(120,130,140,1)",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: 'transparent'
            },
            tooltip: true,
            tooltipOpts: {
                content: "%yK",
                defaultTheme: false
            }
        });
    };*/

    //flot1();

    $.ajax({
        type: 'post',
        url: 'http://ecole.test/home/charts',
        datatype: 'json',
        beforeSend: function () {},
        success: function (json) {
            if (json.statuts == 0) {
                var data = [];
                var data2 = [];
                for (var i = 0; i<json.paiements.length; i++){
                    data.push([i, json.paiements[i].montant]);
                }
                for (var j = 0; j<json.transactions.length; j++){
                    data2.push([j, json.transactions[j].montant]);
                }
                var dataset =  [
                    {
                        data: data,
                        color: "#f25656",
                        lines: {
                            show: true,
                            fill: 0,
                        },
                        shadowSize: 0,
                    }, {
                        data: data,
                        color: "#fff",
                        lines: {
                            show: false,
                        },
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#f25656",
                            lineWidth: 2
                        },
                        curvedLines: {
                            apply: false,
                        },
                        shadowSize: 0
                    }, {
                        data: data2,
                        color: "rgba(34,186,160,1)",
                        lines: {
                            show: true,
                            fill: 0,
                        },
                        shadowSize: 0,
                    },{
                        data: data2,
                        color: "#fff",
                        lines: {
                            show: false,
                        },
                        curvedLines: {
                            apply: false,
                        },
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "rgba(34,186,160,1)",
                            lineWidth: 2
                        },
                        shadowSize: 0
                    }
                ];
                var ticks = [[0, "1"], [1, "2"], [2, "3"], [3, "4"], [4, "5"], [5, "6"], [6, "7"], [7, "8"],
                    [8, "9"], [9, "10"], [10, "11"], [11, "12"], [12, "13"], [13, "14"], [14, "15"], [15, "16"],
                    [16, "17"], [17, "18"], [18, "19"], [19, "20"], [20, "21"], [21, "22"], [22, "23"], [23, "24"], [24, "25"]];
                var plot1 = $.plot("#flotchart1", dataset, {
                    series: {
                        color: "#14D1BD",
                        lines: {
                            show: true,
                            fill: 0.2
                        },
                        shadowSize: 0,
                        curvedLines: {
                            apply: true,
                            active: true
                        }
                    },
                    yaxis: {
                        tickSize: 1000
                    },
                    xaxis: {
                        ticks: ticks
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        color: "rgba(120,130,140,1)",
                        hoverable: true,
                        borderWidth: 0,
                        backgroundColor: 'transparent'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: "%y XAF",
                        defaultTheme: false
                    }
                });
            }
        },
        complete: function () {},
        error: function (jqXHR, textStatus, errorThrown) {}
    });
    
});