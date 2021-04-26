$(document).ready(function(){
    // Teste de geolocalização
    //navigator.geolocation.getCurrentPosition(show_map);
    $(".btn-remover-plantao").click(function(){
        plantao_id = $(this).attr('escala');
		Swal.fire({
            title: 'Tem certeza que deseja remover esse plantão?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Confirmar`,
            denyButtonText: `Deu ruim`,
            cancelButtonText: 'Cancelar',
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.value == true) {
                $.ajax({
                    url: '/sgc/admin/escalas/deletar_plantao/'+plantao_id,
                    method: 'post',
                    data: {
                        escala: plantao_id
                    },
                    success: function(responseData){
                        swal(
                        'Sucesso!', 
                        'Escala Publicada com Sucesso', 
                        'success');
                    },
                    error: function(responseData){
                        swal(
                        'Sucesso!', 
                        'Plantão deletado com Sucesso', 
                        'success');
                    }
                })
            } else {
              //Swal.fire('Deu ruim', plantao_id, 'info')
            }
          })
	});
    
    $(".btn-publicar-escala").click(function(){
        var unidade = $(this).attr('unidade');
        var data_ini = $(this).attr('data_ini');
        var data_fim = $(this).attr('data_fim');
        var turno = $(this).attr('turno');
        var setor = $(this).attr('setor');
        var vinculo = $(this).attr('vinculo');
        $.ajax({
            url: '/sgc/admin/escalas/publicar_escala/1',
            method: 'post',
            data: {
                unidade: unidade,
                data_ini: data_ini,
                data_fim: data_fim,
                turno: turno,
                setor: setor,
                vinculo: vinculo
            },
            success: function(responseData){
                swal(
                'Sucesso!', 
                'Escala Publicada com Sucesso', 
                'success');
            },
            error: function(responseData){
                swal(
                'Erro!', 
                'Erro ao publicar escala', 
                'error');
            }
        })
    });

    $(".btn-despublicar-escala").click(function(){
        var unidade = $(this).attr('unidade');
        var data_ini = $(this).attr('data_ini');
        var data_fim = $(this).attr('data_fim');
        var turno = $(this).attr('turno');
        var setor = $(this).attr('setor');
        var vinculo = $(this).attr('vinculo');
        $.ajax({
            url: '/sgc/admin/escalas/publicar_escala/0',
            method: 'post',
            data: {
                unidade: unidade,
                data_ini: data_ini,
                data_fim: data_fim,
                turno: turno,
                setor: setor,
                vinculo: vinculo
            },
            success: function(responseData){
                swal(
                'Sucesso!', 
                'Escala Despublicada com Sucesso', 
                'success');
            },
            error: function(responseData){
                swal(
                'Erro!', 
                'Erro ao Despublicar escala', 
                'error');
            }
        })
    });
    
    $(document).on('change', '#profissional_id', function() {
        var profissional = $(this).val();
        var escala = $(this).next('input').val();
        var hora_ini = $(this).prev('input').val();
        var data_ini = document.getElementById("data_plantao_"+escala).value;
        var url = '/sgc/admin/escalas/atribuirescala/';
        var sucess = false;
        // swal('Erro', 'escala - '+escala+' horaIN - '+hora_ini + 'Data - ' + data_ini ,'error');
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
                            /*$('#row_id_' + escala).fadeOut('slow', 
                            function(here){ 
                                $('#row_id_' + escala).remove();
                            });*/
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
                        /*$('#row_id_' + escala).fadeOut('slow', 
                        function(here){ 
                            $('#row_id_' + escala).remove();
                        });*/
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

/*
function show_map(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log(latitude);
    console.log(longitude);
  }*/