$(function() {

	// EXIBIR MODAIS
	$("#btn_add_profissional").click(function(){
		clearErrors();
		$("#form_profissional")[0].reset();
		$("#modal_profissional").modal();
	});

	$("#btn_add_evento").click(function(){
		clearErrors();
		$("#form_evento")[0].reset();
		$("#modal_evento").modal();
	});

	$("#btn_add_folha").click(function(){
		clearErrors();
		$("#form_folha")[0].reset();
		$("#modal_folha").modal();
	});

	$("#profissional_upload_img").change(function(){
		uploadImg($(this), $("#profissional_img_path"), $("#profissional_img"));
	});

	$("#form_profissional").submit(function() {
 			$.ajax({
			type: "POST",
			url: 'fopag/cadastrar_profissional/',
			dataType: "JSON",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_profissional").siblings(".help-block").html(loadingImg("Cadastrando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_profissional").modal("hide");
					swal("Sucesso!","profissional salva com sucesso!", "success");
					dt_profissional.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			},
			error: function(response){
				$("#modal_profissional").modal("hide");
				swal("Erro!","Erro ao Salvar profissional!", "warning");
				dt_profissional.ajax.reload();
			}
		})

		return false;
	});

	$("#form_folha").submit(function() {
		$.ajax({
	   type: "POST",
	   url: 'fopag/cadastrar_folha/',
	   dataType: "JSON",
	   data: $(this).serialize(),
	   beforeSend: function() {
		   clearErrors();
		   $("#btn_save_folha").siblings(".help-block").html(loadingImg("Cadastrando..."));
	   },
	   success: function(response) {
		   clearErrors();
		   if (response["status"]) {
			   $("#modal_folha").modal("hide");
			   swal("Sucesso!","folha salva com sucesso!", "success");
			   dt_folha.ajax.reload();
		   } else {
			   showErrorsModal(response["error_list"])
		   }
	   },
	   error: function(response){
		   $("#modal_folha").modal("hide");
		   swal("Perfeito!","Folha Gerada com Sucesso!", "success");
		   dt_folha.ajax.reload();
	   }
   })

   return false;
});

$("#form_lancador_profissional").submit(function() {

	var dt_eventos_profissional = $("#dt_eventos_profissional").DataTable({

		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		dom: 'Brt',
        buttons: [
			{
                extend: 'excelHtml5',
                messageTop: 'Teste.',
				text: 'Teste'
            },
			{
				extend: 'print',
				title: '',
				messageTop: '<p style="text-align: center;"><img src="/sgc/assets/frameworks/cemerge/images/logo.png" alt="Cemerge" /></p> <table style="margin-left: auto; margin-right: auto; width: 100%; height: 100px;"> 	<tbody> 		<tr> 			<td colspan="2"><center><strong><span style="font-size: 12pt;">CEMERGE - Cooperativa dos Médicos Emergencistas do Estado do Ceará</span></strong></center></td> 		</tr> 		<tr> 			<td style="text-align: left;"><strong> Matricula: 00001</strong></td> 			<td style="text-align: right;"><strong>RECIBO DE PAGAMENTOS</strong></td> 		</tr> 		<tr> 			<td style="text-align: left;"><strong>FUNCIONARIO: ANDERSON DE SOUSA PEREIRA</strong></td> 			<td style="text-align: right;"><strong>REFERÊNCIA: JANEIRO/2021</strong></td> 		</tr> 	</tbody> </table>',
                text: '  Contra Cheque',
				messageBottom: '<table style="margin: 15px 0 40px; width: 99%; height: 30px;"> 	<tbody> 		<tr> 			<td><strong>&nbsp;TOTAL DE PROVENTOS: R$ 5.000,00</strong></td> 			<td style="text-align: right;"><strong>TOTAL DE DESCONTOS: R$ 500,00</strong></td> 			<td style="text-align: right;"><strong>VALOR LIQUIDO: R$ 4.500,00  </strong></td> 		</tr> 	</tbody> </table>',
				className: 'btn btn-success text-center fa fa-print fa-2x',
				exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }

            },
			{
				extend: 'print',
				title: '',
                text: '  Mensagem',
				messageBottom: '<center>   <table style="margin: 45% 0 0; border: 1px solid black; border-radius: 30px; width: 80%; height: 60px;"> 		<tbody> 			<tr> 				<td>   					<label><strong>&nbsp;&nbsp;MATRICULA: 0001</strong></label>   						<br>   						<span><strong>&nbsp;&nbsp;Anderson de Sousa Pereira</strong></span> 			  </td> 		  </tr> 	</tbody>   </table> 	<table style="margin: 10px 0 0; border: 1px solid black; border-radius: 30px; width: 80%; height: 144px;"> 		<tbody> 			<tr> 				<td> 					<p style="text-align: center;"><span style="font-size: 12pt;">Existem observações no seu Contra Cheque</span></p> 					<p style="text-align: center;"><span style="font-size: 12pt;">* OBS1: Teste de Mensagem para o cooperado</span></p> 					<p style="text-align: center;"><span style="font-size: 12pt;">* OBS2: Teste de Mensagem para o cooperado</span></p> 					<p style="text-align: center;"><span style="font-size: 12pt;">* OBS3: Teste de Mensagem para o cooperado</span></p> 				</td> 			</tr> 		</tbody> 	</table> </center>',
				className: 'btn btn-success text-center fa fa-print fa-2x',
				exportOptions: {
                    columns: [ ]
                }

            }
        ],	
		"ajax": {
			"url": "fopag/ajax_listar_folhas_profissional",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_profissional();
		},
		select: true,
	});

	$.ajax({
   type: "POST",
   url: 'fopag/ajax_listar_folhas_profissional',
   dataType: "JSON",
   data: $(this).serialize(),
   beforeSend: function() {
	   clearErrors();
	   $("#btn_lancador").siblings(".help-block").html(loadingImg("Carregando..."));
   },
   success: function(response) {
	   clearErrors();
	   if (response["status"]) {
		   $("#modal_lancador_profissional").modal("hide");
			clearErrors();
			$("#form_jade")[0].reset();
			$("#modal_jade").modal();
		   //swal("Sucesso!","Deu certo!", "success");
		   dt_eventos_profissional.reload();
	   } else {
		   showErrorsModal(response["error_list"])
	   }
   },
   error: function(response){
	clearErrors();
	if (response["status"]) {
		$("#modal_lancador_profissional").modal("hide");
		swal("Erro!","Algo deu errado!", "error");
		dt_eventos_profissional.reload();
	} else {
		showErrorsModal(response["error_list"])
	}
   }
	})

	return false;
});

	function active_btn_profissional() {
		
		$(".btn-edit-profissional").click(function(){
			$.ajax({
				type: "POST",
				url: "fopag/ajax_get_profissional_data",
				dataType: 'json',
				data: {"id": $(this).attr('id')},
				success: function(response) {
					clearErrors();
					$("#form_profissional")[0].reset();
					$.each(response["input"], 
					function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_profissional").modal();
				},
				error: function(response){
					swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
				}
			})
		});

		$(".btn-profissional-folha").click(function(){
			clearErrors();
			$("#form_lancador_profissional")[0].reset();
			$("#modal_lancador_profissional").modal();
			document.getElementById("profissional_id").value = $(this).attr('id');
		});

		$(".btn-profissional-edit").click(function(){
			clearErrors();
			$id = $(this).attr('id');
			window.location.href = href="http://localhost/sgc/admin/profissionais/edit/"+$id;
		});

		$(".btn-del-profissional").click(function(){
			
			$id = $(this).attr('id');
			swal({
				title: "Atenção!",
				text: "Deseja deletar essa profissional?",
				type: "warning",
				showCancelButton: true,
				coprofissionalirmButtonColor: "#d9534f",
				coprofissionalirmButtonText: "Sim",
				cancelButtontext: "Não",
				closeOnCoprofissionalirm: true,
				closeOnCancel: true,
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type: "POST",
						url: 'fopag/deletar_profissional/'+$id,
						data: {"id" : $id},
						success: function(response) {
							swal("Sucesso!", "profissional removida com sucesso", "success");
							dt_profissional.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
						}
					})
				}
			})

		});
	}

	var dt_profissional = $("#dt_profissional").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,		
		"ajax": {
			"url": "fopag/ajax_listar_profissionais",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_profissional();
		},
		select: true,
	});

	

	var dt_folha = $("#dt_folha").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,		
		"ajax": {
			"url": "fopag/ajax_listar_folhas",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_folha();
		},
		select: true,
	});

	$("#form_evento").submit(function() {
		$.ajax({
	   type: "POST",
	   url: 'fopag/cadastrar_evento/',
	   dataType: "JSON",
	   data: $(this).serialize(),
	   beforeSend: function() {
		   clearErrors();
		   $("#btn_save_evento").siblings(".help-block").html(loadingImg("Cadastrando..."));
	   },
	   success: function(response) {
		   clearErrors();
		   if (response["status"]) {
			   $("#modal_evento").modal("hide");
			   swal("Sucesso!","evento salva com sucesso!", "success");
			   dt_eventos.ajax.reload();
		   } else {
			   showErrorsModal(response["error_list"])
		   }
	   },
	   error: function(response){
		   $("#modal_evento").modal("hide");
		   swal("Erro!","Erro ao Salvar evento!", "warning");
		   dt_eventos.ajax.reload();
	   }
   })

   return false;
});

