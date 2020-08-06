$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });
    $(document).on('click','.deleteFacture', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
        mess = "Êtes vous sûr de vouloir supprimer cette facture ?";
        UIkit.modal.confirm(mess, function(){
            if(url!==''){
                $.ajax({
                    type: 'get',
                    url: url,
                    datatype: 'json',
                    beforeSend: function () {},
                    success: function (json) {
                        if (json.status == 0) {
                            UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'top-center'});
                            window.location.reload();
                        } else {
                            UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'top-center'});
                        }
                    },
                    complete: function () {},
                    error: function (jqXHR, textStatus, errorThrown) {
                        UIkit.notify({message:"Une erreur est survenue",status:'danger',timeout : 5000,pos:'top-center'});
                    }
                });
            }else{
                UIkit.notify({message:"Une erreur est survenue",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

    $(document).on('submit', '#formFacture', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var data = $form.serialize();
        var act = $('.sendBtn').text();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            datatype: 'json',
            beforeSend: function () {
                $('.sendBtn').text('CHARGEMENT ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.status == 0) {
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
                UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
            }
        });
    });

    $(document).on('click','#addFacture', function (e) {
        e.preventDefault();
        $('#dateContainer').hide('fade');
        $('.titleForm').text("Ajoutez une facture");
        $('#quantite').val('');
        $('#nomClient').val('');
        $('#produit').val('');
        $('#id').val('');
        $('.sendBtn').text("Ajouter");
        UIkit.modal('#modalFacture').show();
    });


    $(document).on('click','.editFacture', function (e) {
        e.preventDefault();
        var produit = $(this).data('produit'),
            id = $(this).data('id'),
            nom = $(this).data('nom'),
            quantite = $(this).data('quantite'),
            date = $(this).data('date');
        $('#dateContainer').show('fade');
        $('#messageformSalle').html("");
        $('#date').val(date);
        $('#nomClient').val(nom);
        $('#quantite').val(quantite);
        $('#produit').val(produit);
        $('#id').val(id);
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("Modifiez la facture");
        $('.sendBtn').text("Modifier");
        UIkit.modal('#modalFacture').show();
    });
});