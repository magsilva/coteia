<?php
include_once( "function.inc" );
$sess = new coweb_session;
$sess->read();

?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="Shortcut icon" href="<?php echo $URL_IMG; ?>/Logo.ico" />
<link href="coteia.css" rel="stylesheet" type="text/css" />
<title>CoTeia</title>
</head>

<body>

<h2>CoTeia</h2>
<?php

// Conexao com BD
$dbh = db_connect();
# seleciona base de dados

mysql_select_db($dbname,$dbh);

$sql = mysql_query("SELECT ident,titulo,autor FROM  paginas where ((ident like '$ident.%') && (ident not like '$ident.%.%')) order by titulo", $dbh);	
while ($tupla = mysql_fetch_array($sql)) {
	if (!empty($tupla["titulo"])) {
		$final = $tupla["titulo"];
	}
	$id = $tupla["ident"];
	$sql_aux = mysql_query("SELECT id_pag FROM gets",$dbh);
	//$ident recebe o nÃºmero de paginas ja criadas na swiki (relacionadas na tabela GETS)

	$token = false;
	while ( ($tupla_aux = mysql_fetch_array( $sql_aux ) ) && ( $token == false ) ) {
		$comp = $tupla_aux[ "id_pag" ];
		if ( $id == $comp ) {
				$token=true;
		}	
		else {
				$token=false;
		}
	}
	
	if ($token==true) {
		echo "\t<li><a href=\"mostra.php?ident=$id\" onMouseOver=\"window.status='$final'; return true\" onMouseOut=\"window.status=' '; return true\">$final </a>";
	}
	echo "<a href=\"#\" onClick=\"window.open('atualiza_login.php?id=$id','login','top=50,left=100,menubar=no,resizable=no,width=300,height=200')\">
	<img src=\"$URL_IMG/doc.png\" alt=\"Edit this Page\"/></a></li>";

	
}



?>

<br />
<hr />
<b><a href="help.php" onmouseover="window.status='Ajuda - Coteia'; return true" onmouseout="window.status=' '; return true">Help</a></b>

<br />
<img alt="CoTeia" src="<?php echo $URL_IMG;?>/logo.png" />
<br /><i>CoTeia - Ferramenta de Edição Colaborativa Baseada na Web </i>

</body>

</html>
