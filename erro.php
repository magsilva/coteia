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
?>

<div align="center">
<?php
	if ($st == 2) {
?>
<p>Falha na estrutura XML</p>
<p>Lembre-se: <b>Sempre</b> utilize XML bem-formado, com elementos válidos.</p>
<p>As tags (marcações) podem estar incorretas quanto a:</p
<ul>
	<li>sintaxe (com atributos sem aspas ou incorretamente fechadas),</li>
	<li>aninhamento (não é permitido o uso de tags aninhadas).</li>
</ul>
<br />
<p><strong><a href="javascript:history.go(-1)">Voltar</a></strong></p>
<?php
	} else {
?>
<p>Falha na estrutura de edição/criação de documentos CoTeia!</p>
<p><b>Possíveis problemas:</b></p>
<ul>
	<li>Identificador inválido</li>
	<li>Página existente (não pode ser recriada)</li>
</ul>

<br />
<hr />
<p>Erros encontrados:</p>
<?php echo $error_message; ?>

<br />
<p><strong><a href="javascript:history.go(-1)">Voltar</a></strong></p>

</div>

</body>

</html>
<?php
}
?>
