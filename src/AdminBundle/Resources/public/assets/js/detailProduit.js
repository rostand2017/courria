/**
 * Created by Dell on 24/05/2017.
 */
$(document).ready(function() {
    $(document).on('click','.deleteImage', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id'),
            parent = $(this).parent();
        mess = "Voulez vous vraiment supprimer l'image de ce produit?";
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
                            parent.remove();
                            UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'top-center'});
                        } else {
                            UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'top-center'});
                        }
                    },
                    complete: function () {},
                    error: function (jqXHR, textStatus, errorThrown) {}
                });
            }else{
                UIkit.notify({message:"Une erreur est apparue, recharger",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

});