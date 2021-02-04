$(document).ready(function() {
    $(document).on('change', '#unidadehospitalar_id', function() {
        var val = $(this).val();
        var url = '/sgc/admin/profissionais/profissionaisporunidade/' + val;

        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#profissional_id').empty();
                $.each(responseData, function(i, s) {
                    $('#profissional_id').append($('<option></option>').val(s.id).html(s.nome));
                });
            },
        });
    });
});

$(window).load(function(){

});