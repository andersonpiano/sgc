$(document).ready(function(){
    // Teste de geolocalização
    //navigator.geolocation.getCurrentPosition(show_map);

    


    $(document).on('change', '#profissional_id', function() {
        var profissional = $(this).val();
        var escala = $(this).next('input').val();
        var hora_ini = $(this).prev('input').val();
        var data_ini = document.getElementById("data_plantao_"+escala).value;
        var url = '/sgc/admin/escalas/atribuirescala/';
        var sucess = false;
        $.ajax({
            url: url,
            method: 'post',
            data: {
                profissional : profissional,
                escala : escala,
                data_ini : data_ini,
                hora_ini : hora_ini
            },
            success: function(responseData) {
                // Se sucesso, remover ou travar o dropdown

                $sucess = JSON.parse(responseData).sucess;
                $vinculo = JSON.parse(responseData).vinculo;

                if($vinculo == '3'){
                    $("#modal_vinculo").modal();
                    $('.btn-vinculo').click(function(){
                        $("#modal_vinculo").modal("hide");
                        if ($sucess == true){
                            $('#row_id_' + escala).fadeOut('slow', 
                            function(here){ 
                                $('#row_id_' + escala).remove();
                            });
                            $.ajax({
                                url: '/sgc/admin/escalas/troca_vinculo_escala/',
                                method: 'post',
                                data: {
                                    vinculo_id : document.getElementById('vinculos_atribuir').value,
                                    escala : escala
                                },
                            success: function(responseData) {
                            swal("Sucesso","Troca realizada com sucesso","success");
                            }, 
                            error: function(responseData){
                                swal("Erro!","Erro ao atribuir vinculo.", 'error');
                            }
                        });
                        } else {
                            swal("Erro!","Este plantão já foi repassado a outro profissional ou profissional selecionado ja está escalado em outro plantão.", 'error');
                            //document.getElementById('profissional_id').value = JSON.parse(responseData).profissional;
                            //console.log($('[name="escala_id_' + escala + '"]').prev('select').val());
                            selectProfissional = $('[name="escala_id_' + escala + '"]').prev('select');
                            selectProfissional.val(JSON.parse(responseData).profissional_ant);
                        };
                    });
                } else {
                    if ($sucess == true){
                        $('#row_id_' + escala).fadeOut('slow', 
                        function(here){ 
                            $('#row_id_' + escala).remove();
                        });
                        swal("Sucesso","Troca realizada com sucesso","success");
                    } else {
                        swal("Erro!","Este plantão já foi repassado a outro profissional ou profissional selecionado ja está escalado em outro plantão.", 'error');
                        //document.getElementById('profissional_id').value = JSON.parse(responseData).profissional;
                        //console.log($('[name="escala_id_' + escala + '"]').prev('select').val());
                        selectProfissional = $('[name="escala_id_' + escala + '"]').prev('select');
                        selectProfissional.val(JSON.parse(responseData).profissional);
                    };
                };  
            },
            error: function(responseData) {
                swal("Erro!","Ocorreu um erro ao atribuir a escala ao profissional. Por favor, tente novamente mais tarde","error");
                console.log(responseData);
            }
        }); 
});
});



function show_map(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log(latitude);
    console.log(longitude);
  }