$(document).ready(function(){
    $(document).on('change', '#tipo_plantao', function() {
        var tipo_plantao = $(this).val();
        var escala = $(this).next('input').val();
        var url = '/sgc/admin/escalas/alterartipoplantao/';
        $.ajax({
            url: url,
            method: 'post',
            data: {
                tipo_plantao : tipo_plantao,
                escala : escala
            },
            success: function(responseData) {},
            error: function(responseData) {
                alert("Ocorreu um erro ao alterar o tipo de plantão. Por favor, tente novamente mais tarde");
                console.log(responseData);
            }
        });
    });
});