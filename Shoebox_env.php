<?php
// filename  Shoebox_env.php
// purpose   top leve include file for php variables et al
// usage     include "Shoebox_env.php";
// history
//      swhite  25-may-14       created
//


// 
// local variables 
//
$load_once  = 1;
$self       = "SWhite";
$php_self   = $_SERVER['PHP_SELF'];
date_default_timezone_set('America/Los_Angeles');
$today      = date("Y-m-d");
//
// sql constants
//
$ctype_vendor   = 2;
$ctype_customer = 1;
// AR or AP invoice type
$invtype_ar     = 1;
$invtype_ap     = 2;
// Inv cycle
$invrecur_mon   = 1;
$invrecur_week  = 2;
$invrecur_daily = 4;
//
// globals
//
$buildpage = "";
// company
$comp_name   = array();
$comp_addr1  = array();
$comp_addr2  = array();
$comp_addr3  = array();
$comp_phone1 = array();
$comp_phone2 = array();
$comp_fax    = array();
$comp_notes  = array();
$comp_ctype  = array();
$comp_acct   = array();
$default_acct = array();
// accts
$acct_name   = array();
$acct_type   = array();
$acct_pname  = array();
$acct_pid    = array();
$acct_lvl    = array();
// invoices
$inv_cid     = array();
$inv_idate   = array();
$inv_itype   = array();
$inv_amt     = array();
$inv_acct    = array();
$inv_balance = array();
$inv_auto    = array();
// logging
$logfile     = "logs/Shoebox_".$today.".log";
$fp = fopen($logfile,'a');
// reporting
$report_list = array();
$report_list[1] = "Profit and Loss";
$report_list[2] = "Customer List";
$report_list[3] = "Vendor List";
$report_list[4] = "Transactions List";
$report_list[5] = "Open Invoices";
$rpttitle       = "";
$rptcols        = array();
$rptcollen      = array();
$rptpreview     = "";
$rptlineitems   = array();
$rptfile        = "logs/Shoebox_rpt.tmp";
$rptfp          = fopen($rptfile,'w');
$rptpage        = "";
$rpt_pandl_name = array();
$rpt_pandl_lvl  = array();
$rpt_pandl_type = array();
$rpt_100        = 0;
$rpt_200        = 0;
$rpt_300        = 0;
$rpt_rev        = 0;
$rpt_net        = 0;
$rot_bold       = "\x1B[1;37m";
$rpt_reset      = "\x1B[0;0m";
//
// Schedule entities — loaded from sched.json at runtime.
// Edit that file to change the dropdown without touching code.
//
$schedE_config_file = __DIR__ . "/sched.json";
$schedE_options     = array();
if (is_readable($schedE_config_file)) {
  $schedE_json = file_get_contents($schedE_config_file);
  $decoded     = json_decode($schedE_json, true);
  if (is_array($decoded)) {
    $schedE_options = $decoded;
  } else {
    error_log("sched.json failed to decode: ".json_last_error_msg());
  }
} else {
  error_log("sched.json not readable at ".$schedE_config_file);
}
//
// form hints
//
$form_q_home   = 'Ora-wiki home page';
$form_q_apinv  = 'Ora-wiki A/P Invoices';
$form_q_appay  = 'Ora-wiki A/P Pay Invoices';
$form_q_arinv  = 'Ora-wiki A/R Invoices';
$form_q_arpay  = 'Ora-wiki A/R Receipts';
$form_q_comp   = 'Ora-wiki Companies: Customers and Vendors';
$rptpage        = "";
$form_q_acct   = 'Ora-wiki Chart of Accounts';

$form_q_add    = "Add a To Do record";
$form_q_query  = "Query To Do records";
$form_q_status = "Select a status value or all";
$form_q_subject= "Enter 1 or more chars for beginning of subject string";
$form_q_type   = "Select a record type or all";
$form_q_reset  = "Reset query selections";

$form_q_todo   = 'View, edit, add tickets for bugs, enhancments, tasks, notes';
$form_q_doc    = 'View or add documents here';
$form_q_cal    = 'View or edit calendar events for the group or individual';
$form_q_time   = 'Track your time spent here';
$form_q_admin  = 'View and manage Ora-wiki metadata here';
$form_q_login  = 'Ora-wiki login or logout';

