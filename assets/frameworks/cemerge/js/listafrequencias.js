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
})
