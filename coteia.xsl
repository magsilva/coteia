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
	function abre(name_file,swiki) {
		window.open('http://143.107.183.160/~simonef/webdr/checkout.php?arq='+name_file+'&amp;swiki='+swiki,'janelachk','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=500,height=430');
	}
	function AbreChat(chat_folder) {
		window.open('http://143.107.183.160/~simonef/webdr/chat.php?id='+chat_folder,'janelachat','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=360,height=250');
	}
	function AbreAnotacao(id,sw_id,ann_folder) {
		window.open('http://143.107.183.160/~simonef/webdr/anotacao.php?p=0&amp;sw_id='+sw_id+'&amp;annotates=http://143.107.183.160/~simonef/webdr/mostra.php?ident='+id+'&amp;id_usuario='+id_usuario+'&amp;id_grupo='+id_grupo+'&amp;id_pasta='+ann_folder+'&amp;mostra=false','janelaann','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=700,height=480');
	}
	function frequencia(id_eclass) {
		window.open('http://143.107.183.160/~simonef/webdr/freq/index.php?curso_id='+id_eclass,'janelafreq','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=650,height=600');
	}
	function agenda(id) {
		window.open('http://coweb.icmc.usp.br/norisk/coweb_disciplina.php?user='+id,'jagenda','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=780,height=500');
	}
	function Imprime() {
		window.print();  
	}
	</script>
	<title><xsl:apply-templates select="tit"/></title>
	</head>
	<body>
		<img src="imagem/viewbw.gif" />
		<xsl:if test="lock='1'">
			<a href="edit.php?ident={id}"><img src="imagem/editlock.gif" /></a>
		</xsl:if>
		<xsl:if test="lock != '1'">
			<a href="edit.php?ident={id}"><img src="imagem/edit.gif" /></a>
		</xsl:if>
		<img src="imagem/historybw.gif" />
		<xsl:if test="id != sw_id">
			<a href="mostra.php?ident={sw_id}"><img src="imagem/indice.gif" /></a>
		</xsl:if>
		<xsl:if test="id = sw_id">
			<img src="imagem/indicebw.gif" />
		</xsl:if>
		<a href="JavaScript:AbreMapa({sw_id})">	<img src="imagem/map.gif" /></a>
		<a href="changes.php?ident={id}"><img src="imagem/changes.gif" /></a>
		<xsl:if test="id != sw_id">
			<a href="upload.php?ident={id}"><img src="imagem/upload.gif" /></a>
		</xsl:if>
		<xsl:if test="id = sw_id">
			<img src="imagem/uploadbw.gif" />
		</xsl:if>
		<a href="search.php?ident={id}"><img src="imagem/search.gif" /></a>
		<a href="help.php"><img src="imagem/help.gif" /></a>
		<a href="JavaScript:AbreChat({chat_folder})"><img src="imagem/chat.gif" /></a>
		<img src="imagem/notebw.gif" />
		<a href="JavaScript:Imprime()"><img src="imagem/print.gif" /></a>

		<h2><xsl:apply-templates select="tit"/></h2>
		<xsl:for-each select="bdy">
			<p><xsl:apply-templates select="."/></p>
		</xsl:for-each>
		<br/>
		<hr/>
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
<img src="imagem/pdf.gif" />
</xsl:if>
<xsl:if test="@id = '2'">
<img src="imagem/web.gif" />
</xsl:if>
<xsl:if test="@id = '3'">
<img src="imagem/doc.gif" />
</xsl:if>
<xsl:if test="@id = '4'">
<img src="imagem/ppt.gif" />
</xsl:if>
<xsl:if test="@id = '5'">
<img src="imagem/zip.gif" />
</xsl:if>
<xsl:if test="@id = '6'">
<img src="imagem/download.gif" />
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
<img src="imagem/useron.gif" />
<img src="imagem/useroff.gif" />
</a>
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="note">
<a href="JavaScript:AbreAnotacao('{/page/id}{@id}','{/page/id}','{/page/ann_folder}')">
<xsl:apply-templates/>
<img src="imagem/note_interno.gif" align="middle"/>
</a>    
</xsl:template>

<xsl:template match="agenda">
<a href="JavaScript:agenda('{@id}')">
<font style="text-decoration: underline; color: blue;">Hor�rios</font>
<img src="imagem/iconenorisk.jpg" />
</a>
<xsl:apply-templates/>
</xsl:template>

</xsl:stylesheet> 
