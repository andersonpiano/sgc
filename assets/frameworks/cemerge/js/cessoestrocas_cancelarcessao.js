function cancelarCessao($cessao_id) {
    var conf = confirm('Deseja realmente cancelar a cessão?');
    if (conf) {
        alert('Cessão cancelada');
    }
}