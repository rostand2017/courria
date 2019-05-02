/**
 * Created by Dell on 24/05/2017.
 */
$(document).ready(function() {

    $(document).on('click','.deleteProduct', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        mess = "Voulez vous vraiment supprimer cette coiffure?";
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
                UIkit.notify({message:"Une erreur est apparue, recharger",status:'danger',timeout : 5000,pos:'top-center'});
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
        var nom = $('#nom').val(),
            prix = $('#prix').val(),
            sous = $('#sousCategorie').val(),
            act = $('.sendBtn').text();
        if (nom != '' && prix != '' && sous != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                datatype: 'json',
                beforeSend: function () {
                    $('.sendBtn').text('Chargement ...').prop('disabled', true);
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
                error: function (jqXHR, textStatus, errorThrown) {}
            });

        } else {
            UIkit.notify({message:"Une erreur est apparue, recharger",status:'danger',timeout : 5000,pos:'top-center'});
        }
    });
    $(document).on('submit','#filtre',function (e) {
        var prixMax=$("#prixMax").val();
        var prixMin=$("#prixMin").val();
        if(eval(prixMax)<eval(prixMin)){
            e.preventDefault();
            UIkit.notify({message:'Le Prix Max doit etre superieur Au Prix Minimal',status:'danger',timeout : 5000,pos:'top-center'});
        }

    });
    $(document).on('click','#addCustomer', function (e) {
        e.preventDefault();
        $('#nom').val('');
        $('#prix').val('');
        $('#categorie').val('');
        $('#sousCategorie').val('');
        $('#description').val('');
        $('#idCustomer').val('');
        $('#action').val('add');
        $('#pictureContent').show();
        $('.titleForm').text("AJOUTER UNE NOUVELLE COIFFURE");
        $('.sendBtn').text("AJOUTER");
        UIkit.modal('#modalCustomer').show();
    });
    $(document).on('click','.editProduct', function (e) {
        e.preventDefault();
        var nom = $(this).data('nom'),
            prix = $(this).data('prix'),
            idSous = $('#sousCategorie'),
            detail = $(this).data('detail'),
            categorie = $(this).data('categorie'),
            sousCategorie = $(this).data('souscategorie'),
            id = $(this).data('id');
        $('#nom').val(nom);
        $('#prix').val(prix);
        $('#description').val(detail);
        $('#categorie').val(categorie);
        load("http://coiffure.dev/admin/coiffure/ajax",categorie,"Choisir la sous catégorie",idSous,sousCategorie);
        $('#idCustomer').val(id);
        $('#action').val('edit');
        $('#pictureContent').hide();
        $('.md-input-wrapper').addClass('md-input-focus');
        $('.titleForm').text("MODIFIER UNE COIFFURE");
        $('.sendBtn').text("MODIFIER");
        UIkit.modal('#modalCustomer').show();
    });
    $(document).on('change','#categorie',function (e) {
        var id = $(this).val(),
            url = $(this).data("url"),
            idSousCategorie = $("#sousCategorie");
        load(url,id,"Choisir la sous catégorie",idSousCategorie,"");
    });
    $(document).on('change','#categorieS',function (e) {
        var id = $(this).val(),
            url = $(this).data("url"),
            idSousCategorie = $("#sousCategorieS");
        load(url,id,"Chercher par sous catégorie",idSousCategorie,"");
    });
    function load(url,id,option,idSousCategorie,isDefault){
        $.ajax({
            type: 'post',
            url: url,
            data: "id="+id+"&mes="+option,
            datatype: 'json',
            beforeSend: function () {
                idSousCategorie.html('');
            },
            success: function (json) {
                if (json.statuts == 0){
                    idSousCategorie.html(json.contenu);
                    if(isDefault != ""){
                        idSousCategorie.val(isDefault);
                    }
                }else{
                }
            },
            complete: function () {

            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    }

    $(document).on('change','#prixMax',function (e) {
        var valMax = $(this).val(),
            valMin = $('#prixMin').val();
        if(valMin != ''){
            if(valMin>valMax){
                $(this).val(valMin);
            }
        }
    });

    $(document).on('change','#prixMin',function (e) {
        var valMin = $(this).val(),
            valMax = $('#prixMax').val();
        if(valMax != ''){
            if(valMin>valMax){
                $(this).val(valMax);
            }
        }
    });

});