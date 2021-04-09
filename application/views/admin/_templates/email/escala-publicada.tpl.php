<html>
<body>
    <h1>Sistema de Gestão Cemerge</h1>
    <h2><?php echo(lang('plantoes_email_aviso_cessao'));?></h2>
    <p><?php
    $message = 'Oi, ' . $profissional_nome . '!<br>';
    $message .= 'Foi publicada uma nova escala em que você foi inserido<br>';
    $message .= 'Dados da escala:<br>';
    $message .= $data;
    echo($message);
    ?></p>
</body>
</html>