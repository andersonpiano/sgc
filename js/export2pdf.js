$(document).ready(function(){
    $(document).on('click', '#bt_gerar_pdf', function() {
        var doc = new jsPDF('landscape', 'pt', 'a4');
        doc.fromHTML($('#wrapper'), function() {
            doc.save("SGC.pdf");
        });
    });
});