<?php
/*
* Exibe no browser o arquivo que é passado como parametro.
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
		echo "Essa é uma <b>área restrita</b>.<br />Você não tem permissão para acessá-la.";
		exit;
	}
}

$sucesso = @include("$PATH_ARQUIVOS/$ident.html"); 

if (!$sucesso) {
?>

<html>

<head>
	<title>Erro - Página não encontrada</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
include( "toolbar.php" );
?>

<br />
<br />
<div align="center">
	Desculpe! A página requisitada não foi encontrada.
	<br />
	Em caso de dúvidas, entre em contato com o <a HREF="mailto:<?php echo $ADMIN_MAIL; ?>">administrador</a>.
	<br />
	<a href="index.php"><img src="<?php echo $URL_IMG; ?>/home.png" /></a>
</div>
<?php
}
?>

</body>

</html>

