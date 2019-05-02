function modalVerify(params) {
    UIkit.modal.prompt('Tape your password:', '', function(val){
        if(val!=''){
            $.ajax({
                type: 'post',
                url: "http://sesame.dev/compte/check",
                data: 'pass=' + val,
                datatype: 'json',
                beforeSend: function () {},
                success: function (json) {
                    if (json.statuts == 0) {
                        params();
                    } else {
                        UIkit.notify({message:json.mes,status:'danger',timeout : 5000,pos:'bottom-center'});
                    }
                },
                complete: function () {},
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        }else{
            UIkit.notify({message:"A error appear, please reload",status:'danger',timeout : 5000,pos:'bottom-center'});
        }
    });
}