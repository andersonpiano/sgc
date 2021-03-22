$(document).ready(function() {
    $(document).on('change', '#profissional_id', function() {
        var val = $(this).val();
        var url = '/sgc/admin/setores/setor_por_profissional/' + val;

        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#setor_id').empty();
                $.each(responseData, function(i, s) {
                    $('#setor_id').append($('<option></option>').val(s.id).html(s.nome));
                });
            },
        });
    });
});
