$(document).ready(function(){

		
	$(".btn-justificativas-pendentes").click(function(){
		
		//var setor = $(this).attr('id');
//		dt_pendentes.reload();
		$("#modal_justificativas_pendentes").modal();
		
		
	});

	var dt_pendentes = $("#dt_pendentes").DataTable({

		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		dom: 'Brt',
		"ajax": {
			"url": "/sgc/admin/justificativas/ajax_justificativas_pendentes_coordenador",
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
