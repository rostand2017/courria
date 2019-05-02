$(document).ready(function(){

    $(document).on('submit','#changePasswordForm',function (e) {
        e.preventDefault();
        var old = $('#old').val(),
            pass = $('#new').val(),
            url = $(this).attr('action'),
            repeat = $('#new_repeat').val();
        if(pass!='' && repeat!='' && url!='' && old!=''){
            if(pass == repeat){
                $.ajax({
                    type: 'post',
                    url: url,
                    data: 'new='+pass+'&old='+old+'&confirm='+repeat,
                    datatype: 'json',
                    beforeSend: function () {
                        $('.sendChange').text('Loading ...').prop('disabled',true);
                    },
                    success: function (json) {
                        if (json.statuts == 0){
                            UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'bottom-center'});
                            window.location.reload();
                        }else{
                            UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                        }
                    },
                    complete: function () {
                        $('.sendChange').prop('disabled',false).text('Change');
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert('erreur : '+errorThrown);
                    }
                });
            }else{
                UIkit.notify({message:'Password must be identical',status:'danger',timeout : 5000,pos:'bottom-center'});
            }
        }else{
            UIkit.notify({message:'Please fill all the input',status:'danger',timeout : 5000,pos:'bottom-center'});
        }
    });

    $(document).on('submit','#changeImageForm',function (e) {
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
                $('.sendChange').text('Loading ...').prop('disabled',true);
            },
            success: function (json) {
                if (json.statuts == 0){
                    window.location.assign(json.lien);
                }else{
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendChange').text('Change').prop('disabled',false);
            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    });

    $(document).on('click','#changeCni', function(e){
        e.preventDefault();
        var modal = UIkit.modal("#addPictureMandataire");
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
                $('.addBtn').text('Loading ...').prop('disabled',true);
            },
            success: function (json) {
                if (json.statuts == 0){
                    window.location.reload();
                }else{
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.addBtn').text('Change').prop('disabled',false);
            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    });

});
