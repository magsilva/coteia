<?
//parametros:
//1 = adiciona novo usuario
//2 = apaga usuario
//3 = edita configuracao de usuario

if (isset($par)) {

	include_once("function.inc");

	$dbh = db_connect();

        # seleciona base de dados
        mysql_select_db($dbname,$dbh);

	if ($par == "1") {
   
	$query = "insert into admin (id,nome,login,pass,email) values (NULL,'$user_nome','$user_login',PASSWORD('$user_senha'),'$user_mail')"or die ("Falha ao inserir no Banco de Dados");
	$sql = mysql_query("$query",$dbh);

	header("Location:useradmin.php"); 
		
	} elseif ($par == "2")  {

	$query = "delete from admin where (id='$id') and (login <> 'admin')" or die ("Falha ao inserir no Banco de Dados");
	$sql = mysql_query("$query",$dbh);

	header("Location:useradmin.php"); 

	} elseif ($par == "3") {
        
	$query = "update admin set login='$user_login',nome='$user_nome',email='$user_mail' where (id='$id')" or die ("Falha ao inserir no Banco de Dados");
	$sql = mysql_query("$query",$dbh);
	
	if ((strcasecmp($user_senha,"passwd")) != "0") {

	$q_aux = "update admin set pass=PASSWORD('$user_senha') where (id='$id')" or die ("Falha ao inserir no Banco de Dados");
	$sql_aux = mysql_query("$q_aux",$dbh);
	
	}

	header("Location:useradmin.php"); 

	}
}
?>
