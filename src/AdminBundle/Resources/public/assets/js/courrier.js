$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });
    $(document).on('click','.deleteCourrier', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            mess = "Êtes vous sûr de vouloir supprimer le courrier de "+$(this).data('expediteur')+" ?";
        UIkit.modal.confirm(mess, function(){
            if(url!==''){
                $.ajax({
                    type: 'post',
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

    $(document).on('click','.transfertToService', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            mess = "Êtes vous sûr de vouloir transferer le courrier de "+$(this).data('expediteur')+" ?";
        UIkit.modal.confirm(mess, function(){
            if(url!==''){
                $.ajax({
                    type: 'post',
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

    $(document).on('submit', '#formCourrier', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var form = $(this);
        var data = new FormData(form[0]);
        var nom = $('#expediteur').val(),
            act = $('.sendBtn').text();
        if (nom !== '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                datatype: 'json',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('.sendBtn').text('CHARGEMENT ...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.status === 0) {
                        $('#messageformSalle').html("<div class='uk-alert uk-alert-success uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                        window.location.reload();
                    } else {
                        $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                    }
                },
                complete: function () {
                    $('.sendBtn').prop('disabled', false).text(act);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Une erreur est survenue</span></div>");
                }
            });

        } else {
            $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Renseignez le nom du courrier</span></div>");
            UIkit.notify({message:"Renseignez les différents champs",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });

    $(document).on('click','#addCourrier', function (e) {
        e.preventDefault();
        $('#files').show();
        $('#files').val('');
        $('#messageformSalle').html("");
        $('#expediteur').val('');
        $('#objet').val('');
        $('.sendBtn').text("Ajouter");
        $('.titleForm').text("Ajouter un nouveau courrier");
        UIkit.modal('#modalCourrier').show();
    });

    $(document).on('click','.editCourrier', function (e) {
        e.preventDefault();
        var objet = $(this).data('objet'),
            expediteur = $(this).data('expediteur'),
            service = $(this).data('service'),
            id = $(this).data('id');
        $('#messageformSalle').html("");
        $('#objet').val(objet);
        $('#expediteur').val(expediteur);
        $('#service').val(service);
        $('#id').val(id);
        $('#files').hide();
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("Modifier le courrier");
        $('.sendBtn').text("Modifier");
        UIkit.modal('#modalCourrier').show();
    });

    // transfert

    $(document).on('click','.transfertCourrier', function (e) {
        e.preventDefault();
        $("#formService").attr("action", $(this).data("url"));
        $('.md-input-wrapper').addClass('md-input-focus');
        UIkit.modal('#modalService').show();
    });

    $(document).on('submit', '#formService', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var form = $(this);
        var data = new FormData(form[0]);
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            datatype: 'json',
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.sendBtnService').text('CHARGEMENT ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.status === 0) {
                    $('#messageformService').html("<div class='uk-alert uk-alert-success uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                    window.location.reload();
                } else {
                    $('#messageformService').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendBtnService').prop('disabled', false).text("transferer");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Une erreur est survenue</span></div>");
            }
        });
    });

    // observation
    $(document).on('click','.observation', function (e) {
        e.preventDefault();
        $("#formObservation").attr("action", $(this).data('url'));
        UIkit.modal('#modalObservation').show();
    });

    $(document).on('submit', '#formObservation', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var form = $(this);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            datatype: 'json',
            beforeSend: function () {
                $('.sendBtnService').text('CHARGEMENT ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.status === 0) {
                    $('#messageformObservation').html("<div class='uk-alert uk-alert-success uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                    window.location.reload();
                } else {
                    $('#messageformObservation').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>"+json.mes+"</span></div>");
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendBtnObservation').prop('disabled', false).text("transferer");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#messageformObservation').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Une erreur est survenue</span></div>");
            }
        });
    });
});