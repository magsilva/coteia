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
$today = getdate(); 
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 
if ($month <= '6') {
$semester = 1;
} else {
$semester = 2;
}

echo "<b>Semestre Atual: $semester&ordm; de $year</b><br /><ul>\n";

//semestre atual
$sem_atual = $semester . '_' . $year;

// Conexao com BD
$dbh = db_connect();
# seleciona base de dados
mysql_select_db($dbname,$dbh);
$sql = mysql_query("SELECT id,status,titulo,id_chat,admin,admin_mail,visivel FROM swiki where (semestre='$sem_atual' || semestre='T') order by titulo",$dbh);	
while ($tupla = mysql_fetch_array($sql)) {
	if (!empty($tupla["titulo"])) {
		$final = $tupla["titulo"];
	}
	$session_id = $tupla["id_chat"];
	$admin = $tupla["admin"];
	$admail = $tupla["admin_mail"];

	$ident = $tupla["id"];
	$status = $tupla["status"];
	$sql_aux = mysql_query("SELECT id_sw FROM gets",$dbh);
	
	//$ident recebe o nÃºmero de paginas ja criadas na swiki (relacionadas na tabela GETS)
	$query_cont = "SELECT COUNT(*) as CONTADOR from gets where id_sw='$ident'";
	$sql_cont = mysql_query("$query_cont",$dbh);
	$tupla_cont = mysql_fetch_array($sql_cont);
	$nro_paginas = $tupla_cont["CONTADOR"];

	$token = false;
	while ( ($tupla_aux = mysql_fetch_array( $sql_aux ) ) && ( $token == false ) ) {
		$comp = $tupla_aux[ "id_sw" ];
		if ( $ident == $comp ) {
			$token=true;
		}	else {
			$token=false;
		}
	}

	if ($token==true) {
		echo "\t<li><a href=\"mostra.php?ident=$ident\" onMouseOver=\"window.status='$final'; return true\" onMouseOut=\"window.status=' '; return true\">$final</a> ($nro_paginas) página(s):  (administrador: <a href=\"mailto:$admail\">$admin</a>)";
		echo " <a href=\"#\" onClick=\"window.open('atualiza_login.php?id=$ident','login','top=50,left=100,menubar=no,resizable=no,width=300,height=200')\">
        <img border=\"0\" src=\"$URL_IMG/button_edit.png\" alt=\"Edit this Page\"/></a>";
	
	} 
	else {
                echo "\t<li>$final (Página não Criada)";
        }

        echo " <a href=\"atualiza_swiki.php?ident= $ident\">
	<img border=\"0\" src=\"$URL_IMG/button_properties.png\" alt=\"Edit this Page\"/></a></li>";
	
}

echo "</ul><br /><b>Outras Entradas:</b><br /><ul>\n";

$sql = mysql_query("SELECT id,status,titulo,id_chat,admin,admin_mail,visivel FROM swiki where (semestre<>'$sem_atual' && semestre<>'T') order by titulo",$dbh);	

while ($tupla = mysql_fetch_array($sql)) {
	if (!empty($tupla["titulo"])) {
		$final = $tupla["titulo"];
	}

	$session_id = $tupla["id_chat"];
	$admin = $tupla["admin"];
	$admail = $tupla["admin_mail"];

	$ident = $tupla["id"];
	$status = $tupla["status"];
	$sql_aux = mysql_query("SELECT id_sw FROM gets",$dbh);

	//$ident recebe o nÃºmero de paginas ja criadas na swiki (relacionadas na tabela GETS)
	$query_cont = "SELECT COUNT(*) as CONTADOR from gets where id_sw='$ident'";
	$sql_cont = mysql_query("$query_cont",$dbh);
	$tupla_cont = mysql_fetch_array($sql_cont);
	$nro_paginas = $tupla_cont["CONTADOR"];

	$token = false;
	while (($tupla_aux = mysql_fetch_array($sql_aux)) && ($token == false)) {
		$comp = $tupla_aux["id_sw"];
		if ($ident==$comp) {
			$token=true; 
		} else {
			$token=false;
		}
	}

	if ($token==true) {
		echo "\t<li><a href=\"mostra.php?ident=$ident\" onMouseOver=\"window.status='$final'; return true\" onMouseOut=\"window.status=' '; return true\">$final</a> ($nro_paginas) página(s):  (administrador: <a href=\"mailto:$admail\">$admin</a>)";
		 echo " <a href=\"#\" onClick=\"window.open('atualiza_login.php?id=$ident','login','top=50,left=100,menubar=no,resizable=no,width=300,height=200')\">
        <img border=\"0\" src=\"$URL_IMG/button_edit.png\" alt=\"Edit this Page\"/></a>";

	} else {
                echo "\t<li>$final (Página não Criada)";
        }

	echo " <a href=\"atualiza_swiki.php?ident= $ident\">
        <img border=\"0\" src=\"$URL_IMG/button_properties.png\" alt=\"Edit this Page\"/></a></li>";

}

echo "</ul><br /><b>Total de Entradas:</b>";

$query_cont = "SELECT COUNT(*) as CONTADOR from swiki where visivel='S'";  
$sql_cont = mysql_query($query_cont,$dbh); 	
$tupla_cont = mysql_fetch_array($sql_cont); 	 	
$nro_swvs = $tupla_cont["CONTADOR"];

$query_cont = "SELECT COUNT(*) as CONTADOR from swiki where visivel='N'";  
$sql_cont = mysql_query($query_cont,$dbh); 	
$tupla_cont = mysql_fetch_array($sql_cont); 	 	
$nro_swnvs = $tupla_cont["CONTADOR"];

echo " $nro_swvs [+ $nro_swnvs]";

echo "<br /><b>Total de Páginas:</b>";

$query_cont = "SELECT COUNT(*) as CONTADOR from paginas";  
$sql_cont = mysql_query($query_cont,$dbh);
$tupla_cont = mysql_fetch_array($sql_cont);
$nro_pgs = $tupla_cont["CONTADOR"];

echo " $nro_pgs<br />";

?>

<br />
<hr />
<b><a href="help.php" onmouseover="window.status='Ajuda - Coteia'; return true" onmouseout="window.status=' '; return true">Help</a></b>

<br />
<img alt="CoTeia" src="<?php echo $URL_IMG;?>/logo.png" />
<br /><i>CoTeia - Ferramenta de Edição Colaborativa Baseada na Web </i>

</body>

</html>
