<?php
// filename  shoeboxai_tools_startup.php
// purpose   collect php functions here 
// usage     include "shoeboxai_tools_startup.php";
// history
//	swhite	25-may-14	created
//
// call once at startup
// 
//
// debug messages
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//print $version;
include "shoeboxai_db.php";
include "shoeboxai_env.php";
include "shoeboxai_tools.php";
include "shoeboxai_tools_acct.php";
include "shoeboxai_tools_comp.php";
include "shoeboxai_tools_inv.php";
include "shoeboxai_tools_rpt.php";

//
// initialize arrays
//
get_companies();
get_accts();

?>
