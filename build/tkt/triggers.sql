-- filename:  triggers.sql
-- purpose:   Build Portal mysql triggers
-- usage:     source triggers.sql
-- history:     
--      swhite  11-feb-2013     created

--
-- Portal.users
--
select 'Create triggers for Portal.users';
drop trigger if exists Portal.upd_users;
delimiter |
create trigger Portal.upd_users
        before update
        on Portal.users
 for each row begin
    insert into Portal.users_history (
      uid       ,
      old_uname     ,
      new_uname     ,
      old_lastname  ,
      new_lastname  ,
      old_firstname ,
      new_firstname ,
      old_middlename,
      new_middlename,
      old_addr1     ,
      new_addr1     ,
      old_addr2     ,
      new_addr2     ,
      old_addr3     ,
      new_addr3     ,
      old_addr4     ,
      new_addr4     ,
      old_addr5     ,
      new_addr5     ,
      old_email     ,
      new_email     ,
      old_phone     ,
      new_phone     ,
      old_cell      ,
      new_cell      ,
      old_fax       ,
      new_fax      ,
      old_roles    ,
      new_roles    ,
      old_apps     ,
      new_apps     ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.uname     ,
      new.uname     ,
      old.lastname  ,
      new.lastname  ,
      old.firstname ,
      new.firstname ,
      old.middlename,
      new.middlename,
      old.addr1     ,
      new.addr1     ,
      old.addr2     ,
      new.addr2     ,
      old.addr3     ,
      new.addr3     ,
      old.addr4     ,
      new.addr4     ,
      old.addr5     ,
      new.addr5     ,
      old.email     ,
      new.email     ,
      old.phone     ,
      new.phone     ,
      old.cell      ,
      new.cell      ,
      old.fax       ,
      new.fax      ,
      old.roles    ,
      new.roles    ,
      old.apps    ,
      new.apps    ,
      old.updated  ,
      new.updated  ,
      old.upd_by   ,
      new.upd_by  ,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Portal.del_users;
delimiter |
create trigger Portal.del_users
        before delete
        on Portal.users
 for each row begin
    insert into Portal.users_history (
      uid       ,
      old_uname     ,
      new_uname     ,
      old_lastname  ,
      new_lastname  ,
      old_firstname ,
      new_firstname ,
      old_middlename,
      new_middlename,
      old_addr1     ,
      new_addr1     ,
      old_addr2     ,
      new_addr2     ,
      old_addr3     ,
      new_addr3     ,
      old_addr4     ,
      new_addr4     ,
      old_addr5     ,
      new_addr5     ,
      old_email     ,
      new_email     ,
      old_phone     ,
      new_phone     ,
      old_cell      ,
      new_cell      ,
      old_fax       ,
      new_fax      ,
      old_roles    ,
      new_roles    ,
      old_apps    ,
      new_apps    ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.uname     ,
      ''     ,
      old.lastname  ,
      '',
      old.firstname ,
      '',
      old.middlename,
      '',
      old.addr1     ,
      '',
      old.addr2     ,
      '',
      old.addr3     ,
      '',
      old.addr4     ,
      '',
      old.addr5     ,
      '',
      old.email     ,
      '',
      old.phone     ,
      '',
      old.cell      ,
      '',
      old.fax       ,
      '',
      old.roles    ,
      '',
      old.apps    ,
      '',
      old.updated  ,
      '',
      old.upd_by   ,
      '',
      'D');
  end;
|
delimiter ;
show warnings;

--
-- Portal.clients
--
select 'Create triggers for Portal.clients';
drop trigger if exists Portal.upd_clients;
delimiter |
create trigger Portal.upd_clients
        before update
        on Portal.clients
 for each row begin
    insert into Portal.clients_history (
      cid       ,
      old_cname     ,
      new_cname     ,
      new_name  ,
      old_name ,
      old_addr1     ,
      new_addr1     ,
      old_addr2     ,
      new_addr2     ,
      old_addr3     ,
      new_addr3     ,
      old_addr4     ,
      new_addr4     ,
      old_addr5     ,
      new_addr5     ,
      old_email     ,
      new_email     ,
      old_phone     ,
      new_phone     ,
      old_altphone     ,
      new_altphone     ,
      old_fax      ,
      new_fax      ,
      old_apps    ,
      new_apps    ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.cname     ,
      new.cname     ,
      new.name  ,
      old.name ,
      old.addr1     ,
      new.addr1     ,
      old.addr2     ,
      new.addr2     ,
      old.addr3     ,
      new.addr3     ,
      old.addr4     ,
      new.addr4     ,
      old.addr5     ,
      new.addr5     ,
      old.email     ,
      new.email     ,
      old.phone     ,
      new.phone     ,
      old.altphone     ,
      new.altphone     ,
      old.fax      ,
      new.fax      ,
      old.apps    ,
      new.apps    ,
      old.updated  ,
      new.updated  ,
      old.upd_by   ,
      new.upd_by  ,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Portal.del_clients;
delimiter |
create trigger Portal.del_clients
        before delete
        on Portal.clients
 for each row begin
    insert into Portal.clients_history (
      cid       ,
      old_cname     ,
      new_cname     ,
      old_name ,
      new_name  ,
      old_addr1     ,
      new_addr1     ,
      old_addr2     ,
      new_addr2     ,
      old_addr3     ,
      new_addr3     ,
      old_addr4     ,
      new_addr4     ,
      old_addr5     ,
      new_addr5     ,
      old_email     ,
      new_email     ,
      old_phone     ,
      new_phone     ,
      old_altphone     ,
      new_altphone     ,
      old_fax      ,
      new_fax      ,
      old_apps    ,
      new_apps    ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.cname     ,
      ''     ,
      old.name ,
      ''  ,
      old.addr1     ,
      ''     ,
      old.addr2     ,
      ''     ,
      old.addr3     ,
      ''     ,
      old.addr4     ,
      ''     ,
      old.addr5     ,
      ''     ,
      old.email     ,
      ''     ,
      old.phone     ,
      ''     ,
      old.altphone     ,
      ''     ,
      old.fax      ,
      ''      ,
      old.apps    ,
      ''    ,
      old.updated  ,
      ''  ,
      old.upd_by   ,
      ''  ,
      'D');
  end;
|
delimiter ;
show warnings;


--
-- Portal.client_users
--
select 'Create triggers for Portalclient_.users';
drop trigger if exists Portal.upd_client_users;
delimiter |
create trigger Portal.upd_client_users
        before update
        on Portal.client_users
 for each row begin
    insert into Portal.client_users_history (
      cuid       ,
      old_uname     ,
      new_uname     ,
      old_cname  ,
      new_cname  ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.uname     ,
      new.uname     ,
      old.cname     ,
      new.cname     ,
      old.updated  ,
      new.updated  ,
      old.upd_by   ,
      new.upd_by  ,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Portal.del_client_users;
delimiter |
create trigger Portal.del_client_users
        before delete
        on Portal.client_users
 for each row begin
    insert into Portal.client_users_history (
      cuid       ,
      old_uname     ,
      new_uname     ,
      old_cname  ,
      new_cname  ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.uname     ,
      ''     ,
      old.cname     ,
      ''     ,
      old.updated  ,
      ''  ,
      old.upd_by   ,
      ''  ,
      'D');
  end;
|
delimiter ;
show warnings;

--
-- Portal.jobs
--
select 'Create triggers for Portal.jobs';
drop trigger if exists Portal.upd_jobs;
delimiter |
create trigger Portal.upd_jobs
        before update
        on Portal.jobs
 for each row begin
    insert into Portal.jobs_history (
      jid       ,
      old_name     ,
      new_name     ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.name     ,
      new.name     ,
      old.updated  ,
      new.updated  ,
      old.upd_by   ,
      new.upd_by  ,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Portal.del_jobs;
delimiter |
create trigger Portal.del_jobs
        before delete
        on Portal.jobs
 for each row begin
    insert into Portal.jobs_history (
      jid       ,
      old_name     ,
      new_name     ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.name     ,
      ''     ,
      old.updated  ,
      ''  ,
      old.upd_by   ,
      ''  ,
      'D');
  end;
|
delimiter ;
show warnings;

--
-- Portal.user_jobs
--
select 'Create triggers for Portal.user_jobs';
drop trigger if exists Portal.upd_user_jobs;
delimiter |
create trigger Portal.upd_user_jobs
        before update
        on Portal.user_jobs
 for each row begin
    insert into Portal.user_jobs_history (
      ujid       ,
      old_uname     ,
      new_uname     ,
      old_jid     ,
      new_jid     ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.uname     ,
      new.uname     ,
      old.jid     ,
      new.jid     ,
      old.updated  ,
      new.updated  ,
      old.upd_by   ,
      new.upd_by  ,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Portal.del_user_jobs;
delimiter |
create trigger Portal.del_user_jobs
        before delete
        on Portal.user_jobs
 for each row begin
    insert into Portal.user_jobs_history (
      ujid       ,
      old_uname     ,
      new_uname     ,
      old_jid     ,
      new_jid     ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.uname     ,
      ''     ,
      old.jid     ,
      ''     ,
      old.updated  ,
      ''  ,
      old.upd_by   ,
      ''  ,
      'D');
  end;
|
delimiter ;
show warnings;

--
-- Tkt.rec 
--
select 'Create triggers for Tkt.rec';
drop trigger if exists Tkt.upd_rec;
delimiter |
create trigger Tkt.upd_rec
        before update
        on Tkt.rec
 for each row begin
    insert into Tkt.rec_history (
      recid        ,
      old_summary  ,
      new_summary  ,
      old_type     ,
      new_type     ,
      old_status   ,
      new_status   ,
      old_assigned ,
      new_assigned ,
      old_cre_by,
      new_cre_by,
      old_upd_by , 
      new_upd_by  ,
      old_upddate ,
      new_upddate ,
      action)
    values (
      old.id       ,
      old.summary  ,
      new.summary  ,
      old.`type`     ,
      new.`type`     ,
      old.status   ,
      new.status   ,
      old.assigned ,
      new.assigned ,
      old.cre_by,
      new.cre_by,
      old.upd_by ,
      new.upd_by  ,
      old.upddate ,
      new.upddate ,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Tkt.del_rec;
delimiter |
create trigger Tkt.del_rec
        before delete
        on Tkt.rec
 for each row begin
    insert into Tkt.rec_history (
      recid        ,
      old_summary  ,
      new_summary  ,
      old_type     ,
      new_type     ,
      old_status   ,
      new_status   ,
      old_assigned ,
      new_assigned ,
      old_cre_by,
      new_cre_by,
      old_upd_by ,
      new_upd_by  ,
      old_upddate ,
      new_upddate ,
      action)
    values (
      old.id       ,
      old.summary  ,
      ''  ,
      old.`type`     ,
      ''     ,
      old.status   ,
      ''   ,
      old.assigned ,
      '' ,
      old.cre_by,
      '',
      old.upd_by ,
      ''  ,
      old.upddate ,
      '' ,
      'D');
  end;
| 
delimiter ;
show warnings;

--
-- Tkt.body 
--
select 'Create triggers for Tkt.body';
drop trigger if exists Tkt.upd_body;
delimiter |
create trigger Tkt.upd_body
        before update
        on Tkt.body
 for each row begin
    insert into Tkt.body_history (
      recid       ,
      old_descr ,
      new_descr ,
      old_created,
      new_created,
      old_upddate,
      new_upddate,
      action  )
    values (
      old.recid       ,
      old.descr , 
      new.descr , 
      old.created,
      new.created,
      old.upddate,
      new.upddate,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Tkt.del_body;
delimiter |
create trigger Tkt.del_body
        before delete
        on Tkt.body
 for each row begin
    insert into Tkt.body_history (
      recid       ,
      old_descr ,
      new_descr ,
      old_created,
      new_created,
      old_upddate,
      new_upddate,
      action  )
    values (
      old.recid       ,
      old.descr ,
      '' ,
      old.created,
      '',
      old.upddate,
      '',
      'D');
  end;
|
delimiter ;
show warnings;


--
-- Time.rec
--
select 'Create triggers for Time.rec';
drop trigger if exists Time.upd_rec;
delimiter |
create trigger Time.upd_rec
        before update
        on Time.rec
 for each row begin
    insert into Time.rec_history (
      recid       ,
      old_title     ,
      new_title     ,
      old_cname     ,
      new_cname     ,
      old_uname     ,
      new_uname     ,
      old_summary     ,
      new_summary     ,
      old_status     ,
      new_status     ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.title     ,
      new.title     ,
      old.cname     ,
      new.cname     ,
      old.uname     ,
      new.uname     ,
      old.summary     ,
      new.summary     ,
      old.status     ,
      new.status     ,
      old.updated  ,
      new.updated  ,
      old.upd_by   ,
      new.upd_by  ,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Time.del_rec;
delimiter |
create trigger Time.del_rec
        before delete
        on Time.rec
 for each row begin
    insert into Time.rec_history (
      recid       ,
      old_title     ,
      new_title     ,
      old_cname     ,
      new_cname     ,
      old_uname     ,
      new_uname     ,
      old_summary     ,
      new_summary     ,
      old_status     ,
      new_status     ,
      old_updated  ,
      new_updated  ,
      old_upd_by   ,
      new_upd_by  ,
      action)
    values (
      old.id       ,
      old.title     ,
      ''     ,
      old.cname     ,
      ''     ,
      old.uname     ,
      ''     ,
      old.summary     ,
      ''     ,
      old.status     ,
      ''     ,
      old.updated  ,
      ''  ,
      old.upd_by   ,
      ''  ,
      'D');
  end;
|
delimiter ;
show warnings;

--
-- Time.body
--
select 'Create triggers for Time.body';
drop trigger if exists Time.upd_body;
delimiter |
create trigger Time.upd_body
        before update
        on Time.body
 for each row begin
    insert into Time.body_history (
      bodyid       ,
      recid       ,
      old_day     ,
      new_day     ,
      old_minutes   ,
      new_minutes   ,
      old_descr     ,
      new_descr     ,
      old_updated   ,
      new_updated   ,
      old_upd_by    ,
      new_upd_by    ,
      action)
    values (
      old.id       ,
      old.recid       ,
      old.day     ,
      new.day     ,
      old.minutes   ,
      new.minutes   ,
      old.descr     ,
      new.descr,
      old.updated     ,
      new.updated,
      old.upd_by     ,
      new.upd_by,
      'U');
  end;
|
delimiter ;
show warnings;

drop trigger if exists Time.del_body;
delimiter |
create trigger Time.del_body
        before delete
        on Time.body
 for each row begin
    insert into Time.body_history (
      bodyid       ,
      recid       ,
      old_day     ,
      new_day     ,
      old_minutes   ,
      new_minutes   ,
      old_descr     ,
      new_descr     ,
      old_updated   ,
      new_updated   ,
      old_upd_by    ,
      new_upd_by    ,
      action)
    values (
      old.id       ,
      old.recid       ,
      old.day     ,
      ''     ,
      old.minutes   ,
      ''   ,
      old.descr     ,
      '',
      old.updated     ,
      '',
      old.upd_by     ,
      '',
      'D');
  end;
|
delimiter ;
show warnings;


