<?php
// filename  shoeboxai_tools_inv.php
// purpose   collect php functions here 
// usage     include "shoeboxai_tools_inv.php";
// history
//	swhite	25-may-14	created
//
//
// function get_open_invoices()
//   
function get_open_invoices($itype,$schedE) {
  global $version,$pdo,$inv_cid,$inv_idate,$inv_itype,$inv_amt,$inv_acct,$inv_balance,$inv_recur,$invtype_ap,$invtype_ar,$php_self,$today;

  $sql  = "select i.id id,i.cid cid,c.name name,i.idate idate,i.itype itype,i.amt amt,i.acct acct,i.balance balance,i.recur recur ";
  $sql .= " from ShoeboxAI.inv i, ShoeboxAI.companies c ";
  $sql .= " where balance!=0 and idate <= curdate() ";
  $sql .= " and c.id=i.cid ";
  $sql .= " and itype=".$itype;
  $sql .= " and entity_id='".$schedE."' ";
  $sql .= " order by cid,idate";

  $buildpage  = "<tr><td>Id</td><td>Company</td><td>Date</td><td>Type</td><td>Amount</td><td>Acct</td><td>Balance</td><td>Recur</td></tr>";
  if ($version == "5.0") {
    $result = run_query($version,$dp,$sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
    while($row = run_fetch($version,$result)) {
      $id      = $row['id'];
      $cid     = $row['cid'];
      $name    = $row['name'];
      $idate   = $row['idate'];
      $itype   = $row['itype'];
      $amt     = $row['amt'];
      $acct    = $row['acct'];
      $balance = $row['balance'];
      $recur   = $row['recur'];
      $buildpage .= "<tr><form name='EditInv_".$id."' method='post' action='".$php_self."'>";
      $buildpage .= "<td><input type=hidden value='".$id."' name='f_inv_id'>".$id."</td>";
      $buildpage .= "<td>".$name."</td>";
      $buildpage .= "<td>".$idate."</td>";
      $buildpage .= "<td>".$itype."</td>";
      $buildpage .= "<td>".$amt."</td>";
      $buildpage .= "<td>".$acct."</td>";
      $buildpage .= "<td>".$balance."</td>";
      $buildpage .= "<td>".recur_label($recur)."</td>";
      $buildpage .= "<td><input size=5 type=submit value='Edit' name='f_inv_upd'></td>\n";
      $buildpage .= "<td><input size=5 type=submit value='Delete' name='f_inv_del'></td></form></tr>\n";
    }
  }
  else {
    foreach ($pdo->query($sql) as $row) {
      $id      = $row['id'];
      $cid     = $row['cid'];
      $name    = $row['name'];
      $idate   = $row['idate'];
      $itype   = $row['itype'];
      $amt     = $row['amt'];
      $acct    = $row['acct'];
      $balance = $row['balance'];
      $recur   = $row['recur'];
      $buildpage .= "<tr><form name='EditInv_".$id."' method='post' action='".$php_self."'>";
      $buildpage .= "<td><input type=hidden value='".$id."' name='f_inv_id'>".$id."</td>";
      $buildpage .= "<td>".$name."</td>";
      $buildpage .= "<td>".$idate."</td>";
      $buildpage .= "<td>".$itype."</td>";
      $buildpage .= "<td>".$amt."</td>";
      $buildpage .= "<td>".$acct."</td>";
      $buildpage .= "<td>".$balance."</td>";
      $buildpage .= "<td>".recur_label($recur)."</td>";
      $buildpage .= "<td><input size=5 type=submit value='Edit' name='f_inv_upd'></td>\n";
      $buildpage .= "<td><input size=5 type=submit value='Delete' name='f_inv_del'></td></form></tr>\n";
    }
  }
  if ($itype == $invtype_ap) {$ar_or_ap = 'f_inv_add_ap';} else {$ar_or_ap = 'f_inv_add_ar';} 
  $buildpage .= "<tr><td colspan=6 class='acct1'><input title='AP Invoices' type=submit name='".$ar_or_ap."' value='Add'></td></tr>\n";
  echo $buildpage;
}
//
// function get_open_invpay()
//   
function get_open_invpay($itype,$schedE) {
  global $version,$pdo,$invtype_ap,$invtype_ar,$php_self,$today,$dp;
  
  $i = 0;

  $sql  = "select i.id id,i.cid,c.name name,date_format(i.idate,'%Y-%m-%d') idate,i.amt amt,i.acct acct,i.balance balance ";
  $sql .= " from ShoeboxAI.inv i, ShoeboxAI.companies c ";
  $sql .= " where balance!=0 and idate < curdate() ";
  $sql .= " and i.cid=c.id ";
  $sql .= " and itype=".$itype;
  $sql .= " and entity_id='".$schedE."' ";
  $sql .= " order by cid,idate";

  if ($itype == $invtype_ap ) {$paytype = "Payment"; $title = "Pay Invoices";} 
  else                        {$paytype = "Receipt"; $title = "Record Receipts";} 

  $buildpage  = "<tr><th colspan=5>".$title."</th><th colspan=2>Login=".$schedE."</th></tr>";
  $buildpage .= "<tr><form name='PayInv' method='post' action='".$php_self."'><input type=hidden name=f_invpay_type value=".$itype.">";
  $buildpage .= "<th>Id</th><th>Company</th><th>Date</th><th>Paid Date</th><th>Type</th><th>Amount</th><th>Balance</th><th>".$paytype."</th></tr>";

  if ($version == "5.0") {
    $result = run_query($version,$dp,$sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
    while($row = run_fetch($version,$result)) {
      $i            = $i + 1;
      $id           = $row['id'];
      $cid          = $row['cid'];
      $name         = $row['name'];
      $idate        = $row['idate'];
      $amt          = $row['amt'];
      $balance      = $row['balance'];

      $buildpage .= "<tr>";
      $buildpage .= "<td><input type=hidden name='f_inv_".$i."' id='f_inv_".$i."' value='".$id."'>".$id."</td>";
      $buildpage .= "<td>".$name."</td>";
      $buildpage .= "<td>".$idate."</td>";
      $buildpage .= "<td><input size=25 type=date name='f_pdate_".$i."' id='f_pdate_".$i."' value='".$today."'></td>";
      $buildpage .= "<td>".$itype."</td>";
      $buildpage .= "<td>".$amt."</td>";
      $buildpage .= "<td>".$balance."</td>";
      $buildpage .= "<td><input type=decimal name='f_payamt_".$i."' id='f_payamt_".$i."' value=".$balance."></td>";
      $buildpage .= "</tr>\n";
    }
  }
  else {
    foreach ($pdo->query($sql) as $row) {
      $i            = $i + 1;
      $id           = $row['id'];
      $cid          = $row['cid'];
      $name         = $row['name'];
      $idate        = $row['idate'];
      $amt          = $row['amt'];
      $balance      = $row['balance'];

      $buildpage .= "<tr>";
      $buildpage .= "<td><input type=hidden name='f_inv_".$i."' id='f_inv_".$i."' value='".$id."'>".$id."</td>";
      $buildpage .= "<td>".$name."</td>";
      $buildpage .= "<td>".$idate."</td>";
      $buildpage .= "<td><input size=25 type=date name='f_pdate_".$i."' id='f_pdate_".$i."' value='".$today."'></td>";
      $buildpage .= "<td>".$itype."</td>";
      $buildpage .= "<td>".$amt."</td>";
      $buildpage .= "<td>".$balance."</td>";
      $buildpage .= "<td><input type=decimal name='f_payamt_".$i."' id='f_payamt_".$i."' value=".$balance."></td>";
      $buildpage .= "</tr>\n";
    }
  }
  $buildpage .= "<tr><td colspan=6 class='acct1'>";
  $buildpage .= "<input type=hidden name='f_invnum' id='f_invnum' value='".$i."'>";
  $buildpage .= "<input type=submit name='f_invpay_submit' value='Submit'></td></tr>\n";
  echo $buildpage;
}
//
// function invpay_submit()
//
function invpay_submit($itype,$invnum) {
  global $version,$pdo,$invtype_ap,$invtype_ar,$php_self,$today;
  
  for ($i = 1; $i <= $invnum; $i++) {
    $id      = $_REQUEST['f_inv_'.$i];
    $amt     = $_REQUEST['f_payamt_'.$i];
    $pdate   = $_REQUEST['f_pdate_'.$i];

    if ($amt != 0) {
      $sql  = "update ShoeboxAI.inv set balance=balance-".$amt." where id=".$id;
      runsql($sql);
      $sql  = "insert into ShoeboxAI.pay (iid,pdate,ptype,amt) values (";
      $sql .= $id.",'".$pdate."',".$itype.",".$amt.")";
      runsql($sql);
    }
  }
}

//
// function mk_id_options 
//     make comp id option list
//
function mk_id_options($invtype) {
  global $comp_name,$comp_ctype,$comp_acct;
  $options = "";
  foreach ($comp_name as $id => $name) {
    if ($invtype == $comp_ctype[$id]) {
      $options .= "<option value='".$id."'>".$name." (".$comp_acct[$id].")</option>\n";
    }
  }
  return $options;
}
//
// function mk_acct_options
//     make acct option list
//
function mk_acct_options() {
  global $acct_name,$acct_type,$acct_pname,$acct_lvl;
  $options = "<option value='0'>--None--</option>\n";
  foreach ($acct_name as $id => $name) {
    $name = str_pad($name,strlen($name)+($acct_lvl[$id]-1)*2," ",STR_PAD_LEFT);
    // if ($acct_pname != "") { $pname = " - ".$acct_pname.")";}
    // else                   { $pname = ")"; } 
    $options .= "<option value='".$id."'>".$name." (".$id.")</option>\n";
  }
  return $options;
}
//
// function build_addinv_page()
//
function build_addinv_page($invtype) {
  global $invtype_ap,$invtype_ar,$php_self,$schedE;
  $buildpage = "<tr><td class=label>Company</td><td><select name='f_inv_cid' id='f_inv_cid' onChange='GetAcct();'>";
  $buildpage .= mk_id_options($invtype);
  $buildpage .= "</td></tr>\n";
  $buildpage .= "<tr><td class=label>Date</td><td><input  size=30 type=date name='f_inv_idate'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Amt</td><td><input  size=30 type=decimal name='f_inv_amt'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Acct</td><td><select name='f_inv_acct' id='f_inv_acct'>";
  $buildpage .= mk_acct_options();
  $buildpage .= "</td></tr>\n";
  if ($invtype == $invtype_ar) {$itype = 'AR';} else {$itype = 'AP';}
  $buildpage .= "<tr><td class=label>Type</td><td><input type=hidden name=f_inv_itype id=f_inv_itype value=$invtype>".$itype."</td></tr>\n";
  $buildpage .= "<tr><td class=label>Recur</td><td><select name='f_inv_recur'>".recur_options(0)."</select></td></tr>\n";
  $buildpage .= "<tr><td class=label>Entity</td><td><input readonly type=text name='f_inv_entity_id' value='".$schedE."'></td></tr>\n";
  $buildpage .= "<tr><td colspan=2><input type=submit name='f_inv_ins' value='Insert'></td></tr>\n";
  echo $buildpage;
}

//
// function build_updinv_page() {
//
function build_updinv_page($id) {
  global $invtype_ap,$invtype_ar,$php_self,$pdo,$version,$dp;
 
  $sql  = "select id,cid,idate,itype,amt,acct,balance,recur ";
  $sql .= " from ShoeboxAI.inv i ";
  $sql .= " where id=".$id;

  $result = run_query($version,$dp,$sql);
  if (!$result) { die("Failed query:  sql=$sql"); }
  $row = run_fetch($version,$result);
  $id      = $row['id'];
  $cid     = $row['cid'];
  $idate   = $row['idate'];
  $itype   = $row['itype'];
  if ($itype == 1) {$invtype=$invtype_ar;} else {$invtype=$invtype_ap;}
  $amt     = $row['amt'];
  $acct    = $row['acct'];
  $balance = $row['balance'];
  $recur   = $row['recur'];

  $buildpage  = "<form name='UpdInv' method='post' action='".$php_self."'>";
  $buildpage  = "<tr><td class=label>Id</td><td><input size=8 readonly type=text name='f_inv_id' value='".$id."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Comp Id</td><td><select name='f_inv_cid' id='f_inv_cid'><option value='".$cid."'>".$cid."</option>";
  $buildpage .= mk_id_options($invtype);
  $buildpage .= "</td></tr>\n";
  $buildpage .= "<tr><td class=label>Date</td><td><input  size=30 type=date name='f_inv_idate' value='".$idate."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Amt</td><td><input  size=30 type=decimal name='f_inv_amt' value='".$amt."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Acct</td><td><select name='f_inv_acct'><option value ='".$acct."'></option>";
  $buildpage .= mk_acct_options();
  $buildpage .= "</td></tr>\n";
  $buildpage .= "<tr><td class=label>Balance</td><td><input  size=30 type=decimal name='f_inv_balance' value='".$balance."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Type</td><td><select name='f_inv_itype'>";
  $buildpage .= "  <option value='".$itype."'>".$itype."</option>";
  $buildpage .= "  <option value='".$invtype_ap."'>AP</option>";
  $buildpage .= "  <option value='".$invtype_ar."'>AR</option>";
  $buildpage .= "</select></td></tr>\n";
  $buildpage .= "<tr><td class=label>Recur</td><td><select name='f_inv_recur'>".recur_options($recur)."</select></td></tr>\n";
  $buildpage .= "<tr><td colspan=2><input type=submit name='f_inv_updsubmit' value='Submit'></td></tr></form>\n";
  echo $buildpage;
}

//
// function upd_inv()
//
function updinv($id,$entity_id,$cid,$idate,$itype,$amt,$acct,$balance,$recur) {
  $sql  = "update ShoeboxAI.inv set ";
  $sql .= " cid='".$cid."',";
  $sql .= " entity_id='".$entity_id."',";
  $sql .= " idate='".$idate."',";
  $sql .= " itype=".$itype.",";
  $sql .= " amt=".$amt.",";
  $sql .= " acct='".$acct."',";
  $sql .= " balance=".$balance.",";
  $sql .= " recur=".intval($recur)." where id='".$id."'";
  runsql($sql);
}

//
// function ins_inv()
//
function insinv($entity_id,$cid,$idate,$itype,$amt,$acct,$recur) {
  global $pdo,$version,$php_self;
  $recur = intval($recur);

  $sql  = "insert into ShoeboxAI.inv ";
  $sql .= "(cid,entity_id,idate,itype,amt,acct,balance,recur) values (";
  $sql .= $cid.",";
  $sql .= "'".$entity_id."',";
  $sql .= "'".$idate."',";
  $sql .= $itype.",";
  $sql .= $amt.",";
  $sql .= $acct.",";
  $sql .= $amt.",";
  $sql .= $recur.")";
  runsql($sql);

  $sql  = "update ShoeboxAI.companies set acct=".$acct." where id=".$cid;
  runsql($sql);

  //
  // recurring invoices: generate child rows through end of the parent's year.
  // recur values: 1=monthly, 2=weekly, 4=daily (matches $invrecur_*).
  //
  if ($recur != 0) {
    $interval = recur_interval($recur);
    if ($interval !== null) {
      $start = new DateTime($idate);
      $year  = $start->format('Y');
      $next  = clone $start;
      $next->add($interval);
      while ($next->format('Y') == $year) {
        $next_idate = $next->format('Y-m-d');
        $sql  = "insert into ShoeboxAI.inv ";
        $sql .= "(cid,entity_id,idate,itype,amt,acct,balance,recur) values (";
        $sql .= $cid.",";
        $sql .= "'".$entity_id."',";
        $sql .= "'".$next_idate."',";
        $sql .= $itype.",";
        $sql .= $amt.",";
        $sql .= $acct.",";
        $sql .= $amt.",";
        $sql .= $recur.")";
        runsql($sql);
        $next->add($interval);
      }
    }
  }
}

//
// function del_inv() {
//
function delinv($id) {
  runsql("delete from ShoeboxAI.inv where id='".$id."'");
}

//
// Recur helpers — values mirror $invrecur_mon (1), $invrecur_week (2), $invrecur_daily (4).
//
function recur_label($recur) {
  switch (intval($recur)) {
    case 1: return 'Monthly';
    case 2: return 'Weekly';
    case 4: return 'Daily';
    default: return 'None';
  }
}

function recur_options($selected) {
  $selected = intval($selected);
  $out = '';
  foreach (array(0 => 'None', 1 => 'Monthly', 2 => 'Weekly', 4 => 'Daily') as $v => $label) {
    $sel = ($v === $selected) ? ' selected' : '';
    $out .= "<option value='".$v."'".$sel.">".$label."</option>";
  }
  return $out;
}

function recur_interval($recur) {
  switch (intval($recur)) {
    case 1: return new DateInterval('P1M');
    case 2: return new DateInterval('P1W');
    case 4: return new DateInterval('P1D');
    default: return null;
  }
}

?>
