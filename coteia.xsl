<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xt="http://www.jclark.com/xt"
                extension-element-prefixes="xt">

<xsl:template match="/">
	<html>
	<xsl:apply-templates select="page"/>
	</html>
	</xsl:template>

	<xsl:template match="page">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name ="Autor" content="{aut}"/>
	<meta name ="Palavra-Chave" content="{kwd1},{kwd2},{kwd3}"/>
	<script language="JavaScript">
	function abre(name_file,swiki)
	{
	window.open('http://143.107.183.160/~simonef/webdr/checkout.php?arq='+name_file+'&amp;swiki='+swiki,'janelachk','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=500,height=430');
	}
	function AbreChat(chat_folder)
        {
	window.open('http://143.107.183.160/~simonef/webdr/chat.php?id='+chat_folder,'janelachat','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=360,height=250');
        }
	function AbreAnotacao(id,sw_id,ann_folder)
        {
	window.open('http://143.107.183.160/~simonef/webdr/anotacao.php?p=0&amp;sw_id='+sw_id+'&amp;annotates=http://143.107.183.160/~simonef/webdr/mostra.php?ident='+id+'&amp;id_usuario='+id_usuario+'&amp;id_grupo='+id_grupo+'&amp;id_pasta='+ann_folder+'&amp;mostra=false','janelaann','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=700,height=480');
        }
	function AbreAnotacaoDR(id,id_issue,ann_folder,id_usuario,id_grupo)
        {
        window.open('http://143.107.183.160/~simonef/webdr/dr_anotacao.php?tipo=1&amp;id_issue='+id_issue+'&amp;id_usuario='+id_usuario+'&amp;id_grupo='+id_grupo+'&amp;annotates=http://143.107.183.160/~simonef/webdr/mostra.php?ident='+id+'&amp;id_pasta='+ann_folder+'&amp;mostra=false','janelaann','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=700,height=480');
        }
        function AbreConclusaoDR(id_issue) 
        {
        window.open('http://143.107.183.160/~simonef/DocRat/proj_conclusion.php?id_issue='+id_issue,'janelaann','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=700,height=280');
        }
        function AbreDownloadDR(ident)
        {
        document.location='http://143.107.183.160/~simonef/webdr/upload.php?ident='+ident;
        }
        function AbreNewPageDR(ident)
        {
        document.location='http://143.107.183.160/~simonef/DocRat/proj_page.php?ident='+ident;
        }
        function AbreMapa(id)
        {
	window.open('http://143.107.183.160/~simonef/webdr/map.php?id='+id,'janelamap','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=520,height=480');
        }
	function frequencia(id_eclass)
        {
	window.open('http://143.107.183.160/~simonef/webdr/freq/index.php?curso_id='+id_eclass,'janelafreq','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=650,height=600');
        }
	function agenda(id)
        {
	window.open('http://coweb.icmc.usp.br/norisk/coweb_disciplina.php?user='+id,'jagenda','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=780,height=500');
        }
        function Imprime()
        {
	window.print();  
	}
        </script>
	<title><xsl:apply-templates select="tit"/></title>
        </head>
	<body text="#000000" vLink="#cc0000" aLink="#cccc00" link="#0000ff" bgColor="#ffffff">
                <img src="imagem/viewbw.gif" width="52" height="48" border="0"/>
	        <xsl:if test="lock='1'">
                <A href="edit.php?ident={id}">
                <img src="imagem/editlock.gif" width="52" height="48" border="0"/></A>
	        </xsl:if>
	        <xsl:if test="lock != '1'">
                <A href="edit.php?ident={id}">
                <img src="imagem/edit.gif" width="52" height="48" border="0"/></A>
	        </xsl:if>
                <img src="imagem/historybw.gif" width="52" height="48" border="0"/>
	        <xsl:if test="id != sw_id">
                <A href="mostra.php?ident={sw_id}">
                <img src="imagem/indice.gif" width="52" height="48" border="0"/></A>
	        </xsl:if>
	        <xsl:if test="id = sw_id">
                <img src="imagem/indicebw.gif" width="52" height="48" border="0"/>
	        </xsl:if>
                <A href="JavaScript:AbreMapa({sw_id})">
                <img src="imagem/map.gif" width="52" height="48" border="0"/></A>
                <A href="changes.php?ident={id}">
		<img src="imagem/changes.gif" width="52" height="48" border="0"/></A>
                <xsl:if test="id != sw_id">
                <A href="upload.php?ident={id}">
		<img src="imagem/upload.gif" width="52" height="48" border="0"/></A>
                </xsl:if>
                <xsl:if test="id = sw_id">
                <img src="imagem/uploadbw.gif" width="52" height="48" border="0"/>
                </xsl:if>
                <A href="search.php?ident={id}">
                <img src="imagem/search.gif" width="52" height="48" border="0"/></A>
                <A href="help.php">
		<img src="imagem/help.gif" width="52" height="48" border="0"/></A>
		<A href="JavaScript:AbreChat({chat_folder})">
                <img src="imagem/chat.gif" width="52" height="48" border="0"/></A>
                
                <img src="imagem/notebw.gif" width="52" height="48" border="0"/>

		<A href="JavaScript:Imprime()">
                <img src="imagem/print.gif" width="52" height="48" border="0"/></A>
	<h2><xsl:apply-templates select="tit"/></h2>
	<xsl:for-each select="bdy">
			<p><xsl:apply-templates select="."/></p>
	</xsl:for-each>
	<br/><hr/>
	<p><b>Referenciam este documento: </b></p>
	<ul>
	<xsl:for-each select="ref">
			<li><xsl:apply-templates select="."/></li>
	</xsl:for-each>
	</ul>
