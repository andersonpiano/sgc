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
						swal('Sucesso','Médico Excluido com sucesso','success');
						location.reload();
						} else {
							swal('Erro','Este plantão foi recebido por cessão/troca e não pode ser removido aqui.','error');
						}					
					},
					error: function(){
						swal('Erro','Este plantão foi recebido por cessão/troca e não pode ser removido aqui.','error');
					}
					})
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
