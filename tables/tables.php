<?php

include_once( "../config.php.inc" );
include_once( "cvs_utils.php.inc" );

function recurse_chmod($path2dir, $mode){
   $dir = new dir($path2dir);
   while( ( $file = $dir->read() ) !== false ) {
       if( is_dir( $dir->path.$file ) ) {
           recurse_chmod( $dir->path.$file, $mode );
       } else {
           chmod( $file, $mode );
       }
   }
   $dir->close();
}

function setup_dir( $dir ) {
	if ( !file_exists( $dir ) ) {
		mkdir( $dir, 0777 );
	}
	chmod( $dir, 0777 );
	recursive_chmod( $dir, 0777 );
}

function login_cvs() {
  global $PATH_COWEB, $CVS_USERNAME, $CVS_HOST, $CVS_REPOSITORY, $CVS_PASSWORD, $CVS_PASSFILE;

  $pass_file = fopen( $CVS_PASSFILE, "w" );
  fwrite( $pass_file, ":pserver:".$CVS_USERNAME."@".$CVS_HOST.":".$CVS_REPOSITORY );
  fwrite( " " );
  fwrite( scramble( $CVS_PASSWORD ) );
  fclose( $passfile );
}

setup_dir( $PATH_XML );
setup_dir( $PATH_XHTML );
setup_dir( $PATH_UPLOAD );
setup_dir( $PATH_ARQUIVOS );


print <<<END
USE mysql;
insert into user (host,user,password) values ('$dbhost','$dbuser', PASSWORD('$dbpass'));
insert into db (host,db,user,select_priv,insert_priv,update_priv,delete_priv,alter_priv) values ('$dbhost','$dbname','$dbuser','Y','Y','Y','Y','Y');
FLUSH PRIVILEGES;

CREATE DATABASE $dbname;

USE $dbname;

CREATE TABLE admin (
	id int not null auto_increment,
	nome varchar(100),
	email varchar(25),
	login varchar(20) not null,
	pass text,
	primary key (id)
);

CREATE TABLE sessions (
	id int(10) not null auto_increment, 
	sess_key varchar(6) not null,
	val varchar(250) not null,
	ip varchar(35) not null,
	sec_expire varchar(50),
	access int(25) not null default '0',
	primary key (id)
);

CREATE TABLE swiki (
	id int auto_increment not null,
	status enum('0','1','2','3') default '0',
	visivel enum('S','N') default 'S',
	semestre varchar(10),
	titulo varchar(80),
	username varchar(10),
	password text,
	log_adm varchar(8),
	admin varchar(40),
	admin_mail varchar(60),
	data datetime,
	annotation_login enum('S','N') default 'N' 
	id_chat int,
	id_ann int,
	id_eclass int default 0,
	primary key (id)
);

CREATE TABLE gets (
	id_pag varchar(20) not null ,
	id_sw int not null ,
	data datetime,
	primary key (id_pag,id_sw)
);

CREATE TABLE paginas (
	ident char(20) not null,
	indexador varchar(150),
	titulo varchar(150),
	conteudo text,
	ip varchar(15),
	data_criacao datetime,
	data_ultversao datetime,
	pass text,
	kwd1 varchar(30),
	kwd2 varchar(30),
	kwd3 varchar(30),
	autor text,
	primary key (ident)
);

CREATE TABLE backup (
	ident varchar(20),
	indexador varchar(80),
	titulo varchar(80),
	conteudo text,
	ip varchar(15),
	data datetime,
	pass text,
	kwd1 varchar(30),
	kwd2 varchar(30),
	kwd3 varchar(30),
	autor text
);

insert into admin values (NULL, 'admin' , '$ADMIN_MAIL' ,'admin', MD5('$ADMIN_PASSWORD'));
END;
?>
