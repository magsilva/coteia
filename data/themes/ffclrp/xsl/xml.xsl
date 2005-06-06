<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <xsl:apply-templates select="page"/>
</xsl:template>

<xsl:template match="page">

<xsl:for-each select="rawbdy">
	<xsl:apply-templates select="."/>
</xsl:for-each>

</xsl:template>

<xsl:apply-templates />

</xsl:stylesheet>
