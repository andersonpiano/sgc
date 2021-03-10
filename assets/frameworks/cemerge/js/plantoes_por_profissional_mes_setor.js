$(document).ready(function(){
    $(document).on('change', '#profissionalsubstituto_id', function() {
        var profissional_id = $('#profissionalsubstituto_id').val();
        var setor_id = $("[name='setor_id']").val();
        var mes = $("[name='mes_plantao']").val();
        var url = '/sgc/admin/plantoes/plantoesdisponiveisporprofissionalmesesetor/' + mes + '/' + setor_id + '/' + profissional_id;
        //moment().format('L');
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#escalatroca_id').empty();
                $.each(responseData, function(i, p) {
                    $('#escalatroca_id').append($('<option></option>').val(p.id).html(new moment(p.dataplantao).format('DD/MM/YYYY') + ' das ' + p.horainicialplantao + ' às ' + p.horafinalplantao));
                });
            },
        });
    });
});


$(window).load(function(){


});