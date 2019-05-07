$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });

    $(document).on('click','.editClient', function (e) {
        $('#messageformSalle').html('');
        e.preventDefault();
        var nom = $(this).data('nom'),
            prenom = $(this).data('prenom'),
            id = $(this).data('id');
        $('#nom').val(nom);
        $('#prenom').val(prenom);
        $('#id').val(id);
        $('#action').val('edit');
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER LES INFORMATIONS DE L'UTILISATEUR");
        $('.sendBtn').text("MODIFIER");
        UIkit.modal('#modalClient').show();
    });

	$(document).on('submit', '#formClient', function (e) {
		e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		var nom = $('#nom').val(),
		    prenom = $('#prenom').val(),
        act = $('.sendBtn').text();
		if (nom != '' && prenom != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
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

                }
            });

        } else {
            $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Renseignez les différents champs</span></div>");
            UIkit.notify({message:"Renseignez les différents champs",status:'danger',timeout : 5000,pos:'top-center'});
		}
	});
    $(document).on('click','#addClient', function (e) {
        e.preventDefault();
        $('#nom').val('');
        $('#prenom').val('');
        $('#id').val('');
        $('#action').val('add');
        $('.titleForm').text("AJOUTER UN UTILISATEUR");
        $('.sendBtn').text("AJOUTER");
        UIkit.modal('#modalClient').show();
    });
    $(document).on('click','.deleteClient', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Êtes vous sûr de vouloir supprimer l'utilisateur "+$(this).data('nom')+" ?";
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
                    error: function (jqXHR, textStatus, errorThrown) {}
                });
            }else{
                UIkit.notify({message:"Une erreur est survenue",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });
});