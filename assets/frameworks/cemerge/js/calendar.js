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
    if (currentPage == '/sgc/admin/plantoes' ||
        currentPage == '/sgc/admin/coordenador/plantoes' ||
        currentPage == '/sgc/admin/profissional/plantoes'
    ) {
        switch (tipoescala.value) {
            case "0" : // Minha escala consolidada
                url = '/sgc/admin/plantoes/minhaescalaconsolidada/mes/' + month;
                url += '/setor/' + setor.value;
                url += '/profissional/' + profissional.value;
                break;
            case "1" : // Minhas trocas e passagens
                url = '/sgc/admin/plantoes/minhastrocasepassagens/mes/' + month;
                url += '/setor/' + setor.value;
                url += '/profissional/' + profissional.value;
                break;
            case "2" : // Consolidada do setor para o profissional
                url = '/sgc/admin/plantoes/escalaconsolidadadosetor/mes/' + month;
                url += '/setor/' + setor.value;
                break;
            case "3" : // Original do setor
                url = '/sgc/admin/plantoes/escalaoriginaldosetor/mes/' + month;
                url += '/setor/' + setor.value;
                break;
            case "4" : // Consolidada do setor para o coordenador
                url = '/sgc/admin/plantoes/escalaconsolidadadosetor/mes/' + month;
                url += '/setor/' + setor.value;
                break;
            case "5" : // Trocas e passagens do setor // Corrigir
                url = '/sgc/admin/plantoes/trocasepassagensdosetor/mes/' + month;
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

/*
extendedProps -> objeto json dentro de evento que pode trazer mais informações sobre o evento.
eventContent: { html: '<i>some html</i>' }
Após events
viewRender: function( view, element ) {
        if (view.name !== 'agendaDay' && view.name !== 'basicDay') {
            return false;
      }
   
        var $container = $(".fc-body").find(".fc-widget-content:first");

            var html = '<div class="fc-day-grid fc-unselectable">' +
            '<div class="fc-row fc-week fc-widget-content" style="border-right-width: 1px; margin-right: 16px;">' +
                '<div class="fc-bg">' +
                    '<table>' +
                        '<tbody>' +
                            '<tr>' +
                                '<td class="fc-axis fc-widget-content" style="width: 45px;"><span>Time</span></td>' +
                                '<td class="fc-day fc-widget-content fc-mon fc-state-highlight events-label">Event</td>' +
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +
                '</div>' +
                '<div class="fc-content-skeleton">' +
                    '<table>' +
                        '<tbody>' +
                            '<tr>' +
                                '<td class="fc-axis" style="width:45px"></td>' +
                                '<td></td>' +
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +
                '</div>' +
            '</div>' +
        '</div>';

            $container.prepend(html);
*/