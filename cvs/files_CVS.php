<?

require ("function_cvs.inc");
require ("../function.inc");

	$dbh = db_connect();
	
	# seleciona base de dados
	mysql_select_db($dbname,$dbh);
      
$sql = mysql_query("SELECT ident FROM paginas order by ident",$dbh);	

while ($tupla = mysql_fetch_array($sql)) {

	$ident = $tupla[ident];		

	add_cvs($ident,"html/");

}

?>
