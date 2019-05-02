$(document).ready(function() {

	$(document).on('submit', '#formCustomer', function (e) {
		e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		var nom = $('#nom').val(),
			telephone = $('#numero').val(),
        act = $('.sendBtns').text();
		if (nom != '' && telephone != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                datatype: 'json',
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
                    $('.sendBtns').text(act).prop('disabled', false);
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
			id = $(this).data('id');
        $('#nom').val(nom);
        $('#numero').val(numero);
        $('#idUser').val(id);
        $('#action').val('edit');
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("UPDATE A CUSTOMER");
        $('.sendBtn').text("UPDATE");
        UIkit.modal('#modalCustomer').show();
	});

    $(document).on('click','.addTransaction', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#idTransaction').val(id);
        $('.md-input-wrapper').addClass('md-input-focus');
        UIkit.modal('#modalTransaction').show();
    });

    $(document).on('submit', '#formTransaction', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var amount = $('#amount').val(),
            type = $('#type').val(),
            id = $('#idTransaction').val(),
            action = "add",
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