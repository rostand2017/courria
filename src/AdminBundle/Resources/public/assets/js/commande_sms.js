$(document).ready(function() {

    $(document).on('click','.deleteProduct', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Êtes vous sûr de vouloir supprimer cette commande?";
        UIkit.modal.confirm(mess, function(){
            if(url!=''&&id!=''){
                $.ajax({
                    type: 'post',
                    url: url,
                    data: 'id='+id,
                    datatype: 'json',
                    beforeSend: function () {},
                    success: function (json) {
                        if (json.statuts == 0) {
                            window.location.reload();
                        } else {
                            UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'top-center'});
                        }
                    },
                    complete: function () {},
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }else{
                UIkit.notify({message:"Une erreur est apparue, recharger",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

    $(document).on('submit', '#validForm', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var act = $('#validButton').text();
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                datatype: 'json',
                beforeSend: function () {
                    $('#validButton').text('Chargement ...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts == 0) {
                        window.location.reload();
                    }else{
                        UIkit.notify({message: json.mes, status:'danger',timeout : 5000,pos:'top-center'});
                    }
                },
                complete: function () {
                    $('#validButton').text(act).prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
    });


     $(document).on('click','.detailTransaction', function(e){
            $("#id_commande").val( $(this).data('id') );
            UIkit.modal('#modalDetail').show();
    });

});
