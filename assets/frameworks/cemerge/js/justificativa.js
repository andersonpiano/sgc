$(document).ready(function(){

    var query = location.search.slice(1);
    var partes = query.split('&');
    var data = {};
    partes.forEach(function (parte) {
    var chaveValor = parte.split('=');
    var chave = chaveValor[0];
    var valor = chaveValor[1];
    data[chave] = valor;
    });
    if (data["plantao_id"] != null){
        document.getElementById("setor_id").prop = false;
        document.getElementById("setor_id").value = data['setor_id'];
        document.getElementById("data_plantao").readOnly = true;
        document.getElementById("data_plantao").value = data['data_plantao'];
        document.getElementById("hora_entrada").value = data['hora_in'];
        if (data['hora_in'] != null){
        document.getElementById("hora_entrada").readOnly = true;
        }
        document.getElementById("hora_saida").value = data['hora_out'];
        if (data['hora_out'] != null){
        document.getElementById("hora_saida").readOnly = true;
        }
        document.getElementById("hora_saida").value = $profissional_id;
    } else {
    
    }

}); 
