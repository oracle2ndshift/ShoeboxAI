-- 
-- filename:  constants.sql
-- purpose:   definition for constants for orawiki
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

-- usage:     source constaints.sql
-- history:	
--	swhite	11-feb-2013	created

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
set @apps_tkt        = 16;
set @apps_time       = 32;
set @apps_doc        = 64;
set @apps_calendar   = 128;

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

