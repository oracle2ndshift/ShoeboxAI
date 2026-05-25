-- purpose:   Build orawiki schema
--
-- bitmaps:  
--  (1,'New');
--  (2,'Active');
--  (4,'Inactive');
--  (8,'Approved');
--  (16,'Unused');
--  (32,'Unused');
--  (64,'Unused');
--  (128,'Unused');
--  (264,'Unused');
--  (512,'Unused');
--  (1024,'Unused');
--  (2048,'Unused');
--  (4096,'Unused');
--  (8193,'Unused');
--  (16384,'Unused');
--  (32768,'Unused');
--  (65536,'Unused');
--  (131072,'Unused');
--  (262144,'Unused');
--  (524288,'Unused');
--  (1048576,'Unused');
--  (2097152,'Unused');
--  (4194304,'Unused');

-- usage:     source build.sql
-- history:	
--	swhite	11-feb-2013	created

select 'Drop databases';
drop database ToDo;
drop database Time;
drop database Portal;
drop database Logs;
drop database Admin;
drop database Doc;

tee build.lis
--
-- schemas
-- 
select 'Create databases';
create database ToDo;
create database Portal;
create database Time;
create database Logs;
create database Admin;
create database Doc;
--
-- constants
--
select 'Create constants';
-- bit map
set @status_new      = 1;
set @status_active   = 2;
set @status_inactive = 4;
set @status_approved = 8;

-- bit map
set @role_guest      = 1;
set @role_root       = 2;
set @role_admin      = 4;
set @role_emp        = 8;
set @role_mgr        = 16;
set @role_user       = 32;

-- bit map
set @apps            = 1;
set @apps_portal     = 2;
set @apps_admin      = 4;
set @apps_rpt        = 8;
set @apps_todo        = 16;
set @apps_time       = 32;
set @apps_doc        = 64;
set @apps_calendar   = 128;

-- integer
set @todo_type_task   = 1;
set @todo_type_bug    = 2;
set @todo_type_enh    = 3;
set @todo_type_note   = 4;

-- integer - the status values signify work flow
set @todo_st_new            = 10; 
set @todo_st_reopened       = 11;
set @todo_st_mgr_review     = 20;
set @todo_st_mgr_research   = 21;
set @todo_st_doc_wip        = 30;  -- writing/documentation
set @todo_st_doc_waiting    = 31;
set @todo_st_call_wip       = 32;  -- phone call needed
set @todo_st_call_waiting   = 33;  
set @todo_st_dev_wip        = 40;  -- development work
set @todo_st_dev_waiting    = 41;
set @todo_st_res_wip        = 50;  -- research/investigate in progress
set @todo_st_res_waiting    = 51;
set @todo_st_qa_wip         = 80;
set @todo_st_qa_waiting     = 81;
set @todo_st_fixed          = 90;
set @todo_st_misc_done      = 91;
set @todo_st_wont_fix       = 93;
set @todo_st_worksforme     = 94;

-- --------------------------------------------------------------------------------
-- Admin tables
-- --------------------------------------------------------------------------------
select 'Create Admin.apps bitmap table';
create table Admin.apps (
  id integer not null,
  name       varchar(25),
  primary key (id) 
) engine=InnoDB default charset=latin1 comment 'Bitmap apps data';

insert into Admin.apps values (@apps,'Guest');
insert into Admin.apps values (@apps_portal,'Portal');
insert into Admin.apps values (@apps_admin,'Admin');
insert into Admin.apps values (@apps_rpt,'Reporting');
insert into Admin.apps values (@apps_todo,'To Do');
insert into Admin.apps values (@apps_time,'Time Tracking');
insert into Admin.apps values (@apps_doc,'Document Library');
insert into Admin.apps values (@apps_calendar,'Calendar');

select 'Create Admin.status bitmap table';
create table Admin.status (
  id         integer not null,
  name       varchar(25),
  primary key (id)  
) engine=InnoDB default charset=latin1 comment 'Bitmap status data';

insert into Admin.status values (@status_new,'New');
insert into Admin.status values (@status_active,'Active');
insert into Admin.status values (@status_inactive,'Inactive');
insert into Admin.status values (@status_approved,'Approved');

select 'Create Admin.role bitmap table';
create table Admin.role (
  id         integer not null,
  name       varchar(25),
  primary key (id)  
) engine=InnoDB default charset=latin1 comment 'Bitmap role data';

insert into Admin.role values (@role_guest,'Guest');
insert into Admin.role values (@role_root,'Root');
insert into Admin.role values (@role_admin,'Admin');
insert into Admin.role values (@role_emp,'Employee');
insert into Admin.role values (@role_mgr,'Manager');
insert into Admin.role values (@role_user,'User');

