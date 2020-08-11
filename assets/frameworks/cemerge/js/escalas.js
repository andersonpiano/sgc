$(document).ready(function(){
    $(document).on('change', '#unidadehospitalar_id', function() {
        var val = $(this).val();
        var url = '/sgc/admin/escalas/setores/' + val;
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#setor_id').empty();
                $.each(responseData, function(i, p) {
                    $('#setor_id').append($('<option></option>').val(p.id).html(p.nome));
                });
            },
        });
    });
    $(document).on('change', '#profissional_id', function() {
        var profissional = $(this).val();
        var escala = $(this).next('input').val();
        var url = '/sgc/admin/escalas/atribuirescala/';
        $.ajax({
            url: url,
            method: 'post',
            data: {
                profissional : profissional,
                escala : escala
            },
            success: function(responseData) {
                // Se sucesso, remover ou travar o dropdown
                /*
                $('#row_id_' + escala).fadeOut('slow', 
                    function(here){ 
                        $('#row_id_' + escala).remove();
                    }
                );
                console.log(responseData);
                */
            },
            error: function(responseData) {
                alert("Ocorreu um erro ao atribuir a escala ao profissional. Por favor, tente novamente mais tarde");
                console.log(responseData);
            }
        });
    });
});


$(window).load(function(){


});