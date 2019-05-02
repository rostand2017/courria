$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });

    $(document).on('click','.deleteProduct', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Voulez vous supprimer cette news?";
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
                UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

    $(document).on('click','.changeProduct', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $('.idCashier').val(id);
        var modal = UIkit.modal("#addPictureCashier");
        modal.show();

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
                if (json.statuts == 0){
                    window.location.reload();
                }else{
                    alert(json.mes);
                }
            },
            complete: function () {
                $('.addBtn').text('MODIFIER').prop('disabled',false);
            },
            error: function(jqXHR, textStatus, errorThrown){

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
			detail = $('#detail').val(),
        act = $('.sendBtn').text();
		if (nom != '' && detail != '') {
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
        $('#detail').val('');
        $('#idMandataire').val('');
        $('#action').val('add');
        $('#pictureContent').show();
        $('.titleForm').text("PUBLIER UNE NEWS");
        $('.sendBtn').text("AJOUTER");
        UIkit.modal('#modalCustomer').show();
    });
	$(document).on('click','.editProduct', function (e) {
		e.preventDefault();
		var nom = $(this).data('nom'),
			prix = $(this).data('prix'),
            detail = $(this).data('detail'),
			id = $(this).data('id');
        $('#nom').val(nom);
        $('#detail').val(detail);
        $('#idCustomer').val(id);
        $('#action').val('edit');
        $('#pictureContent').hide();
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER UNE NEWS");
        $('.sendBtn').text("MODIFIER");
        UIkit.modal('#modalCustomer').show();
	});

});