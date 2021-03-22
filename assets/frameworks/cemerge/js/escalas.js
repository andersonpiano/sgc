$(document).ready(function(){
    // Teste de geolocalização
    //navigator.geolocation.getCurrentPosition(show_map);

    $(document).on('change', '#profissional_id', function() {

        var val = $(this).val();
        var url2 = '/sgc/admin/setores/setor_por_profissional/' + val;

        $.ajax({
            url: url2,
            method: 'get',
            dataType: 'json',
            success: function(responseData) {
                $('#setor_id').empty();
                $.each(responseData, function(i, s) {
                    $('#setor_id').append($('<option></option>').val(s.id).html(s.nome));
                });
            },
        });

        var profissional = $(this).val();
        var escala = $(this).next('input').val();
        var url = '/sgc/admin/escalas/atribuirescala/';
        var sucess = false;
        $.ajax({
            url: url,
            method: 'post',
            data: {
                profissional : profissional,
                escala : escala
            },
            success: function(responseData) {
                // Se sucesso, remover ou travar o dropdown

                $sucess = JSON.parse(responseData).sucess;

                if ($sucess == true){
                    /*$('#row_id_' + escala).fadeOut('slow', 
                    function(here){ 
                        $('#row_id_' + escala).remove();
                    }*/
                    alert("Troca realizada com sucesso");
                } else {
                    alert("Este plantão já foi repassado a outro profissional e não pode ser trocado aqui.");
                    //document.getElementById('profissional_id').value = JSON.parse(responseData).profissional;
                    //console.log($('[name="escala_id_' + escala + '"]').prev('select').val());
                    selectProfissional = $('[name="escala_id_' + escala + '"]').prev('select');
                    selectProfissional.val(JSON.parse(responseData).profissional);
                };
            },
            error: function(responseData) {
                alert("Ocorreu um erro ao atribuir a escala ao profissional. Por favor, tente novamente mais tarde");
                console.log(responseData);
            }
        });
        
     
}); 
});


    $(document).on('change', '#profissional_id', function() {
        
    });



$(window).load(function(){


});

function show_map(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log(latitude);
    console.log(longitude);
  }