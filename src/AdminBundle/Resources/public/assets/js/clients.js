$(document).ready(function() {
    $(document).on('click','.detailTransaction', function(e){
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        if(url!='') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'id=' + id,
                datatype: 'html',
                beforeSend: function () {
                    $('#contenuModalDetails').html('');
                },
                success: function (data) {
                    UIkit.modal('#modalDetail').show();
                    $('#contenuModalDetails').html(data);

                },
                complete: function () {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }
    });
    UIkit.datepicker('#echeance',{
        format: 'DD.MM.YYYY'
    });
    UIkit.datepicker('#abonnement',{
        format: 'DD.MM.YYYY'
    });

	$(document).on('submit', '#formCustomer', function (e) {
		e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		var nom = $('#nom').val(),
			echeance = $('#echeance').val(),
			telephone = $('#numero').val(),
			abonnement = $('#abonnement').val(),
            decodeur = $('#decodeur').val(),
            bouquet = $('#bouquet').val(),
        act = $('.sendBtn').text();
		if (nom != '' && decodeur != '' && telephone != '' && abonnement != '' && echeance != '' && bouquet != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                datatype: 'json',
                beforeSend: function () {
                    $('.sendBtn').text('Loading ...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts == 0) {
                        window.location.reload();
                    } else {
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
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'top-center'});
		}
	});
    $(document).on('click','#addCustomer', function (e) {
        e.preventDefault();
        $('#nom').val('');
        $('#bouquet').val('');
        $('#echeance').val('');
        $('#abonnement').val('');
        $('#idUser').val('');
        $('#quartier').val('');
        $('#decodeur').val('');
        $('#email').val('');
        $('#numero').val('');
        $('#action').val('add');
        $('.titleForm').text("ADD A NEW CUSTOMER");
        $('.sendBtn').text("SAVE");
        UIkit.modal('#modalCustomer').show();
    });
	$(document).on('click','.editCustomer', function (e) {
		e.preventDefault();
		var nom = $(this).data('nom'),
			numero = $(this).data('numero'),
			decodeur = $(this).data('decodeur'),
			echeance = $(this).data('echeance'),
			abonnement = $(this).data('abonnement'),
            bouquet = $(this).data('bouquet'),
            email = $(this).data('email'),
            quartier = $(this).data('quartier'),
			id = $(this).data('id');
        $('#nom').val(nom);
        $('#decodeur').val(decodeur);
        $('#echeance').val(echeance);
        $('#email').val(email);
        $('#quartier').val(quartier);
        $('#numero').val(numero);
        $('#abonnement').val(abonnement);
        $('#bouquet').val(bouquet);
        $('#idUser').val(id);
        $('#action').val('edit');
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("UPDATE A CUSTOMER");
        $('.sendBtn').text("UPDATE");
        UIkit.modal('#modalCustomer').show();
	});
    $(document).on('click','.validPayment', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#idCustomer').val(id);
        $('.md-input-wrapper').addClass('md-input-focus');
        UIkit.modal('#modalValid').show();
    });

    $(document).on('submit','#formValid', function (e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            mois = $('#mois').val(),
            etat = $('#etat').val(),
            id = $('#idCustomer').val();
        if(url!=''&&id!=''&&mois!=''&&etat!=''){
            $.ajax({
                type: 'post',
                url: url,
                data: 'id='+id+'&mois='+mois+'&etat='+etat,
                datatype: 'json',
                beforeSend: function () {},
                success: function (json) {
                    if (json.statuts == 0) {
                        window.location.reload();
                    } else {
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                    }
                },
                complete: function () {},
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        }else{
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });

    $(document).on('click','.changeBouquet', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#idChange').val(id);
        UIkit.modal('#modalBouquet').show();
    });

    $(document).on('submit', '#formBouquet', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        var act = $('.sendBtns').text();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            datatype: 'json',
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.sendBtns').text('Loading ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.statuts == 0) {
                    window.location.reload();
                } else {
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendBtns').prop('disabled', false).text(act);
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    });

});