function active_btn_folha() {
   
	$(".btn-edit-folha").click(function(){
		$.ajax({
			type: "POST",
			url: "fopag/ajax_get_folha_data",
			dataType: 'json',
			data: {"id": $(this).attr('id')},
			success: function(response) {
				clearErrors();
				$("#form_folha")[0].reset();
				$.each(response["input"], 
				function(id, value) {
					$("#"+id).val(value);
				});
				$("#modal_folha").modal();
			},
			error: function(response){
				swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
			}
		})
	});
 
	$(".btn-del-folha").click(function(){
		
		$id = $(this).attr('id');
		swal({
			title: "Atenção!",
			text: "Deseja deletar essa folha?",
			type: "warning",
			showCancelButton: true,
			cofolhairmButtonColor: "#d9534f",
			cofolhairmButtonText: "Sim",
			cancelButtontext: "Não",
			closeOnCofolhairm: true,
			closeOnCancel: true,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: "POST",
					url: 'fopag/deletar_folha/'+$id,
					data: {"id" : $id},
					success: function(response) {
						swal("Sucesso!", "folha removida com sucesso", "success");
						dt_folhas.ajax.reload();
					},
					error: function(response){
						swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
					}
				})
			}
		})
 
	});
 }

function active_btn_evento() {
   
   $(".btn-edit-evento").click(function(){
	   $.ajax({
		   type: "POST",
		   url: "fopag/ajax_get_evento_data",
		   dataType: 'json',
		   data: {"id": $(this).attr('id')},
		   success: function(response) {
			   clearErrors();
			   $("#form_evento")[0].reset();
			   $.each(response["input"], 
			   function(id, value) {
				   $("#"+id).val(value);
			   });
			   $("#modal_evento").modal();
		   },
		   error: function(response){
			   swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
		   }
	   })
   });

   $(".btn-del-evento").click(function(){
	   
	   $id = $(this).attr('id');
	   swal({
		   title: "Atenção!",
		   text: "Deseja deletar essa evento?",
		   type: "warning",
		   showCancelButton: true,
		   coeventoirmButtonColor: "#d9534f",
		   coeventoirmButtonText: "Sim",
		   cancelButtontext: "Não",
		   closeOnCoeventoirm: true,
		   closeOnCancel: true,
	   }).then((result) => {
		   if (result.value) {
			   $.ajax({
				   type: "POST",
				   url: 'fopag/deletar_evento/'+$id,
				   data: {"id" : $id},
				   success: function(response) {
					   swal("Sucesso!", "evento removida com sucesso", "success");
					   dt_eventos.ajax.reload();
				   },
				   error: function(response){
					   swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
				   }
			   })
		   }
	   })

   });
}

var dt_eventos = $("#dt_eventos").DataTable({
   "oLanguage": DATATABLE_PTBR,
   "autoWidth": false,
   "processing": true,
   "serverSide": true,
   "ajax": {
	   "url": "fopag/ajax_listar_eventos",
	   "method": "POST",
   },
   "columnDefs": [
	   { targets: "no-sort", orderable: false },
	   { targets: "dt-center", className: "dt-center" },
   ],
   "drawCallback": function() {
	   active_btn_evento();
   }
});
})
