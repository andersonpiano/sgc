$(document).ready(function(){

	// EXIBIR MODAIS
	$(".btn-troca-coordenador").click(function(){
		$("#modal_coordenador").modal();
		clearErrors();
	});
	/*
		$( ".btn-troca-coordenador").focus(function() {
		alert( "Handler for .focus() called." );
		});
	*/
	/*$( ".btn-troca-coordenador").mouseover(function() {
		//alert( "Handler for .focus() called." );
		$(".btn-troca-coordenador").text("I am fine");
		});*/	
		
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

	$(".btn-ativar").click(function(){
		var id = $(this).attr('setor');
		$.ajax({
			type: "POST",
			url: "/sgc/admin/setores/ativar/",
			dataType: 'json',
			data:{
				id: id
			},
			success: function(response) {
				clearErrors();
				//swal("Sucesso!", 'Status do setor alterado com sucesso','success');
				Swal.fire({
					title: 'Status do setor alterado com sucesso!',
					type: 'info',
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
		//
	});

	function active_btn_profissional() {
		$(".btn-add-profissional").click(function(){
			var profissional = $(this).attr('id');
			var setor = document.getElementById('setor_id').value;
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
					//swal("Sucesso!", 'Profissional Adicionado com Sucesso','success');
				},
				error: function(response){
					//swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
					clearErrors();
					$("#modal_add_to_setor").modal("hide");
					//swal("Sucesso!", 'Profissional Adicionado com Sucesso','success');
					document.location.reload(true);
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
			//swal('Sucesso',"Coordenador alterado!","success");
			document.location.reload(true);
		},
		error: function(){
			swal('Erro','Ocorreu um trocar o Coordenador.','error');
		}
		})		
	});
})
