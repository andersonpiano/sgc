<html>
<body>
    <h1>Sistema de Gestão Cemerge</h1>
    <h2><?php echo(lang('plantoes_email_confirmacao_cessao'));?></h2>
    <p><?php
    $message = 'Oi, ' . $profissional_passagem_nome . '! <br>';
    $message .= $profissional_substituto_nome . ' aceitou o plantão que você passou pra ele(a)!';
    echo($message);
    ?></p>
</body>
</html>