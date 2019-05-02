$(document).ready(function() {

    $(document).on('click','.deleteSlider', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Voulez vous vraiment supprimer ce slider?";
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
        alert(url);
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
                    alert(json.mes);
                }
            },
            complete: function () {
                $('.addBtn').text('Change').prop('disabled',false);
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
        var nom = $('#nom').val();
        if (nom != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                datatype: 'json',
                beforeSend: function () {
                    $('.sendsBtn').text('Chargement ...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts == 0) {
                        window.location.reload();
                    } else {
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                    }
                },
                complete: function () {
                    $('.sendsBtn').text('Modifier').prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });

        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });
    $(document).on('submit', '#formSlider', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        var text1 = $('#text1').val(),
            act = $('.sendBtn').text();
            var action=$('#action1').val();action=='edit'?act='MODIFIER':act='AJOUTER';
        if (text1 != '') {
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
                    $('.sendBtn').prop('disabled', false).text("");$('.sendBtn').text(act);

                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });

        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });

    $(document).on('click','#addSlider', function (e) {
        e.preventDefault();
        $('#lien').val('http://www.');
        $('#idMandataire').val('');
        $('#action1').val('add');
        $('#pictureContent').show();
        $('.titleForm').text("AJOUTER UN SLIDER");
        $('.sendBtn').text("");
        $('.sendBtn').text("AJOUTER");
        UIkit.modal('#modalSlider').show();
    });
    $(document).on('click','.detailTransaction', function(e){
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        if(url!='') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'id=' + id,
                datatype: 'html',
                beforeSend: function () {
                    $('#contenuModalDetails').html('');
                },
                success: function (data) {
                    UIkit.modal('#modalDetail').show();
                    $('#contenuModalDetails').html(data);
                    $('#contenuModalDetails').show();

                },
                complete: function () {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }
    });
    $(document).on('click','.editCustomer', function (e) {
        e.preventDefault();
        var nom = $(this).data('nom'),
            email=$(this).data('email'),
            email2=$(this).data('email2'),
            bp=$(this).data('bp'),
            adresse=$(this).data('adresse'),
            photo=$(this).data('photo'),

            numero1=$(this).data('numero1'),
            numero2=$(this).data('numero2'),
            petitedesc=$(this).data('petitedesc'),
            grandedesc=$(this).data('grandedesc'),
            id = $(this).data('id');

        $('#email1').val(email);
        $('#email2').val(email2);
        $('#adresse').val(adresse);
        $('#bp').val(bp);
        $('#petiteDesc').val(petitedesc);
        $('#grandeDesc').val(petitedesc);
        $('#numero1').val(numero1);
        $('#numero2').val(numero2);
        $('#photo').val(photo);

        $('#idCustomer').val(id);
        $('#nom').val(nom);
        $('#action').val('edit');
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER UN PARAMETRE");
        $('.sendsBtn').text("MODIFIER");
        UIkit.modal('#modalCustomer').show();
    });
    $(document).on('click','.editSlider', function (e) {
        e.preventDefault();
        var text1 = $(this).data('text1'),
            text2=$(this).data('text2'),
            image=$(this).data('image'),
        id = $(this).data('id');

        $('#text1').val(text1);
        $('#text2').val(text2);
        $('#photo1').val(image);



        $('#idSlider1').val(id);


        $('#pictureContent1').hide();
        $('#action1').val('edit');
        // $('#pictureContent').hide();
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER UN SLIDER");
        $('.sendBtn').text("MODIFIER");
        UIkit.modal('#modalSlider').show();
    });

});