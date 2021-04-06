$(document).ready(function(){
    
    $(document).on('change', '#profissionalsubstituto_id', function() {
        var profissional = document.getElementById('profissionalsubstituto_id').value;
        var data_ini = $(this).attr('data');
        var setor = $(this).attr('setor');
        var url = '/sgc/admin/escalas/escala_por_profissional/'+data_ini+'/'+profissional+'/'+setor;
        swal('erro', data_ini, 'error');
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#frequencias_disponiveis').empty();
                $.each(responseData, function(i, p) {
                    $('#frequencias_disponiveis').append($('<option></option>').val(p.i).html(p.p));
                });
            },
        });
    });
});


$(window).load(function(){


});