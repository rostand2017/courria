$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });

    $(document).on('click','.deleteUser', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            mess = "Êtes vous sûr de vouloir supprimer l'utilisateur "+$(this).data('nom')+" ?";
        UIkit.modal.confirm(mess, function(){
            if(url!==''){
                $.ajax({
                    type: 'get',
                    url: url,
                    datatype: 'json',
                    beforeSend: function () {},
                    success: function (json) {
                        if (json.status === 0) {
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
                UIkit.notify({message:"Une erreur est survenue",status:'danger',timeout : 5000,pos:'top-center'});
            }
        });
    });

    $(document).on('submit', '#formUser', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var data = $form.serialize();
        var act = $('.sendBtn').text();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            datatype: 'json',
            beforeSend: function () {
                $('.sendBtn').text('CHARGEMENT ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.status === 0) {
                    $('#messageform').html("<div class='uk-alert uk-alert-success uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                    window.location.reload();
                } else {
                    $('#messageform').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendBtn').prop('disabled', false).text(act);
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    });

    $(document).on('click','#addUser', function (e) {
        e.preventDefault();
        $('#email').val('');
        $('#nom').val('');
        $('#service').val('');
        $('#formUser').attr("action", "");
        $('.sendBtn').text("Ajouter");
        $('.titleForm').text("Ajouter un nouvel utilisateur");
        UIkit.modal('#modalUser').show();
    });

    $(document).on('click','.editUser', function (e) {
        e.preventDefault();
        var email = $(this).data('email'),
            nom = $(this).data('nom'),
            service = $(this).data('service'),
            fonction = $(this).data('fonction'),
            url = $(this).data('url');
        $('#email').val(email);
        $('#nom').val(nom);
        $('#service').val(service);
        $('#fonction').val(fonction);
        $('#formUser').attr("action", url);
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("Modifier l'utilisateur");
        $('.sendBtn').text("Modifier");
        UIkit.modal('#modalUser').show();
    });
});