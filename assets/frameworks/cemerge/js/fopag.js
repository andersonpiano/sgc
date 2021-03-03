$(function() {

	// EXIBIR MODAIS
	$("#btn_add_profissional").click(function(){
		clearErrors();
		$("#form_profissional")[0].reset();
		$("#modal_profissional").modal();
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
		}
	});
})
