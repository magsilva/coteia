<html>

<head>
	<title>ChatServer - CoTeia</title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="coteia.js"></script>
</head>

<body>

<br />

<div align="center">
<table border=0 width="100%" bgcolor=#E1F0FF bordercolor=#C0C0C0 bordercolordark=#C0C0C0 bordercolorlight=#C0C0C0>
<tr>
	<td bgcolor=#0099FF align="center">
		<b>Chat Server & CoTeia</b>
	</td>
</tr>
<tr>
	<td align="center">
		<form method="POST" action="http://catuaba.icmc.usp.br/chatserver/cs_fs.php" target="_self" onSubmit="return validar(this);">
			<input type="hidden" name="session_id" value="<?echo $id?>" />
			Nome ou Apelido:
			<input type="text" size="20" name="username" />
	</td>
</tr>
<tr>
	<td align=center>
		<input type="submit" name="chatserver" value="Entrar" />
	</td>
</tr>
</form>
</table>

<a href="http://catuaba.icmc.usp.br/chatserver/saved_sessions.php?session_id=<?echo $id?>">Sessões de Chat</a>
</div>

</body>

</html> 