-- --------------------------------------------------------------------------------
-- Portal tables
-- --------------------------------------------------------------------------------
select 'Create Portal.users';
create table Portal.users (
  id         integer not null auto_increment,
  uname      varchar(10) not null comment 'Short string unique identifier',
  lastname   varchar(50) not null,
  firstname  varchar(50) not null,
  middlename varchar(10) not null default '',
  addr1      varchar(100) not null default '',
  addr2      varchar(100) not null default '',
  addr3      varchar(100) not null default '',
  addr4      varchar(100) not null default '',
  addr5      varchar(100) not null default '',
  email      varchar(100) not null default '',
  phone      varchar(50) not null default '',
  cell       varchar(50) not null default '',
  fax        varchar(50) not null default '',
  roles      integer not null default 1 comment 'Bitmap column',
  apps       integer not null default 1 comment 'Bitmap column',
  created    datetime not null,
  updated    datetime not null,
  upd_by     varchar(10) not null comment 'Same as uname',
  primary key (id),
  unique key uk_uname (uname),
  key k_email (email),
  key k_lastname_firstname (lastname,firstname),
  key k_roles (roles),
  foreign key fk_users_roles (roles) references Admin.role(id)
) engine=InnoDB default charset=latin1 comment 'App users';

insert into Portal.users (uname,lastname,firstname,roles,apps,created,updated,upd_by)
values ('system','System','',@role_root,@apps_all,now(),now(),'dba');

insert into Portal.users (uname,lastname,firstname,roles,apps,created,updated,upd_by)
values ('dba','DBA','',@role_root,@apps_all,now(),now(),'dba');

select 'Create Portal.clients';
create table Portal.clients (
  id         integer not null auto_increment,
  cname      varchar(25) not null comment 'Short string name unique identifier',
  name       varchar(100) not null comment 'Full client name',
  addr1      varchar(100) not null default '' comment 'Freeform address lines',
  addr2      varchar(100) not null default '' comment 'Freeform address lines',
  addr3      varchar(100) not null default '' comment 'Freeform address lines',
  addr4      varchar(100) not null default '' comment 'Freeform address lines',
  addr5      varchar(100) not null default '' comment 'Freeform address lines',
  email      varchar(100) not null default '',
  phone      varchar(50) not null default '',
  altphone   varchar(50) not null default '',
  fax        varchar(50) not null default '',
  apps       integer not null default 0 comment 'Bitmap column',
  created    datetime not null,
  updated    datetime not null,
  upd_by     varchar(10) not null comment 'Same as uname',
  primary key (id),
  unique key uk_cname (cname),
  key k_clients_name (name)  
) engine=InnoDB default charset=latin1 comment 'App clients';

insert into Portal.clients (cname,name,created,updated,upd_by)
values ('None','None',now(),now(),'system');

select 'Create Portal.client_users';
create table Portal.client_users (
  id         integer not null auto_increment,
  cname      varchar(25) not null comment 'Short string unique identifier',
  uname      varchar(10) not null comment 'Short string unique identifier',
  created    datetime not null,
  updated    datetime not null,
  upd_by     varchar(10) not null comment 'Same as uname',
  primary key (id),
  unique key (uname,cname),
  foreign key fk_client_users_uname (uname) references Portal.users(uname)
  on delete cascade on update cascade,
  foreign key fk_client_users_cname (cname) references Portal.clients(cname) 
  on delete cascade on update cascade
) engine=InnoDB default charset=latin1 comment 'Many to one, users and clients';

select 'Create Portal.jobs';
create table Portal.jobs (
  id         integer not null auto_increment,
  name       varchar(50) not null,
  created    datetime not null,
  updated    datetime not null,
  upd_by     varchar(10) not null comment 'Same as uname',
  primary key (id),
  unique key uk_jobs_name (name)  
) engine=InnoDB default charset=latin1 comment 'Jobs list for users';

insert into Portal.jobs (name,created,updated,upd_by) 
values ('dba',now(),now(),'dba');

select 'Create Portal.user_jobs';
create table Portal.user_jobs (
  id         integer not null auto_increment,
  uname      varchar(10) not null,
  jid        integer not null,
  created    datetime not null,
  updated    datetime not null,
  upd_by     varchar(10) not null comment 'Same as uname',
  primary key (id),
  unique key uk_user_jobs_uname_jid (uname,jid),
  foreign key fk_user_jobs_uname (uname) references Portal.users(uname)
  on delete cascade on update cascade,
  foreign key fk_user_jobs_jid (jid) references Portal.jobs(id)  
  on delete cascade on update cascade
) engine=InnoDB default charset=latin1 comment 'Many to one, users and jobs';

insert into Portal.user_jobs (uname,jid,created,updated,upd_by)
values ('dba',1,now(),now(),'dba');

-- -----------------------------------------------------------------------------
-- ToDo tables
-- -----------------------------------------------------------------------------
-- select 'Create ToDo.type';
-- create table ToDo.type (
--   id         integer not null,
--   name       varchar(10),
--   unique key (id),
--   unique key todo_type_name (name))
-- engine=MyISAM default charset=latin1 comment 'Bitmap ticket types';
-- 
-- insert into ToDo.type values (@todo_type_task,'Task');
-- insert into ToDo.type values (@todo_type_bug,'Bug');
-- insert into ToDo.type values (@todo_type_enh,'Enhancement');
-- insert into ToDo.type values (@todo_type_note,'Notes');

