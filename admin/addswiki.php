<?php
include_once( "function.inc" );
$sess = new coweb_session;
$sess->read();
?>

<html>

<head>
<script language="JavaScript">
function ValidaForm() {
	if ( document.formadmin.new_swiki.value == 0 ||
			document.formadmin.admin.value == 0 ||
			document.formadmin.admail.value == 0 ) {
		alert( 'Os campos de nome, administrador e mail são de preenchimento obrigatório.' );
		document.formadmin.new_swiki.focus();
		return false;
	}

	if ( document.formadmin.status.value == 1 && ( document.formadmin.usuario.value == "" || document.formadmin.passwd.value == "" ) ) {
		alert( 'Os campos login e senha devem ser preenchidos' );
		return false;
	}

	return true;
}    
</script>

<?php
	include( "header.php" );
	if ( $erro == 2 ) {
		$mensagem = "Alias já existente. Por favor altere.";
		$nome = $_REQUEST[ 'new_swiki' ];
		$alias = $_REQUEST[ 'alias' ];
		$admin = $_REQUEST[ 'admin' ];
		$email = $_REQUEST[ 'admail' ];
		$tipo = $_REQUEST[ 'status' ];
		$login = $_REQUEST[ 'usuario' ];
		$senha = $_REQUEST[ 'passwd' ];
		$visivel = $_REQUEST[ 'vis' ];
		$semestre = $_REQUEST[ 'sem' ];
		$eclass = $_REQUEST[ 'eclass' ];
		$anotacoes = $_REQUEST[ 'ann_log' ];
	} else {
		if ( $erro == 1 ) {
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
<h2><?php echo $mensagem;?></h2>

<table border="1" cellspacing="0" cellpadding="5" class="box-table">
<tr>
	<td valign="middle" colspan="2" class="table-header">Adicionar Swiki</td>
</tr>
<tr>
	<td valign="middle">Nome</td>
	<td><input type="text" value="<?php echo $nome;?>" name="new_swiki" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Alias</td>
	<td><input type="text" value="<?php echo $alias;?>" name="alias" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Administrador</td>
	<td><input type="text" value="<?php echo $admin;?>" name="admin" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Email</td>
	<td><input type="text" value="<?php echo $email;?>" name="admail" size="25" /></td>
</tr>
<tr> 
	<td valign="middle">Tipo</td>
	<td>
		<select name="status">
			<option value="0" <?php if ( $tipo == 0 ) echo " selected";?>>Padrão</option>;
			<option value="1" <?php if ( $tipo == 1 ) echo " selected";?>>Login</option>;
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Login (opcional)</td>
	<td><input type="text" value="<?php echo $login;?>" name="usuario" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Senha (opcional)</td>
	<td><input type="password" value="<?php echo $senha;?>" name="passwd" size="25" /></td>
</tr>
<tr>
	<td valign="middle">Visível</td>
	<td>
		<select name="vis">
			<option value="S" <?php if ( $visivel == "S" ) echo " selected";?>>Sim</option>
			<option value="N" <?php if ( $visivel == "N" ) echo " selected";?>>Não</option>
		</select>
	</td>
</tr>
<tr>
	<td valign="middle">Semestre</td>
	<td><select name="sem">
		<?php
			$today = getdate();
			$year = $today[ 'year' ];
			$month = $today[ 'month' ];
			$year_ini = $year - 2;
			$year_fim = $year + 2;
			echo "<option value=\"T\"";
			if ($semestre == "T") {
				echo " selected";
			}
			echo ">Todos</option>";
			for ( $year_ini, $j=1; $year_ini <= $year_fim; $year_ini++, $j-- ) {
				echo "<option value=\"1_$year_ini\"";
				if ( $year_ini == $year && $month <= 6 && $semestre=="" ) {
					echo " selected";
				} else {
					if ( $semestre == "1_$year_ini" ) {
						echo " selected";
					}
				}
				echo ">$j&ordm; semestre - $year_ini</option>";
				$j++;
				echo "<option value=\"2_$year_ini\"";
				if ( $year_ini == $year && $month > 6 && $semestre=="" ) {
					echo " selected";
				}	else {
					if ($semestre == "2_$year_ini") {
						echo " selected";
					}
				}
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
				if ($eclass=="0") {
					echo " selected";
				}
				echo ">Nenhum Curso</option>";
				$dbh_ce = ce_db_pconnect();
				
				# seleciona base de dados
				mysql_select_db( $ce_dbname, $dbh_ce );
				
				$query = "SELECT curso_id,nome,semestre FROM curso ORDER BY nome";
				$sql = mysql_query( $query, $dbh_ce );
				
				while ( $tupla = mysql_fetch_array( $sql ) ) {
					$curso_id = $tupla[ "curso_id" ];
					$nome = $tupla[ "nome" ];
					$semestre = $tupla[ "semestre" ];
					echo "<option value=\"$curso_id\"";
					if ( $eclass == $curso_id ) {
						echo " selected";
					}
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
			<option value="S" <?php if ( $anotacoes == "S" ) echo " selected";?>>Sim</option>";
			<option value="N" <?php if ( $anotacoes == "N" ) echo " selected";?>>Não</option>";
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
