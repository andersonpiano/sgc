$(function() {

	// EXIBIR MODAIS
	$("#btn_add_categoria").click(function(){
		clearErrors();
		$("#form_categoria")[0].reset();
		$("#categoria_img_path").attr("src", "");
		$("#modal_categoria").modal();
	});

	$("#btn_add_especializacao").click(function(){
		clearErrors();
		$("#form_especializacao")[0].reset();
		swal("Novo estilo de alerta!");
		$("#especializacao_photo_path").attr("src", "");
		$("#modal_especializacao").modal();
	});

	$("#btn_upload_categoria_img").change(function() {
		uploadImg($(this), $("#categoria_img_path"), $("#categoria_img"));
	});

	$("#btn_upload_especializacao_photo").change(function() {
		uploadImg($(this), $("#especializacao_photo_path"), $("#especializacao_photo"));
	});

	$("#form_categoria").submit(function() {

		$.ajax({
			type: "POST",
			url: BASE_URL + "admin/categorias/cadastro",
			dataType: "json",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_categoria").siblings(".help-block").html(loadingImg("Verificando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_categoria").modal("hide");
					swal("Sucesso!","Curso salvo com sucesso!", "success");
					dt_categoria.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			}
		})

		return false;
	});

	$("#form_especializacao").submit(function() {

		$.ajax({
			type: "POST",
			url: BASE_URL + "restrict/ajax_save_especializacao",
			dataType: "json",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_especializacao").siblings(".help-block").html(loadingImg("Verificando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_especializacao").modal("hide");
					swal("Sucesso!","Membro salvo com sucesso!", "success");
					dt_especializacao.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			}
		})

		return false;
	});

	function active_btn_categoria() {
		
		$(".btn-edit-categoria").click(function(){
			$.ajax({
				type: "POST",
				url: BASE_URL + "restrict/ajax_get_categoria_data",
				dataType: "json",
				data: {"categoria_id": $(this).attr("categoria_id")},
				success: function(response) {
					clearErrors();
					$("#form_categoria")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$("#categoria_img_path").attr("src", response["img"]["categoria_img_path"]);
					$("#modal_categoria").modal();
				}
			})
		});

		$(".btn-del-categoria").click(function(){
			
			categoria_id = $(this);
			swal({
				title: "Atenção!",
				text: "Deseja deletar esse curso?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d9534f",
				confirmButtonText: "Sim",
				cancelButtontext: "Não",
				closeOnConfirm: true,
				closeOnCancel: true,
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type: "POST",
						url: BASE_URL + "restrict/ajax_delete_categoria_data",
						dataType: "json",
						data: {"categoria_id": categoria_id.attr("categoria_id")},
						success: function(response) {
							swal("Sucesso!", "Ação executada com sucesso", "success");
							dt_categoria.ajax.reload();
						}
					})
				}
			})

		});
	}

	/*var dt_categoria = $("#dt_categorias").DataTable({
		//"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": BASE_URL + "restrict/ajax_list_categoria",
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_categoria();
		}
	});*/
	
	function active_btn_especializacao() {
		
		$(".btn-edit-especializacao").click(function(){
			$.ajax({
				type: "POST",
				url: BASE_URL + "restrict/ajax_get_especializacao_data",
				dataType: "json",
				data: {"especializacao_id": $(this).attr("especializacao_id")},
				success: function(response) {
					clearErrors();
					$("#form_especializacao")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$("#especializacao_photo_path").attr("src", response["img"]["especializacao_photo_path"]);
					$("#modal_especializacao").modal();
				}
			})
		});

		$(".btn-del-especializacao").click(function(){
			
			especializacao_id = $(this);
			swal({
				title: "Atenção!",
				text: "Deseja deletar esse membro?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d9534f",
				confirmButtonText: "Sim",
				cancelButtontext: "Não",
				closeOnConfirm: true,
				closeOnCancel: true,
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type: "POST",
						url: BASE_URL + "restrict/ajax_delete_especializacao_data",
						dataType: "json",
						data: {"especializacao_id": especializacao_id.attr("especializacao_id")},
						success: function(response) {
							swal("Sucesso!", "Ação executada com sucesso", "success");
							dt_especializacao.ajax.reload();
						}
					})
				}
			})

		});
	}
/*
	var dt_especializacao = $("#dt_especializacao").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": BASE_URL + "restrict/ajax_list_especializacao",
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_especializacao();
		}
	});*/

})