<xsl:stylesheet version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xt="http://www.jclark.com/xt"
                extension-element-prefixes="xt">

<xsl:template match="/">
 <html>
  <xsl:apply-templates select="page"/>
 </html>
</xsl:template>

<xsl:template match="page">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
 <meta name ="Author" content="{aut}" />
 <meta name ="Keywords" content="{kwd1},{kwd2},{kwd3}" />
 <link href="data/themes/ffclrp/css/coteia-media_screen.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="coteia.js"></script>
 <title><xsl:apply-templates select="tit"/></title>
</head>
<body>

<div class="toolbar">

<img src="data/themes/ffclrp/images/viewbw.png" alt="View (disabled)" />

<xsl:if test="lock='1'">
	<a href="edit.php?wikipage_id={id}"><img src="data/themes/ffclrp/images/editlock.png" alt="Edit (password protected)" title="Edit (password protected)" /></a>
</xsl:if>
<xsl:if test="lock != '1'">
	<a href="edit.php?wikipage_id={id}"><img src="data/themes/ffclrp/images/edit.png" alt="Edit" title="Edit" /></a>
</xsl:if>

<xsl:if test="lock='1'">
	<a href="edit.php?wikipage_id={id}&amp;add=true"><img src="data/themes/ffclrp/images/addlock.png" alt="Edit (add mode and password protected)" title="Edit (add mode and password protected)" /></a>
</xsl:if>
<xsl:if test="lock != '1'">
	<a href="edit.php?wikipage_id={id}&amp;add=true"><img src="data/themes/ffclrp/images/add.png" alt="Edit (add mode)" title="Edit (add mode)" /></a>
</xsl:if>

<a href="history.php?wikipage_id={id}"><img src="data/themes/ffclrp/images/history.png" alt="History" title="History" /></a>

<xsl:if test="id != sw_id">
	<a href="show.php?wikipage_id={sw_id}"><img src="data/themes/ffclrp/images/indice.png" alt="Top page" title="Top page" /></a>
</xsl:if>

<xsl:if test="id = sw_id">
	<a href="index.php"><img src="data/themes/ffclrp/images/indice.png" alt="CoTeia's main page" title="CoTeia's main page" /></a>
</xsl:if>

<a href="JavaScript:AbreMapa({sw_id})"> <img src="data/themes/ffclrp/images/map.png" alt="Site's map" title="Site's map" /></a>

<a href="changes.php?swiki_id={sw_id}"><img src="data/themes/ffclrp/images/changes.png" alt="Recent changes" title="Recent changes" /></a>

<a href="repository.php?wikipage_id={id}"><img src="data/themes/ffclrp/images/upload.png" alt="Attachments" title="Attachments" /></a>

<a href="search.php?wikipage_id={id}"><img src="data/themes/ffclrp/images/search.png" alt="Search" title="Search" /></a>

<a href="help.php"><img src="data/themes/ffclrp/images/help.png" alt="Help" title="Help" /></a>

<a href="JavaScript:AbreChat({chat_folder})"><img src="data/themes/ffclrp/images/chat.png" alt="Chat" title="Chat" /></a>

<a href="JavaScript:AbreAnotacao('{/page/id}{@id}','{/page/id}','{/page/ann_folder}')">
	<img src="data/themes/ffclrp/images/note.png" alt="Annotation" title="Annotation" />
</a>

<a href="JavaScript:Imprime()"><img src="data/themes/ffclrp/images/print.png" alt="Print" title="Print" /></a>
</div>

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

<xsl:template match="div">
<div><xsl:apply-templates/></div>
</xsl:template>

<xsl:template match="br">
<br/><xsl:apply-templates/>
</xsl:template>

<xsl:template match="p">
<p><xsl:apply-templates/></p>
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

<xsl:template match="dd">
<dd><xsl:apply-templates/></dd>
</xsl:template>

<xsl:template match="dt">
<dt><xsl:apply-templates/></dt>
</xsl:template>

<xsl:template match="dl">
<dl><xsl:apply-templates/></dl>
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
<a href="checkout.php?wikipage_id={/page/id}&amp;filename={@file}">
<xsl:apply-templates/>

<xsl:if test="@id = '1'">
<img src="images/pdf.png" />
</xsl:if>
<xsl:if test="@id = '2'">
<img src="images/web.png" />
</xsl:if>
<xsl:if test="@id = '3'">
<img src="images/doc.png" />
</xsl:if>
<xsl:if test="@id = '4'">
<img src="images/ppt.png" />
</xsl:if>
<xsl:if test="@id = '5'">
<img src="images/zip.png" />
</xsl:if>
<xsl:if test="@id = '6'">
<img src="images/download.png" />
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
<img src="images/useron.png" />
<img src="images/useroff.png" />
</a>
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="note">
<a href="JavaScript:AbreAnotacao('{/page/id}{@id}','{/page/id}','{/page/ann_folder}')">
<xsl:apply-templates/>
<img src="images/note_interno.png" align="middle"/>
</a>    
</xsl:template>

<xsl:template match="agenda">
<a href="JavaScript:agenda('{@id}')">
<font style="text-decoration: underline; color: blue;">Horarios</font>
<img src="images/iconenorisk.jpg" />
</a>
<xsl:apply-templates/>
</xsl:template>

</xsl:stylesheet>
