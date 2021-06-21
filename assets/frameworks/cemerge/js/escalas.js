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

$(".btn-remover-medico").click(function(){
    //$("#modal_excluir_profissional").modal();
    //clearErrors();
    Swal.fire({
        title: 'Informe o motivo da exclusão',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Remover',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: (motivo) => {
            escala = $(this).attr('escala');
            profissional = $(this).attr('profissional');
            $.ajax({
                url: '/sgc/admin/logs/registro/',
                method: 'post',
            data: {
                id: escala,
                tabela : 'escalas - id=' + escala,
                motivo : motivo,
                campo_alterado : 'profissional_id = '+profissional+' - Deletado'
                },
            success: function(responseData) {
                $.ajax({
                    url: '/sgc/admin/escalas/remover_medico/',
                    method: 'post',
                    type: 'json',
                data: {
                    escala: escala,
                    },
                success: function(responseData){
                    if (JSON.parse(responseData).sucess){
                        /*swal({
                            icon: 'success',
                            title: "Sucesso",
                            text: 'Médico Excluido com sucesso!',
                            showCancelButton: false,
                            confirmButtonText: "OK"
                            //cancelButtonText: "No",
                        }, function (isConfirm) {
                            if (isConfirm) {
                                document.location.reload(true)
                            }
                            document.location.reload(true)
                        })*/
                    swal('Sucesso','Médico Excluido com sucesso','success');
                    //await new Promise(r => setTimeout(r, 2000));
                    //document.location.reload(true);
                    } else {
                        swal('Erro','Este plantão foi recebido por cessão/troca e não pode ser removido aqui.','error');
                    }					
                },
                error: function(){
                    swal('Erro','Este plantão foi recebido por cessão/troca e não pode ser removido aqui.','error');
                }
                
                })
                //
                },
            error: function(responseData){
                swal('Erro','Log não pode ser gravado.','error');
                }
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        /*if (result.success) {
          Swal.fire({
            icon: 'sucess',
            title: 'Parabéns',
            text: 'Médico Excluido com sucesso',
            footer: 'Em caso de dúvidas contactar o Gestor de Ti'
          })
        }*/
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

            //swal('sucesso', $vinculo);

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
if ('#tipos_plantao'){
    $(document).on('change', '#tipos_plantao', function() {
        var tipo = $(this).val();
        var escala = $(this).attr('escala');
        var url = '/sgc/admin/escalas/troca_tipo_plantao';

        // swal('Erro', 'escala - '+escala+' horaIN - '+hora_ini + 'Data - ' + data_ini ,'error');
        $.ajax({
            url: url,
            method: 'post',
            data: {
                escala : escala,
                tipo : tipo
            },
            success: function(responseData) {
                // Se sucesso, remover ou travar o dropdown

                $sucess = JSON.parse(responseData).sucesso;


                if($sucess == 'certo'){
                    swal("Sucesso","Troca realizada com sucesso","success");
                } else {
                    swal("Erro!","Ocorreu um erro na troca.", 'error');

                };  
            },
            error: function(responseData) {
                swal("Sucesso","Troca realizada com sucesso","success");
                console.log(responseData);
            }
        }); 
    });
}


});

/*
function show_map(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log(latitude);
    console.log(longitude);
  }*/