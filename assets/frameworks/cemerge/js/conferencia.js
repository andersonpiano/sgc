$(function(){

	// EXIBIR MODAIS
	$(".btn-batidas-ignoradas").click(function(){
		$("#modal_jade").modal();
		clearErrors();
		active_btn_jade();	
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
