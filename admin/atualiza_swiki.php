<?
include_once("function.inc");

// Encontra id_swiki
$get_swiki = explode(".",$ident);

if ($get_swiki[0] == "")
{
  //verifica se existe swiki
  if ($atualiza=="0") header("Location:setswiki.php"); //Redireciona para interface anterior
}
else
{
	$atualiza = $get_swiki[0];
}

   $sess = new coweb_session;

   $sess->read();

   $dbh = db_connect();
   
   if ($erro == 2)
	$mensagem = "Alias já existente. Por favor altere.";

   # seleciona base de dados
   mysql_select_db($dbname,$dbh);
 
   $query = "SELECT status, titulo, username, admin, admin_mail,visivel,semestre,id_eclass,annotation_login,alias FROM swiki where id='$atualiza'";
   $sql = mysql_query("$query",$dbh);
                while ($tupla = mysql_fetch_array($sql)){  
                        $status = $tupla[status];
                        $titulo = $tupla[titulo];
                        $username = $tupla[username];
                        $senha = NULL;
                        $admin = $tupla[admin];
                        $admin_mail = $tupla[admin_mail];
                        $visivel = $tupla[visivel];
                        $semestre = $tupla[semestre];
                        $id_eclass = $tupla[id_eclass];
                        $annLog = $tupla[annotation_login];
			$alias = $tupla[alias];
                }
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
    if ((document.formadmin.status.value == 1) && ((document.formadmin.passwd.value == 0) || (document.formadmin.usuario.value == 0) )) {
	alert('Com login, os campos login e senha são obrigatórios!');
	document.formadmin.passwd.focus();
	return false;
    } 
	return true;
}    
</script>
<?
        include("header.php");
?>
<center>
<form method="post" action="function.php" name="formadmin" onSubmit="return ValidaForm();">
<FONT SIZE=+1 COLOR="#AAOOOO">
<? echo $mensagem; ?>
</FONT>
<table border="1" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td valign="middle" colspan="2" class="table-header">Atualizar Swiki</td>
    </tr>
	<tr>
	<td valign="middle">Nome</td>
	<td><input type="text" name="new_swiki" size="25" maxlength="70" value="<? echo $titulo?>"></td>
        </tr>
	<tr>
	<td valign="middle">Alias</td>
	<td><input type="text" name="alias" size="25" maxlength="70" value="<? echo $alias?>"></td>
	</tr>
        <tr><td valign="middle">Administrador</td>
        <td><input type="text" name="admin" size="25" maxlength="40" value="<? echo $admin?>"></td>
	</tr>
	<tr><td valign="middle">Email</td>
        <td><input type="text" name="admail" size="25" maxlength="50" value="<? echo $admin_mail?>"></td>
        </tr>
        <tr><td valign="middle">Login (opcional)</td>
        <td><input type="text" name="usuario" size="25" maxlength="10" value="<? echo $username?>"></td>
	</tr>
        <tr><td valign="middle">Senha (opcional)</td>
        <td><input type="password" name="passwd" size="25" maxlength="10" value="<? echo $senha?>"></td>
        </tr>
        <tr> 
        <td valign="middle">Status</td>
	<td><select name="status">
        <option value="0" <? if ($status =="0") echo "selected" ?>>Padrão</option>
        <option value="1" <? if ($status =="1") echo "selected" ?>>Login</option>
        </select></td></tr>
        <tr>
        <tr> 
        <td valign="middle">Visibilidade</td>
	<td><select name="vis">
        <option value="S" <? if ($visivel =="S") echo "selected" ?>>Sim</option>
        <option value="N" <? if ($visivel =="N") echo "selected" ?>>N&atilde;o</option>
        </select></td></tr>
	<tr>
        <td valign="middle">Semestre (visualização)</td>
        <td><select name="sem">
        <option value="T" <? if ($semestre =="T") echo "selected" ?>>Todos</option>
<?
        $today = getdate();
        $year = $today['year'];
        $year_ini = $year - 2;
        $year_fim = $year + 2;
        
        for ($year_ini,$j=1;$year_ini<=$year_fim;$year_ini++,$j--) {
        
	//verifica comparacao com BD
	$aux = '1_'.$year_ini;
	if ($semestre==$aux) $st="selected";
		else $st="";

        echo "<option value=\"1_$year_ini\" $st>$j&ordm; Sem - $year_ini</option>";

	//semetre: 1 ou 2
        $j++;

	//verifica comparacao com BD
	$aux = '2_'.$year_ini;
	if ($semestre==$aux) $st="selected";
		else $st="";

        echo "<option value=\"2_$year_ini\" $st>$j&ordm; Sem - $year_ini</option>";
        
        }
?>
    </select></td></tr>
    <tr>
        <td valign="middle">Curso eClass:</td>
        <td><select name="eclass">
<?

    $dbh_ce = ce_db_pconnect();

    # seleciona base de dados
    mysql_select_db($ce_dbname,$dbh_ce);

    $query = "SELECT curso_id,nome,semestre FROM curso ORDER BY nome";
    $sql = mysql_query($query,$dbh_ce);

    echo "<option value=\"0\">Nenhum Curso</option>\n";

    while ($tupla = mysql_fetch_array($sql)) {

        $curso_id = $tupla[curso_id];
        $nome = $tupla[nome];
        $semestre = $tupla[semestre];

        if ($id_eclass == $curso_id) $sel = "selected";
                else $sel = "";

        echo "<option value=\"$curso_id\" $sel>$nome - $semestre</option>\n";

    }

?>
    </select></td></tr>
    <tr> 
        <td valign="middle">Anota&ccedil;&otilde;es Privadas:</td>
	<td><select name="ann_log">
        <option value="S" <? if ($annLog =="S") echo "selected" ?>>Sim</option>
        <option value="N" <? if ($annLog =="N") echo "selected" ?>>N&atilde;o</option>
    </select></td></tr>
    <tr>
	<td valign="middle" colspan="2">
	<input type="reset" name="apaga" value="Limpa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="continua" value="Submit"></td>
    </tr>
	<tr>
    <td valign="middle" colspan="2" class="table-footer">
	<a href="main.php">Menu Principal</a></td>
    </tr>
	<input type="hidden" name="parametro" value="3"> 
	<input type="hidden" name="atualiza" value="<?echo $atualiza?>"> 
	</table></form></center><br>

<?php
        include("footer.php");
?>
