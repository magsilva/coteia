Requisitos:
- Servidor Apache (com PHP e esse com suporte a MySQL).
- Cliente CVS
- Acesso a um servidor MySQL e CVS.

Obtendo o c�digo via CVS;
 cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs login
 cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs checkout CoTeia

Obtendo o c�digo sem os metadados do CVS:
  cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs export -r HEAD CoTeia

Instala��o:
- Copie a CoTeia para um diret�rio disponibiliz�vel por seu servidor Apache e que possua o PHP ativado.
- Configure o PHP utilizado pelo Apache (arquivo "/etc/php.ini"), habilitando
a diretiva "register_globals".
- Modifique o arquivo "config.php" da CoTeia, configurando as vari�veis aplic�veis. Existe um exemplo em "doc/config.php.inc.eg".
- Execute o script "tables/setup.php".
- Altere o propriet�rio e o grupo dos arquivos da CoTeia de maneira adequada ao funcionamento no servidor em quest�o (geralmente alterar o propriet�rio para "www").
- Execute os esquemas SQL gerados no diret�rio "tables/" (arquivos com extens�o ".sql"). Observe que alguns dos banco de dados utilizados, em especial o de base de usu�rios compartilhada e cursos do eClass, podem estar disponibilizados na rede. Nesse caso, n�o � necess�rio executar os scripts para criar as respectivas bases de dados.
  Se mais de uma das bases de dados estiver no mesmo SGBD, altere os arquivos SQL, comentando a cria��o do usu�rio na base repetidas vezes.
- Copie o conte�do do arquivo "tables/htaccess" para o arquivo de configura��o do servidor Apache.
- Reinicie o Apache (devido �s altera��es de configura��o no PHP e da diretiva de configura��o de diret�rio acima adicionada).

Sobre o servi�o de chat:
- O servi�o de chat (chat.php) utiliza os recursos de outro servidor (o chatserver).


Copyright:
Este software inclui o software XML Parser de James Clark cujo copyright pode ser encontrado em libs/copyright-xp.txt. Mais informa��es sobre esse software podem ser encontradas em http://www.jclark.com/xml/xp/index.html.

Este software inclui o software XML Parser de Bill Lindsey cujo copyright pode ser encontrado em libs/copyright-xt.txt. Mais informa��es sobre esse software podem ser encontradas em http://www.blnz.com/xt/index.html.
