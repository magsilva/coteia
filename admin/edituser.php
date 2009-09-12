<?
    include_once("function.inc");

    $sess = new coweb_session;

    $sess->read();

    global $dbname;
	
    db_connect();

    # seleciona base de dados
    mysql_select_db($dbname);
 
	$query = mysql_query("select nome,login,email from admin where id='$id'");
	while ($tupla = mysql_fetch_array($query)) {
		$euser_nome = $tupla["nome"];
		$euser_login = $tupla["login"];
		$euser_mail = $tupla["email"];
	}

?>
<HTML>
<HEAD>
<?
        include("header.php");
?>
<script LANGUAGE="JavaScript">
function ValidaForm() 
{

    if ((document.formadmin.user_login.value == 0) || (document.formadmin.user_mail.value == 0)) {
        alert('Os campos de email e login são de preenchimento obrigatório !');
        document.formadmin.user_mail.focus();
        return false;
    } 

    if ((document.formadmin.user_mail.value.search("@") == -1 || document.formadmin.user_mail.value.search("[.*]") == -1)) {
        alert( "Entre com um email válido !" );
        document.formadmin.user_mail.focus();
        return false ;
    }

    if (document.formadmin.user_senha.value != document.formadmin.user_resenha.value) {
        alert('As senhas digitadas não coincidem !');
        document.formadmin.user_senha.focus();
        return false;
    }
     
  return true;
}    
</script>
<center>
<table border="1" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td valign="middle" colspan="2" class="table-header">Editar Usu&aacute;rio</td>
    </tr>
	<form method="post" action="userfcao.php" name="formadmin" onSubmit="return ValidaForm();">
	<tr>
        <td valign="middle">Nome:</td>
        <td><input type="text" name="user_nome" size="25" maxlength="70" value="<?echo $euser_nome?>"></td>
        </tr>
        <tr><td valign="middle">Email:</td>
        <td><input type="text" name="user_mail" size="25" maxlength="50" value="<?echo $euser_mail?>"></td>
        </tr>
        <tr>
        <td valign="middle">Login:</td>
        <td><input type="text" name="user_login" size="25" maxlength="70" value="
<?
	echo $euser_login."\"";
	if ($euser_login == "admin") echo " DISABLED";
	echo ">";
?>
	</td>
        </tr>
        <tr><td valign="middle">Senha</td>
        <td><input type="password" name="user_senha" size="25" maxlength="10" value="passwd"></td>
        </tr>
        <tr><td valign="middle">Confirma Senha</td>
        <td><input type="password" name="user_resenha" size="25" maxlength="10" value="passwd"></td>
        </tr>
	<td valign="middle" colspan="2">
	<input type="reset" name="apaga" value="Limpa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="continua" value="Submit"></td>
        </tr>
	<tr>
        <td valign="middle" colspan="2" class="table-footer">
	<a href="main.php">Menu Principal</a></td>
        </tr>
        <input type="hidden" name="par" value="3">
        <input type="hidden" name="id" value="<?echo $id?>">
	</form></table></center><br>
<?
        include("footer.php");
?>
