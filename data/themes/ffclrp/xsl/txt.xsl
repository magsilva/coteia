<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xt="http://www.jclark.com/xt"
	extension-element-prefixes="xt">

<xsl:template match="/">
  <xsl:apply-templates select="page"/>
</xsl:template>

<xsl:template match="page">
Autor: {aut}
Palavras-chave: {kwd1},{kwd2},{kwd3}
Titulo: <xsl:apply-templates select="tit" />

<xsl:for-each select="bdy">
 <p><xsl:apply-templates select="."/></p>
</xsl:for-each>

Referenciam este documento:
<xsl:for-each select="ref">
- <xsl:apply-templates select="."/>
</xsl:for-each>

</xsl:template>


<xsl:template match="br">

</xsl:template>

<xsl:template match="b">
_<xsl:apply-templates/>_
</xsl:template>

<xsl:template match="i">
_<xsl:apply-templates/>_
</xsl:template>

<xsl:template match="h1">
<xsl:apply-templates/>
-----
</xsl:template>

<xsl:template match="h2">
<xsl:apply-templates/>
---
</xsl:template>

<xsl:template match="h3">
<xsl:apply-templates/>
--
</xsl:template>

<xsl:template match="strong">
_<xsl:apply-templates/>_
</xsl:template>

<xsl:template match="li">
- <xsl:apply-templates/>
</xsl:template>

<xsl:template match="hr">
<xsl:apply-templates/>
------------------------------------------------------------------
</xsl:template>

</xsl:stylesheet>
