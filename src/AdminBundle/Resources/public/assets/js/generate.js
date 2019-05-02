$(document).ready(function(){
	$(document).on('submit','#generateForm', function (e) {
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
			beforeSend: function () {},
			success: function (json) {
				if (json.statuts == 0){
					window.location.reload();
				}else{
					alert(json.mes);
				}
			},
			complete: function () {},
			error: function(jqXHR, textStatus, errorThrown){}
		});
	});
});