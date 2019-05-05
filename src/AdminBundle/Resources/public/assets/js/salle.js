$(document).ready(function() {

    $(document).on('click','.deleteSalle', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Êtes vous sûr de vouloir supprimer cette salle?";
        UIkit.modal.confirm(mess, function(){
            if(url!=''&&id!=''){
                $.ajax({
                    type: 'post',
                    url: url,
                    data: 'id='+id,
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
                        console.log(errorThrown);
                    }
                });
            }else{
                $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Renseignez les champs requis</span></div>");
                UIkit.notify({message:"Renseignez les champs requis!",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

    $(document).on('submit', '#formSalle', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var nom = $('#nom').val(),
            lieu = $('#lieu').val(),
            capacite = $('#capacite').val(),
            act = $('.sendBtn').text();
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        if (nom != '' && lieu!='' && capacite!='') {
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
                    if (json.status == 0) {
                        $('#messageformSalle').html("<div class='uk-alert uk-alert-success uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                        UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'top-center'});
                        window.location.reload();
                    } else {
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                        $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                    }
                },
                complete: function () {
                    $('.sendBtn').text(act).prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });

        }else{
            $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Renseignez les champs requis</span></div>");
            UIkit.notify({message:"Renseigner tous les champs requis",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });
    $(document).on('click','#addSalle', function (e) {
        e.preventDefault();
        $('#nom').val('');
        $('#lieu').val('');
        $('#capacite').val('');
        $('#id').val('');
        $('#action').val('add');
        $('.titleForm').text("AJOUTER UNE NOUVELLE SALLE");
        $('.sendBtn').text("AJOUTER");
        UIkit.modal('#modalSalle').show();
    });
    $(document).on('click','.editSalle', function (e) {
        e.preventDefault();
        var nom = $(this).data('nom'),
            lieu = $(this).data('lieu'),
            capacite = $(this).data('capacite'),
            id = $(this).data('id');
        $('#nom').val(nom);
        $('#lieu').val(lieu);
        $('#capacite').val(capacite);
        $('#id').val(id);
        $('#action').val('edit');
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER LES INFORMATIONS DE CETTE SALLE");
        $('.sendBtn').text("MODIFIER");
        UIkit.modal('#modalSalle').show();
    });
});
