<?
	include_once("function.inc");
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="GENERATOR" content="Mozilla/4.6 [en] (WinNT; I) [Netscape]">
   <title>Ajuda</title>
<style type="text/css">
<!--
A:link {text-decoration:none;color: #0000BB}
A:visited {text-decoration:none;color: #0000FF}
--></style>
</head>
<BODY text=#000000 vLink=#0000cc aLink=#ffff00 link=#cc0000 bgColor=#ffffff>
<img src="<?echo $URL_IMG?>/viewbw.png" border="0"/>
<img src="<?echo $URL_IMG?>/editbw.png" border="0"/>
<img src="<?echo $URL_IMG?>/historybw.png" border="0"/>
<img src="<?echo $URL_IMG?>/indicebw.png" border="0"/>
<img src="<?echo $URL_IMG?>/mapbw.png" border="0"/>
<img src="<?echo $URL_IMG?>/changesbw.png" border="0"/>
<img src="<?echo $URL_IMG?>/uploadbw.png" border="0"/>
<img src="<?echo $URL_IMG?>/searchbw.png" border="0"/>
<img src="<?echo $URL_IMG?>/helpbw.png" border="0"/>
<img src="<?echo $URL_IMG?>/chatbw.png" border="0">
<img src="<?echo $URL_IMG?>/notebw.png" border="0"/>
<img src="<?echo $URL_IMG?>/printbw.png" border="0"/>
<br><br>
<FONT FACE="sans-serif" COLOR="#FF6633">
<P><H1>CoTeia - Ajuda</H1></P>
</FONT>
<FONT FACE="sans-serif" COLOR="#000000">
<h2>&Iacute;ndice</h2>
<br><a href="#intro1">CoTeia</a>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#intro1">O que &eacute; ?</a>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#intro2">Ferramentas</a>
<br><a href="#sintaxe">Sintaxe</a>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#sintaxe1">HTML b&aacute;sico</a>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#sintaxe2">Cria&ccedil;&atilde;o de links</a>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a 
href="#sintaxe3">Refer&ecirc;ncia a arquivos - Upload</a>
<br><a href="#lock">Travamento de p&aacute;ginas</a>
<br><a href="#erros">Cr&iacute;ticas</a>
<br><br>
<UL>
<a name="intro1">
	<LI><P>A CoTeia � uma ferramenta colaborativa e ass�ncrona para a edi��o de p�ginas Web. Implantado no Instituto de Ci�ncias Matem�ticas e de Computa��o no in�cio do ano de 2000, foram encontradas, atrav�s de sua utiliza��o, algumas limita��es. 
<br>Assim, viu-se a possibilidade de construir-se uma nova infra-estrutura, totalmente independente da <a href="http://coweb.cc.gatech.edu/csl/9/">vers�o original</a>, que foi implementada pela equipe de <a 
href="http://www.cc.gatech.edu/gvu/people/Faculty/Mark.Guzdial.html">Mark Guzdial</a>, coordenador do <a href="http://coweb.cc.gatech.edu/csl/1">Laborat�rio de Software Colaborativo</a> do Instituto de Tecnologia da Georgia , Atlanta, EUA. 
	<P>A nova infra-estrutura, denominada CoTeia, est� sendo implementada como um servi�o que explora as facilidades integradas disponibilizadas por um servidor Apache estendido com um interpretador PHP e um servidor de banco de  dados.</P>
<a name="intro2">
	<LI><P>Ferramentas Utilizadas: Servidor Web Apache, Interpretador PHP, Banco de Dados MySQL, Java Script, Meta Linguagem XML,Folha de Estilo XSL e Processador XT.</P>
<a name="sintaxe1">
	<LI><B>Sintaxe:</B>
	<DL><P>
<a name="sintaxe1">
		<DD><LI>&lt;B&gt;<i>texto</i>&lt;/B&gt; (negrito) 
		<DD><LI>&lt;I&gt;<i>texto</i>&lt;/I&gt; (it�lico) 
		<DD><LI>&lt;HR/&gt; (linha horizontal) 
		<DD><LI>&lt;CENTER&gt;<i>texto</i>&lt;/CENTER&gt; (tabula��o) 
		<DD><LI>&lt;H1&gt;<i>texto</i>&lt;/H1&gt; (cabe�alho) 
		<DD><LI>&lt;H2&gt;<i>texto</i>&lt;/H2&gt; (cabe�alho) 
		<DD><LI>&lt;H3&gt;<i>texto</i>&lt;/H3&gt; (cabe�alho) 
		<DD><LI>&lt;PRE&gt;<i>texto</i>&lt;/PRE&gt; (texto pr&eacute;-formatado)
		<DD><LI>&lt;UL&gt;&lt;LI&gt;<i>�tens</i>&lt;/LI&gt;&lt;/UL&gt; (lista n�o ordenada) 
		<DD><LI>&lt;OL&gt;&lt;LI&gt;<i>�tens</i>&lt;/LI&gt;&lt;/OL&gt; (lista ordenada) 
		<DD><LI>&lt;IMG SRC="<i>caminho</i>" BORDER="<i>borda</i>" ALIGN="<i>alinhamento</i>" WIDTH="<i>largura</i>" HEIGHT="<i>altura</i> ALT="<i>coment�rio</i>"<b>/</b>&gt; (imagem) 
		<DD><LI>&lt;FONT FACE="<I>tipo</I>" COLOR="<I>cor</I>" SIZE="<I>tamanho</I>"&gt;<I>texto</I>&lt;/FONT&gt; (fonte) 
		<DD><LI>&lt;TABLE BORDER="<I>tamanho do rebordo da tabela</I>" WIDTH="<I>espa�o acupado pela tabela, em pixels ou percentagem</I>"&lt;TR WIDTH="<I>largura</I>" ALIGN="<I>alinhamento horizontal</I>"&gt;&lt;TD WIDTH="<I>largura</I>" ALIGN="<I>alinhamento horizontal</I>"&gt;<I>texto</I>&lt;/TD&gt;&lt;/TR&gt;&lt;/TABLE&gt; (tabela: TR=cada linha e TD=cada c&eacute;lula) 
		<DD><LI>&lt;A HREF="<I>destino</I>" TARGET="local de abertura"&gt;<I>texto</I>&lt;/A&gt; (link) 
		<DD><LI>&lt;A HREF="mailto:<I>destino(s)</I>"&gt;<I>texto</I>&lt;/A&gt; (mail) 
		<DD><LI>&lt;A NAME="<I>&acirc;ncora</I>"/&gt; (&acirc;ncora) 
		<a name="sintaxe2">		
		<DD><LI>Cria��o de links:  &lt;LNK&gt;<I>link</I>&lt;/LNK&gt; (evite colocar espa&ccedil;os entre o <I>link</I> e as tags &lt;LNK&gt; e &lt;/LNK&gt;)
		<a name="sintaxe3">
                <DD><LI>Refer�ncia:  &lt;UPL FILE="nome do arquivo"&gt;<I>texto</I>&lt;/UPL&gt;
		</DL>
	</P></DL></UL>
<a name="lock">
	<UL><LI><P><B>Lock:</B></P>
	<DL><P>
	<DD><LI>Funcionalidade de bloqueio de hiperdocumentos: permite ao usu�rio associar senhas aos documentos, impedindo que os mesmos sejam modificados sem o conhecimento da senha especificada.  
	<BR>
	</DL></UL>
<FONT SIZE="+1" FACE="Britannic Bold" COLOR="#000000">
<img src="<?echo $URL_IMG?>/mail.png" border="0"  width="90" height="115"><p>
Contato: <a href="mailto:<?echo $ADMIN_MAIL;?>"><? echo $ADMIN; ?></a>.
</FONT><p>
<h2><center><a href="index.php"><img src="<?echo $URL_IMG?>/home.png" height="40" border="0"></a></center></h2>
</body>
</html>
