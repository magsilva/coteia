<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<link href="coteia.css" rel="stylesheet" type="text/css" />
	<title>Erro encontrado</title>
</head>

<body>

<?php
include_once( "function.inc" );
include( "toolbar.inc" );

$date = gmdate( "r" );
$ip = $_SERVER[ "REMOTE_ADDR" ];
$hostname = @gethostbyaddr( $ip );
$page = $_SERVER[ "PHP_SELF" ];
$browser = $_SERVER[ "HTTP_USER_AGENT" ];

$log_entry =
	 $date
	. "|" . $ip
	. "|" . $hostname
	. "|" . $browser
	. "|" . $page
	. "|" . $error_code
	. "|" . $ident
	. "\n";
$fp = fopen( "log.txt","a" );
fputs( $fp, $log );
fclose( $fp );


//st = 1 => Erro no BD.
//st = 2 => nao criou arquivo fisico
//st = 3 => erro de indexador ao criar nova pagina
//st = 4 => senha incorreta

switch ( $error_code ) {
	case 4:
?>
		<h2>Senha incorreta</h2>
		<p>O nome do usuário ou a senha utilizada para acessar a swiki estão incorretos. Por favor, volte à página anterior e tente novamente. Caso o problema se repita, entre em contato com o <a href="mailto:<?php echo $ADMIN_MAIL;?>">administrador da CoTeia (<?php echo $ADMIN; ?>)</a>.</p>
<?php
		break;
?>


<?php
	case 2:
?>
<h2>Um erro foi encontrado na estrutura XML de seu documento</h2>

<p>A CoTeia detectou um erro na estrutura do documento que você acabou de editar na CoTeia (toda página wiki é um documento XML bem-formado). Os erros mais mais comuns são:</p>
<ul>
	<li>Valores dos atributos de um elemento XML não estão delimitados por aspas. Por exemplo: &lt;img src=figura.jpg /&gt; <strong>(errado)</strong> e &lt;img src="figura.jpg" /&gt; <strong>(certo)</strong>.</li>
	<li>As tags (marcações) não estão corretamente fechadas. Por exemplo: &lt;i&gt;texto <strong>errado</strong> &lt;i&gt;texto&lt;/i&gt;  <strong>(certo)</strong>, &lt;br&gt; <strong>(errado)</strong> e &lt;br /&gt; <strong>(certo)</strong>.</li>
	<li>As tags (marcações) estão aninhadas de forma incorreta. Por exemplo: &lt;i&gt;&lt;b&gt;texto&lt;i&gt;&lt;b&gt; <strong>(errado)</strong> e &lt;i&gt;&lt;b&gt;texto&lt;b&gt;&lt;i&gt; <strong>(correto)</strong>.</li>
</ul>

<br />

<p>Abaixo estão as mensagens detalhadas sobre os erros encontrados no documento:</p>
<div align="center" style="background-color: #FEFBA7; border-width: 1; border-color: black; border-style: solid; width: 60%">
<?php
	foreach ( $result as $error_message ) {
		echo "\n", $error_message, "<br />";
	}
?>
</div>
<?php
		break;
?>


<?php
	case 1:
		 mail( $ADMIN_MAIL, "[CoTeia] - Erro no BD", $mysql_error( $dbh ) );
?>
<h2>Erro na inserção dos dados na base</h2>

<p>Ocorreu um erro quando alterando os dados da página wiki na base de dados. O adminstrador da CoTeia está sendo informado (automaticamente) sobre o problema ocorrido. Caso considere necessário, entre em contato com ele pelo email <a href="mailto:<?php echo $ADMIN_MAIL;?>">(<?php echo $ADMIN; ?>)</a> ou relate a falha no Caso prefira, relate o problema diretamente no <a href="http://www.magsilva.dynalias.net/webTools/BugzillaSAFE/enter_bug.cgi?product=CoTeia">Bugzilla</a>. Abaixo estão informações mais detalhadas sobre o problema encontrado:</p>

<div style="background-color: #FEFBA7; border-width: 1; border-color: black; border-style: solid; width:
60%">
<?php
  echo "\n", $mysql_error( $dbh );
?>
</div>
<?php
		break;
?>


<?php
	case 0:
?>

<h2>A CoTeia não entendeu sua requisição</h2>

<p>A CoTeia detectou um problema quando tentando atender sua requisição. As possíveis causas são:</p>
<ol>
	<li>Página wiki inexistente (provavelmente mudou de endereço)</li>
	<li>Algum problema do programa sendo utilizado para acessar a CoTeia</li>
	<li>Algum erro interno da CoTeia</li>
</ol>

<p>Provavelmente o erro teve como razão o primeiro item. Mas caso você ache que se trata de uma falha da CoTeia, envie um email para o <a href="mailto:<?php echo $ADMIN_MAIL;?>">administrador da CoTeia (<?php echo $ADMIN; ?>)</a> e detalhe o problema ocorrido. Caso prefira, relate o problema diretamente no <a href="http://www.magsilva.dynalias.net/webTools/BugzillaSAFE/enter_bug.cgi?product=CoTeia">Bugzilla</a>.</p>
<?php
		break;
?>

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