$form_submitupd  = 'Click to update this record.';
$form_submitdel  = 'Click to delete this record.';
$form_submitadd  = 'Click to add a record.';
$form_submitque  = 'Click to query the library.';
$form_uploadfile = 'Enter or browse for gif file upload.';

$form_q_db      = 'Database name';
$form_q_user    = 'Database username';
$form_q_pwd     = 'Database password';

//
// constants
//
// bit map
$status_new      = 1;
$status_active   = 2;
$status_inactive = 4;
$status_approved = 8;

// bit map
$role_guest      = 1;
$role_root       = 2;
$role_admin      = 4;
$role_emp        = 8;
$role_mgr        = 16;
$role_user       = 32;

// bit map
$apps            = 1;
$apps_portal     = 2;
$apps_admin      = 4;
$apps_rpt        = 8;
$apps_todo       = 16;
$apps_time       = 32;
$apps_doc        = 64;
$apps_calendar   = 128;

// integer
$todo_type_task   = 1;
$todo_type_bug    = 2;
$todo_type_enh    = 3;
$todo_type_note   = 4;
$todo_type_msg = array();
$todo_type_msg[$todo_type_task] = "Task";
$todo_type_msg[$todo_type_bug]  = "Bug";
$todo_type_msg[$todo_type_enh]  = "Enhancement";
$todo_type_msg[$todo_type_note] = "Note";

$todo_type_opt = "<option value=0>-All--</option>";
while (list($key, $val) = each($todo_type_msg)) {
  $todo_type_opt = $todo_type_opt."<option value=".$key.">".$val."</option>";
}

// integer - the status values signify work flow
$todo_st_new            = 10;
$todo_st_reopened       = 11;
// $todo_st_mgr_review     = 20;
// $todo_st_mgr_research   = 21;
$todo_st_doc_wip        = 30;
$todo_st_doc_waiting    = 31;
$todo_st_call_wip       = 32;
$todo_st_call_waiting   = 33;
$todo_st_dev_wip        = 44;
$todo_st_dev_waiting    = 41;
$todo_st_res_wip        = 50;
$todo_st_res_waiting    = 51;
$todo_st_qa_wip         = 80;
$todo_st_qa_waiting     = 81;
$todo_st_fixed          = 90;
$todo_st_misc_done      = 91;
$todo_st_wont_fix       = 92;
$todo_st_worksforme     = 93;

$todo_st_msg = array();
$todo_st_msg[$todo_st_new]         = "New";
$todo_st_msg[$todo_st_reopened]    = "Reopened";
// $todo_st_msg[$todo_st_mgr_review  = "Mgr review";
// $todo_st_msg[$todo_st_mgr_research= "Mgr research";
$todo_st_msg[$todo_st_doc_wip]     = "Doc/writing WIP";
$todo_st_msg[$todo_st_doc_waiting] = "Doc/writing waiting";
$todo_st_msg[$todo_st_call_wip]    = "Phone call WIP";
$todo_st_msg[$todo_st_call_waiting]= "Phone call waiting";
$todo_st_msg[$todo_st_dev_wip]     = "Dev WIP";
$todo_st_msg[$todo_st_dev_waiting] = "Dev waiting";
$todo_st_msg[$todo_st_res_wip]     = "Research WIP";
$todo_st_msg[$todo_st_res_waiting] = "Research waiting";
$todo_st_msg[$todo_st_qa_wip]      = "QA WIP";
$todo_st_msg[$todo_st_qa_waiting]  = "QA waiting";
$todo_st_msg[$todo_st_fixed]       = "Fixed";
$todo_st_msg[$todo_st_misc_done]   = "Done";
$todo_st_msg[$todo_st_wont_fix]    = "Wont fix";
$todo_st_msg[$todo_st_worksforme]  = "Works for me";

$todo_st_opt = "<option value=0>-All--</option>";
while (list($key, $val) = each($todo_st_msg)) {
  $todo_st_opt = $todo_st_opt."<option value=".$key.">".$val."</option>";
}

?>
