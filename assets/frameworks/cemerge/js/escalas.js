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
});


$(window).load(function(){


});