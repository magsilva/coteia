1º Passo - acertar o httpd.conf - definir index.php como entrada default de diretorio - e 
php.ini - modificar global_register para "On" e error_reporting para "E_ERROR" -.

2º Passo - modificar o script de criacao do banco ["tables/tables.txt"]:

3º Passo = digitar:
shell > entrar na interface do mysql. P.ex. >mysql -u root -p
mysql > use mysql;
mysql > insert into user (host,user,password) values ('localhost','[USER]', PASSWORD('[PASS]'));
mysql > insert into db (host,db,user,select_priv,insert_priv,update_priv,delete_priv,alter_priv) values ('localhost','[base de dados]',[USER]','Y','Y','Y','Y','Y');
mysql > flush privileges;

privilégio      nome_campo              relaciona

select          select_privt            tabela
insert          insert_priv             tabela
update          update_priv             tabela
delete          delete_priv             tabela
alter           alter_priv              tabela

4º Passo = digitar:

mysql > \. [PATH_SCRIPT]/tables.txt
mysql > quit

5º Passo = na area de administrador (diretorio admin/), modificar o script com as funcoes 
de conexao com o banco e as constantes do 
sistema ["function.inc"]

6º Passo = na area principal (diretorio raiz), modificar os scripts function.inc e coteia.xsl

7º Passo = acertar as permissoes do seguintes diretorios:

pastas com permissao 777 (total):
- files
- upload
- xml

arquivos com permissao 777 (total):
- log.txt

8º Passo = Para realizar backup do banco de dados:
shell> mysqldump --databases [database] -p > filename.sql

Administrador: Carlos Arruda Junior
Email: credajun@icmc.usp.br








