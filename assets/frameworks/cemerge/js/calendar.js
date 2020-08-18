document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var setor = document.getElementById('setor_id');
    var profissional = document.getElementById('profissional_id');
    var datainicial = document.getElementById('datainicial');
    var dtinicial = new Date(datainicial.value);
    var month = dtinicial.getUTCMonth() + 1;
    var url = "";
    var tipoescala = document.getElementById('tipoescala');
    var currentPage = window.location.pathname;
    if (currentPage == '/sgc/admin/plantoes') {
        switch (tipoescala.value) {
            case "0" : // Minha escala consolidada
                url = 'plantoes/minhaescalaconsolidada/mes/' + month;
                url += '/setor/' + setor.value;
                url += '/profissional/' + profissional.value;
                break;
            case "1" : // Minhas trocas e passagens
                url = 'plantoes/minhastrocasepassagens/mes/' + month;
                url += '/setor/' + setor.value;
                url += '/profissional/' + profissional.value;
                break;
            case "2" : // Consolidada do setor
                url = 'plantoes/escalaconsolidadadosetor/mes/' + month;
                url += '/setor/' + setor.value;
                break;
            default :
                break;
        }
    } else if (currentPage == '/sgc/admin/escalas') {
        switch (tipoescala.value) {
            case "0" : // Original
                // Implementar
                url = 'escalas/escalaoriginaldosetor/mes/' + month;
                url += '/setor/' + setor.value;
                break;
            case "1" : // Consolidada
                url = 'escalas/escalaconsolidadadosetor/mes/' + month;
                url += '/setor/' + setor.value;
                break;
            case "2" : // Trocas e Passagens
                // Implementar
                url = 'escalas/trocasepassagensdosetor/mes/' + month;
                url += '/setor/' + setor.value;
                break;
            default :
                break;
        }
    }
    if (calendarEl != null && url != "") {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            height: 'auto',
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            initialDate: datainicial.value,
            headerToolbar: {
                left: 'title',
                center: '',
                right: ''
            },
            events: {
                url: url,
                failure: function() {
                    alert("Ocorreu um erro ao buscar os plantões");
                    //document.getElementById('script-warning').style.display = 'block'
                }
            },
            loading: function(bool) {
                //document.getElementById('loading').style.display = bool ? 'block' : 'none';
            }
        });
        calendar.render();
    }
});