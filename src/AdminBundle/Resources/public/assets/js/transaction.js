$(document).ready(function() {

	$(document).on('click','.editTransaction', function (e) {
		e.preventDefault();
		var amount = $(this).data('amount'),
			type = $(this).data('type'),
			id = $(this).data('id');
        $('#amount').val(amount);
        $('#type').val(type);
        $('#idTransaction').val(id);
        $('.md-input-wrapper').addClass('md-input-focus');
        UIkit.modal('#modalTransaction').show();
	});
//http://www.uselitewine.com/index.php?id=1
    $(document).on('submit', '#formTransaction', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var amount = $('#amount').val(),
            type = $('#type').val(),
            id = $('#idTransaction').val(),
            action = "edit",
            act = $('.sendBtn').text();
        if (amount != '' && type != '' && id != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'type='+type+'&amount='+amount+'&action='+action+'&id='+id,
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
                    $('.sendBtn').text(act).prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });

        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });

});