$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });
    $(document).on('click','.deleteProduit', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            mess = "Êtes vous sûr de vouloir supprimer le produit "+$(this).data('nom')+" ?";
        UIkit.modal.confirm(mess, function(){
            if(url!==''){
                $.ajax({
                    type: 'post',
                    url: url,
                    datatype: 'json',
                    beforeSend: function () {},
                    success: function (json) {
                        if (json.status === 0) {
                            UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'top-center'});
                            window.location.reload();
                        } else {
                            UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'top-center'});
                        }
                    },
                    complete: function () {},
                    error: function (jqXHR, textStatus, errorThrown) {}
                });
            }else{
                UIkit.notify({message:"Une erreur est survenue",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

    $(document).on('submit', '#formProduit', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var form = $(this);
        var data = new FormData(form[0]);
        var nom = $('#nom').val(),
            act = $('.sendBtn').text();
        if (nom !== '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                datatype: 'json',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('.sendBtn').text('CHARGEMENT ...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.status === 0) {
                        $('#messageformSalle').html("<div class='uk-alert uk-alert-success uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                        window.location.reload();
                    } else {
                        $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                    }
                },
                complete: function () {
                    $('.sendBtn').prop('disabled', false).text(act);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Une erreur est survenue</span></div>");
                }
            });

        } else {
            $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Renseignez le nom du produit</span></div>");
            UIkit.notify({message:"Renseignez les différents champs",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });

    $(document).on('click','#addProduit', function (e) {
        e.preventDefault();
        $('#messageformSalle').html("");
        $('#nom').val('');
        $('#modele').val('');
        $('#fabricant').val('');
        $('#prix').val('');
        $('#description').val('');
        $('#image').val('');
        $('#id').val('');
        $('.sendBtn').text("Ajouter");
        $('.titleForm').text("Ajouter un nouveau produit");
        UIkit.modal('#modalProduit').show();
    });

    $(document).on('click','.editProduit', function (e) {
        e.preventDefault();
        var description = $(this).data('description'),
            nom = $(this).data('nom'),
            fabricant = $(this).data('fabricant'),
            modele = $(this).data('modele'),
            prix = $(this).data('prix'),
            type = $(this).data('type'),
            id = $(this).data('id');
        $('#messageformSalle').html("");
        $('#description').val(description);
        $('#nom').val(nom);
        $('#fabricant').val(fabricant);
        $('#modele').val(modele);
        $('#prix').val(prix);
        $('#type').val(type);
        $('#id').val(id);
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("Modifier le produit");
        $('.sendBtn').text("Modifier");
        UIkit.modal('#modalProduit').show();
    });
});