</body>
</xsl:template>

<xsl:template match="br">
<br/><xsl:apply-templates/>
</xsl:template>

<xsl:template match="b">
<b><xsl:apply-templates/></b>
</xsl:template>

<xsl:template match="i">
<i><xsl:apply-templates/></i>
</xsl:template>

<xsl:template match="h1">
<h1><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="h2">
<h2><xsl:apply-templates/></h2>
</xsl:template>

<xsl:template match="h3">
<h3><xsl:apply-templates/></h3>
</xsl:template>

<xsl:template match="pre">
<pre><xsl:apply-templates/></pre>
</xsl:template>

<xsl:template match="strong">
<strong><xsl:apply-templates/></strong>
</xsl:template>

<xsl:template match="ul">
<ul><xsl:apply-templates/></ul>
</xsl:template>

<xsl:template match="ol">
<ol><xsl:apply-templates/></ol>
</xsl:template>

<xsl:template match="li">
<li><xsl:apply-templates/></li>
</xsl:template>

<xsl:template match="center">
<center><xsl:apply-templates/></center>
</xsl:template>

<xsl:template match="hr">
<hr/><xsl:apply-templates/>
</xsl:template>

<xsl:template match="lnk">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="img">
<img>
<xsl:attribute name="src">
<xsl:apply-templates select="@src"/>
</xsl:attribute>
<xsl:if test="@border">
<xsl:attribute name="border">
<xsl:apply-templates select="@border"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@align">
<xsl:attribute name="align">
<xsl:apply-templates select="@align"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@width">
<xsl:attribute name="width">
<xsl:apply-templates select="@width"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@height">
<xsl:attribute name="height">
<xsl:apply-templates select="@height"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@alt">
<xsl:attribute name="alt">
<xsl:apply-templates select="@alt"/>
</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</img>
</xsl:template>

<xsl:template match="a">
<a>
<xsl:if test="@href">
<xsl:attribute name="href">
<xsl:apply-templates select="@href"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@name">
<xsl:attribute name="name">
<xsl:apply-templates select="@name"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@target">
<xsl:attribute name="target">
<xsl:apply-templates select="@target"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@onmouseover">
<xsl:attribute name="onmouseover">
<xsl:apply-templates select="@onmouseover"/>
</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</a>
</xsl:template>

<xsl:template match="font">
<font>
<xsl:if test="@size">
<xsl:attribute name="size">
<xsl:apply-templates select="@size"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@color">
<xsl:attribute name="color">
<xsl:apply-templates select="@color"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@face">
<xsl:attribute name="face">
<xsl:apply-templates select="@face"/>
</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</font>
</xsl:template>

