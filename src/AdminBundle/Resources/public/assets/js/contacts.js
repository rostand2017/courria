$(document).ready(function(){
    $(function() {
        // date range
        altair_form_adv.date_range();
    });

    altair_form_adv = {
        // date range
        date_range: function() {
            var $dp_start = $('#uk_dp_start'),
                $dp_end = $('#uk_dp_end');

            var start_date = UIkit.datepicker($dp_start, {
                format:'DD.MM.YYYY'
            });

            var end_date = UIkit.datepicker($dp_end, {
                format:'DD.MM.YYYY'
            });

            $dp_start.on('change',function() {
                end_date.options.minDate = $dp_start.val();
                setTimeout(function() {
                    $dp_end.focus();
                },300);
            });

            $dp_end.on('change',function() {
                start_date.options.maxDate = $dp_end.val();
            });
        }
    };

	$(document).on('click', '.sendBtnCont', function (e) {
		e.preventDefault();
		var url = $(this).data('url'),
			check = $('#Ccheck').val(),
			act = $('.sendBtnCont').text();
		if (check != '' && url != '') {
			$.ajax({
				type: 'post',
				url: url,
				data: 'check='+check,
				datatype: 'json',
				beforeSend: function () {
					$('.sendBtnCont').text('Loading ...').prop('disabled', true);
				},
				success: function (json) {
					if (json.statuts == 0) {
						$('.cContentChange').addClass('none');
						$('.cValid').removeClass('none').html(json.contenu);
					} else {
						UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
					}
				},
				complete: function () {
					$('.sendBtnCont').prop('disabled', false).text(act);
				},
				error: function (jqXHR, textStatus, errorThrown) {

				}
			});
		} else {
			UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
		}
	});

	$(document).on('click', '#contactSend', function (e) {
		e.preventDefault();
		var url = $(this).data('url'),
			id = $(this).data('id'),
			act = $(this).text();
		if(id != '' && url != ''){
			modalVerify(function () {
				submit(id,url,act)
			});
		}else{
			UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
		}
	});

	$(document).on('click', '.cBack', function (e) {
		e.preventDefault();
		$('.cContentChange').removeClass('none');
		$('.cValid').addClass('none').html('');
	});

	function submit(id,url,act) {
		$.ajax({
			type: 'post',
			url: url,
			data: 'idContact='+id,
			datatype: 'json',
			beforeSend: function () {
				$('.sendChange').text('Loading ...').prop('disabled',true);
			},
			success: function (json) {
				if(json.statuts == 0){
                    UIkit.modal('#mailbox_new_message').hide();
                    UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'bottom-center'});
					window.location.reload();
				}else{
					UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
				}
			},
			complete: function () {
				$('.sendBtn').prop('disabled',false).text(act);
			},
			error: function(jqXHR, textStatus, errorThrown){

			}
		});
	}

	$(document).on('click','.removeContact', function (e) {
		e.preventDefault();
		var url = $(this).data('url'),
			id = $(this).data('id');
		UIkit.modal.confirm('Are you sure you want to delete this contact from your list?', function(){
			if(url!=''&&id!=''){
				modalVerify(function () {
					remove(id,url);
				});
			}else{
				UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
			}
		});
	});

	function remove(id,url){
		$.ajax({
			type: 'post',
			url: url,
			data: 'id='+id,
			datatype: 'json',
			beforeSend: function () {},
			success: function (json) {
				if (json.statuts == 0) {
					UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'bottom-center'});
					window.location.reload();
				} else {
					UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
				}
			},
			complete: function () {},
			error: function (jqXHR, textStatus, errorThrown) {}
		});
	}
	
});