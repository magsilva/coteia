<?
   include_once("function.inc");

   $sess = new coweb_session;

   $sess->read();
?>


<html>
<head>
	<title>Muda Senha</title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table width="100%">

<h1>Muda Senha</h1>
<form name="login" METHOD="post" action=mudasenha.php >
	<br /><b>Senha:</b>
	<br /><input type="password" name="passwd" size="16" />

	<BR /><input type=submit value="OK" name="login" />

	<input type="hidden" name="id" value="<?php echo $id;?>" />
</form>

</body>

</html>
