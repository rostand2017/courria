$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });

    $(document).on('click','.changeConcert', function(e){
        e.preventDefault();
        $('#messageformImage').html('');
        var id = $(this).data('id');
        $('#idImage').val(id);
        var modal = UIkit.modal("#addPicture");
        modal.show();

    });
    $(document).on('click','.deleteConcert', function (e) {
        e.preventDefault();
        $('#messageformSalle').html('');
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Voulez vous annuler ce concert?";
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
    $(document).on('click','.editConcert', function (e) {
        $('#messageformSalle').html('');
        e.preventDefault();
        var intitule = $(this).data('intitule'),
            prix = $(this).data('prix'),
            description = $(this).data('description'),
            nbPlace = $(this).data('nbplace'),
            date = $(this).data('date'),
            time = $(this).data('time'),
            artistes = $(this).data('artistes'),
            salle = $(this).data('salle'),
            id = $(this).data('id');
        $('#intitule').val(intitule);
        $('#description').val(description);
        $('#prix').val(prix);
        $('#nbPlace').val(nbPlace);
        $('#date').val(date);
        $('#time').val(time);
        $('#artistes').val(artistes);
        $('#salle').val(salle);
        $('#id').val(id);
        $('#action').val('edit');
        $('#pictureContent').hide();
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER LES INFORMATIONS SUR CE CONCERT");
        $('.sendBtn').text("MODIFIER");
        UIkit.modal('#modalConcert').show();
    });


    $(document).on('submit','#addPictureForm',function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
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
                $('.addBtn').text('Chargement ...').prop('disabled',true);
            },
            success: function (json) {
                if (json.status == 0){
                    window.location.reload();
                }else{
                    $('#messageformImage').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                }
            },
            complete: function () {
                $('.addBtn').text('Modifier').prop('disabled',false);
            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    });
	$(document).on('submit', '#formConcert', function (e) {
		e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		var intitule = $('#intitule').val(),
			description = $('#description').val(),
			prix = $('#prix').val(),
			date = $('#date').val(),
			time = $('#time').val(),
			nbPlace = $('#nbPlace').val(),
			salle = $('#salle').val(),
        act = $('.sendBtn').text();
		if (intitule != '' && description != '' && prix != '' && prix > 1000 && date != '' && time != '' && nbPlace != '' && salle != '') {
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
    $(document).on('click','#addConcert', function (e) {
        e.preventDefault();
        $('#intitule').val('');
        $('#description').val('');
        $('#id').val('');
        $('#nbPlace').val('');
        $('#date').val('');
        $('#time').val('');
        $('#artistes').val('');
        $('#prix').val('');
        $('#salle').val('');
        $('#action').val('add');
        $('#pictureContent').show();
        $('.titleForm').text("AJOUTER UN CONCERT");
        $('.sendBtn').text("AJOUTER");
        UIkit.modal('#modalConcert').show();
    });

});