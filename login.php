<html>

<head>
	<title>Login</title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="100%">

<h1>Login Obrigatório</h1>

<form name="login" method="post" action="autentica.php">
	<br /><b>Usuário:</b>
	<br /><input type="text" name="usuario" size="16" />

	<br /><b>Senha:</b>
	<br /><input type="password" name="passwd" size="16" />

	<br /><input type=submit value="Login" name="login" />

	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="token" value="<?php echo $token;?>" />
	<input type="hidden" name="index" value="<?php echo $index;?>" />
</form>

</body>

</html>
