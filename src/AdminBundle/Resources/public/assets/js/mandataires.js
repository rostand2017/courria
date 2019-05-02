$(document).ready(function() {
	$(document).on('submit', '#contForm', function (e) {
		e.preventDefault();
        var url = $(this).data('url');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		var nom = $('#nom').val(),
			prenom = $('#prenom').val(),
			telephone = $('#numero').val(),
			cni = $('#cni').val(),
            act = $('.sendBtn').text();
		if (nom != '' && prenom != '' && telephone != '' && cni != '') {
			modalVerify(function () {
                submit(data,url,act);
            })
		} else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
		}
	});
    function submit(data,url,act) {
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
    }
	$(document).on('click','.editMandataire', function (e) {
		e.preventDefault();
		var nom = $(this).data('nom'),
			prenom = $(this).data('prenom'),
			cni = $(this).data('cni'),
			numero = $(this).data('numero'),
			id = $(this).data('id');
        $('#nom').val(nom);
        $('#prenom').val(prenom);
        $('#cni').val(cni);
        $('#numero').val(numero);
        $('#idMandataire').val(id);
        $('#action').val('edit');
        $('#pictureContent').hide();
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("UPDATE A REPRESENTANT");
        $('.sendBtn').text("UPDATE");
        UIkit.modal('#mailbox_new_message').show();
	});
    $(document).on('click','#addMandataire', function (e) {
        e.preventDefault();
        $('#nom').val('');
        $('#prenom').val('');
        $('#cni').val('');
        $('#numero').val('');
        $('#idMandataire').val('');
        $('#action').val('add');
        $('#pictureContent').show();
        $('.titleForm').text("ADD A NEW REPRESENTANT TO THE LIST");
        $('.sendBtn').text("SAVE");
        UIkit.modal('#mailbox_new_message').show();
    });
    $(document).on('click','.removeMandataire', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        UIkit.modal.confirm('Are you sure you want to delete this mandatory from your list?', function(){
            if(url!=''&&id!=''){
                modalVerify(function () {
                   remove(id,url);
                });
            }else{
                UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
            }
        });
    });
    function remove(id,url) {
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
    $(function () {
        altair_form_adv.date_range();
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
    $(document).on('click','.addPhotoMandataire', function(e){
        e.preventDefault();
        var url = $(this).data('url');
        var id = $(this).data('id');
        $('.idMandataire').val(id);
        var modal = UIkit.modal("#addPictureMandataire");
        modal.show();

    });

    $(document).on('submit','#addPictureForm',function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        modalVerify(function () {
           addPhoto(data,url);
        });
    });

    function addPhoto(data,url) {
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: false,
            processData: false,
            datatype: 'json',
            beforeSend: function () {
                $('.addBtn').text('Loading ...').prop('disabled',true);
            },
            success: function (json) {
                if (json.statuts == 0){
                    window.location.reload();
                }else{
                    alert(json.mes);
                }
            },
            complete: function () {
                $('.addBtn').text('Change').prop('disabled',false);
            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    }

});