$(document).ready(function(){

	// EXIBIR MODAIS
	$(".btn-troca-coordenador").click(function(){
		$("#modal_coordenador").modal();
		clearErrors();
	});
	$(".btn-addtosector").click(function(){
		$("#modal_add_to_setor").modal();
		var setor = $(this).attr('setor');

		var dt_profissional = $("#dt_profissionais").DataTable({

			"oLanguage": DATATABLE_PTBR,
			"autoWidth": false,
			"processing": true,
			"serverSide": true,	
			"ajax": {
				"url": "/sgc/admin/setores/ajax_profissionais",
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
		
	});

	function active_btn_profissional() {
		$(".btn-add-profissional").click(function(){
			var profissional = $(this).attr('id');
			$.ajax({
				type: "POST",
				url: "/sgc/admin/profissionais/linktosector/"+profissional,
				dataType: 'json',
				data: {
					"setor_id": 2,
					"profissional" : profissional
				},
				success: function(response) {
					clearErrors();
					$("#modal_add_to_setor").modal("hide");
					swal("Sucesso!", 'Profissional Adicionado com Sucesso','success');
				},
				error: function(response){
					//swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
					clearErrors();
					$("#modal_add_to_setor").modal("hide");
					swal("Sucesso!", 'Profissional Adicionado com Sucesso','success');
					document.reload(forcedReload);
				}
			})
		});
		
	};

	$(".btn-selecionar-coordenador").click(function(){
		$("#modal_coordenador").modal("hide");
		clearErrors();
		var profissional = document.getElementById('profissional_id').value;
		var setor = $(this).attr('setor');
		$.ajax({
			url: '/sgc/admin/setores/trocar_coordenador/',
			method: 'post',
			type: 'json',
		data: {
			profissional : profissional,
			setor : setor
			},
		success: function(responseData){
			swal('Sucesso',"Coordenador alterado!","success");
			document.reload(forcedReload);
		},
		error: function(){
			swal('Erro','Ocorreu um trocar o Coordenador.','error');
		}
		})		
	});
})
