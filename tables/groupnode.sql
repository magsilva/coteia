USE mysql;
insert into user (host,user,password) values ('$HOST_COWEB','$anota_dbuser', PASSWORD('$anota_dbpword'));
insert into db (host,db,user,select_priv,insert_priv,update_priv,delete_priv,alter_priv) values ('$HOST_COWEB','$anota_dbname','$anota_dbuser','Y','Y','Y','Y','Y');
FLUSH PRIVILEGES;

CREATE DATABASE $anota_dbname;

USE $anota_dbname;

CREATE TABLE annotation (
  id int(11) NOT NULL auto_increment,
  id_main int(11) NOT NULL default '0',
  id_father int(11) NOT NULL default '0',
  level int(11) NOT NULL default '0',
  format varchar(30) NOT NULL default '',
  type varchar(30) default '',
  title varchar(50) default '',
  kwd1 varchar(50) default NULL,
  kwd2 varchar(50) default NULL,
  kwd3 varchar(50) default NULL,
  id_owner int(11) NOT NULL default '0',
  id_group int(11) NOT NULL default '0',
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  id_last_user int(11) NOT NULL default '0',
  id_last_group int(11) NOT NULL default '0',
  last_modified datetime NOT NULL default '0000-00-00 00:00:00',
  annotates varchar(100) default NULL,
  context varchar(100) default NULL,
  access_permission int(11) NOT NULL default '0',
  body text,
  token varchar(100) default NULL,
  version int(11) NOT NULL default '0',
  PRIMARY KEY (id)
);

CREATE TABLE annotation_history (
  id_annotation int(11) NOT NULL default '0',
  id_user int(11) NOT NULL default '0',
  id_group int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  msg varchar(50) default NULL,
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (id_annotation,id_user,id_group,version)
);

CREATE TABLE event (
  id int(11) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  last_modified datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (id)
);

CREATE TABLE folder (
  id int(11) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  id_main int(11) NOT NULL default '0',
  id_father int(11) NOT NULL default '0',
  level int(11) NOT NULL default '0',
  id_owner int(11) NOT NULL default '0',
  id_group int(11) NOT NULL default '0',
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  id_last_user int(11) NOT NULL default '0',
  id_last_group int(11) NOT NULL default '0',
  last_modified datetime NOT NULL default '0000-00-00 00:00:00',
  access_permission int(11) NOT NULL default '0',
  PRIMARY KEY (id)
);

CREATE TABLE rel_annotation_folder (
  id_folder int(11) NOT NULL default '0',
  id_annotation int(11) NOT NULL default '0',
  insertion_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (id_folder,id_annotation)
);

CREATE TABLE rel_event_resource_user (
  id_event int(11) NOT NULL default '0',
  id_resource int(11) NOT NULL default '0',
  id_user int(11) NOT NULL default '0',
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (id_event,id_resource,id_user)
);
