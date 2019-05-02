$(document).ready(function () {
    function number_format (number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    $.ajax({
        type: 'post',
        url: 'http://sesame.dev/compte/charts',
        datatype: 'json',
        beforeSend: function () {},
        success: function (json) {
            if (json.statuts == 0) {
                /*
                ***  Variationdu solde du compte
                 */
                var c3chart_spline_id = '#c3_chart_spline';
                var soldes = json.soldes;
                var arraySoldeAvant = [];
                var arraySoldeApres = [];
                arraySoldeAvant.push('Solde avant');
                arraySoldeApres.push('Solde après');
                for (var i=0;i< soldes.length;i++){
                    arraySoldeApres.push(soldes[i].soldeApres);
                    arraySoldeAvant.push(soldes[i].soldeAvant);
                }
                c3.generate({
                    bindto: c3chart_spline_id,
                    data: {
                        columns: [
                            arraySoldeAvant,
                            arraySoldeApres
                        ],
                        type: 'spline'
                    },
                    color: {
                        pattern: ['#5E35B1', '#FB8C00']
                    }
                });


                /*
                 ***  Evolution des transactions dans un compte
                 */

                var mg_linked_charts_id1 = '#mg_chart_currency1',
                    mg_linked_charts_id2 = '#mg_chart_currency2',
                    mg_linked_charts_id1_height = $(mg_linked_charts_id1).height(),
                    mg_linked_charts_id2_height = $(mg_linked_charts_id2).height();

                var mg_linked_charts_id1_width = $(mg_linked_charts_id1).width(),
                    mg_linked_charts_id2_width = $(mg_linked_charts_id2).width();
                var debits = json.debits;
                var jsonDebits = [];
                for(var j = 0; j < debits.length; j++){
                    jsonDebits.push({"date":new Date(debits[j].jour),"value":Number(debits[j].somme)});
                }
                var credits = json.credits;
                var jsonCredits = [];
                for(var k = 0; k < credits.length; k++){
                    jsonCredits.push({"date":new Date(credits[k].jour),"value":Number(credits[k].somme)});
                }
                MG.data_graphic({
                    title: "Evolution of debit transactions",
                    description: "Evolution of debit transactions.",
                    data: jsonDebits,
                    interpolate: d3.curveLinear,
                    width: mg_linked_charts_id1_width,
                    height: mg_linked_charts_id1_height,
                    target: mg_linked_charts_id1,
                    show_secondary_x_label: false,
                    show_confidence_band: ['l', 'u'],
                    x_extended_ticks: true,
                    color: '#8C001A'
                });

                MG.data_graphic({
                    title: "Evolution of credit transactions",
                    description: "Evolution of credit transactions.",
                    data: jsonCredits,
                    width: mg_linked_charts_id2_width,
                    height: mg_linked_charts_id2_height,
                    target: mg_linked_charts_id2,
                    animate_on_load: true,
                    x_accessor: 'date',
                    y_label: 'Amount of credit',
                    x_label: 'Date',
                    left: 70,
                    y_accessor: 'value'
                });
            } else {
                UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
            }
        },
        complete: function () {},
        error: function (jqXHR, textStatus, errorThrown) {}
    });
    
});