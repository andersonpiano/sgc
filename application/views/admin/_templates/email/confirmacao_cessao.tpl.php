<html>
<body>
    <h1>Sistema de Gestão Cemerge</h1>
    <h2><?php echo(lang('plantoes_email_confirmacao_cessao'));?></h2>
    <p><?php
    $message = 'Oi, ' . $profissional_passagem_nome . '!<br>';
    $message .= $profissional_substituto_nome . ' aceitou o plantão que você passou pra ele(a)!<br>';
    $message .= 'Dados do plantão:<br>';
    $message .= $unidadehospitalar . '/' . $setor . '<br>';
    $message .= 'Dia ' . date('d/m/Y', strtotime($dataplantao));
    $message .= ' de ' . date('H:i', strtotime($horainicialplantao));
    $message .= ' às ' . date('H:i', strtotime($horafinalplantao)) . '<br>';
    $message .= 'Por favor, entre na plataforma e confirme.';
    echo($message);
    ?></p>
</body>
</html>