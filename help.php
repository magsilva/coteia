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

<h2>Índice</h2>

<br /><a href="#intro1">CoTeia - O que é?</a>
<br /><a href="#intro2">Ferramentas</a>
<br /><a href="#sintaxe">Sintaxe</a>
<br /><a href="#sintaxe1">HTML básico</a>
<br /><a href="#sintaxe2">Criação de links</a>
<br /><a href="#sintaxe3">Referência a arquivos - Upload</a>
<br /><a href="#lock">Travamento de páginas</a>
<br /><a href="#erros">Críticas</a>

<a name="intro1"></a><h3>CoTeia - O que é?</h3>
<p>A CoTeia é uma ferramenta colaborativa e assíncrona para a edição de páginas Web. Implantada no <a href="http:/www.icmc.usp.br/">Instituto de Ciências Matemáticas e de Computação</a> no início do ano de 2000, foram encontradas, através de sua utilização, algumas limitações.</p>

<p>Assim, viu-se a possibilidade de construir-se uma nova infra-estrutura, totalmente independente da <a href="http://coweb.cc.gatech.edu/csl/9/">versão original</a>, que foi implementada pela equipe de <a 
href="http://www.cc.gatech.edu/gvu/people/Faculty/Mark.Guzdial.html">Mark Guzdial</a>, coordenador do <a href="http://coweb.cc.gatech.edu/csl/1">Laboratório de Software Colaborativo</a> do Instituto de Tecnologia da Georgia , Atlanta, EUA.</p>

<p>A nova infra-estrutura, denominada CoTeia, está sendo implementada como um serviço que explora as facilidades integradas disponibilizadas por um servidor Apache estendido com um interpretador PHP e um servidor de banco de dados.</p>

<a name="intro2"></a><h3>Ferramentas</h3>
<p>As ferramentas utilizadas foram: Servidor Web Apache, Interpretador PHP, Banco de Dados MySQL, Java Script, Meta Linguagem XML, Folha de Estilo XSL e Processador XT.</p>

<a name="sintaxe"></a><h3>Sintaxe</h3>

<h4>HTML Básico</h4>
<dl>
	<dt>&lt;B&gt;<i>texto</i>&lt;/B&gt;</dt>
	<dd>Negrito</dd>

	<dt>&lt;I&gt;<i>texto</i>&lt;/I&gt;</dt>
	<dd>Itálico</dd>

	<dt>&lt;HR/&gt;</dt>
	<dd>Linha horizontal</dd>

	<dt>&lt;CENTER&gt;<i>texto</i>&lt;/CENTER&gt;</dt>
	<dd>Tabulação</dd>

	<dt>&lt;H1&gt;<i>texto</i>&lt;/H1&gt;</dt>
	<dd>Cabeçalho (primeiro nível)</dd>

	<dt>&lt;H2&gt;<i>texto</i>&lt;/H2&gt;</dt>
	<dd>Cabeçalho (segundo nível)</dd>

	<dt>&lt;H3&gt;<i>texto</i>&lt;/H3&gt;</dt>
	<dd>Cabeçalho (terceiro nível)</dd>

	<dt>&lt;PRE&gt;<i>texto</i>&lt;/PRE&gt;</dt>
	<dd>Texto pré-formatado</dd>

	<dt>&lt;UL&gt;&lt;LI&gt;<i>ítens</i>&lt;/LI&gt;&lt;/UL&gt;</dt>
	<dd>Lista não ordenada</dd>

	<dt>&lt;OL&gt;&lt;LI&gt;<i>ítens</i>&lt;/LI&gt;&lt;/OL&gt;</dt>
	<dd>Lista ordenada</dd>

	<dt>&lt;IMG SRC="<i>caminho</i>" ALIGN="<i>alinhamento</i>" ALT="<i>comentário</i>"<b>/</b>&gt;</dt>
	<dd>Imagem</dd>

	<dt>&lt;FONT FACE="<i>tipo</i>" COLOR="<i>cor</i>" SIZE="<i>tamanho</i>"&gt;<i>texto</i>&lt;/FONT&gt;</dt>
	<dd>Fonte</dd>

	<dt>&lt;TABLE BORDER="<i>tamanho do rebordo da tabela</i>" WIDTH="<i>espaço acupado pela tabela, em pixels ou percentagem</i>"&lt;TR WIDTH="<i>largura</i>" ALIGN="<i>alinhamento horizontal</i>"&gt;&lt;TD WIDTH="<i>largura</i>" ALIGN="<i>alinhamento horizontal</i>"&gt;<i>texto</i>&lt;/TD&gt;&lt;/TR&gt;&lt;/TABLE&gt;</dt>
	<dd>Tabela: TR=cada linha e TD=cada célula)</dd>

	<dt>&lt;A HREF="<i>destino</i>" TARGET="local de abertura"&gt;<i>texto</i>&lt;/A&gt;</dt>
	<dd>Link</dd>

	<dt>&lt;A HREF="mailto:<i>destino(s)</i>"&gt;<i>texto</i>&lt;/A&gt;</dt>
	<dd>Email</dd>

	<dt>&lt;A NAME="<i>&acirc;ncora</i>"/&gt;</dt>
	<dd>Âncora</dd>
</dl>

<a name="sintaxe2"></a><h4>Criação de links</h4>
<dl>
	<dt>Criação de links</dt>
	<dd>&lt;LNK&gt;<i>link</i>&lt;/LNK&gt; (evite colocar espaços entre o <i>link</i> e as tags &lt;LNK&gt; e &lt;/LNK&gt;)</dd>
</dl>

<a name="sintaxe3"></a><h4>Referência a arquivos - Upload</h4>
<dl>
	<dt>Referência</dt>
	<dd>&lt;UPL FILE="nome do arquivo"&gt;<i>texto</i>&lt;/UPL&gt;</dd>
</dl>

<a name="lock"></a><h4>Lock</h4>
<p>Funcionalidade de bloqueio de hiperdocumentos: permite ao usuário associar senhas aos documentos, impedindo que os mesmos sejam modificados sem o conhecimento da senha especificada.</p>

<br />
<img src="<?php echo $URL_IMG;?>/mail.gif" alt="Email" />Contato: <a href="mailto:<?php echo $ADMIN_MAIL;?>"><?php echo $ADMIN; ?></a>.

<div align="center">
	<a href="index.php"><img src="<?php echo $URL_IMG;?>/home.png" alt="Home" /></a>
</div>

</body>

</html>
