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
		echo "Essa é uma <b>área restrita</b>.<br>Você não tem permissão para acessá-la.";
		exit;
	}
}

$sucesso = @include("$PATH_ARQUIVOS/$ident.html"); 

if (!$sucesso) {
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="Pragma" content="no-cache" />
	<title>Página Não Encontrada</title>
</head>

<body>
<img src='<?php echo $URL_IMG; ?>/viewbw.png' />
<img src='<?php echo $URL_IMG; ?>/editbw.png' />
<img src='<?php echo $URL_IMG; ?>/historybw.png' />
<img src='<?php echo $URL_IMG; ?>/indicebw.png' />
<img src='<?php echo $URL_IMG; ?>/mapbw.png' />
<img src='<?php echo $URL_IMG; ?>/changesbw.png' />
<img src='<?php echo $URL_IMG; ?>/uploadbw.png' />
<img src='<?php echo $URL_IMG; ?>/searchbw.png' />
<img src='<?php echo $URL_IMG; ?>/helpbw.png' />
<img src='<?php echo $URL_IMG; ?>/chatbw.png' >
<img src='<?php echo $URL_IMG; ?>/notebw.png' />
<img src='<?php echo $URL_IMG; ?>/printbw.png' />
<br />

<div align="center">
	Desculpe! A página requisitada não foi encontrada.
	<br />
	Em caso de dúvidas, entre em contato com o <a HREF="mailto:<?php echo $ADMIN_MAIL; ?>">administrador</a>.
	<br />
	<a href="index.php"><img src="$URL_IMG/home.png" /></a>
</div>

</body>

</html>
