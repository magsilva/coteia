USE mysql;
insert into user (host,user,password) values ('localhost','root', PASSWORD('eimi3a7e'));
insert into db (host,db,user,select_priv,insert_priv,update_priv,delete_priv,alter_priv) values ('localhost','coteia_users','root','Y','Y','Y','Y','Y');
FLUSH PRIVILEGES;

CREATE DATABASE coteia_users;

USE coteia_users;

CREATE TABLE t_group (
  id int(11) NOT NULL auto_increment,
  name varchar(40) NOT NULL default '',
  creation_datetime datetime NOT NULL default '0000-00-00 00:00:00',
  modification_datetime datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
);

CREATE TABLE t_user (
  id int(11) NOT NULL auto_increment,
  login varchar(15) NOT NULL default '',
  name varchar(40) NOT NULL default '',
  email varchar(25) default NULL,
  password varchar(32) NOT NULL default '',
  id_default_group int(11) NOT NULL default '0',
  creation_datetime datetime NOT NULL default '0000-00-00 00:00:00',
  modification_datetime datetime NOT NULL default '0000-00-00 00:00:00',
  commentary varchar(255) default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE t_user_group (
  id_group int(11) NOT NULL default '0',
  id_user int(11) NOT NULL default '0',
  creation_datetime datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id_group,id_user)
);