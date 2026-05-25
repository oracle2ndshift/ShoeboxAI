<?php
// filename  Shoebox_tools_startup.php
// purpose   collect php functions here 
// usage     include "Shoebox_tools_startup.php";
// history
//	swhite	25-may-14	created
//
// call once at startup
// 
//
print $version;
include "Shoebox_db.php";
include "Shoebox_env.php";
include "Shoebox_tools.php";
include "Shoebox_tools_acct.php";
include "Shoebox_tools_comp.php";
include "Shoebox_tools_inv.php";
include "Shoebox_tools_rpt.php";

//
// initialize arrays
//
get_companies();
get_accts();

?>
