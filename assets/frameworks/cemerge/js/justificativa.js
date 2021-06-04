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
        document.getElementById("batida_entrada").readOnly = true;
        document.getElementById("batida_saida").readOnly = true;
        
    if (data["plantao_id"] != null){
        document.getElementById("escala_id").prop = false;
        document.getElementById("escala_id").value = data['plantao_id'];
        document.getElementById("setor_id").prop = false;
        document.getElementById("setor_id").value = data['setor_id'];
        
        document.getElementById("data_plantao").readOnly = true;
        document.getElementById("data_plantao").value = data['data_plantao'];
        
    } else {
        
    
    }

}); 