<xsl:template match="table">
<table>
<xsl:if test="@border">
<xsl:attribute name="border">
<xsl:apply-templates select="@border"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@width">
<xsl:attribute name="width">
<xsl:apply-templates select="@width"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@align">
<xsl:attribute name="align">
<xsl:apply-templates select="@align"/>
</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="td">
<td>
<xsl:if test="@width">
<xsl:attribute name="width">
<xsl:apply-templates select="@width"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@align">
<xsl:attribute name="align">
<xsl:apply-templates select="@align"/>
</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</td>
</xsl:template>

<xsl:template match="tr">
<tr>
<xsl:if test="@width">
<xsl:attribute name="width">
<xsl:apply-templates select="@width"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@align">
<xsl:attribute name="align">
<xsl:apply-templates select="@align"/>
</xsl:attribute>
</xsl:if>
<xsl:if test="@valign">
<xsl:attribute name="valign">
<xsl:apply-templates select="@valign"/>
</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</tr>
</xsl:template>

<xsl:template match="ref">
<a>
<xsl:attribute name="href">
<xsl:apply-templates select="@id"/>
</xsl:attribute>
<xsl:apply-templates/>
</a>
</xsl:template>

<xsl:template match="upl">
<a href="JavaScript:abre('{@file}','{/page/sw_id}')">
<xsl:apply-templates/>
<xsl:if test="@id = '1'">
<img src="imagem/pdf.gif" width="18" height="18" border="0"/>
</xsl:if>
<xsl:if test="@id = '2'">
<img src="imagem/web.gif" width="18" height="18" border="0"/>
</xsl:if>
<xsl:if test="@id = '3'">
<img src="imagem/doc.gif" width="18" height="18" border="0"/>
</xsl:if>
<xsl:if test="@id = '4'">
<img src="imagem/ppt.gif" width="18" height="18" border="0"/>
</xsl:if>
<xsl:if test="@id = '5'">
<img src="imagem/zip.gif" width="18" height="18" border="0"/>
</xsl:if>
<xsl:if test="@id = '6'">
<img src="imagem/download.gif" width="18" height="18" border="0"/>
</xsl:if>
</a>
</xsl:template>

<xsl:template match="sw"> 
<a> 
<xsl:attribute name="href"> 
<xsl:apply-templates select="@id"/> 
</xsl:attribute> 
<xsl:apply-templates/> 
</a>
</xsl:template>

<xsl:template match="freq">
<a href="JavaScript:frequencia('{/page/id_eclass}')">
<img src="imagem/useron.gif" width="18" height="18" border="0"/>
<img src="imagem/useroff.gif" width="18" height="18" border="0"/>
</a>
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="note">
<a href="JavaScript:AbreAnotacao('{/page/id}{@id}','{/page/id}','{/page/ann_folder}')">
<xsl:apply-templates/>
<img src="imagem/note_interno.gif" width="38" height="38" border="0" align="middle"/>
</a>    
</xsl:template>

<xsl:template match="drnote">
<a href="JavaScript:AbreAnotacaoDR('{/page/id}{@id}','{@id_issue}','{/page/ann_folder}',{@id_usuario},{@id_grupo})">
<xsl:apply-templates/>
<img src="imagem/noteDR.jpg" width="30" height="30" border="0"/>
</a>
</xsl:template>

<xsl:template match="drconclusion">
<a href="JavaScript:AbreConclusaoDR('{@id_issue}')">
<xsl:apply-templates/>
<img src="imagem/bloquinho_anota.gif" width="20" height="20" border="0"/>
</a>
</xsl:template>

<xsl:template match="drupload">
<a href="JavaScript:AbreDownloadDR('{@ident}')">
<xsl:apply-templates/>
<img src="imagem/upload.gif" border="0"/>
</a>
</xsl:template>

<xsl:template match="drnewpage">
<a href="JavaScript:AbreNewPageDR('{@ident}')">
<xsl:apply-templates/>
<img src="imagem/nova_pagina.jpg" border="0"/>
</a>
</xsl:template>

<xsl:template match="agenda">
<a href="JavaScript:agenda('{@id}')">
<font style="text-decoration: underline; color: blue;">Hor&#225;rios</font>
<img src="imagem/iconenorisk.jpg" width="24" height="24" border="0"/>
</a>
<xsl:apply-templates/>
</xsl:template>

</xsl:stylesheet> 

