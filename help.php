<?
	include_once("function.inc");
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Ajuda</title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
include( "toolbar.inc" );
?>

<br />

<h1>Ajuda</h1>

<h2>�ndice</h2>

<br /><a href="#intro1">CoTeia - O que �?</a>
<br /><a href="#intro2">Ferramentas</a>
<br /><a href="#sintaxe">Sintaxe</a>
<br /><a href="#sintaxe1">HTML b�sico</a>
<br /><a href="#sintaxe2">Cria��o de links</a>
<br /><a href="#sintaxe3">Refer�ncia a arquivos - Upload</a>
<br /><a href="#lock">Travamento de p�ginas</a>
<br /><a href="#erros">Cr�ticas</a>

<a name="intro1"></a><h3>CoTeia - O que �?</h3>
<p>A CoTeia � uma ferramenta colaborativa e ass�ncrona para a edi��o de p�ginas Web. Implantada no <a href="http:/www.icmc.usp.br/">Instituto de Ci�ncias Matem�ticas e de Computa��o</a> no in�cio do ano de 2000, foram encontradas, atrav�s de sua utiliza��o, algumas limita��es.</p>

<p>Assim, viu-se a possibilidade de construir-se uma nova infra-estrutura, totalmente independente da <a href="http://coweb.cc.gatech.edu/csl/9/">vers�o original</a>, que foi implementada pela equipe de <a 
href="http://www.cc.gatech.edu/gvu/people/Faculty/Mark.Guzdial.html">Mark Guzdial</a>, coordenador do <a href="http://coweb.cc.gatech.edu/csl/1">Laborat�rio de Software Colaborativo</a> do Instituto de Tecnologia da Georgia , Atlanta, EUA.</p>

<p>A nova infra-estrutura, denominada CoTeia, est� sendo implementada como um servi�o que explora as facilidades integradas disponibilizadas por um servidor Apache estendido com um interpretador PHP e um servidor de banco de dados.</p>

<a name="intro2"></a><h3>Ferramentas</h3>
<p>As ferramentas utilizadas foram: Servidor Web Apache, Interpretador PHP, Banco de Dados MySQL, Java Script, Meta Linguagem XML, Folha de Estilo XSL e Processador XT.</p>

<a name="sintaxe"></a><h3>Sintaxe</h3>

<h4>HTML B�sico</h4>
<dl>
	<dt>&lt;B&gt;<i>texto</i>&lt;/B&gt;</dt>
	<dd>Negrito</dd>

	<dt>&lt;I&gt;<i>texto</i>&lt;/I&gt;</dt>
	<dd>It�lico</dd>

	<dt>&lt;HR/&gt;</dt>
	<dd>Linha horizontal</dd>

	<dt>&lt;CENTER&gt;<i>texto</i>&lt;/CENTER&gt;</dt>
	<dd>Tabula��o</dd>

	<dt>&lt;H1&gt;<i>texto</i>&lt;/H1&gt;</dt>
	<dd>Cabe�alho (primeiro n�vel)</dd>

	<dt>&lt;H2&gt;<i>texto</i>&lt;/H2&gt;</dt>
	<dd>Cabe�alho (segundo n�vel)</dd>

	<dt>&lt;H3&gt;<i>texto</i>&lt;/H3&gt;</dt>
	<dd>Cabe�alho (terceiro n�vel)</dd>

	<dt>&lt;PRE&gt;<i>texto</i>&lt;/PRE&gt;</dt>
	<dd>Texto pr�-formatado</dd>

	<dt>&lt;UL&gt;&lt;LI&gt;<i>�tens</i>&lt;/LI&gt;&lt;/UL&gt;</dt>
	<dd>Lista n�o ordenada</dd>

	<dt>&lt;OL&gt;&lt;LI&gt;<i>�tens</i>&lt;/LI&gt;&lt;/OL&gt;</dt>
	<dd>Lista ordenada</dd>

	<dt>&lt;IMG SRC="<i>caminho</i>" ALIGN="<i>alinhamento</i>" ALT="<i>coment�rio</i>"<b>/</b>&gt;</dt>
	<dd>Imagem</dd>

	<dt>&lt;FONT FACE="<i>tipo</i>" COLOR="<i>cor</i>" SIZE="<i>tamanho</i>"&gt;<i>texto</i>&lt;/FONT&gt;</dt>
	<dd>Fonte</dd>

	<dt>&lt;TABLE BORDER="<i>tamanho do rebordo da tabela</i>" WIDTH="<i>espa�o acupado pela tabela, em pixels ou percentagem</i>"&lt;TR WIDTH="<i>largura</i>" ALIGN="<i>alinhamento horizontal</i>"&gt;&lt;TD WIDTH="<i>largura</i>" ALIGN="<i>alinhamento horizontal</i>"&gt;<i>texto</i>&lt;/TD&gt;&lt;/TR&gt;&lt;/TABLE&gt;</dt>
	<dd>Tabela: TR=cada linha e TD=cada c�lula)</dd>

	<dt>&lt;A HREF="<i>destino</i>" TARGET="local de abertura"&gt;<i>texto</i>&lt;/A&gt;</dt>
	<dd>Link</dd>

	<dt>&lt;A HREF="mailto:<i>destino(s)</i>"&gt;<i>texto</i>&lt;/A&gt;</dt>
	<dd>Email</dd>

	<dt>&lt;A NAME="<i>&acirc;ncora</i>"/&gt;</dt>
	<dd>�ncora</dd>
</dl>

<a name="sintaxe2"></a><h4>Cria��o de links</h4>
<dl>
	<dt>Cria��o de links</dt>
	<dd>&lt;LNK&gt;<i>link</i>&lt;/LNK&gt; (evite colocar espa�os entre o <i>link</i> e as tags &lt;LNK&gt; e &lt;/LNK&gt;)</dd>
</dl>

<a name="sintaxe3"></a><h4>Refer�ncia a arquivos - Upload</h4>
<dl>
	<dt>Refer�ncia</dt>
	<dd>&lt;UPL FILE="nome do arquivo"&gt;<i>texto</i>&lt;/UPL&gt;</dd>
</dl>

<a name="lock"></a><h4>Lock</h4>
<p>Funcionalidade de bloqueio de hiperdocumentos: permite ao usu�rio associar senhas aos documentos, impedindo que os mesmos sejam modificados sem o conhecimento da senha especificada.</p>

<br />
<img src="<?php echo $URL_IMG;?>/mail.gif" alt="Email" />Contato: <a href="mailto:<?php echo $ADMIN_MAIL;?>"><?php echo $ADMIN; ?></a>.

<div align="center">
	<a href="index.php"><img src="<?php echo $URL_IMG;?>/home.png" alt="Home" /></a>
</div>

</body>

</html>
