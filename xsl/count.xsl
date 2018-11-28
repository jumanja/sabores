<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

  </head>

  <body>

    <table>
     <xsl:for-each select="matrix/row">
          <xsl:if test="position()=1">
                    <tr>
                        <xsl:for-each select="col">
                            <th><xsl:value-of select="name" /></th>
                        </xsl:for-each>
                    </tr>
          </xsl:if>
          <th></th>
          <tr>
              <xsl:for-each select="col">
                  <td><xsl:value-of select="value" /></td>
              </xsl:for-each>
          </tr>

     </xsl:for-each>
    </table>
  </body>
</html>
</xsl:template>

</xsl:stylesheet>