<?
//st = 1 => erro de identificador ao criar nova pagina
//st = 2 => nao criou arquivo fisico
//st = 3 => erro de indexador ao criar nova pagina

include_once("function.inc");

$d = getdate();
$data=$d["mday"]."-".$d["mon"]."-".$d["year"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];

$ip = $REMOTE_ADDR;
$host = getHostByAddr($REMOTE_ADDR);
$pagina = $PHP_SELF;
$browser = $HTTP_USER_AGENT; 

$log = $data." | ".$ip." | ".$host." | ".$pagina." | ".$st." : ".$ident." | ".$browser."\n";
$fp = fopen( "log.txt","a" );
fputs( $fp, $log );
fclose( $fp );
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<link href="coteia.css" rel="stylesheet" type="text/css" />
	<title>Erro encontrado</title>
</head>

<body>

<?php
include( "toolbar.inc" );

if ($st == 4) {
?>
<div align="center">
<b>Senha incorreta!</b>
<p>Em caso de dúvidas, entre em contato com o <a href="mailto:<?php echo $ADMIN_MAIL;?>"><?php echo $ADMIN; ?></a>.</p>
</div>

<?php
} else if ($st == 2) {
?>

<h2>Falha na estrutura XML</h2>

<p>Lembre-se: <b>Sempre</b> utilize XML bem-formado, com elementos válidos.</p>
<p>As tags (marcações) podem estar incorretas quanto a:</p
<ul>
	<li>sintaxe (com atributos sem aspas ou incorretamente fechadas),</li>
	<li>aninhamento (não é permitido o uso de tags aninhadas).</li>
</ul>
<br />
<p>Detalhamento dos erros encontrados:</p>
<div align="center">
<div style="background-color: #FEFBA7; border-width: 1; border-color: black; border-style: solid; width: 60%">
<?php
foreach ( $result as $error_message ) {
	echo "\n", $error_message, "<br />";
}
?>
</div>
</div>

<?php
} else if ( $st == 1 ) {
?>
<h2>Erro na base de dados</h2>

<p>Ocorreu um erro quando inserindo os dados na base de dados. As seguinte mensagens de diagnóstico foram enviadas pelo SGBD</p>

<div style="background-color: #FEFBA7; border-width: 1; border-color: black; border-style: solid; width:
60%">
<?php
  echo "\n", $mysql_error( $dbh );
?>
</div>

<?php
} else if ($st == 0 ) {
?>
<h2>Falha na estrutura de edição/criação de wikipage</h2>

<p>Possíveis problemas:</p>
<ul>
	<li>Identificador inválido</li>
	<li>Página já existente (e que não pode ser recriada)</li>
</ul>

<?php
}
?>


<br />
<br />
<div align="center">
	<p><strong><a href="javascript:history.go(-1)">Voltar</a></strong></p>
</div>

</body>

</html>

<?php
	exit();
?>
