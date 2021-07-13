$(document).ready(function(){

		
	$(".btn-justificativas-pendentes").click(function(){
		
		//var setor = $(this).attr('id');
		$("#modal_justificativas_pendentes").modal();
		
		
	});
	$(".btn-justificativas-view").click(function(){
		document.getElementById("sumir").style.display = 'none';
		
		var justificativa = $(this).attr('justificativa');
        $.ajax({
            url: "/sgc/admin/justificativas/justificativa_view",
            method: 'post',
            data: {
                justificativa : justificativa,
            },
            success: function(responseData) {
                // Se sucesso, remover ou travar o dropdown
                $profissional = JSON.parse(responseData).medico;
				$setor = JSON.parse(responseData).setor;
				$data = JSON.parse(responseData).data;
				$turno = JSON.parse(responseData).turno;
				$entrada_sistema = JSON.parse(responseData).entrada_sistema;
				$saida_sistema = JSON.parse(responseData).saida_sistema;
				$entrada_justificada = JSON.parse(responseData).entrada_justificada;
				$saida_justificada = JSON.parse(responseData).saida_justificada;
				$descricao = JSON.parse(responseData).descricao;
				$status = JSON.parse(responseData).status;
				$motivo = JSON.parse(responseData).motivo;

				//console.log(responseData);
				document.getElementById('nome_profissional').innerText = $profissional;
				document.getElementById('nome_setor').innerText = $setor;
				document.getElementById('data').innerText = $data;
				document.getElementById('turno_plantao').innerText = $turno;
				document.getElementById('hora_entrada_sistema').innerText = $entrada_sistema;
				document.getElementById('hora_saida_sistema').innerText = $saida_sistema;
				document.getElementById('hora_entrada_justificada').innerText = $entrada_justificada;
				document.getElementById('hora_saida_justificada').innerText = $saida_justificada;
				document.getElementById('descricao').innerText = $descricao;

				if($status == "0"){
					document.getElementById('condicao').innerText = 'Aguardando Aprovação';
					document.getElementById("sumir").style.display = 'none';
				} else if($status == "1"){
					document.getElementById('condicao').innerText = 'Deferida';
					document.getElementById("sumir").style.display = 'none';
				} else if ($status == "2"){
					document.getElementById('condicao').innerText = 'Indeferida';
					document.getElementById("sumir").style.display = 'block';
					document.getElementById('motivo').innerText = $motivo;
					
				} else if ($status == "4"){
				 	document.getElementById('condicao').innerText = 'Ignorada';
					 document.getElementById("sumir").style.display = 'none';
				} else {
					document.getElementById('condicao').innerText = 'Desconhecido';
					document.getElementById("sumir").style.display = 'none';
				}
				
				/*document.getElementById("aprovar").setAttribute("justificativa", justificativa);
				document.getElementById("desaprovar").setAttribute("justificativa", justificativa);
				document.getElementById("editar").setAttribute("justificativa", justificativa);
				document.getElementById("ignorar").setAttribute("justificativa", justificativa);*/
				/*document.getElementById("editar").setAttribute("href", "/sgc/admin/justificativas/edit/"+justificativa);
				document.getElementById("editar").setAttribute("justificativa", justificativa);*/
				document.getElementById("justificativa_ignorar").setAttribute("justificativa", justificativa);
				document.getElementById("justificativa_indeferir").setAttribute("justificativa", justificativa);
				document.getElementById("justificativa_edit").setAttribute("justificativa", justificativa);
				document.getElementById("justificativa_aprovar").setAttribute("justificativa", justificativa);
            },
            error: function(responseData) {
                //swal("Erro",$sucess, "error");
                console.log(responseData);
            }
        }); 

		$("#modal_justificativas_view").modal();
		
		
	});

	$(".btn-justificativas-edit").click(function(){
		document.getElementById("sumir").style.display = 'none';
		
		var justificativa = $(this).attr('justificativa');
        $.ajax({
            url: "/sgc/admin/justificativas/justificativa_view",
            method: 'post',
            data: {
                justificativa : justificativa,
            },
            success: function(responseData) {
                // Se sucesso, remover ou travar o dropdown
                $profissional = JSON.parse(responseData).medico;
				$setor = JSON.parse(responseData).setor;
				$data = JSON.parse(responseData).data;
				$turno = JSON.parse(responseData).turno;
				$entrada_sistema = JSON.parse(responseData).entrada_sistema;
				$saida_sistema = JSON.parse(responseData).saida_sistema;
				$entrada_justificada = JSON.parse(responseData).entrada_justificada;
				$saida_justificada = JSON.parse(responseData).saida_justificada;
				$descricao = JSON.parse(responseData).descricao;
				$status = JSON.parse(responseData).status;
				$motivo = JSON.parse(responseData).motivo;

				//console.log(responseData);
				document.getElementById('nome_profissional_edit').innerText = $profissional;
				document.getElementById('nome_setor_edit').innerText = $setor;
				document.getElementById('data_edit').innerText = $data;
				document.getElementById('turno_plantao_edit').innerText = $turno;
				document.getElementById('hora_entrada_sistema_edit').innerText = $entrada_sistema;
				document.getElementById('hora_saida_sistema_edit').innerText = $saida_sistema;
				document.getElementById('hora_entrada_edit').value = $entrada_justificada;
				document.getElementById('hora_saida_edit').value = $saida_justificada;
				document.getElementById('descricao_edit').innerText = $descricao;

				if($status == "0"){
					document.getElementById('condicao_edit').innerText = 'Aguardando Aprovação';
					document.getElementById("sumir_edit").style.display = 'none';
				} else if($status == "1"){
					document.getElementById('condicao_edit').innerText = 'Deferida';
					document.getElementById("sumir_edit").style.display = 'none';
				} else if ($status == "2"){
					document.getElementById('condicao_edit').innerText = 'Indeferida';
					document.getElementById("sumir_edit").style.display = 'block';
					document.getElementById('motivo_edit').innerText = $motivo;
					
				} else if ($status == "4"){
				 	document.getElementById('condicao_edit').innerText = 'Ignorada';
					 document.getElementById("sumir_edit").style.display = 'none';
				} else {
					document.getElementById('condicao_edit').innerText = 'Desconhecido';
					document.getElementById("sumir_edit").style.display = 'none';
				}
				
				document.getElementById("salvar-edit").setAttribute("justificativa", justificativa);
            },
            error: function(responseData) {
                //swal("Erro",$sucess, "error");
                console.log(responseData);
            }
        }); 

		$("#modal_justificativas_edit").modal();
		
		
	});


	$(".btn-deferir").click(function(){
		var id = $(this).attr('justificativa');
		$.ajax({
			type: "POST",
			url: "/sgc/admin/justificativas/aprovar/",
			dataType: 'json',
			data: {
				"justificativa": id,
			},
			success: function(response) {
				$("#modal_justificativas_view").modal("hide");
				swal("Sucesso!", 'Justificativa deferida com Sucesso','success');
				Swal.fire({
					title: 'Justificativa deferida com Sucesso!',
					type: 'success',
					showDenyButton: false,
					showCancelButton: false,
				  }).then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.value) {
						document.location.reload(true);
					} else if (result.isDenied) {
						document.location.reload(true);
					}
				})
			},
			error: function(response){
				swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
			}
		})
	});

	$(".btn-save-edit").click(function(){
		var id = $(this).attr('justificativa');
		$.ajax({
			type: "POST",
			url: "/sgc/admin/justificativas/save/",
			dataType: 'json',
			data: {
				"justificativa": id,
				"hora_entrada" : document.getElementById("hora_entrada_edit").value,
				"hora_saida" : document.getElementById("hora_saida_edit").value,
			},
			success: function(response) {
				$("#modal_justificativas_view").modal("hide");
				Swal.fire({
					title: 'Justificativa salva com Sucesso!',
					type: 'success',
					showDenyButton: false,
					showCancelButton: false,
				  }).then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.value) {
						if(document.getElementById("hora_entrada_edit").value == ''){
							document.getElementById('hora_entrada_justificada').innerText = '-';
						} else {
							document.getElementById('hora_entrada_justificada').innerText = document.getElementById("hora_entrada_edit").value;
						}
						
						if(document.getElementById("hora_saida_edit").value == ''){
							document.getElementById('hora_saida_justificada').innerText = '-';
						} else {
							document.getElementById('hora_saida_justificada').innerText = document.getElementById("hora_saida_edit").value;
						}
						$("#modal_justificativas_edit").modal("hide");
						$("#modal_justificativas_view").modal();
					} else if (result.isDenied) {
					}
				})
			},
			error: function(response){
				swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
			}
		})
	});
	$(".btn-indeferir").click(function(){
		var id = $(this).attr('justificativa');
		Swal.fire({
			type: 'info',
			title: 'Informe o motivo do indeferimento',
			input: 'text',
			inputAttributes: {
			  autocapitalize: 'off'
			},
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Indeferir',
			cancelButtonText: 'Cancelar',
			showLoaderOnConfirm: true,
			preConfirm: (motivo) => {
        		$.ajax({
            		url: '/sgc/admin/justificativas/edit_recusa/',
            		method: 'post',
            	data: {
					justificativa: id,
     				motivo_recusa : motivo,
					},
				success: function(responseData) {
					Swal.fire({
						title: 'Justificativa indeferida com Sucesso!',
						type: 'success',
						showDenyButton: false,
						showCancelButton: false,
					  }).then((result) => {
						/* Read more about isConfirmed, isDenied below */
						if (result.value) {
							document.location.reload(true);
						} else if (result.isDenied) {
							document.location.reload(true);
						}
					});
					},
				error: function(responseData){
					swal('Erro','Log não pode ser gravado.','error');
					}
				})
			},
			allowOutsideClick: () => !Swal.isLoading()
		})
	});
	$(".btn-ignorar").click(function(){
		var id = $(this).attr('justificativa');
		$.ajax({
			type: "POST",
			url: "/sgc/admin/justificativas/ignorar/",
			dataType: 'json',
			data: {
				"justificativa": id,
			},
			success: function(response) {
				$("#modal_justificativas_view").modal("hide");
				Swal.fire({
					title: 'Justificativa ignorada com Sucesso!',
					type: 'success',
					showDenyButton: false,
					showCancelButton: false,
				  }).then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.value) {
						document.location.reload(true);
					} else if (result.isDenied) {
						document.location.reload(true);
					}
				})
			},
			error: function(response){
				swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
			}
		})
	});
	$data_ini = document.getElementById('data_plantao_inicio').value;
	$data_fim = document.getElementById('data_plantao_fim').value;

	var dt_pendentes = $("#dt_pendentes").DataTable({

		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		//"processing": true,
        //scrollCollapse: true,
		//"serverSide": false,
		//paging: true,
		"pageLength": 12,
		dom: 'frtip',
		buttons: [
			{
				extend: 'print',
				customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
				title: '<p style="text-align: center;"><img src="/sgc/assets/frameworks/cemerge/images/logo.png" alt="Cemerge" /></p>',
				messageTop: '<center><h2>Justificativas pendentes</h2></center>',
                text: ' Imprimir',
				//messageBottom: '<table style="margin: 15px 0 10px; width: 99%; height: 30px;"> 	<tbody> 		<tr> 			<td><strong>&nbsp;TOTAL DE PROVENTOS: R$ 5.000,00</strong></td> 			<td style="text-align: right;"><strong>TOTAL DE DESCONTOS: R$ 500,00</strong></td> 			<td style="text-align: right;"><strong>VALOR LIQUIDO: R$ 4.500,00 </strong></td> 		</tr> 	</tbody> </table> <p><span style="font-size: 10pt;"><strong>RECEBI A IMPORTÂNCIA ACIMA DISCRIMINADA, REFERENTE A PRESTAÇÃO DE SERVIÇOS MÉDICOS EM REGIME COOPERATIVISTA E NA QUALIDADE DE ASSOCIADO.</strong></span></p> <table style="margin: 5px 0 10px; width: 99%; height: 30px;"> 	<tbody> 		<tr> 			<td style="text-align: left;">FORTALEZA-CE&nbsp; ____/____/____&nbsp;</td><td style="text-align: center;">&nbsp;______________________________________________</td> 		</tr> 	</tbody> </table>',
				className: 'btn btn-success text-center fa fa-print',
				exportOptions: {
                    columns: [ 0, 1, 2, 3],
                }
            }],
		"ajax": {
			"url": "/sgc/admin/justificativas/ajax_justificativas_pendentes_coordenador/"+$data_ini+'/'+$data_fim,
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		/*"drawCallback": function() {
			active_btn_profissional();
		},*/
		select: true,
	});

})
