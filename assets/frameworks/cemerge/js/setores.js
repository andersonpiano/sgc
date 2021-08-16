$(document).ready(function() {

    if (document.getElementById("sumir")){
        document.getElementById("sumir").style.display = 'none';
    }
    
    $(document).on('change', '#unidadehospitalar_id', function() {
        var val = $(this).val();
        var user_type = $('#user_type').val(); // 0-Profissional, 1-Coordenadorplantao, 2-Administrador
        var user_id = $('#user_id').val();
        var url = '';
        if (typeof user_type !== 'undefined' && user_type == 0) {
            url = '/sgc/admin/setores/setores/' + val + '/' + user_id;
        } else if (typeof user_type !== 'undefined' && user_type == 1) {
            url = '/sgc/admin/setores/setores/' + val + '/0/' + user_id;
        } else if (typeof user_type !== 'undefined' && user_type == 2) {
            url = '/sgc/admin/setores/setores/' + val;
        } else {
            url = '/sgc/admin/setores/setores/' + val;
        }

        /*
        var profissional_id = $('#profissional_id').val();
        if (typeof profissional_id !== 'undefined') {
            url = '/sgc/admin/setores/setores/' + val + '/' + profissional_id;
        } else {
            url = '/sgc/admin/setores/setores/' + val;
        }
        */

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