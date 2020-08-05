<html>
<body>
    <h1>Sistema de Gestão Cemerge</h1>
    <h2><?php echo(lang('plantoes_email_aviso_cessao'));?></h2>
    <p><?php
    $message = 'Oi, ' . $profissional_substituto_nome . '!<br>';
    $message .= $profissional_passagem_nome . ' passou um plantão para você!<br>';
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