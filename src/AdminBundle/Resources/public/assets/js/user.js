$(document).ready(function() {
	UIkit.datepicker('#birthday',{
		format: 'DD.MM.YYYY',
		maxDate: new Date()
	});

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
	$(document).on('click','.desactivateCustomer', function (e) {
		e.preventDefault();
		var val;
		var url = $(this).data('url'),
			id = $(this).data('id'),
			etat = $(this).data('etat');
		if(etat==1){
			mess = "Voulez vous vraiment désactiver cet employé ?";
			val = 0;
		}else{
			mess = "Voulez vous vraiment activer cet employé ?";
			val = 1;
		}
		UIkit.modal.confirm(mess, function(){
			if(url!=''&&id!=''){
				desactivate(url,id,val);
			}else{
				UIkit.notify({message:"Une erreur est survenue",status:'danger',timeout : 5000,pos:'top-center'});
			}
		});
	});
	function desactivate(url,id,etat) {
		$.ajax({
			type: 'post',
			url: url,
			data: 'id='+id+'&etat='+etat,
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
	}
	$(document).on('click','.resetPassword', function (e) {
		e.preventDefault();
		var url = $(this).data('url'),
			id = $(this).data('id');
		mess = "Êtes vous sûr de vouloir réinitialiser le mot de passe de cet employé ?";
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
						} else {
							UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'top-center'});
						}
					},
					complete: function () {},
					error: function (jqXHR, textStatus, errorThrown) {}
				});
			}else{
				UIkit.notify({message:"Une erreur est survenue, recharger",status:'danger',timeout : 5000,pos:'top-center'});
			}
		});
	});

	$(document).on('submit', '#formCustomer', function (e) {
		e.preventDefault();
		var url = $(this).attr('action');
		var $form = $(this);
		var formdata = (window.FormData) ? new FormData($form[0]) : null;
		var data = (formdata !== null) ? formdata : $form.serialize();
		var nom = $('#nom').val(),
			prenom = $('#prenom').val(),
			telephone = $('#numero').val(),
			sexe = $('#sexe').val(),
			act = $('.sendBtn').text(),
			count = telephone.length;

		if (nom != '' && prenom != '' && telephone != '' && sexe != '' && count == 9) {
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
			UIkit.notify({message:"Une erreur est survenue, recharger",status:'danger',timeout : 5000,pos:'top-center'});
		}
	});
	$(document).on('click','#addCustomer', function (e) {
		e.preventDefault();
		$('#nom').val('');
		$('#prenom').val('');
		$('#specialite').val('');
		$('#numero').val('');
		$('#idMandataire').val('');
		$('#birthday').val('');
		$('#email').val('');
		$('#genderCustomer').val('');
		$('#ville').val("");
		$('#adresse').val("");
		$('#action').val('add');
		$('#pictureContent').show();
		$('.titleForm').text("ADD A NEW CUSTOMER");
		$('.sendBtn').text("SAVE");
		UIkit.modal('#modalCustomer').show();
	});
	$(document).on('click','.editCustomer', function (e) {
		e.preventDefault();
		var nom = $(this).data('nom'),
			prenom = $(this).data('prenom'),
			specialite = $(this).data('specialite'),
			numero = $(this).data('numero'),
			sexe = $(this).data('sexe'),
			email = $(this).data('email'),
			city = $(this).data('ville'),
			adresse = $(this).data('adresse'),
			naissance = $(this).data('naissance'),
			id = $(this).data('id');
		$('#nom').val(nom);
		$('#prenom').val(prenom);
		$('#specialite').val(specialite);
		$('#numero').val(numero);
		$('#email').val(email);
		$('#sexe').val(sexe);
		$('#birthday').val(naissance);
		$('#ville').val(city);
		$('#adresse').val(adresse);
		$('#idCustomer').val(id);
		$('#action').val('edit');
		$('#pictureContent').hide();
		$('.md-input-wrapper').addClass('md-input-focus');
		$('.titleForm').text("MODIFIER UN UTILISATEUR");
		$('.sendBtn').text("UPDATE");
		UIkit.modal('#modalCustomer').show();
	});

	$(function () {
		altair_form_adv.date_range();
		altair_form_adv2.date_range();
	});
	altair_form_adv = {
		date_range: function () {
			var $dp_start = $('#uk_dp_start'),
				$dp_end = $('#uk_dp_end');

			var start_date = UIkit.datepicker($dp_start, {
				format: 'DD.MM.YYYY'
			});

			var end_date = UIkit.datepicker($dp_end, {
				format: 'DD.MM.YYYY'
			});

			$dp_start.on('change', function () {
				end_date.options.minDate = $dp_start.val();
				setTimeout(function () {
					$dp_end.focus();
				}, 300);
			});

			$dp_end.on('change', function () {
				start_date.options.maxDate = $dp_end.val();
			});
		}
	};
	altair_form_adv2 = {
		date_range: function () {
			var $dp_start = $('#uk_dp_startC'),
				$dp_end = $('#uk_dp_endC');

			var start_date = UIkit.datepicker($dp_start, {
				format: 'DD.MM.YYYY'
			});

			var end_date = UIkit.datepicker($dp_end, {
				format: 'DD.MM.YYYY'
			});

			$dp_start.on('change', function () {
				end_date.options.minDate = $dp_start.val();
				setTimeout(function () {
					$dp_end.focus();
				}, 300);
			});

			$dp_end.on('change', function () {
				start_date.options.maxDate = $dp_end.val();
			});
		}
	};

});