function cancelarCessao(cessao_id) {
    var conf = confirm('Deseja realmente cancelar a cessão?');
    var url = '/sgc/admin/plantoes/cancelarcessao/' + cessao_id;
    if (conf) {
        $.ajax({
            url: url,
            data: {
                id: cessao_id
            },
            method: 'post',
            dataType: 'json',
            success: function() {
                $('#row' + cessao_id).fadeOut("normal", function() {
                    $(this).remove();
                });
            },
        });
    }
}
