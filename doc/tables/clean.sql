USE coteia;
DELETE FROM `admin`;
DELETE FROM `gets` ;
DELETE FROM `paginas`;
DELETE FROM `sessions`;
DELETE FROM `swiki`;
insert into admin values (NULL, 'admin' , 'magsilva@icmc.usp.br' ,'admin', MD5('eimi3a7e'));

USE coteia_chatserver;
DELETE FROM `access`;
DELETE FROM `msg`;
DELETE FROM `saved`;
DELETE FROM `session`;
 
USE coteia_eclass;
DELETE FROM `aluno`;
DELETE FROM `assiste`;
DELETE FROM `aula`;
DELETE FROM `aula_coteia`;
DELETE FROM `cursa`;
DELETE FROM `curso`;
DELETE FROM `diaSemana`;
DELETE FROM `duracao`;
DELETE FROM `horario`;
DELETE FROM `ministra`;
DELETE FROM `profMinistraCurso`;
DELETE FROM `professor`;
DELETE FROM `sala`;
DELETE FROM `whenWhereDoCurso`;

USE coteia_groupnode;
DELETE FROM `annotation`;
DELETE FROM `annotation_history`;
DELETE FROM `event`;
DELETE FROM `folder`;
DELETE FROM `rel_annotation_folder`;
DELETE FROM `rel_event_resource_user`;

USE coteia_users;
DELETE FROM `t_group`;
DELETE FROM `t_user`;
DELETE FROM `t_user_group`;