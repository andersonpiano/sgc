$(function() {

	// EXIBIR MODAIS
	$("#btn_add_categoria").click(function(){
		clearErrors();
		$("#form_categoria")[0].reset();
		$("#modal_categoria").modal();
	});

	$("#btn_add_fornecedor").click(function(){
		clearErrors();
		$("#form_fornecedor")[0].reset();
		$("#modal_fornecedor").modal();
	});

	$("#btn_add_nf").click(function(){
		clearErrors();
		$("#form_nf")[0].reset();
		$("#modal_nf").modal();
	});

	$("#btn_add_produto").click(function(){
		clearErrors();
		$("#form_produto")[0].reset();
		$("#modal_produto").modal();
	});

	$("#btn_add_entrada").click(function(){
		clearErrors();
		$("#form_entrada")[0].reset();
		$("#modal_entrada").modal();
	});

	$("#btn_add_saida").click(function(){
		clearErrors();
		$("#form_saida")[0].reset();
		$("#modal_saida").modal();
	});

	$("#btn_add_responsavel").click(function(){
		clearErrors();
		$("#form_responsavel")[0].reset();
		$("#modal_responsavel").modal();
	});
	
	$("#btn_add_estoque").click(function(){
		clearErrors();
		$("#form_estoque")[0].reset();
		$("#modal_estoque").modal();
	});

	$("#produto_upload_img").change(function(){
		uploadImg($(this), $("#produto_img_path"), $("#produto_img"));
	});

	$("#nf_upload_img").change(function(){
		uploadImg($(this), $("#nf_img_path"), $("#nf_img"));
	});

	$("#form_categoria").submit(function() {
 			$.ajax({
			type: "POST",
			url: 'estoque/cadastrar_categoria/',
			dataType: "JSON",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_categoria").siblings(".help-block").html(loadingImg("Cadastrando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_categoria").modal("hide");
					swal("Sucesso!","Categoria salva com sucesso!", "success");
					dt_categoria.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			},
			error: function(response){
				$("#modal_categoria").modal("hide");
				swal("Erro!","Erro ao Salvar Categoria!", "warning");
				dt_categoria.ajax.reload();
			}
		})

		return false;
	});

	$("#form_fornecedor").submit(function() {

		$.ajax({
			type: "POST",
			url: "estoque/cadastrar_fornecedor",
			dataType: "JSON",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_fornecedor").siblings(".help-block").html(loadingImg("Cadastrando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_fornecedor").modal("hide");
					swal("Sucesso!","Especialização salva com sucesso!", "success");
					dt_fornecedor.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			},
			error: function(response){
				$("#modal_fornecedor").modal("hide");
				swal("Erro!","Especialização salva com sucesso!", "warning");
				dt_fornecedor.ajax.reload();
			}
		})
		return false;
	});

	$("#form_nf").submit(function() {

		$.ajax({
			type: "POST",
			url: "estoque/cadastrar_nf",
			dataType: "JSON",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_nf").siblings(".help-block").html(loadingImg("Cadastrando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_nf").modal("hide");
					swal("Sucesso!","Especialização salva com sucesso!", "success");
					dt_nf.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			},
			error: function(response){
				$("#modal_nf").modal("hide");
				swal("Erro!","Erro ao salvar NF!", "warning");
				dt_nf.ajax.reload();
			}
		})
		return false;
	});

	$("#form_produto").submit(function() {

		$.ajax({
			type: "POST",
			url: "estoque/cadastrar_produto",
			dataType: "JSON",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_produto").siblings(".help-block").html(loadingImg("Cadastrando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_produto").modal("hide");
					swal("Sucesso!","Produto salvo com sucesso!", "success");
					dt_produtos.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			},
			error: function(response){
				$("#modal_produto").modal("hide");
				swal("Erro!","Erro ao salvar Produto!", "warning");
				showErrorsModal(response["error_list"])
				dt_produtos.ajax.reload();
			}
		})
		return false;
	});

	function active_btn_categoria() {
		
		$(".btn-edit-categoria").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_categoria_data",
				dataType: 'json',
				data: {"id": $(this).attr('id')},
				success: function(response) {
					clearErrors();
					$("#form_categoria")[0].reset();
					$.each(response["input"], 
					function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_categoria").modal();
				},
				error: function(response){
					swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
				}
			})
		});

		$(".btn-del-categoria").click(function(){
			
			$id = $(this).attr('id');
			swal({
				title: "Atenção!",
				text: "Deseja deletar essa Categoria?",
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
						url: 'estoque/deletar_categoria/'+$id,
						data: {"id" : $id},
						success: function(response) {
							swal("Sucesso!", "Categoria removida com sucesso", "success");
							dt_categoria.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
						}
					})
				}
			})

		});
	}

	var dt_categoria = $("#dt_categoria").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_categorias",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_categoria();
		}
	});

	function active_btn_nf() {
		
		$(".btn-edit-nf").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_nf_data",
				dataType: 'json',
				data: {"id": $(this).attr('id')},
				success: function(response) {
					clearErrors();
					$("#form_nf")[0].reset();
					$.each(response["input"], 
					function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_nf").modal();
				},
				error: function(response){
					swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
				}
			})
		});

		$(".btn-del-nf").click(function(){
			
			$id = $(this).attr('id');
			swal({
				title: "Atenção!",
				text: "Deseja deletar essa Categoria?",
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
						url: 'estoque/deletar_nf/'+$id,
						data: {"id" : $id},
						success: function(response) {
							swal("Sucesso!", "Categoria removida com sucesso", "success");
							dt_nf.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
							dt_nf.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_nf = $("#dt_nf").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_nf",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_nf();
		}
	});

	function active_btn_produtos() {
		
		$(".btn-edit-produto").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_produto_data",
				dataType: 'json',
				data: {"id": $(this).attr('id')},
				success: function(response) {
					clearErrors();
					$("#form_produto")[0].reset();
					$.each(response["input"], 
					function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_produto").modal();
				},
				error: function(response){
					swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
				}
			})
		});

		$(".btn-del-produto").click(function(){
			
			$id = $(this).attr('id');
			swal({
				title: "Atenção!",
				text: "Deseja deletar essa Categoria?",
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
						url: 'estoque/deletar_produto/'+$id,
						data: {"id" : $id},
						success: function(response) {
							swal("Sucesso!", "Categoria removida com sucesso", "success");
							dt_produtos.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
							dt_produtos.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_produtos = $("#dt_produtos").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_produtos",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_produtos();
		}
	});

	function active_btn_estoque() {
		
		$(".btn-edit-estoque").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_estoque_data",
				dataType: 'json',
				data: {"id": $(this).attr('id')},
				success: function(response) {
					clearErrors();
					$("#form_estoque")[0].reset();
					$.each(response["input"], 
					function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_estoque").modal();
				},
				error: function(response){
					swal("Erro!", 'Ocorreu um erro ao executar essa ação','error');
				}
			})
		});

		$(".btn-del-estoque").click(function(){
			
			$id = $(this).attr('id');
			swal({
				title: "Atenção!",
				text: "Deseja deletar essa Categoria?",
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
						url: 'estoque/deletar_estoque/'+$id,
						data: {"id" : $id},
						success: function(response) {
							swal("Sucesso!", "Categoria removida com sucesso", "success");
							dt_nf.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
						}
					})
				}
			})

		});
	}

	var dt_estoque = $("#dt_estoque").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_estoque",
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_estoque();
		}
	});
	
	function active_btn_fornecedor() {
		
		$(".btn-edit-fornecedor").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_fornecedor_data",
				dataType: "JSON",
				data: {"id": $(this).attr("id")},
				success: function(response) {
					clearErrors();
					$("#form_fornecedor")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_fornecedor").modal();
				}
			})
		});

		$(".btn-del-fornecedor").click(function(){
			
			$fornecedor_id = $(this).attr('id');
			swal({
				title: "Atenção!",
				text: "Deseja deletar esse Fornecedor?",
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
						url: "estoque/deletar_fornecedor/"+$fornecedor_id,
						data: {"id": $fornecedor_id},
						success: function(response) {
							swal("Sucesso!", "Ação executada com sucesso", "success");
							dt_fornecedor.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
							dt_fornecedor.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_fornecedor = $("#dt_fornecedor").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_fornecedores",
			"type": "POST",
			"dataType": "JSON",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_fornecedor();
		}
	});


	function active_btn_entrada() {
		
		$(".btn-edit-entrada").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_entrada_data",
				dataType: "JSON",
				data: {"id": $(this).attr("id")},
				success: function(response) {
					clearErrors();
					$("#form_entrada")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_entrada").modal();
				}
			})
		});

		$(".btn-del-entrada").click(function(){
			
			$id = $(this).attr('id');
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
						url: "estoque/deletar_entrada/"+$id,
						data: {"id": $id},
						success: function(response) {
							swal("Sucesso!", "Ação executada com sucesso", "success");
							dt_entrada.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
							dt_entrada.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_entrada = $("#dt_entrada").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_entrada",
			"type": "POST",
			"dataType": "JSON",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_entrada();
		}
	});

	function active_btn_saida() {
		
		$(".btn-edit-saida").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_saida_data",
				dataType: "JSON",
				data: {"id": $(this).attr("id")},
				success: function(response) {
					clearErrors();
					$("#form_saida")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_saida").modal();
				}
			})
		});

		$(".btn-del-saida").click(function(){
			
			$id = $(this).attr('id');
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
						url: "estoque/deletar_saida/"+$id,
						data: {"id": $id},
						success: function(response) {
							swal("Sucesso!", "Ação executada com sucesso", "success");
							dt_entrada.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
							dt_entrada.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_saida = $("#dt_saida").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_saida",
			"type": "POST",
			"dataType": "JSON",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_saida();
		}
	});

	function active_btn_responsaveis() {
		
		$(".btn-edit-responsavel").click(function(){
			$.ajax({
				type: "POST",
				url: "estoque/ajax_get_responsavel_data",
				dataType: "JSON",
				data: {"id": $(this).attr("id")},
				success: function(response) {
					clearErrors();
					$("#form_responsavel")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_responsavel").modal();
				}
			})
		});

		$(".btn-del-responsavel").click(function(){
			
			$id = $(this).attr('id');
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
						url: "estoque/deletar_responsavel/"+$id,
						data: {"id": $id},
						success: function(response) {
							swal("Sucesso!", "Ação executada com sucesso", "success");
							dt_responsaveis.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
							dt_resposnaveis.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_responsaveis = $("#dt_responsaveis").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "estoque/ajax_listar_responsaveis",
			"type": "POST",
			"dataType": "JSON",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_responsaveis();
		}
	});
	})