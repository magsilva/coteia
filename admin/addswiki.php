<?php
include_once( "function.inc" );
$sess = new coweb_session;
$sess->read();
?>

<html>

<head>
<script LANGUAGE="JavaScript">
function ValidaForm() 
{
 
    if ((document.formadmin.new_swiki.value == 0) || (document.formadmin.admin.value == 0) || (document.formadmin.admail.value == 0)) {
		alert('Os campos de nome, administrador e mail são de preenchimento obrigatório !');
        document.formadmin.new_swiki.focus();
        return false;
    }
 
	return true;
}    
</script>
<?
        include("header.php");
?>

<div align="center">
<form method="post" action="function.php" name="formadmin" onSubmit="return ValidaForm();">

<table border="1" cellspacing="0" cellpadding="5" class="box-table">
<tr>
	<td valign="middle" colspan="2" class="table-header">Adicionar Swiki</td>
</tr>
<tr>
	<td valign="middle">Nome:</td>
	<td><input type="text" name="new_swiki" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Administrador</td>
	<td><input type="text" name="admin" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Email</td>
	<td><input type="text" name="admail" size="25" /></td>
</tr>
<tr> 
	<td valign="middle">Tipo</td>
	<td>
		<select name="status">
			<option value="0" selected>Padrão</option>
			<option value="1" >Login</option>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Login (opcional)</td>
	<td><input type="text" name="usuario" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Senha (opcional)</td>
	<td><input type="password" name="passwd" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Visível</td>
	<td>
		<select name="vis">
			<option value="S" selected>Sim</option>
			<option value="N" >Não</option>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Semestre</td>
	<td><select name="sem">
		<option value="T" selected>Todos</option>
		<?php
			$today = getdate();
			$year = $today['year'];
			$year_ini = $year - 2;
			$year_fim = $year + 2;
			for ( $year_ini, $j=1; $year_ini <= $year_fim; $year_ini++, $j-- ) {
				echo "<option value=\"1_$year_ini\">$j&ordm; semestre - $year_ini</option>";
				$j++;
				echo "<option value=\"2_$year_ini\">$j&ordm; semestre - $year_ini</option>";
			}
		?>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Curso eClass</td>
	<td>
		<select name="eclass">
			<option value="0" selected>Nenhum Curso</option>
			<?php
				$dbh_ce = ce_db_pconnect();
				
				# seleciona base de dados
				mysql_select_db($ce_dbname,$dbh_ce);
				
				$query = "SELECT curso_id,nome,semestre FROM curso ORDER BY nome";
				$sql = mysql_query($query,$dbh_ce);
				
				while ($tupla = mysql_fetch_array($sql)) {
					$curso_id = $tupla[curso_id];
					$nome = $tupla[nome];
					$semestre = $tupla[semestre];
					echo "<option value=\"$curso_id\">$nome - $semestre</option>\n";
				}
			?>
		</select>
	</td>
</tr>
<tr> 
	<td valign="middle">Anotações Privadas:</td>
	<td>
		<select name="ann_log">
			<option value="S" >Sim</option>
			<option value="N" selected >Não</option>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle" colspan="2">
		<input type="submit" name="continua" value="Submit"/>
	</td>
</tr>
<tr>
	<td valign="middle" colspan="2" class="table-footer">
		<a href="main.php">Menu Principal</a>
	</td>
</tr>
<input type="hidden" name="parametro" value="1" />
</table>
</form>
</div>

<?php
	include("footer.php");
?>
