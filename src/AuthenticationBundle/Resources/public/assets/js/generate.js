/**
 * Created by Ndjeunou on 03/08/2017.
 */
$(document).ready(function(){

    $(document).on('click','.pdfgenerate1',function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        viewTransaction1(url);
    });

    $(document).on('click','.excellgenerate1',function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        viewTransaction1(url);
    });

    function viewTransaction1(url) {
        var type = $('.typeG1').val(),
            intitule = $('.intituleG1').val(),
            debut = $('.debutG1').val(),
            end = $('.finG1').val();
        var link1 = type!=''?'&type='+type:'';
        var link2 = intitule!=''?'&intitule='+intitule:'';
        var link3 = debut!=''?'&debut='+debut:'';
        var link4 = end!=''?'&end='+end:'';
        window.location.assign(url+link1+link2+link3+link4);
    }

    $(document).on('click','.pdfgenerate2',function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        viewTransaction2(url);
    });

    $(document).on('click','.excellgenerate2',function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        viewTransaction2(url);
    });

    function viewTransaction2(url) {
        var classe = $('.classeG2').val(),
            debut = $('.debutG2').val(),
            end = $('.finG2').val();
        var link1 = classe!=''?'&classe='+classe:'';
        var link2 = debut!=''?'&debut='+debut:'';
        var link3 = end!=''?'&end='+end:'';
        window.location.assign(url+link1+link2+link3);
    }

    $(document).on('click','.pdfgenerate3',function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        viewTransaction3(url);
    });

    $(document).on('click','.excellgenerate3',function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        viewTransaction3(url);
    });

    function viewTransaction3(url) {
        var charge = $('.chargeG3').val(),
            classe = $('.classeG3').val(),
            debut = $('.debutG3').val(),
            end = $('.finG3').val();
        var link1 = charge!=''?'&charge='+charge:'';
        var link2 = classe!=''?'&classe='+classe:'';
        var link3 = debut!=''?'&debut='+debut:'';
        var link4 = end!=''?'&end='+end:'';
        window.location.assign(url+link1+link2+link3+link4);
    }

});