-- select 'Create ToDo.category';
-- create table ToDo.category (
--   id         integer not null,
--   name       varchar(15),
--   primary key (id),
--   unique key todo_cat_name (name))
-- engine=MyISAM default charset=latin1 comment 'Metadata ticket status categories';
-- 
-- insert into ToDo.category (id,name) values (10,'New');
-- insert into ToDo.category (id,name) values (20,'Triage');
-- insert into ToDo.category (id,name) values (30,'Documentation');
-- insert into ToDo.category (id,name) values (40,'Development');
-- insert into ToDo.category (id,name) values (50,'Architecture');
-- insert into ToDo.category (id,name) values (80,'QA');
-- insert into ToDo.category (id,name) values (90,'Closed');

-- select 'Create ToDo.status';
-- create table ToDo.status (
--   id         int(2) not null,
--   name       varchar(50) not null,
--   category   varchar(15) not null,
--   primary key (id),
--   unique key todo_stat_name (name)
-- ) engine=MyISAM default charset=latin1 comment 'Bitmap ticket status';
-- 
-- 
-- insert into ToDo.status values (@todo_st_new,'New','New');
-- insert into ToDo.status values (@todo_st_reopened,'Re-opened','New');
-- insert into ToDo.status values (@todo_st_mgr_review,'Review','Triage');
-- insert into ToDo.status values (@todo_st_mgr_research,'Research','Triage');
-- insert into ToDo.status values (@todo_st_doc_wip,'Doc Work in progress','Documentation');
-- insert into ToDo.status values (@todo_st_doc_waiting,'Doc Waiting for something','Documentation');
-- insert into ToDo.status values (@todo_st_dev_wip,'Dev Work in progress','Development');
-- insert into ToDo.status values (@todo_st_dev_waiting,'Dev Waiting for something','Development');
-- insert into ToDo.status values (@todo_st_arc_wip,'Arch Work in Progress','Architecture');
-- insert into ToDo.status values (@todo_st_arc_waiting,'Arch Waiting for something','Architecture');
-- insert into ToDo.status values (@todo_st_qa_wip,'QA Work in progress','QA');
-- insert into ToDo.status values (@todo_st_qa_waiting,'QA Waiting for something','QA');
-- insert into ToDo.status values (@todo_st_fixed,'Fixed','Closed');
-- insert into ToDo.status values (@todo_st_wont_fix,'Wont Fix','Closed');
-- insert into ToDo.status values (@todo_st_cant_reproduce,'Cannot Reproduce','Closed');

select 'Create ToDo.rec';
create table ToDo.rec (
  id         integer not null auto_increment,
  summary    varchar(80) not null default '',
  status     integer(2) not null default 10 comment 'Metadata column',
  assigned   varchar(10) not null default '' comment 'Username',
  cre_by     varchar(10) not null default '' comment 'Username',
  upd_by     varchar(10) not null default '' comment 'Username',
  created    datetime not null,
  upddate    datetime not null,
  primary key (id),
  key k_todo_rec_own (assigned,status),
  key k_todo_rec_stat (status)
) engine=InnoDB default charset=latin1 comment 'ToDo record header';
  
select 'Create ToDo.body';
create table ToDo.body (
  id         integer not null auto_increment,
  recid      integer not null,
  descr      varchar(4000) not null default '',
  created    datetime not null,
  upddate    datetime not null,
  primary key (id),
  key k_todono (recid),
  foreign key fk_todo_body (recid) references ToDo.rec(id)
  on delete cascade on update cascade
) engine=InnoDB default charset=latin1 comment 'ToDo record body';
  
-- -----------------------------------------------------------------------------
-- Time tables
-- -----------------------------------------------------------------------------
select 'Create Time.rec';
create table Time.rec (
  id         integer not null auto_increment,
  title      varchar(50) not null default '' comment 'Document title',
  cname      varchar(30) not null default 'None' comment 'Short string unique identifier',
  uname      varchar(10) not null comment 'Short string unique identifier',
  summary    varchar(100) not null default '',
  status     int(2) not null default 1 comment 'Metadata column',
  created    datetime not null,
  updated    datetime not null,
  upd_by     varchar(10) not null comment 'Same as uname',
  primary key (id),
  foreign key fk_time_uname (uname) references Portal.users(uname)
  on delete cascade on update cascade,
  foreign key fk_time_cname (cname) references Portal.clients(cname)
  on delete cascade on update cascade
) engine=InnoDB default charset=latin1 comment 'Time track header';

select 'Create Time.body';
create table Time.body (
  id         integer not null auto_increment,
  recid      integer not null,
  day        datetime not null,
  minutes    integer not null default 0,
  descr      varchar(100) not null default '',
  created    datetime not null,
  updated    datetime not null,
  upd_by     varchar(10) not null comment 'Same as uname',
  primary key (id),
  foreign key fk_body_recid (recid) references Time.rec(id)
  on delete cascade on update cascade
) engine=InnoDB default charset=latin1 comment 'Time track body records';

notee
