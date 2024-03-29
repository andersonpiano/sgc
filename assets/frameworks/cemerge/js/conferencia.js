$(document).ready(function(){

	// EXIBIR MODAIS
	$(".btn-batidas-ignoradas").click(function(){
		$("#modal_jade").modal();
		clearErrors();
		active_btn_jade();	
		
		var dt_jade = $("#dt_jade").DataTable({
			"oLanguage": DATATABLE_PTBR,
			"autoWidth": false,
			"processing": true,
			"serverSide": true,
			"order": [[ 0, "desc" ]],
			dom: 'Brt',		
			"ajax": {
				"url": "/sgc/admin/escalas/ajax_listar_batidas_ignoradas",
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
	});

	$(".btn-batidas-aceitar").click(function(){
		$("#modal_aceitar_batida").modal();
		clearErrors();
		escala = $(this).attr('escala');
		frequencia = $(this).attr('frequencia');
		profissional = $(this).attr('profissional');
		

		$(".btn-aceitar").click(function(){
			batida = document.getElementById('tipobatida_aceitar').value;
			//swal(escala, frequencia + ' ' + batida,'sucess');
			$.ajax({
				url: "/sgc/admin/escalas/corrigirfrequenciaescalatipobatida/",
				method: 'post',
				data: {
					'escala_id': escala,
					'frequencia_id': frequencia,
					'profissional_id': profissional,
					'tipobatida': batida
				},
				success: function(response) {
					clearErrors();
					swal("Sucesso!", 'Batida registrada!','success');
					$("#modal_aceitar_batida").modal("hide");
				},
				error: function(response){
					clearErrors();
					$("#modal_jade").modal("hide");
					swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
				}
			})
		});

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

})
