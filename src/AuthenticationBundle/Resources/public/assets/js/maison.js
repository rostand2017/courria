
$(document).ready(function(){
    $(document).on('submit', '#newForm', function (e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            act = $('.newBtn').text();
        form = $(this);
        data = window.FormData? new FormData(form[0]):$(this).serialize();

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            datatype: 'json',
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.newBtn').text('Chargement...').prop('disabled', true);
            },
            success: function (json) {
                if (json.status == 0) {
                    $('.newModal').modal('hide');
                    window.location.reload();
                }else{
                    toastr.error(json.mes,'Oups!');
                }
            },
            complete: function () {
                $('.newBtn').text(act).prop('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {}
        });
    });
    $(document).on('click','.edit', function (e) {
        e.preventDefault();
        var description = $(this).data('description'),
            price = $(this).data('price'),
            name = $(this).data('name'),
            id = $(this).data('id');
        $('#image1').val('');
        $('#image2').val('');
        $('#image3').val('');
        $('#plan').val('');
        $('#description').val(description);
        $('#name').val(name);
        $('#price').val(price);
        $('#idElement').val(id);
        $('.titleForm').text("MODIFIER UNE MAISON");
        $('.newBtn').text("MODIFIER");
        $('.newModal').modal({backdrop: 'static'});
    });
    $(document).on('click','.new', function (e) {
        e.preventDefault();
        $('#name').val('');
        $('#description').val('');
        $('#price').val('');
        $('#idElement').val('');
        $('#image1').val('');
        $('#image2').val('');
        $('#image3').val('');
        $('#plan').val('');
        $('#action').val('add');
        $('.titleForm').text("NOUVELLE MAISON");
        $('.newBtn').text("AJOUTER");
        $('.newModal').modal({backdrop: 'static'});
    });

    $(document).on('click','.delete', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        swal({
                title: "Etes vous sûr?",
                text: "La maison sera supprimée définitivement",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#46DDC6",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        datatype: 'json',
                        success: function (json) {
                            if (json.status == 0) {
                                toastr.success(json.mes,'Succès!');
                                window.location.reload();
                            } else {
                                toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){}
                    });
                }
            });
    });

});