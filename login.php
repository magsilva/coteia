<html>
<body bgcolor="#E1F0FF">
<table width="100%">
<form NAME="login" METHOD="POST" action="autentica.php">
<tr valign="top">
<td valign="top" colspan="2"><b>Login Obrigatório</b>&nbsp;</a><br><br></td>
</tr>
<tr valign="top">
<td align="Right"><b>Usuário:</b>&nbsp;&nbsp;</td><td><input TYPE="text" Name="usuario" SIZE="16"></td>
</tr>
<tr valign="top">
<td align="right"><b>&nbsp;Senha:</b>&nbsp;&nbsp;</td><td><input TYPE="password" NAME="passwd" SIZE="16">
<br><br></td>
</tr><tr valign="top">
<td colspan="2" align="center"><input type=submit value="Login" name="login"></td>
</tr>
<input type="hidden" name="id" value="<?echo $id?>">
<input type="hidden" name="token" value="<?echo $token?>">
<input type="hidden" name="index" value="<?echo $index?>">
</form>
</table>
</body>
</html>
