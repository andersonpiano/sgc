$(document).ready(function(){
    $(document).on('change', '#profissionalsubstituto_id', function() {
        var data_plantao = $("[name='data_plantao']").val();
        var hora_inicial_plantao = $("[name='hora_inicial_plantao']").val();
        var profissional_id = $('#profissionalsubstituto_id').val();
        var unidadehospitalar_id = $("[name='unidadehospitalar_id']").val();
        var url = '/sgc/admin/plantoes/frequenciasdisponiveisporprofissional/' + data_plantao + '/' + hora_inicial_plantao + '/' + profissional_id + '/' + unidadehospitalar_id;
        //moment().format('L');
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#frequencias_disponiveis').empty();
                $.each(responseData, function(i, d) {
                    $('#frequencias_disponiveis').append($('<option></option>').val(d.cd_ctl_frq).html(new moment(d.dt_frq).format('DD/MM/YYYY') + ' às ' + new moment(d.dt_frq).format('HH:mm:ss')));
                });
            },
        });
    });
});


$(window).load(function(){


});