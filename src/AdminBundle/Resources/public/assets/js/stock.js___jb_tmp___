$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });
    $(document).on('click','.deleteStock', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            mess = "Êtes vous sûr de vouloir supprimer ce stock ?";
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

    $(document).on('submit', '#formStock', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var data = $form.serialize();
        var quantite = $('#quantite').val(),
            act = $('.sendBtn').text();
        if (quantite > 0) {
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

        } else {
            $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Ajouter une quantité</span></div>");
            UIkit.notify({message:"Renseignez les différents champs",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });

    $(document).on('click','#addStock', function (e) {
        e.preventDefault();
        $("#formStock").attr('action', $(this).data('url'));
        $('#quantite').val('');
        $('.sendBtn').text("Ajouter");
        $('.titleForm').text("Ajouter un nouveau stock");
        UIkit.modal('#modalStock').show();
    });

    $(document).on('click','.editStock', function (e) {
        e.preventDefault();
        var quantite = $(this).data('quantite'),
            url = $(this).data('url');
        $('#quantite').val(quantite);
        $("#formStock").attr('action', url);
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("Modifier le stock");
        $('.sendBtn').text("Modifier");
        UIkit.modal('#modalStock').show();
    });
});