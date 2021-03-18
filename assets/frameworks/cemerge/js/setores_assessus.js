$(document).ready(function(){
    var unidadehospitalar_id = $('#unidadehospitalar_id').val();
    /*$(document).on('change', '#profissional_id', function(){
        var unidadehospitalar_id = $('#unidadehospitalar_id').val();
        var setor_id = $('#setor_id').val();
        $.ajax({
            url: '/sgc/admin/setores/assessus_x_sgc/' + setor_id,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $.each(responseData, function(i, setor) {
                    document.getElementById("nome").value = setor_id;
                });
            },
            error: function(){*/
                $.ajax({
                    url: '/sgc/admin/setores/setores_assessus/' + unidadehospitalar_id,
                    method: 'get',
                    dataType: 'json',
                    success: function(responseData) {
                        $('#setores_assessus').empty();
                        $.each(responseData, function(i, p) {
                            $('#setores_assessus').append($('<option></option>').val(p.cd_set).html(p.nm_set));
                        });
                    },
                });/*
            }
        });      
    //});
    */
});