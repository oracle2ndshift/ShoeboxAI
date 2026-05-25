-- --------------------------------------------------------------------------
-- filename:   procs.sql
-- purpose:    collect stored procedure definitions for orawiki here.
-- usage:      source procs.sql
-- history:     
--      swhite  26-feb-2013     created

-- --------------------------------------------------------------------------
-- name:       Admin.set_constants
-- purpose:    Set constant values for use by app and other functions, procedures
-- parameters: none
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
-- history:	
--	swhite	11-feb-2013	created
-- --------------------------------------------------------------------------
drop procedure Admin.set_constants;
delimiter /
create procedure Admin.set_constants()
begin

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
set @apps_tkt        = 16;
set @apps_time       = 32;
set @apps_doc        = 64;
set @apps_calendar   = 128;
set @apps_all = @apps+@apps_portal+@apps_admin+@apps_rpt+@apps_tkt+
                @apps_time+@apps_doc+@apps_calendar;

-- integer
set @tkt_type_task   = 1;
set @tkt_type_bug    = 2;
set @tkt_type_enh    = 3;
set @tkt_type_note   = 4;

-- integer - the status values signify work flow
set @tkt_st_new            = 10; 
set @tkt_st_dev_wip        = 20;
set @tkt_st_dev_waiting    = 21;
set @tkt_st_mgr_review     = 40;
set @tkt_st_mgr_research   = 41;
set @tkt_st_qa_wip         = 80;
set @tkt_st_qa_waiting     = 81;
set @tkt_st_fixed          = 90;
set @tkt_st_wont_fix       = 91;
set @tkt_st_worksforme     = 92;

end/
delimiter ;

-- --------------------------------------------------------------------------
-- name:       Admin.show_constants
-- purpose:    Show constant values for use by app and other functions, procedures
-- parameters: none
-- --------------------------------------------------------------------------
drop procedure Admin.show_constants;
delimiter /
create procedure Admin.show_constants()
begin

select 'Bit map status:';
select ' status_new           = ',@status_new;
select ' status_active        = ',@status_active;
select ' status_inactive      = ',@status_inactive;
select ' status_approved      = ',@status_approved;

select 'Bit map role:';
select ' role_guest           = ',@role_guest;
select ' role_root            = ',@role_root ;
select ' role_admin           = ',@role_admin ;
select ' role_emp             = ',@role_emp   ;
select ' role_mgr             = ',@role_mgr   ;
select ' role_user            = ',@role_user  ;

select 'Bit map apps:';
select ' apps                 = ',@apps      ;
select ' apps_portal          = ',@apps_portal; 
select ' apps_admin           = ',@apps_admin  ;
select ' apps_rpt             = ',@apps_rpt    ;
select ' apps_tkt             = ',@apps_tkt    ;
select ' apps_time            = ',@apps_time   ;
select ' apps_doc             = ',@apps_doc    ;
select ' apps_calendar        = ',@apps_calendar; 
select ' apps_all             = ',@apps_all; 

select 'Integer Tkt types:';
select ' tkt_type_task        = ',@tkt_type_task ;
select ' tkt_type_bug         = ',@tkt_type_bug  ;
select ' tkt_type_enh         = ',@tkt_type_enh  ;
select ' tkt_type_note        = ',@tkt_type_note ;

select 'Integer Tkt status:';
select ' tkt_st_new           = ',@tkt_st_new     ;  
select ' tkt_st_dev_wip       = ',@tkt_st_dev_wip  ; 
select ' tkt_st_dev_waiting   = ',@tkt_st_dev_waiting ;
select ' tkt_st_mgr_review    = ',@tkt_st_mgr_review  ;
select ' tkt_st_qa_wip        = ',@tkt_st_mgr_research;
select ' tkt_st_qa_wip        = ',@tkt_st_qa_wip      ;
select ' tkt_st_qa_waiting    = ',@tkt_st_qa_waiting  ;
select ' tkt_st_fixed         = ',@tkt_st_fixed       ;
select ' tkt_st_wont_fix      = ',@tkt_st_wont_fix   ;
select ' tkt_st_worksforme    = ',@tkt_st_worksforme ;
end/
delimiter ;


