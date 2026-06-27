<?php
// filename  shoeboxai_tools_rpt.php
// purpose   collect php functions here
// usage     include "shoeboxai_tools_rpt.php";
// history
//      swhite  25-may-14       created
//
//
// function get_rpt_page()
//
function get_rpt_page($schedE) {
  global $pdo,$version,$php_self,$ctype_vendor,$ctype_customer,$comp_name,$comp_ctype,$report_list,$dp;

  $bdate = date("Y-1-1");
  $edate = date("Y-12-31");
  $vendors   = "<option value='All'>All</option><option value='None'>None</option><br>";
  $customers = "<option value='All'>All</option><option value='None'>None</option><br>";
  //
  // option arrays
  //
  foreach ($comp_name as $id => $name) {
    $shortname = substr($name,0,10);
    if ($comp_ctype[$id] == $ctype_vendor ) {
      $vendors .= "<option value='".$id."'>".$id." - ".$name."</option>";
    }
    else {
      $customers .= "<option value='".$id."'>".$id." - ".$name."</option>";
    }
  }
  $vendors  .= "</select>";
  $customers .= "</select>";

  $reports  = "<option value='None'>None</option><br>";
  foreach ($report_list as $id => $rname) {
    $reports .= "<option value='".$id."'>".$rname."</option>";
  }
  //
  // build page
  // 
  $buildpage  = "<tr><th>Reports</th><th>Login=".$schedE."</th></tr>\n";
  // 
  $buildpage .= "<tr><td></td><td></td>";
  $buildpage .= "<tr><td>Date range</td><td><input id=f_rpt_bdate name=f_rpt_bdate type=date size=10 value=".$bdate.">";
  $buildpage .= "    <input id=f_rpt_edate name=f_rpt_edate type=date size=10 value=".$edate."><br></td></tr>";
  $buildpage .= "<tr><td>By Vendor</td><td><select id=v_rpt_v name=v_rpt_v>".$vendors."<br></td></tr>";
  $buildpage .= "<tr><td>By Customer</td><td><select id=v_rpt_c name=v_rpt_c>".$customers."<br></td></tr>";
  $buildpage .= "<tr><td>Report Type</td><td><select id=v_rpt_type name=v_rpt_type>".$reports."<br></td></tr>";
  $buildpage .= "<tr><td></td><td><input type=submit name='v_rpt_peak' id='v_rpt_peak' value='Preview'>";
  $buildpage .= "<input type=submit name='v_rpt_exec' id='v_rpt_exec' value='Print'></td></tr>";

  echo $buildpage;
}
//
// function rpt_clear()
//
function rpt_clear() {
  global $rptcols,$rptlineitems,$rptcollen;
  foreach ($rptcols as $i => $value) { unset($rptcols[$i]); }
  foreach ($rptlineitems as $i => $value) { unset($rptlineitems[$i]); }
  foreach ($rptcollen as $i => $value) { unset($rptcollen[$i]); }
  foreach ($rptcollen as $i => $value) { unset($rptcollen[$i]); }
}
//
// function rpt_header()
//
function rpt_header($rtype,$bdate,$edate) {
  global $rpttitle,$rptcols,$rptcollen,$rptpreview,$rptfp,$rptpage,$today,$schedE;

  if ($rtype != 1) {
    $elem = array();

    if ($rptpreview == 'Y') {
      $rptpage  = "<tr><th colspan=".count($rptcols).">".$rpttitle."</th></tr>";
      $rptpage .= "<tr><th colspan=".count($rptcols).">".$today."</th></tr>";
      $rptpage .= "<tr>";
      foreach ($rptcols as $elem) {
        $rptpage .= "<th>".$elem."</th>";
      }
      $rptpage .= "</tr>";
      return $rptpage;
    }
    else {
      fprintf($rptfp,'%40s%s%c',' ',$rpttitle,10);
      fprintf($rptfp,'%40s%s%c',' ',$today,10);
  
      $format   = "";
      $elems    = "";
      $first    = 1;
      foreach ($rptcols as $id => $val) {
        $format    .= "%".$rptcollen[$id]."s";
        $elems[$id] = $val;
      }
      $elems[$id+1] = 10;
      $format .= "%c";
      $str = vsprintf($format,$elems);
      fprintf($rptfp,$str);
    }
  } else {
    $header1 = "Profit and Loss";
    $header2 = "Date: ".$bdate." through ".$edate;
    if ($rptpreview == 'Y') {
      $rptpage  = "<tr colspan=6><th align=center style='font-family:bitstream-galaxy;font-style:bold;font-size:24px;'>".$header1."</th></tr>";
      $rptpage .= "<tr colspan=6><th align=center style='font-family:bitstream-galaxy;font-style:bold;font-size:24px;'>".$schedE."</th></tr>";
      $rptpage .= "<tr colspan=6><th align=center style='font-family:bitstream-galaxy;font-style:bold;font-size:24px;'>".$header2."</th></tr>";
      $rptpage .= "<tr colspan=6><th></th></tr>";
    } else {
      fprintf($rptfp,'%40s%s%c',' ',$header1,10);
      fprintf($rptfp,'%40s%s%c',' ',$schedE,10);
      fprintf($rptfp,'%40s%s%c%c',' ',$header2,10,10);
    }
  }
}
//
// function rpt_line($rpt)
//
function rpt_line() {
  global $rptpreview,$rptlineitems,$rptfp,$rptpage,$rptcollen;

  $elems = array();

  if ($rptpreview == 'Y') {
    $rptpage .= "<tr>";
    foreach ($rptlineitems as $id => $elem) {
      $rptpage .= "<td>".$elem."</td>";
    }
    $rptpage .= "</tr>";
  }
  else {
    $format = "";
    $elems  = "";
    $first  = 1;
    foreach ($rptlineitems as $id => $val) {
      $format    .= "%".$rptcollen[$id]."s";
      $elems[$id] = $val;
    }
    $elems[$id+1] = 10;
    $format .= "%c";
    $str = vsprintf($format,$elems);
    fprintf($rptfp,$str);
  }
}
//
// function rpt_line_pandl($acct,$type,$name,$level,$amt)
//
function rpt_line_pandl($acct,$type,$name,$level,$amt) {
  global $rptpreview,$rptfp,$rptpage,$rpt_100,$rpt_200,$rpt_300,$rpt_rev,$rpt_net,$rpt_bold,$rpt_reset;

  $sql = "";

  if ($acct == '100') {$rpt_100 = $amt;}
  if ($acct == '200') {$rpt_200 = $amt;}
  if ($acct == '300') {
    $rpt_300 = $amt;
    $rpt_rev = $rpt_100-$rpt_200;
    $rpt_net = $rpt_rev-$rpt_300;
  }
  if ($acct == 200) {
    $str .= sprintf("%-5s%-24s%-12s%-12s%-18s%s%c"," "," ",                     " "," "," ","---------------",10);
    $str .= sprintf("%-5s%-24s%-12s%-12s%-18s%12.2f%c"," ","Total Income",      " "," "," ",$rpt_100,         10);
    if ($rptpreview == 'Y') {
      $rptpage .= "<tr>";
      $rptpage .= "<td height=22px style='font-family:bitstream-galaxy;font-style:bold;font-size:26px;' ><pre>".$str."</pre></td>";
      $rptpage .= "</tr>";
    }
    else { fprintf($rptfp,$str); }
    $str = "";
  }
  // subtotal
  if ($acct == 300) {
    $str .= sprintf("%-5s%-24s%-12s%-12s%-18s%s%c"," "," ",                     " "," "," ","---------------",10);
    $str .= sprintf("%-5s%-24s%-12s%-12s%-18s%12.2f%c"," ","Total Cost of Goods Sold"," "," "," ",$rpt_200,   10);
    $str .= sprintf("%-5s%-24s%-12s%-12s%-18s%s%c"," "," ",                     " "," "," ","---------------",10);
    $str .= sprintf("%-5s%-24s%-12s%-12s%-18s%12.2f%c"," ","Gross Profit",      " "," "," ",$rpt_rev,         10);
    if ($rptpreview == 'Y') {
      $rptpage .= "<tr>";
      $rptpage .= "<td height=22px style='font-family:bitstream-galaxy;font-style:bold;font-size:26px;' ><pre>".$str."</pre></td>";
      $rptpage .= "</tr>";
    }
    else { fprintf($rptfp,$str); }
    $str = "";
  }
  
  // the line
  if ($level == 1) {
    $str .= sprintf("%-5s%-24s%-12s%-12s%-12s%s%c",$acct,$name," "," "," "," ",10); }
  elseif ($level == 2) {
    $str .= sprintf("%-5s%-24s%12.2f%-12s%-12s%-12s%c",$acct,$name,$amt," "," "," ",10); }
  elseif ($level == 3) {
    $str .= sprintf("%-5s%-24s%-12s%12.2f%-12s%-12s%c",$acct,"   ".$name," ",$amt," "," ",10); }
  else {
    $str .= sprintf("%-5s%-24s%-12s%-12s%12.2f%-12s%c",$acct,"     ".$name," "," ",$amt," ",10); }
  // the ending total

  if ($rptpreview == 'Y') {
    $rptpage .= "<tr>";
    $rptpage .= "<td height=15px style='font-family:bitstream-galaxy;font-size:20px;'><pre>".$str."</pre></td>";
    $rptpage .= "</tr>";
  }
  else { fprintf($rptfp,$str); }

  if ($acct >= 900) {
    $str  = sprintf("%c%-5s%-24s%-12s%-12s%-18s%s%c",10," "," ",                    " "," "," ","---------------",10);
    $str .= sprintf("%c%-5s%-24s%-12s%-12s%-18s%12.2f%c",10," ","Total Expenses",   " "," "," ",$rpt_300,         10);
    $str .= sprintf("%c%-5s%-24s%-12s%-12s%-18s%s%c",10," "," ",                    " "," "," ","---------------",10);
    $str .= sprintf("%c",10); 
    $str .= sprintf("%c%-5s%-24s%-12s%-12s%-18s%12.2f%c",10," ","Net Income",       " "," "," ",$rpt_net,         10);
    $str .= sprintf("%c%-5s%-24s%-12s%-12s%-18s%s%c",10," "," ",                    " "," "," ","===============",10);
    if ($rptpreview == 'Y') {
      $rptpage .= "<tr>";
      $rptpage .= "<td height=22px style='font-family:bitstream-galaxy;font-style:bold;font-size:26px;' ><pre>".$str."</pre></td>";
      $rptpage .= "</tr>";
    }
    else { fprintf($rptfp,$str); }
  }
}

