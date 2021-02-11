$(function() {

	// EXIBIR MODAIS
	$("#btn_add_categoria").click(function(){
		clearErrors();
		$("#form_categoria")[0].reset();
		$("#modal_categoria").modal();
	});

	$("#btn_add_especializacao").click(function(){
		clearErrors();
		$("#form_especializacao")[0].reset();
		$("#modal_especializacao").modal();
	});

	$("#form_categoria").submit(function() {
 			$.ajax({
			type: "POST",
			url: 'cadastrar_categoria/',
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
				swal("Sucesso!","Categoria salva com sucesso!", "success");
				dt_categoria.ajax.reload();
			}
		})

		return false;
	});

	$("#form_especializacao").submit(function() {

		$.ajax({
			type: "POST",
			url: "cadastrar_especializacao",
			dataType: "JSON",
			data: $(this).serialize(),
			beforeSend: function() {
				clearErrors();
				$("#btn_save_especializacao").siblings(".help-block").html(loadingImg("Cadastrando..."));
			},
			success: function(response) {
				clearErrors();
				if (response["status"]) {
					$("#modal_especializacao").modal("hide");
					swal("Sucesso!","Especialização salva com sucesso!", "success");
					dt_especializacao.ajax.reload();
				} else {
					showErrorsModal(response["error_list"])
				}
			},
			error: function(response){
				$("#modal_especializacao").modal("hide");
				swal("Sucesso!","Especialização salva com sucesso!", "success");
				dt_especializacao.ajax.reload();
			}
		})
		return false;
	});

	function active_btn_categoria() {
		
		$(".btn-edit-categoria").click(function(){
			$.ajax({
				type: "POST",
				url: "ajax_get_categoria_data",
				dataType: 'json',
				data: {"categoria_id": $(this).attr('categoria_id')},
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
			
			$categoria_id = $(this).attr('categoria_id');
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
						url: 'deletar_categoria/'+$categoria_id,
						data: {"categoria_id" : $categoria_id},
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
			"url": "ajax_listar_categorias",
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

	var dt_profissionais = $("#dt_profissionais").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "ajax_listar_profissionais",
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
	
	function active_btn_especializacao() {
		
		$(".btn-edit-especializacao").click(function(){
			$.ajax({
				type: "POST",
				url: "ajax_get_especializacao_data",
				dataType: "JSON",
				data: {"especializacao_id": $(this).attr("especializacao_id")},
				success: function(response) {
					clearErrors();
					$("#form_especializacao")[0].reset();
					$.each(response["input"], function(id, value) {
						$("#"+id).val(value);
					});
					$("#modal_especializacao").modal();
				}
			})
		});

		$(".btn-del-especializacao").click(function(){
			
			$especializacao_id = $(this).attr('especializacao_id');
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
						url: "deletar_especializacao/"+$especializacao_id,
						data: {"especializacao_id": $especializacao_id},
						success: function(response) {
							swal("Sucesso!", "Ação executada com sucesso", "success");
							dt_especializacao.ajax.reload();
						},
						error: function(response){
							swal("Erro!", 'Ocorreu um erro ao executar essa ação','warning');
							dt_especializacao.ajax.reload();
						}
					})
				}
			})

		});
	}

	var dt_especializacao = $("#dt_especializacao").DataTable({
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "ajax_listar_especializacoes",
			"type": "POST",
			"dataType": "JSON",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		"drawCallback": function() {
			active_btn_especializacao();
		}
	});
})

	$(document).on('change', '#especializacao_select', function() {

        var profissional = $(this).val();
        var escala = $(this).next('input').val();
        var url = '/sgc/admin/escalas/atribuirescala/';
        var sucess = false;
        $.ajax({
            url: url,
            method: 'post',
            data: {
                profissional : profissional,
                escala : escala
            },
            success: function(responseData) {
                // Se sucesso, remover ou travar o dropdown

                $sucess = JSON.parse(responseData).sucess;

                if ($sucess == true){
                    /*$('#row_id_' + escala).fadeOut('slow', 
                    function(here){ 
                        $('#row_id_' + escala).remove();
                    }*/
                    alert("Troca realizada com sucesso");
                } else {
                    alert("Este plantão já foi repassado a outro profissional e não pode ser trocado aqui.");
                    //document.getElementById('profissional_id').value = JSON.parse(responseData).profissional;
                    //console.log($('[name="escala_id_' + escala + '"]').prev('select').val());
                    selectProfissional = $('[name="escala_id_' + escala + '"]').prev('select');
                    selectProfissional.val(JSON.parse(responseData).profissional);
                };
            },
            error: function(responseData) {
                alert("Ocorreu um erro ao atribuir a escala ao profissional. Por favor, tente novamente mais tarde");
                console.log(responseData);
            }
	});
});