$(document).ready(function(){
    $(document).on('change', '#unidadehospitalar_id', function() {
        var unidadehospitalar_id = $(this).val();
        var url = '/sgc/admin/profissionais/profissionaisporunidade/' + unidadehospitalar_id;
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
        });
    });
});