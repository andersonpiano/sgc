$(document).ready(function(){
	$(".btn-excluir-frequencia").click(function(){
		var frequencia = $(this).attr('frequencia');
		$.ajax({
			type: "POST",
			url: "/sgc/admin/frequencias/deletar_frequencia/",
			dataType: 'json',
			data: {
				frequencia: frequencia,
			},
			success: function(response) {
				if(response.sucess == true){
					Swal.fire({
						title: 'Sucesso!',
						text: 'Frequência deletada com sucesso!',
						type: 'success',
						showDenyButton: false,
						showCancelButton: false,
					  }).then((result) => {
						/* Read more about isConfirmed, isDenied below */
						if (result.value) {
							document.location.reload(true);
						}
					})
				} else {
					swal("Erro", "Não foi possivel deletar essa frequência", 'error');
				}
			},
			error: function(response){
				swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
			}
		})
	});

	$(".gera-arquivo").click(function(){
		unidade = document.getElementById('unidadehospitalar_id').value;
		setor = document.getElementById('setor_id').value;
		datainicial = document.getElementById('datainicial').value;
		datafinal = document.getElementById('datafinal').value;
		profissional = document.getElementById('profissional_id').value;
		if (profissional == ''){
			profissional = 0;
		}
		if (setor == ''){
			setor = 0;
		}

		if(unidade == ''){
			swal('Aviso', 'Ops, você não selecionou a unidade hospitalar', 'info');
		} else {
			window.location.href='/sgc/admin/escalas/exportaFolha/'+unidade+'/'+setor+'/'+datainicial+'/'+datafinal+'/'+profissional;
		}
	});
})
