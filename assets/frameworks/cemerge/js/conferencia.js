$(function(){

	// EXIBIR MODAIS
	$(".btn-batidas-ignoradas").click(function(){
		$("#modal_jade").modal();
		clearErrors();
		active_btn_jade();	
	});

	$(".btn-remover-medico").click(function(){
		//$("#modal_excluir_profissional").modal();
		clearErrors();
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
			preConfirm: (login) => {
			  return fetch(`//api.github.com/users/${login}`)
				.then(response => {
				  if (!response.ok) {
					throw new Error(response.statusText)
				  }
				  return response.json()
				})
				.catch(error => {
				  Swal.showValidationMessage(
					`Requisição falhou: ${error}`
				  )
				})
			},
			allowOutsideClick: () => !Swal.isLoading()
		  }).then((result) => {
			if (result.isConfirmed) {
			  Swal.fire({
				title: `${result.value.login}'s avatar`,
				imageUrl: result.value.avatar_url
			  })
			}
		  })
		/*
		Swal.fire({
			title: 'Tem Certeza?',
			text: "Não será possivel reverter essa ação!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Remover',
			cancelButtonText: 'Cancelar'
		  }).then((result) => {
			if (result.isConfirmed) {
			  Swal.fire(
				'Deletado!',
				'Médico Deletado com sucesso.',
				'success'
			  )
			}
		  })*/
	});

	function active_btn_jade() {
		
		$(".btn-reverter-batida-ignorada").click(function(){
			$.ajax({
				url: "desfazerignorarbatida/"+$(this).attr('id'),
				success: function(response) {
					clearErrors();
					swal("Sucesso!", 'Batida Restaurada!','success');
					$("#modal_jade").modal("hide");
					dt_jade.ajax.reload()
				},
				error: function(response){
					clearErrors();
					$("#modal_jade").modal("hide");
					swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
				}
			})
		});
	}

	var dt_jade = $("#dt_jade").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"order": [[ 0, "desc" ]],
		dom: 'Brt',		
		"ajax": {
			"url": "ajax_listar_batidas_ignoradas",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_jade();
		},
		select: true,
	});

})
