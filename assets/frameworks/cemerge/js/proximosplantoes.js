$(document).ready(function(){

    $(".btn-oferecer").click(function(){

		var setor = $(this).attr('setor_id');
		var plantao = $(this).attr('plantao_id');
        var url = '/sgc/admin/profissionais/profissionais_por_setor/' + setor;

        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#profissional_substituto').empty();
                $.each(responseData, function(i, s) {
                    $('#profissional_substituto').append($('<option></option>').val(s.id).html(s.nome));
                });
            },
        });

		if (document.getElementById("sumir")){
			document.getElementById("sumir").style.display = 'none';
		}
		
	
		$(document).on('change', '#tipo_oferta', function(){
			$tipo = $(this).val();
			if ($tipo == 1){
				document.getElementById("sumir").style.display = 'block';
			} else {
				document.getElementById("sumir").style.display = 'none';
			}
		});

		$(document).on('change', '#profissional_substituto', function() {
			var profissional = document.getElementById('profissional_substituto').value;
			var data_ini = new Date;
			var url = '/sgc/admin/escalas/escala_por_profissional/'+data_ini.getFullYear() + "-" + data_ini.getMonth() + "-" + data_ini.getDate() +'/'+profissional+'/'+setor+'/'+1;
			//swal('erro', data_ini, 'error');
			$.ajax({
				url: url,
				method: 'get',
				dataType: 'json',
				success: function(responseData) {
					$('#frequencias_disponiveis').empty();
					$.each(responseData, function(i, p) {
						$('#frequencias_disponiveis').append($('<option></option>').val(i).html(p));
					});
				},
			});
		});

		

		$("#modal_ofertar").modal();
		clearErrors();

		$("#form_oferecer").submit(function() {

			$.ajax({
				type: "POST",
				url: "/sgc/admin/plantoes/tooffer_by_javascript/"+ plantao,
				dataType: "JSON",
				data: $(this).serialize(),
				beforeSend: function() {
					clearErrors();
					$("#btn_confirmar_oferta").siblings(".help-block").html(loadingImg("Registrando..."));
				},
				success: function(response) {
					clearErrors();
					if (response["status"]) {
						$("#modal_ofertar").modal("hide");
						swal("Sucesso!","Oferta efetuada com sucesso!", "success");
					} else {
						showErrorsModal(response["error_list"])
					}
				},
				error: function(response){
					//$("#modal_ofertar").modal("hide");
					swal("Erro!","Erro ao efetuar oferta!", "error");
					showErrorsModal(response["error_list"])
				}
			})
			return false;
		});
	});

});