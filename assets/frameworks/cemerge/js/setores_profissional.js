$(document).ready(function(){

	$(".btn-addtosector").click(function(){
		$("#modal_add_to_setor").modal();
	});

	function active_btn_setor() {
		$(".btn-add-profissional").click(function(){
			var profissional = document.getElementById('profissional_id').value;
			var setor = $(this).attr('id');
			$.ajax({
				type: "POST",
				url: "/sgc/admin/profissionais/linktosector/"+profissional,
				dataType: 'json',
				data: {
					"setor_id": setor,
					"profissional" : profissional
				},
				success: function(response) {
					clearErrors();
					$("#modal_add_to_setor").modal("hide");
					//swal("Sucesso!", profissional+'-'+setor,'success');
					document.location.reload(true);
				},
				error: function(response){
					//swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
					clearErrors();
					$("#modal_add_to_setor").modal("hide");
					//swal("Sucesso!", 'Profissional Adicionado com Sucesso','success');
					//swal("Sucesso!", profissional+'-'+setor,'success');
					document.location.reload(true);
				}
			})
		});
		
	};

	

	profissional = document.getElementById('profissional_id').value;
	var dt_setores_profissional = $("#dt_setores_profissional").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		dom: 'Brt',
		"ajax": {
			"url": "/sgc/admin/setores/ajax_setores_profissional/"+profissional,
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_setor();
		}
	});

	var dt_setores = $("#dt_setores").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "/sgc/admin/setores/ajax_setores/",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_setor();
		}
	});

	//swal('Profissional_id', profissional, 'success');
});