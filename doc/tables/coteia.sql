USE mysql;
insert into user (host,user,password) values ('localhost','root', PASSWORD('eimi3a7e'));
insert into db (host,db,user,select_priv,insert_priv,update_priv,delete_priv,alter_priv) values ('localhost','coteia','root','Y','Y','Y','Y','Y');
FLUSH PRIVILEGES;

CREATE DATABASE coteia;

USE coteia;

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
	annotation_login enum('S','N') default 'N',
	id_chat int,
	id_ann int,
	id_eclass int default 0,
	primary key (id)
);

CREATE TABLE gets (
	id_pag varchar(20) not null ,
	id_sw int not null,
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

insert into admin values (NULL, 'admin' , 'magsilva@icmc.usp.br' ,'admin', MD5('eimi3a7e'));