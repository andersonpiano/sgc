$(document).ready(function(){

		
	$(".btn-add-justificativa").click(function(){
		
		//var setor = $(this).attr('id');
//		dt_pendentes.reload();
		$("#modal_add_justificativa").modal();
		
		
	});

	var dt_pendentes = $("#dt_pendentes").DataTable({

		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		dom: 'Brt',
		"ajax": {
			"url": "/sgc/admin/justificativas/ajax_justificativas_pendentes",
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
