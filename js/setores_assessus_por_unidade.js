$(document).ready(function(){
    $(document).on('change', '#unidadehospitalar_id', function() {
        var unidadehospitalar_id = $('#unidadehospitalar_id').val();
        var url = '/sgc/admin/setores/setores_assessus/' + unidadehospitalar_id;
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#setor_id').empty();
                $.each(responseData, function(i, p) {
                    $('#setor_id').append($('<option></option>').val(p.cd_set).html(p.nm_set));
                });
            },
        });
    });
});