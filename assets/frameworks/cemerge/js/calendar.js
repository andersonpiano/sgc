document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var datainicial = document.getElementById('datainicial');
    var dtinicial = new Date(datainicial.value);
    var month = dtinicial.getUTCMonth() + 1;
    if (calendarEl != null) {
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
                url: 'plantoes/plantoespormes/' + month,
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