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
include( "toolbar.php" );

if ($st == 2) {
?>

<h2>Falha na estrutura XML</h2>

<p>Lembre-se: <b>Sempre</b> utilize XML bem-formado, com elementos v�lidos.</p>
<p>As tags (marca��es) podem estar incorretas quanto a:</p
<ul>
	<li>sintaxe (com atributos sem aspas ou incorretamente fechadas),</li>
	<li>aninhamento (n�o � permitido o uso de tags aninhadas).</li>
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
} else {
?>
<h2>Falha na estrutura de edi��o/cria��o de wikipage</h2>

<p>Poss�veis problemas:</p>
<ul>
	<li>Identificador inv�lido</li>
	<li>P�gina j� existente (e que n�o pode ser recriada)</li>
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
