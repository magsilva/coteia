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
    if ((document.formadmin.status.value == 1) && ((document.formadmin.usuario.value == "") || (document.formadmin.passwd.value == ""))) {
		alert('Com login, os campos login e senha devem ser preenchidos');
		return false;
    } 
	return true;
}    
</script>
<?
        include("header.php");
	if ($erro==2){
		$mensagem = "Alias já existente. Por favor altere.";
		$nome = $_POST['new_swiki'];
		$alias = $_POST['alias'];
		$admin = $_POST['admin'];
		$email = $_POST['admail'];
		$tipo = $_POST['status'];
		$login = $_POST['usuario'];
		$senha = $_POST['passwd'];
		$visivel = $_POST['vis'];
		$semestre = $_POST['sem'];
		$eclass = $_POST['eclass'];
		$anotacoes = $_POST['ann_log'];
	}
	else{
		if ($erro==1){
			$mensagem = "Swiki criada com sucesso!";
		}
		$nome = "";
                $alias = "";
                $admin = "";
                $email = "";
                $tipo = "";
                $login = "";
                $senha = "";
                $visivel = "";
                $semestre = "";
                $eclass = "";
                $anotacoes = "";
	}
?>

<div align="center">
<form method="post" action="function.php" name="formadmin" onSubmit="return ValidaForm();">
<FONT SIZE=+1 COLOR="#AA0000">
<? echo $mensagem;?>
</FONT>
<table border="1" cellspacing="0" cellpadding="5" class="box-table">
<tr>
	<td valign="middle" colspan="2" class="table-header">Adicionar Swiki</td>
</tr>
<tr>
	<td valign="middle">Nome</td>
	<td><input type="text" value="<? echo $nome;?>" name="new_swiki" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Alias</td>
	<td><input type="text" value="<? echo $alias;?>" name="alias" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Administrador</td>
	<td><input type="text" value="<? echo $admin;?>" name="admin" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Email</td>
	<td><input type="text" value="<? echo $email;?>" name="admail" size="25" /></td>
</tr>
<tr> 
	<td valign="middle">Tipo</td>
	<td>
		<select name="status">
			<? if ($tipo==1){ 
				echo "<option value=\"0\">Padrão</option>
				<option value=\"1\" selected >Login</option>";
			   }
			   else{
				echo "<option value=\"0\" selected>Padrão</option>
				<option value=\"1\">Login</option>";
			   }
			?>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Login (opcional)</td>
	<td><input type="text" value="<? echo $login;?>" name="usuario" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Senha (opcional)</td>
	<td><input type="password" value="<? echo $senha;?>" name="passwd" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Visível</td>
	<td>
		<select name="vis">
			<? if ($visivel=="N"){
				echo "<option value=\"S\">Sim</option>
				<option value=\"N\" selected>Não</option>";
			   }
			   else{
				echo "<option value=\"S\" selected>Sim</option>
				<option value=\"N\" >Não</option>";
			   }
			?>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Semestre</td>
	<td><select name="sem">
		<?php
			$today = getdate();
			$year = $today['year'];
			$month = $today['month'];
			$year_ini = $year - 2;
			$year_fim = $year + 2;
			echo "<option value=\"T\"";
			if ($semestre == "T")
				echo " selected";
			echo ">Todos</option>";
			for ( $year_ini, $j=1; $year_ini <= $year_fim; $year_ini++, $j-- ) {
				echo "<option value=\"1_$year_ini\"";
				if (($year_ini == $year) && ($month <= 6) && ($semestre==""))
					echo " selected";
				else
					if ($semestre == "1_$year_ini")
						echo " selected";
				echo ">$j&ordm; semestre - $year_ini</option>";
				$j++;
				echo "<option value=\"2_$year_ini\"";
				if (($year_ini == $year) &&($month > 6) && ($semestre==""))
					echo " selected";
				else
					if ($semestre == "2_$year_ini")
						echo " selected";
				echo ">$j&ordm; semestre - $year_ini</option>";
			}
		?>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Curso eClass</td>
	<td>
		<select name="eclass">
			<?php
				echo "<option value=\"0\"";
				if ($eclass=="0")
					echo " selected";
				echo ">Nenhum Curso</option>";
				$dbh_ce = ce_db_pconnect();
				
				# seleciona base de dados
				mysql_select_db($ce_dbname,$dbh_ce);
				
				$query = "SELECT curso_id,nome,semestre FROM curso ORDER BY nome";
				$sql = mysql_query($query,$dbh_ce);
				
				while ($tupla = mysql_fetch_array($sql)) {
					$curso_id = $tupla[curso_id];
					$nome = $tupla[nome];
					$semestre = $tupla[semestre];
					echo "<option value=\"$curso_id\"";
					if ($eclass == $curso_id)
						echo " selected";
					echo ">$nome - $semestre</option>\n";
				}
			?>
		</select>
	</td>
</tr>
<tr> 
	<td valign="middle">Anotações Privadas</td>
	<td>
		<select name="ann_log">
			<?
			if ($anotacoes== "S"){
				echo "<option value=\"S\" selected>Sim</option>";
				echo "<option value=\"N\">Não</option>";
			}
			else{
				echo "<option value=\"S\">Sim</option>";
				echo "<option value=\"N\" selected>Não</option>";
			}
			?>
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
