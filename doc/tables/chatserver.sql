USE mysql;
insert into user (host,user,password) values ('localhost','root', PASSWORD('eimi3a7e'));
insert into db (host,db,user,select_priv,insert_priv,update_priv,delete_priv,alter_priv) values ('localhost','coteia_chatserver','root','Y','Y','Y','Y','Y');
FLUSH PRIVILEGES;

CREATE DATABASE coteia_chatserver;

USE coteia_chatserver;


CREATE TABLE access (
  username char(20) NOT NULL default '',
  session_id int(11) NOT NULL default '0',
  first_access datetime default NULL,
  last_access datetime default NULL,
  PRIMARY KEY  (username,session_id)
);

CREATE TABLE msg (
  msg_id int(11) NOT NULL auto_increment,
  session_id int(11) NOT NULL default '0',
  sender varchar(20) default NULL,
  receiver varchar(20) default NULL,
  timestamp datetime default NULL,
  msg_content text,
  PRIMARY KEY  (msg_id)
);

CREATE TABLE saved (
  session_id int(11) NOT NULL default '0',
  filename char(45) NOT NULL default '',
  timestamp datetime default NULL
);

CREATE TABLE session (
  session_id int(11) NOT NULL auto_increment,
  session_name char(30) default NULL,
  creation_date datetime default NULL,
  moderator char(20) default NULL,
  last_access datetime default NULL,
  PRIMARY KEY  (session_id)
);