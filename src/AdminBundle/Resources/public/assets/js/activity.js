$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: 'custom/ckeditor_config.js'
        });
	$(document).on('submit', '#formActivity', function (e) {
		e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var data = $form.serialize();
		var description = $('#description').val(),
        act = $('.sendBtn').text();
		if (description != '') {
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

                }
            });

        } else {
            $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Renseignez les différents champs</span></div>");
            UIkit.notify({message:"Renseignez les différents champs",status:'danger',timeout : 5000,pos:'top-center'});
		}
	});

    $(document).on('click','#addActivity', function (e) {
        e.preventDefault();
        $('#description').val('');
        $('#id').val('');
        $('.sendBtn').text("Ajouter");
        $('.titleForm').text("Ajouter une activité");
        UIkit.modal('#modalActivity').show();
    });

    $(document).on('click','.editActivity', function (e) {
        e.preventDefault();
        var description = $(this).data('description'),
            id = $(this).data('id');
        $('#description').val(description);
        $('#id').val(id);
        $('#action').val('edit');
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("Modifier l'activité");
        $('.sendBtn').text("Modifier");
        UIkit.modal('#modalActivity').show();
    });
});