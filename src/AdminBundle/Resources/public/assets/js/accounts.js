$(document).ready(function () {
    
    $(document).on('click','.Acheck', function (e) {
        e.preventDefault();
        modalVerify(function () {
            getSolde();
        });
    });

    function getSolde() {
        $.ajax({
            type: 'post',
            url: "http://sesame.dev/compte/getSolde",
            datatype: 'json',
            beforeSend: function () {},
            success: function (json) {
                if (json.statuts == 0) {
                    UIkit.notify({message:"Your account balance is "+json.solde+" XAF",status:'success',timeout : 5000,pos:'top-center'});
                    UIkit.modal.alert("Your account balance is "+json.solde+" XAF");
                }
            },
            complete: function () {},
            error: function (jqXHR, textStatus, errorThrown) {}
        });
    }

    $(document).on('click','.switchery', function(e){
        e.preventDefault();
        if($('.AclassMandat').hasClass('none')){
            $('.AclassMandat').removeClass('none');
            $('.switchery').attr('value',"yes");
        }else{
            $('.AclassMandat').addClass('none');
            $('.switchery').attr('value',"no");
        }
    });

    $(document).on('click','.ALabelChecked', function(e){
        e.preventDefault();
        if($('.AclassMandat').hasClass('none')){
            $('.AclassMandat').removeClass('none');
            $('.switchery').attr('value',"yes");
        }else{
            $('.AclassMandat').addClass('none');
            $('.switchery').attr('value',"no");
        }
    });

    $(document).on('click','.Ainitier', function (e) {
        e.preventDefault();
        $('.switchery').attr('value',"no");
        UIkit.modal('#AinitierModal').show();
    });
    
    $(document).on('submit', '#AinitierForm', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            montant = $('#Amontant').val(),
            mandat = $('#Amandat').val(),
            swi = $('.switchery').attr('value'),
            act = $('.sendBtnsI').text();
        if (montant != '' && url != '') {
            modalVerify(function () {
                initierRetrait(montant,mandat,swi,url,act);
            })
        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
        }
    });

    function initierRetrait(montant,mandat,swi,url,act) {
        $.ajax({
            type: 'post',
            url: url,
            data: 'montant='+montant+'&mandat='+mandat+'&swi='+swi,
            datatype: 'json',
            beforeSend: function () {
                $('.sendBtnsI').text('Loading ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.statuts == 0) {
                    UIkit.modal('#AinitierModal').hide();
                    UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'bottom-center'});
                    window.location.reload();
                } else {
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendBtnsI').prop('disabled', false).text(act);
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }



    $(document).on('click','.Atransfert2', function (e) {
        e.preventDefault();
        UIkit.modal('#AtransfertNotSesameModal').show();
    });
    $(document).on('click','.Atransfert1', function (e) {
        e.preventDefault();
        UIkit.modal('#AtransfertSesameModal').show();
    });

    $(document).on('submit', '#AtransfertNotSesameForm', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            montant = $('#ANSmontant').val(),
            nom = $('#ANSnom').val(),
            prenom = $('#ANSprenom').val(),
            ville = $('#ANSville').val(),
            numero = $('#ANSnumero').val(),
            code = $('#ANScode').val(),
            act = $('.sendBtnsTN').text();
        if (montant != '' && url != '' && nom != '' && prenom != '' && montant != '' && ville != '') {
            modalVerify(function () {
                transfert2(montant,code,nom,prenom,numero,ville,url,act);
            });
        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
        }
    });

    function transfert2(montant,code,nom,prenom,numero,ville,url,act) {
        $.ajax({
            type: 'post',
            url: url,
            data: 'montant='+montant+'&nom='+nom+'&prenom='+prenom+'&ville='+ville+'&numero='+numero+'&code='+code,
            datatype: 'json',
            beforeSend: function () {
                $('.sendBtnsTN').text('Loading ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.statuts == 0) {
                    UIkit.modal('#AtransfertNotSesameModal').hide();
                    UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'bottom-center'});
                    window.location.reload();
                } else {
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendBtnsTN').prop('disabled', false).text(act);
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }

    $(document).on('submit', '#AtransfertSesameForm1', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            montant = $('#ASamount').val(),
            contact = $('#ASidContact').val(),
            act = $('.sendBtnsTS').text();
        if (montant != '' && url != '' && contact != '') {
            modalVerify(function () {
                transfert1(montant,contact,url,act);
            });
        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
        }
    });
    $(document).on('click', '#ASTSend', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            contact = $(this).data('id'),
            montant = $('#ASVMontant').val(),
            act = $(this).text();
        if (montant != '' && url != '' && contact != '') {
            modalVerify(function () {
                transfert1(montant,contact,url,act);
            });
        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
        }
    });
    function transfert1(montant,contact,url,act) {
        $.ajax({
            type: 'post',
            url: url,
            data: 'montant='+montant+'&contact='+contact,
            datatype: 'json',
            beforeSend: function () {
                $('.sendBtnsTS').text('Loading ...').prop('disabled', true);
            },
            success: function (json) {
                if (json.statuts == 0) {
                    UIkit.modal('#AtransfertSesameModal').hide();
                    UIkit.notify({message:json.mes,status:'success',timeout : 5000,pos:'bottom-center'});
                    window.location.reload();
                } else {
                    UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                }
            },
            complete: function () {
                $('.sendBtnsTS').prop('disabled', false).text(act);
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }

    $(document).on('click', '.sendBtnsTSF', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            check = $('#ASFcheck').val(),
            act = $('.sendBtnsTSF').text();
        if (check != '' && url != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'check='+check,
                datatype: 'json',
                beforeSend: function () {
                    $('.sendBtnsTSF').text('Loading ...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts == 0) {
                        $('.AContentChange').addClass('none');
                        $('.ASTValid').removeClass('none').html(json.contenu);
                    } else {
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                    }
                },
                complete: function () {
                    $('.sendBtnsTSF').prop('disabled', false).text(act);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        } else {
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
        }
    });

    $(document).on('click', '.ASTBack', function (e) {
        e.preventDefault();
        $('.AContentChange').removeClass('none');
        $('.ASTValid').addClass('none').html('');
    });


});