//
// function rpt_print()
//
function rpt_print() {
  global $rptfile,$rptfp,$rptpage;

  fclose($rptfp);
  $cmd = "cp ".$rptfile." /tmp/newfile.txt";
  exec($cmd);
  $cmd = "lpr '".$rptfile."'";
  shell_exec($cmd);
  echo "Printed file ".$rptfile.".  For manual 'lpr /tmp/newfile.txt'\n";
  $rptfp = fopen($rptfile,'w');
}
//
// function rpt_exec($schedE,$bdate,$edate,$vendor,$customer,$rtype,$exec_preview)
//
function rpt_exec($schedE,$bdate,$edate,$vendor,$customer,$rtype,$exec_preview) {
  global $pdo,$version,$php_self,$ctype_vendor,$ctype_customer,$comp_name,$comp_ctype,$report_list,$rpttitle,$rptcols,$rptcollen,$rptpreview,$rptfp,$rptpage,$rptlineitems,$dp;

  //
  // initialize formatting arrays
  //
  rpt_clear();

  // exec_preview = exec or preview 
  if ($exec_preview == 'preview') 
       { $rptpreview = 'Y'; }
  else { $rptpreview = 'N'; }

  // vendor and customer lists
  $ctype = "";
  $where   = "";
  if ($rtype == 2 || $rtype == 3) {
    // customer list or vendor list

    // customer
    if ($rtype == 2) {
      $rpttitle = "Customer List";
      $where .= " and ctype=".$ctype_customer." ";
      if ($customer != 'All') {
        $where .= " and id='".$customer."' ";
      }
    }
    // vendor
    if ($rtype == 3) {
      $rpttitle = "Vendor List";
      $where .= " and ctype=".$ctype_vendor." ";
      if ($vendor != 'All') {
        $where .= " and id='".$vendor."' ";
      }
    } 
    
    // formatting 
    $rptcols[1]   = ('Id');      $rptcollen[1] = ('5');
    $rptcols[2]   = ('Name');    $rptcollen[2] = ('30');
    $rptcols[3]   = ('Type');    $rptcollen[3] = ('7');
    $rptcols[4]   = ('Active');  $rptcollen[4] = ('5');
    $rptcols[5]   = ('Notes');   $rptcollen[5] = ('30');

    // write the header
    rpt_header($rtype,$bdate,$edate) ;

    // build the sql
    $sql = " select id,name,ctype,active,notes ";
    $sql .= "from ShoeboxAI.companies where 1=1 ".$where." order by 2";

    if ($version == "5.0") {
      $result = run_query($version,$dp,$sql);
      if (!$result) { die("Failed query:  sql=$sql"); }
      while($row = run_fetch($version,$result)) {
        $rptlineitems[1] = $row['id'];
        $rptlineitems[2] = $row['name'];
        $rptlineitems[3] = $row['ctype'];
        $rptlineitems[4] = $row['active'];
        $rptlineitems[5] = $row['notes'];
        rpt_line();        
      }
    }
    else {
      foreach ($pdo->query($sql) as $row) {
        $rptlineitems[1] = $row['id'];
        $rptlineitems[2] = $row['name'];
        $rptlineitems[3] = $row['ctype'];
        $rptlineitems[4] = $row['active'];
        $rptlineitems[5] = $row['notes'];
        rpt_line();        
      }
    }
    if ($rptpreview == 'Y') {
      echo $rptpage;
    }
    else {
      rpt_print();
    }
  } elseif  ($rtype == 4) {  
    // transaction list
    $where   = " i.entity_id='".$schedE."' ";
    if ($bdate != '') {$where .= " and i.idate>='".$bdate."' "; }
    if ($edate != '') {$where .= " and i.idate<='".$edate."' "; }

    if ($customer != 'All' && $customer != 'None') {
      $where .= " and (i.cid='".$customer."' or cid='".$vendor."' ) ";
    } elseif ($customer == 'None') {
      $where .= " and i.itype!=".$ctype_customer." ";
    }
    if ($vendor != 'All' && $vendor != 'None') {
      $where .= " and (i.cid='".$vendor."' or cid='".$customer."' ) ";
    } elseif ($vendor == 'None') {
      $where .= " and i.itype!=".$ctype_vendor." ";
    }
    $rpttitle  = "Transaction List: login=".$schedE." vendor=".$vendor." customer=".$customer." between dates=".$bdate." and ".$edate;
    
    // formatting 
    $rptcols[1]   = ('Id');      $rptcollen[1] = ('8');
    $rptcols[2]   = ('Comp');    $rptcollen[2] = ('35');
    $rptcols[3]   = ('Date');    $rptcollen[3] = ('11');
    $rptcols[4]   = ('Amt');     $rptcollen[4] = ('25');
    $rptcols[5]   = ('PayDate'); $rptcollen[5] = ('11');
    $rptcols[6]   = ('Paid');    $rptcollen[6] = ('25');
    $rptcols[7]   = ('Acct');    $rptcollen[7] = ('10');
    $rptcols[8]   = ('Balance'); $rptcollen[8] = ('25');

    // write the header
    rpt_header($rtype,$bdate,$edate) ;

    // build the sql
    $sql  = "select i.id id,";
    $sql .= "c.name  cid,";
    $sql .= "date_format(i.idate,'%Y-%m-%d')     idate,";
    $sql .= "i.amt       amt,";
    $sql .= "date_format(p.pdate,'%Y-%m-%d')     pdate,";
    $sql .= "p.amt       pamt,";
    $sql .= "i.acct      acct,";
    $sql .= "i.balance   balance ";
    $sql .= "from ShoeboxAI.inv i ";
    $sql .= " left join ShoeboxAI.companies c on i.cid=c.id ";
    $sql .= " left join ShoeboxAI.pay p on i.id=p.iid ";
    $sql .= " where ".$where." order by 2,3";

    if ($version == "5.0") {
      $result = run_query($version,$dp,$sql);
      if (!$result) { die("Failed query:  sql=$sql"); }
      while($row = run_fetch($version,$result)) {
        $rptlineitems[1] = $row['id'];
        $rptlineitems[2] = $row['cid'];
        $rptlineitems[3] = $row['idate'];
        $rptlineitems[4] = $row['amt'];
        $rptlineitems[5] = $row['pdate'];
        $rptlineitems[6] = $row['pamt'];
        $rptlineitems[7] = $row['acct'];
        $rptlineitems[8] = $row['balance'];
        rpt_line();        
      }
    }
    else {
      foreach ($pdo->query($sql) as $row) {
        $rptlineitems[1] = $row['id'];
        $rptlineitems[2] = $row['cid'];
        $rptlineitems[3] = $row['idate'];
        $rptlineitems[4] = $row['type'];
        $rptlineitems[5] = $row['amt'];
        $rptlineitems[6] = $row['acct'];
        $rptlineitems[7] = $row['balance'];
        rpt_line();        
      }
    }
    if ($rptpreview == 'Y') {
      echo $rptpage;
    }
    else {
      rpt_print();
    }

  } elseif ($rtype == 1) {
    // profit and loss
    //  this report has special formatting, and is not a list-style like the others
    //
    $where   = " ";
    $str = "";
    if ($bdate != '' and $edate != '') {$where .= " and i.idate between '".$bdate."' and '".$edate."' "; }

    $rpttitle  = "Profit and Loss: Company=".$schedE;
    if ($bdate != '' || $edate != '') {
      $rpttitle .= " Date range=".$bdate." and ".$edate;
    }
    
    // formatting 
    $rptcols[1]   = ('Acct');    $rptcollen[1] = ('5');
    $rptcols[2]   = ('Name');    $rptcollen[2] = ('35');
    $rptcols[3]   = ('Amt');     $rptcollen[3] = ('30');

    // write the header
    rpt_header($rtype,$bdate,$edate) ;

    // build the sql
    //  Sample:
    //    select id,name,ShoeboxAI.acct_get_sum(id,'2015-1-1','2015-12-31','Hoff') 
    //    from ShoeboxAI.acct;
    //
    $sql  = "select a.id acct,";
    $sql .= " a.name      name,";
    $sql .= " a.atype     type,";
    $sql .= " a.level     level,";
    $sql .= " ShoeboxAI.acct_get_sum(a.id,'".$bdate."','".$edate."','".$schedE."') amt ";
    $sql .= " from ShoeboxAI.acct a ";
    $sql .= " order by a.id";

    if ($version == "5.0") {
      $result = run_query($version,$dp,$sql);
      if (!$result) { die("Failed query:  sql=$sql"); }
      while($row = run_fetch($verson,$result)) {
        $acct  = $row['acct'];
        $type  = $row['type'];
        $name  = $row['name'];
        $level = $row['level'];
        $amt   = $row['amt'];
        if ($level < 3 || $amt > 0) {
          rpt_line_pandl($acct,$type,$name,$level,$amt);
        }
      }
    }
    else {
      foreach ($pdo->query($sql) as $row) {
        $acct  = $row['acct'];
        $type  = $row['type'];
        $name  = $row['name'];
        $level = $row['level'];
        $amt   = $row['amt'];
        if ($level < 3 || $amt > 0) {
          rpt_line_pandl($acct,$type,$name,$level,$amt);
        }
      }
    }
    if ($rptpreview == 'Y') {
      echo $rptpage;
    }
    else {
      rpt_print();
    }
  } elseif ($rtype == 5) {
    // open invoices
    rpt_header($rtype,$bdate,$edate);
  }
  
}

?>
