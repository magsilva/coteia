<?
/*
* index.php
*
* Funcionalidade: Permitir ao usuario que insira seu login e sua senha.
* Passa parametros (login e senha) para admin.php e prossegue ou cancela a operacao de login.
*
*/
?>

<html>

<head>

<script>
function verifica_campos(admin) {
    if (admin.usuario.value == "") {
        alert('Campo de Login precisa ser preenchido!');
	return false;
    }
    return true;	
}
</script>

<?
	include("header.php");
?>

<form name="form_admin" method="post" action="autentica.php" onSubmit="return verifica_campos(document.form_admin);">
	<div align="center">
	<table border="1" cellspacing="0" cellpadding="2" class="box-table">
	<tr>
		<th>Login:</th>
		<td><input class="login" type="text" size="15" name="usuario"></td>
	</tr>
	<tr>
		<th>Password:</th>
		<td><input class="login" type="password" size="15" name="passwd"></td>
	</tr>
	<tr>
		<td colspan="2"><input class="login" type="submit" name="entra" value="Login"></td>
	</tr>
	</table>
	</div>
</form>

<?
	include("footer.php");
?>
