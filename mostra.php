<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="Pragma" content="no-cache" />
	<title>P�gina N�o Encontrada</title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
/*
* Exibe no browser o arquivo que � passado como parametro.
*/

include_once("function.inc");

// Encontra id_swiki
$get_swiki = explode(".",$ident);
$id_swiki = $get_swiki[0];

// Conecta com BD
$dbh = db_connect();

// Seleciona base de dados
mysql_select_db($dbname,$dbh);

$sql = mysql_query("SELECT status FROM swiki WHERE id='$id_swiki'");
$tupla = mysql_fetch_array($sql);
$status = $tupla[status];
if ($status == '1') {
	session_start("login");
	if( !(session_is_registered("namuser") AND session_is_registered("coduser"))) {
		echo "Essa � uma <b>�rea restrita</b>.<br />Voc� n�o tem permiss�o para acess�-la.";
		exit;
	}
}

// Para as p�ginas geradas
// include( "toolbar.php" );

$sucesso = @include("$PATH_ARQUIVOS/$ident.html"); 

if (!$sucesso) {
?>


<div align="center">
	Desculpe! A p�gina requisitada n�o foi encontrada.
	<br />
	Em caso de d�vidas, entre em contato com o <a HREF="mailto:<?php echo $ADMIN_MAIL; ?>">administrador</a>.
	<br />
	<a href="index.php"><img src="<?php echo $URL_IMG; ?>/home.png" /></a>
</div>
<?php
}
?>

</body>

</html>

