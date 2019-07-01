$(document).ready(function() {

    $('#detail').ckeditor(function() {},
        {
            customConfig: '../../assets/js/custom/ckeditor_config.js'
        });
    $(document).on('click','.deleteProduct', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Êtes vous sûr de vouloir supprimer le producit "+$(this).data('name')+" ?";
        UIkit.modal.confirm(mess, function(){
            if(url!=''&&id!=''){
                $.ajax({
                    type: 'post',
                    url: url,
                    data: 'id='+id,
                    datatype: 'json',
                    beforeSend: function () {},
                    success: function (json) {
                        if (json.status == 0) {
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

    $(document).on('submit', '#formProduct', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var data = $form.serialize();
        var name = $('#name').val(),
            act = $('.sendBtn').text();
        if (name != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                datatype: 'json',
                beforeSend: function () {
                    $('.sendBtn').text('CHARGEMENT ...').prop('disabled', true);
                },
                success: function (json) {
                    if (json.status == 0) {
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

                }
            });

        } else {
            $('#messageformSalle').html("<div class='uk-alert uk-alert-danger uk-text-center' data-uk-alert=''><a href='' class='uk-alert-close uk-close'></a><span class='alertJss'>Renseignez le nom du produit</span></div>");
            UIkit.notify({message:"Renseignez les différents champs",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });

    $(document).on('click','#addProduct', function (e) {
        e.preventDefault();
        $('#name').val('');
        $('#description').val('');
        $('#id').val('');
        $('.sendBtn').text("Ajouter");
        $('.titleForm').text("Ajouter un nouveau produit");
        UIkit.modal('#modalProduct').show();
    });

    $(document).on('click','.editProduct', function (e) {
        e.preventDefault();
        var description = $(this).data('description'),
            name = $(this).data('name'),
            id = $(this).data('id');
        $('#description').val(description);
        $('#name').val(name);
        $('#id').val(id);
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("Modifier le produit");
        $('.sendBtn').text("Modifier");
        UIkit.modal('#modalProduct').show();
    });
});