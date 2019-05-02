$(document).ready(function(){
	$(document).on('submit','#loginForm', function (e) {
		e.preventDefault();
		var url = $(this).data('url'),
			login = $('#login').val(),
			password = $('#password').val();
		if(login != '' && password != ''){
			$.ajax({
				type: 'post',
				url: 'ajax/log',
				data: 'login='+login+'&password='+password,
				datatype: 'json',
				beforeSend: function () {
					$('#login').prop('disabled',true);
					$('#password').prop('disabled',true);
					$('.sendBtn').text('Loading ...').prop('disabled',true);
				},
				success: function (json) {
					if(json.statuts == 0){
						window.location.assign(json.direct);
					}else{
						$('#password').val('');
						alert(json.mes);
					}
				},
				complete: function () {
					$('#login').prop('disabled',false);
					$('#password').prop('disabled',false);
					$('.sendBtn').text('Connexion').prop('disabled',false);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown+jqXHR+textStatus);

				}
			});
		}else{
			alert("A error appear, please reload");
		}
	});
});