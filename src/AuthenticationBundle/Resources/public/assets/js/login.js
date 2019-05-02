/**
 * Created by Ndjeunou on 03/08/2017.
 */
$(document).ready(function(){
    $(document).on('submit','#loginForm', function (e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            login = $('#Login').val(),
            password = $('#password').val();
        if(login != '' && password != ''){
            $.ajax({
                type: 'post',
                url: url,
                data: 'Login='+login+'&password='+password,
                datatype: 'json',
                beforeSend: function () {
                    $('#Login').prop('disabled',true);
                    $('#password').prop('disabled',true);
                    $('.sendBtn').text('Chargement ...').prop('disabled',true);
                },
                success: function (json) {
                    if(json.statuts == 0){
                        window.location.assign(json.direct);
                    }else{
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: "toast-top-center",
                            showMethod: 'fadeIn',
                            hideMethod: 'fadeOut',
                            timeOut: 3000
                        };
                        toastr.error(json.mes,'Oups!');
                    }
                },
                complete: function () {
                    $('#Login').prop('disabled',false);
                    $('#password').prop('disabled',false);
                    $('.sendBtn').text('Connexion').prop('disabled',false);
                },
                error: function(jqXHR, textStatus, errorThrown){
                }
            });
        }else{
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-center",
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                timeOut: 3000
            };
            toastr.error('Renseigner tous les champs requis','Oups!');
        }
    });
});