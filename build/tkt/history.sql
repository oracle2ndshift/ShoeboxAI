-- filename:  history.sql
-- purpose:   Build orawiki history schema
-- usage:     source history.sql
-- history:     
--      swhite  11-feb-2013     created

drop table Portal.users_history;
drop table Portal.clients_history ;
drop table Portal.client_users_history ;
drop table Portal.jobs_history ;
drop table Portal.user_jobs_history ;
drop table Tkt.rec_history ;
drop table Tkt.body_history ;
drop table Time.rec_history ;
drop table Time.body_history ;

select 'Create Portal.users_history';
create table Portal.users_history (
  id             integer  auto_increment,
  uid            integer ,
  old_uname      varchar(25) ,
  new_uname      varchar(25) ,
  old_lastname   varchar(50) ,
  new_lastname   varchar(50) ,
  old_firstname  varchar(50) ,
  new_firstname  varchar(50) ,
  old_middlename varchar(10) ,
  new_middlename varchar(10) ,
  old_addr1      varchar(100) ,
  new_addr1      varchar(100) ,
  old_addr2      varchar(100) ,
  new_addr2      varchar(100) ,
  old_addr3      varchar(100) ,
  new_addr3      varchar(100) ,
  old_addr4      varchar(100) ,
  new_addr4      varchar(100) ,
  old_addr5      varchar(100) ,
  new_addr5      varchar(100) ,
  old_email      varchar(100) ,
  new_email      varchar(100) ,
  old_phone      varchar(50) ,
  new_phone      varchar(50) ,
  old_cell       varchar(50) ,
  new_cell       varchar(50) ,
  old_fax        varchar(50) ,
  new_fax        varchar(50) ,
  old_roles      integer ,
  new_roles      integer ,
  old_apps       integer ,
  new_apps       integer ,
  old_updated    datetime ,
  new_updated    datetime ,
  old_upd_by     varchar(25) ,
  new_upd_by     varchar(25) ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key (uid),
  key (new_lastname,new_firstname),
  key (old_lastname,old_firstname))
  engine=MyISAM default charset=latin1;

select 'Create Portal.clients_history';
create table Portal.clients_history (
  id         integer  auto_increment,
  cid        integer ,
  old_cname      varchar(25) ,
  new_cname      varchar(25) ,
  old_name       varchar(100) ,
  new_name       varchar(100) ,
  old_addr1      varchar(100)  ,
  new_addr1      varchar(100)  ,
  old_addr2      varchar(100)  ,
  new_addr2      varchar(100)  ,
  old_addr3      varchar(100)  ,
  new_addr3      varchar(100)  ,
  old_addr4      varchar(100)  ,
  new_addr4      varchar(100)  ,
  old_addr5      varchar(100)  ,
  new_addr5      varchar(100)  ,
  old_email      varchar(100)  ,
  new_email      varchar(100)  ,
  old_phone      varchar(50)  ,
  new_phone      varchar(50)  ,
  old_altphone   varchar(50)  ,
  new_altphone   varchar(50)  ,
  old_fax        varchar(50)  ,
  new_fax        varchar(50)  ,
  old_updated    datetime ,
  new_updated    datetime ,
  old_upd_by     varchar(25) ,
  new_upd_by     varchar(25) ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key (cid),
  key (old_cname),
  key (new_cname))
  engine=MyISAM default charset=latin1;

select 'Create Portal.client_users_history';
create table Portal.client_users_history (
  id         integer  auto_increment,
  cuid       integer ,
  old_cname      varchar(25) ,
  new_cname      varchar(25) ,
  old_uname      varchar(25) ,
  new_uname      varchar(25) ,
  old_updated    datetime ,
  new_updated    datetime ,
  old_upd_by     varchar(25) ,
  new_upd_by     varchar(25) ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key (cuid),
  key (old_uname,old_cname),
  key (new_uname,new_cname))
  engine=MyISAM default charset=latin1;

select 'Create Portal.jobs_history';
create table Portal.jobs_history (
  id         integer  auto_increment,
  jid        integer ,
  old_name      varchar(50) ,
  new_name      varchar(50) ,
  old_updated    datetime ,
  new_updated    datetime ,
  old_upd_by     varchar(25) ,
  new_upd_by     varchar(25) ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key (jid),
  key (old_name),
  key (new_name))
  engine=MyISAM default charset=latin1;

select 'Create Portal.user_jobs_history';
create table Portal.user_jobs_history (
  id         integer  auto_increment,
  ujid       integer ,
  old_uname      varchar(25) ,
  new_uname      varchar(25) ,
  old_jid        integer ,
  new_jid        integer ,
  old_updated    datetime ,
  new_updated    datetime ,
  old_upd_by     varchar(25) ,
  new_upd_by     varchar(25) ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key (ujid),
  key (old_uname,old_jid),
  key (new_uname,new_jid))
  engine=MyISAM default charset=latin1;

select 'Create Tkt.rec_history';
create table Tkt.rec_history (
  id             integer  auto_increment,
  recid          integer ,
  old_summary    varchar(80) ,
  new_summary    varchar(80) ,
  old_type       integer(1) ,
  new_type       integer(1) ,
  old_status     integer(2) ,
  new_status     integer(2) ,
  old_assigned   varchar(10) ,
  new_assigned   varchar(10) ,
  old_cre_by varchar(10) ,
  new_cre_by varchar(10) ,
  old_upd_by     varchar(10) ,
  new_upd_by     varchar(10) ,
  old_upddate    datetime ,
  new_upddate    datetime ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key (recid),
  key k_tkt_rec_own_old (old_assigned,old_status,old_type),
  key k_tkt_rec_own_new (new_assigned,new_status,new_type),
  key k_tkt_rec_stat_old (old_status,old_type),
  key k_tkt_rec_stat_new (new_status,new_type)
) engine=MyISAM default charset=latin1;

select 'Create Tkt.body_history';
create table Tkt.body_history (
  id          integer  auto_increment,
  recid       integer ,
  old_descr   varchar(4000) ,
  new_descr   varchar(4000) ,
  old_created datetime ,
  new_created datetime ,
  old_upddate datetime ,
  new_upddate datetime ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key k_tktno (recid)
)  engine=MyISAM default charset=latin1;

select 'Create Time.rec_history';
create table Time.rec_history (
  id         integer  auto_increment,
  recid      integer ,
  old_title      varchar(50) ,
  new_title      varchar(50) ,
  old_cname      varchar(30) ,
  new_cname      varchar(30) ,
  old_uname      varchar(30) ,
  new_uname      varchar(30) ,
  old_summary    varchar(100) ,
  new_summary    varchar(100) ,
  old_status     int(2) ,
  new_status     int(2) ,
  old_updated    datetime ,
  new_updated    datetime ,
  old_upd_by     varchar(25) ,
  new_upd_by     varchar(25) ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key(recid,new_updated)
) engine=MyISAM default charset=latin1;

select 'Create Time.body_history';
create table Time.body_history (
  id         integer  auto_increment,
  bodyid     integer ,
  recid      integer ,
  old_day        integer(1) ,
  new_day        integer(1) ,
  old_minutes    integer ,
  new_minutes    integer ,
  old_descr      varchar(1000) ,
  new_descr      varchar(1000) ,
  old_updated    datetime ,
  new_updated    datetime ,
  old_upd_by     varchar(25) ,
  new_upd_by     varchar(25) ,
  action         varchar(1) not null default 'U',
  primary key (id),
  key (bodyid),
  key (recid))
  engine=MyISAM default charset=latin1;


