-- MySQL dump 8.23
--
-- Host: coweb.icmc.usp.br    Database: groupnote
---------------------------------------------------------
-- Server version	3.23.58

--
-- Table structure for table `annotation`
--

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
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `annotation_history`
--

CREATE TABLE annotation_history (
  id_annotation int(11) NOT NULL default '0',
  id_user int(11) NOT NULL default '0',
  id_group int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  msg varchar(50) default NULL,
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id_annotation,id_user,id_group,version)
) TYPE=MyISAM;

--
-- Table structure for table `event`
--

CREATE TABLE event (
  id int(11) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  last_modified datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `folder`
--

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
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `rel_annotation_folder`
--

CREATE TABLE rel_annotation_folder (
  id_folder int(11) NOT NULL default '0',
  id_annotation int(11) NOT NULL default '0',
  insertion_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id_folder,id_annotation)
) TYPE=MyISAM;

--
-- Table structure for table `rel_event_resource_user`
--

CREATE TABLE rel_event_resource_user (
  id_event int(11) NOT NULL default '0',
  id_resource int(11) NOT NULL default '0',
  id_user int(11) NOT NULL default '0',
  creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id_event,id_resource,id_user)
) TYPE=MyISAM;

