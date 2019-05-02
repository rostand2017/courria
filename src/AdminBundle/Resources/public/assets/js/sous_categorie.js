$(document).ready(function() {

    $(document).on('click','.deleteProduct', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Êtes vous sûr de vouloir supprimer cette sous catégorie?";
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

    $(document).on('submit', '#formCustomer', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var action=$(this).attr("acts");

        var nom = $('#nom').val();
        var idCategorie = $('#idCategorie').val(),
            act = $('.sendBtn').text();
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        if (nom != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                datatype: 'json',
                beforeSend: function () {
                    $('.sendBtn').text('Chargement ...').prop('disabled', true);
                },
                success: function (json) {
                    console.log(json);
                    if (json.statuts == 0) {
                        window.location.reload();
                    } else {
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                    }
                },
                complete: function () {
                    $('.sendBtn').text(act).prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    window.location.reload();

                }
            });

        } else {
            UIkit.notify({message:"Une erreur est apparue, recharger",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });
    $(document).on('click','#addCustomer', function (e) {
        e.preventDefault();
        $('#nom').val('');
        $('#idCategorie').val('');
        $('#idCustomer').val('');
        $('#action').val('add');
        $('.titleForm').text("AJOUTER UNE NOUVELLE SOUS CATEGORIE");
        $('.sendBtn').text("AJOUTER");
        UIkit.modal('#modalCustomer').show();
    });
    $(document).on('click','.editProduct', function (e) {
        e.preventDefault();
        var nom = $(this).data('nom'),
            idCategorie = $(this).data('categorie'),
            id = $(this).data('id');
        $('#nom').val(nom);
        $('#idCategorie').val(idCategorie);
        $('#idCustomer').val(id);
        $('#action').val('edit');
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER UNE SOUS CATEGORIE");
        $('.sendBtn').text("MODIFIER");
        UIkit.modal('#modalCustomer').show();
    });

});
