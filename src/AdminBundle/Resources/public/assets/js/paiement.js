$(document).ready(function() {
    $(document).on('click','.validPaiement', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Are you sure you want to validate this payment?";
        UIkit.modal.confirm(mess, function(){
            if(url!=''&&id!=''){
                desactivate(url,id);
            }else{
                UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

    $(document).on('click','.detailTransaction', function(e){
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        if(url!='') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'id=' + id,
                datatype: 'html',
                beforeSend: function () {
                    UIkit.modal('#modalDetail').show();
                    $('#contenuModalDetails').html('').html('<div class="uk-text-center"><img src="assets/ajax-loader.gif"></div>');
                },
                success: function (data) {
                    $('#contenuModalDetails').html('').html(data);
                },
                complete: function () {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }
    });


    function desactivate(url,id) {
        $.ajax({
            type: 'post',
            url: url,
            data: 'id='+id,
            datatype: 'json',
            beforeSend: function () {},
            success: function (json) {
                if (json.statuts == 0) {
                    UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'top-center'});
                    window.location.reload();
                } else {
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'top-center'});
                }
            },
            complete: function () {},
            error: function (jqXHR, textStatus, errorThrown) {}
        });
    }


});