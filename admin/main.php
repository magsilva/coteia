<?php
	include_once( "function.inc" );
	$sess = new coweb_session;
	$sess->read();
?>

<html>
<head>
<?php
	include( "header.php" );
?> 

<center> 

<table border="1" cellspacing="0" cellpadding="5" class="box-table">
<tr>
	<td valign="middle" class="table-header">Funcionalidades</td>
</tr>
<tr>
	<td valign="middle" nowrap="nowrap">
		<a href="setswiki.php">Atualizar Swiki</a><br />
		<a href="addswiki.php">Incluir Nova Swiki</a><br />
		<a href="delswiki.php">Remover Swiki</a><br />
		<a href="useradmin.php">Usuários</a><br />
		<a href="index_coweb.php">Coweb do Administrador</a><br />
		<a href="erros.php">Verificar Erros</a><br />
		<a href="logout.php">Logout</a><br />
	</td>
</tr>
</table>

</center>

<br />

<?php
	include("footer.php");
?>
