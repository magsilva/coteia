<xsl:stylesheet version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xt="http://www.jclark.com/xt"
                extension-element-prefixes="xt">

<xsl:template match="/">
  <xsl:apply-templates select="page"/>
</xsl:template>

<xsl:template match="page">

<xsl:for-each select="bdy">
 <xsl:apply-templates select="."/>
</xsl:for-each>
</xsl:template>
</xsl:stylesheet>
