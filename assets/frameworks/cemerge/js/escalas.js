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
    $(document).on('change', '#setor_id', function() {
        var val = $(this).val();
        var url = '/sgc/admin/escalas/profissionais/' + val;
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#profissional_id').empty();
                $.each(responseData, function(i, p) {
                    $('#profissional_id').append($('<option></option>').val(p.id).html(p.nome));
                });
            },
            error: function() {
                console.log('Ocorreu um erro ao buscar os profissionais do setor');
            },
        });
    });
});


$(window).load(function(){


});