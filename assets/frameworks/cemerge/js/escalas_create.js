$(document).ready(function(){
    // Teste de geolocalização
    //navigator.geolocation.getCurrentPosition(show_map);
    document.getElementById("diaria_div").style.display = 'none';
    document.getElementById("manha").style.display = 'block';
    document.getElementById("tarde").style.display = 'block';
    document.getElementById("noite").style.display = 'block';
    $(document).on('change', '#tipos', function() {
        //swal('teste', 'Testado', 'success');
        $tipo = $(this).val();
        if ($tipo == 1){
            document.getElementById("diaria_div").style.display = 'none';
            document.getElementById("manha").style.display = 'block';
            document.getElementById("tarde").style.display = 'block';
            document.getElementById("noite").style.display = 'block';
        } else if ($tipo == 2){
            document.getElementById("diaria_div").style.display = 'block';
            document.getElementById("manha").style.display = 'none';
            document.getElementById("tarde").style.display = 'none';
            document.getElementById("noite").style.display = 'none';
        } else {
            document.getElementById("diaria_div").style.display = 'block';
            document.getElementById("manha").style.display = 'none';
            document.getElementById("tarde").style.display = 'none';
            document.getElementById("noite").style.display = 'none';
        }
        
});
});



function show_map(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log(latitude);
    console.log(longitude);
  }