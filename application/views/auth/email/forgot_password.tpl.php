<html>
<body>
	<h1>Sistema de Gestão Cemerge</h1>
	<h2><?php echo sprintf(lang('email_forgot_password_heading'), $identity);?></h2>
	<p><?php echo sprintf(lang('email_forgot_password_subheading'), anchor('auth/resetpassword/'. $forgotten_password_code, lang('email_forgot_password_link')));?></p>
	<p>Caso não tenha solicitado, favor ignorar.</p>
</body>
</html>