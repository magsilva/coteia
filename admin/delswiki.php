<?php
   include_once("function.inc");
   $sess = new coweb_session;
   $sess->read();
?>

<html>
<head>
<script language="JavaScript">
function ValidaForm() {
	if ( document.formadmin.remove.value == 0 ) {
		alert('Selecione uma swiki para ser removida!');
		document.formadmin.remove.focus();
		return false;
	}
	return true;
}    
</script>


<?php
	include( "header.php" );
	if ( isset( $erro ) && $erro == 3 ) {
		$mensagem = "Swiki removida com sucesso!";
		echo "<center><h2>$mensagem</h2></center>";
	}
?>

<center>

<form method="post" action="function.php" name="formadmin" onSubmit="return ValidaForm();">

<table border="1" cellspacing="0" cellpadding="5" class="box-table">
<tr>
	<td valign="middle" colspan="2" class="table-header">Remover Swiki</td>
</tr>
<tr>
	<td valign="middle">Swiki</td>
	<td>
	<select name="remove">
		<option value="0" selected>Escolha a swiki</option></font>
		<?php
			db_connect();
			mysql_select_db( $dbname, $dbh );
			$sql = "SELECT id,titulo FROM swiki order by titulo";
			$query = mysql_query( $sql );
			while ( $tuple = mysql_fetch_array( $query ) ) {
				$title = $tuple[ "titulo" ];
				$id_title = $tuple[ "id" ];
				echo "\t\t\t<option value=\"$id_title\">$title</option>";
			}
		?>
	</select>
	</td>
</tr>
<tr>
	<td valign="middle" colspan="2">
		<input type="submit" name="continua" value="Submit">
	</td>
</tr>
<tr>
	<td valign="middle" colspan="2" class="table-footer">
		<a href="main.php">Menu Principal</a>
	</td>
</tr>

<input type="hidden" name="parametro" value="2"> 
</table>
</form>

</center>

<br />

<?php
	include("footer.php");
?>
