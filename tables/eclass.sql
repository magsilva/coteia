CREATE TABLE aluno (
  aluno_id char(16) NOT NULL default '',
  nome char(60) NOT NULL default '',
  nusp char(7) NOT NULL default '',
  PRIMARY KEY  (aluno_id)
) TYPE=MyISAM;

CREATE TABLE assiste (
  aula_id int(11) default NULL,
  aluno_id char(16) default NULL,
  assistiu char(3) default NULL,
  UNIQUE KEY assiste (aula_id,aluno_id)
) TYPE=MyISAM;

CREATE TABLE aula (
  aula_id int(11) NOT NULL auto_increment,
  curso_id int(11) NOT NULL default '0',
  titulo char(50) NOT NULL default '',
  data date NOT NULL default '0000-00-00',
  inicio time NOT NULL default '00:00:00',
  fim time NOT NULL default '00:00:00',
  PRIMARY KEY  (aula_id)
) TYPE=MyISAM;

CREATE TABLE aula_coteia (
  aula_id int(11) NOT NULL auto_increment,
  curso_id int(11) default NULL,
  titulo varchar(50) default NULL,
  data date default NULL,
  PRIMARY KEY  (aula_id)
) TYPE=MyISAM;

CREATE TABLE cursa (
  curso_id int(11) default NULL,
  aluno_id char(16) default NULL,
  UNIQUE KEY cursa (curso_id,aluno_id)
) TYPE=MyISAM;

CREATE TABLE curso (
  curso_id int(11) NOT NULL auto_increment,
  nome char(50) NOT NULL default '',
  sigla char(8) NOT NULL default '',
  semestre char(6) NOT NULL default '1o 200',
  sala char(6) NOT NULL default 'LAB4',
  gmd char(2) NOT NULL default 'G',
  homepage char(100) NOT NULL default 'http://coweb.icmc.usp.br/',
  horario time NOT NULL default '00:00:00',
  duracao int(3) NOT NULL default '180',
  PRIMARY KEY  (curso_id)
) TYPE=MyISAM;

CREATE TABLE diaSemana (
  id int(11) NOT NULL auto_increment,
  diaSemana varchar(20) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE duracao (
  id int(11) NOT NULL auto_increment,
  duracao time NOT NULL default '00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE horario (
  id int(11) NOT NULL auto_increment,
  horario time NOT NULL default '00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE ministra (
  aula_id int(11) default NULL,
  user_id char(16) default NULL,
  UNIQUE KEY ministra (aula_id,user_id)
) TYPE=MyISAM;

CREATE TABLE profMinistraCurso (
  prof_id varchar(16) NOT NULL default '',
  curso_id int(11) NOT NULL default '0',
  PRIMARY KEY  (prof_id,curso_id)
) TYPE=MyISAM;

CREATE TABLE professor (
  user_id char(16) NOT NULL default '',
  nome char(50) NOT NULL default '',
  senha char(16) NOT NULL default '',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;

CREATE TABLE sala (
  id int(11) NOT NULL auto_increment,
  sala varchar(10) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE whenWhereDoCurso (
  curso_id int(11) NOT NULL default '0',
  diaSemana_id int(11) default NULL,
  sala_id int(11) default NULL,
  horario_id int(11) default NULL,
  duracao_id int(11) default NULL
) TYPE=MyISAM;
