/**
 * Created by Ndjeunou on 03/08/2017.
 */
$(document).ready(function(){
    DateRanges('debut','end');
    $(document).on('click','.new', function (e) {
        e.preventDefault();
        $('#mois').val('');
        $('#cout').val('');
        $('.newModal').modal({backdrop: 'static'});
    });
    $(document).on('submit', '#newForm', function (e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            cout = $('#cout').val(),
            mois = $('#mois').val(),
            act = $('.newBtn').text();
        if (mois != '' && url != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'cout='+cout+'&mois='+mois,
                datatype: 'json',
                beforeSend: function () {
                    $('.newBtn').text('Chargement...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts == 0) {
                        window.location.reload();
                        $('.newModal').modal('hide');
                    } else {
                        toastr.error(json.mes,'Oups!');
                    }
                },
                complete: function () {
                    $('.newBtn').text(act).prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {}
            });
        } else {
            toastr.error("Renseigner tous les champs requis",'Oups!');
        }
    });
    $(document).on('click', '.valid', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id'),
            title = $(this).data('title'),
            btn = $(this);
        alertify.prompt( '<h3 style="margin: 0">Valider le paiement de '+title+' de license</h3>', '<b>Entrer le prix arrêté de cette license</b>', '',
            function(evt, value) {
                $.ajax({
                    type: 'post',
                    url: url,
                    data: 'id='+id+'&prix='+value,
                    datatype: 'json',
                    beforeSend: function(){
                        $('.ajs-input').prop('disabled',true);
                        $('.ajs-ok').text('Chargement...').prop('disabled',true);
                        btn.prop('disabled',true);
                        $('.ajs-content .aMes').remove();
                    },
                    success: function (json) {
                        if(json.statuts == 0){
                            alertify.closeAll();
                            window.location.reload();
                        }else{
                            $('.ajs-content p').after('<p class="aMes alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-warning"></i></strong> '+json.mes+'</p>');
                            alertify.error(json.mes);
                        }
                    },
                    complete: function(){
                        $('.ajs-input').prop('disabled',false);
                        $('.ajs-ok').text('OK').prop('disabled',false);
                        btn.prop('disabled',false);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        btn.prop('disabled',false);
                    }
                });
                return false;
            },
            function() {
                alertify.error('Vous avez annulé le paiement de la license');
            }).setting({
            type:'number'
        }).show().set({onshow:function(){ $('.ajs-content .aMes').remove();}})
    });

});