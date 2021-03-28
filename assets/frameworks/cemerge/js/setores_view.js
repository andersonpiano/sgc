$(document).ready(function(){

	// EXIBIR MODAIS
	$(".btn-troca-coordenador").click(function(){
		$("#modal_coordenador").modal();
		clearErrors();
		active_btn_jade();	
	});
	
	$(".btn-selecionar_coordenador").click(function(){
		$("#modal_coordenador").modal("hide");
		clearErrors();
		var profissional = document.getElementById('setor_id').value;
		$.ajax({
			url: '/sgc/admin/setores/get_usuarios_dropdown_encode/',
			method: 'post',
			type: 'json',
		data: {
			setor: setor,
			},
		success: function(responseData){
		},
		error: function(){
			swal('Erro','Ocorreu um erro ao clicar nesse botão.','error');
		}
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
