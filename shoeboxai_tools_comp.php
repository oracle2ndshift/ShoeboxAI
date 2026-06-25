<?php
// filename  shoeboxai_tools_comp.php
// purpose   collect php functions here 
// usage     include "shoeboxai_tools_comp.php";
// history
//	swhite	25-may-14	created
//
// functions
//
//
// function mk_comp_array()
//   bool:  0=update array only, 1=insert, 2=delete array element
//
function mk_comp_array($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$acct,$notes,$bool) {
  global $version,$comp_name,$comp_addr1,$comp_addr2,$comp_addr3,$comp_phone1,$comp_phone2,$comp_fax,$comp_notes,$comp_ctype,$comp_active,$comp_acct,$php_self,$pdo;

  if ($bool == 1) {
    if (array_key_exists($id,$comp_name)) {
      $sql = "update ShoeboxAI.companies set active=1 where id=".$id;
    } else {
      $sql  = "insert into ShoeboxAI.companies ";
      $sql .= "(name,addr1,addr2,addr3,phone1,phone2,fax,ctype,acct,notes) ";
      $sql .= " values (";
      $sql .= "'".$name."',";
      $sql .= "'".$addr1."',";
      $sql .= "'".$addr2."',";
      $sql .= "'".$addr3."',";
      $sql .= "'".$phone1."',";
      $sql .= "'".$phone2."',";
      $sql .= "'".$fax."',";
      $sql .= "'".$ctype."',";
      $sql .= "'".$acct."',";
      $sql .= "'".$notes."')";
    }
    runsql ($sql);
  }
  if ($id == 0) { $id2 = getlastid($sql); } else {$id2 = $id; }
    
  $comp_name[$id2]   = $name;
  $comp_addr1[$id2]  = $addr1;
  $comp_addr2[$id2]  = $addr2;
  $comp_addr3[$id2]  = $addr3;
  $comp_phone1[$id2] = $phone1;
  $comp_phone2[$id2] = $phone2;
  $comp_fax[$id2]    = $fax;
  $comp_notes[$id2]  = $notes;
  $comp_ctype[$id2]  = $ctype;
  $comp_acct[$id2]   = $acct;
  $comp_active[$id2] = 1;
}
//
// function get_companies()
//
function get_companies() {
  global $version,$comp_id,$comp_name,$comp_addr1,$comp_addr2,$comp_addr3,$comp_phone1,$comp_phone2,$comp_fax,$comp_notes,$comp_ctype,$comp_active,$ctype_vendor,$ctype_customer,$php_self,$pdo;

  $sql  = "select id,name,addr1,addr2,addr3,phone1,phone2,fax,notes,ctype,active,acct";
  $sql .= " from ShoeboxAI.companies where active=1 order by name";

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
    while($row = mysql_fetch_array($result)) {
      $id      = $row['id'];
      $name    = $row['name'];
      $addr1   = $row['addr1'];
      $addr2   = $row['addr2'];
      $addr3   = $row['addr3'];
      $phone1  = $row['phone1'];
      $phone2  = $row['phone2'];
      $fax     = $row['fax'];
      $notes   = $row['notes'];;
      $ctype   = $row['ctype'];
      $active  = $row['active'];
      $acct    = $row['acct'];
      mk_comp_array($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$acct,$notes,0); 
    }
  }
  else {
    foreach ($pdo->query($sql) as $row) {
      $id      = $row['id'];
      $name    = $row['name'];
      $addr1   = $row['addr1'];
      $addr2   = $row['addr2'];
      $addr3   = $row['addr3'];
      $phone1  = $row['phone1'];
      $phone2  = $row['phone2'];
      $fax     = $row['fax'];
      $notes   = $row['notes'];;
      $ctype   = $row['ctype'];
      $active  = $row['active'];
      $acct    = $row['acct'];
      mk_comp_array($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$acct,$notes,0);
    }
  }
}
//
// function get_1_company()
//
function get_1_company($id) {
  global $version,$comp_id,$comp_name,$comp_addr1,$comp_addr2,$comp_addr3,$comp_phone1,$comp_phone2,$comp_fax,$comp_notes,$comp_ctype,$comp_active,$php_self,$pdo;
  $sql  = "select name,addr1,addr2,addr3,phone1,phone2,fax,notes,ctype,acct ";
  $sql .= " from ShoeboxAI.companies where id=".$id;

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
    while($row = mysql_fetch_array($result)) {
      return $row;
    }
  }
  else {
    foreach ($pdo->query($sql) as $row) {
      return $row;
    }
  }
}
//
// function upd_comp()
//
function upd_comp($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$notes) {
  global $version,$pdo;
  $sql  = "update ShoeboxAI.companies set ";
  $sql .= " name='".$name."'" ;
  $sql .= ",addr1='".$addr1."'";
  $sql .= ",addr2='".$addr2."'";
  $sql .= ",addr3='".$addr3."'";
  $sql .= ",phone1='".$phone1."'";
  $sql .= ",phone2='".$phone2."'";
  $sql .= ",fax='".$fax."'";
  $sql .= ",ctype=".$ctype;
  $sql .= " where id=".$id;
 
  runsql($sql);
}
//
// function del_comp()
//
function del_comp($id) {
  global $version,$comp_name,$comp_addr1,$comp_addr2,$comp_addr3,$comp_phone1,$comp_phone2,$comp_fax,$comp_notes,$comp_ctype,$comp_active,$inv_cid,$pdo;
  if (array_key_exists($id,$inv_cid)) {
    $sql = "update ShoeboxAI.companies set active=0 where id=".$id;
    $comp_active[$id] = 0;
  } else {
    $sql = "delete from ShoeboxAI.companies where id=".$id;
    unset($comp_name[$id]);
    unset($comp_addr1[$id]);
    unset($comp_addr2[$id]);
    unset($comp_addr3[$id]);
    unset($comp_phone1[$id]);
    unset($comp_phone2[$id]);
    unset($comp_fax[$id]);
    unset($comp_notes[$id]);
    unset($comp_ctype[$id]);
    unset($comp_active[$id]);
    unset($comp_acct[$id]);
  }
  runsql($sql);
}
//
// function get_comp_list()
// 
function get_comp_list() {
  global $comp_name,$comp_addr1,$comp_addr2,$comp_addr3,$comp_phone1,$comp_phone2,$comp_fax,$comp_notes,$comp_ctype,$comp_active,$php_self,$ctype_vendor;

  $buildpage  = " ";
  $buildpage .= "<tr><td width=150px>Name</td><td width=50px>Type</td><td width=200px>Notes</td><td>Action</td></tr>";
  foreach ($comp_name as $id => $name) {
    if ($comp_active == 0) {
      $pre  = "<font color=blue>";
      $post = "</font>";
    } else {
      $pre  = "";
      $post = "";
    }
    if ($comp_ctype[$id] == $ctype_vendor ) {
      $ctype = 'Vendor';
    }
    else {
      $ctype = 'Cust';
    }
    $buildpage .= "<tr><td>".$pre.$name.$post."</td>";
    $buildpage .= "<td>".$pre.$ctype.$post."</td>";
    $buildpage .= "<td>".$pre.$comp_notes[$id].$post."</td>";
    $buildpage .= "<td>";
    $buildpage .= "<div><form name='EditComp_".$id."' method='post' action='".$php_self."'><table><tr>";
    $buildpage .= "<input type=hidden value='".$id."' name='f_comp_id'>";
    $buildpage .= "<input size=5 type=submit value='Edit' name='f_comp_upd'><br>";
    $buildpage .= "<input size=5 type=submit value='Delete' name='f_comp_del'></td></tr></table></form></div></td></tr>\n";
  }
  $buildpage .= "<tr><td colspan=6 class='acct1'><input title='Add a vendor or customer' type=submit name='f_comp_add' value='Add'></td></tr>\n";
  echo $buildpage;
}
//
// function build_addcomp_page() {   
//
function build_addcomp_page() {
  global $ctype_vendor,$ctype_customer,$php_self;

  $acct_options = mk_acct_options();
 
  $buildpage = "<tr><td class=label>Name</td><td><input  size=30 type=text name='f_comp_name'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Addr1</td><td><input  size=30 type=text name='f_comp_addr1'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Addr2</td><td><input  size=30 type=text name='f_comp_addr2'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Addr3</td><td><input  size=30 type=text name='f_comp_addr3'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Phone</td><td><input  size=30 type=text  name='f_comp_phone1'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Alt Phone</td><td><input  size=30 type=text name='f_comp_phone2'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Fax</td><td><input  size=30 type=text name='f_comp_fax'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Type</td><td><select name='f_comp_ctype'>";
  $buildpage .= "  <option value=".$ctype_vendor.">Vendor</option>";
  $buildpage .= "  <option value=".$ctype_customer.">Customer</option></select></td></tr>\n";
  $buildpage .= "<tr><td class=label>Acct</td><td>";
  $buildpage .= "<select name='f_comp_acct'>".$acct_options."</td></tr>\n";
  $buildpage .= "<tr><td class=label>Notes</td><td><input  size=30 type=text name='f_comp_notes'></td></tr>\n";
  $buildpage .= "<tr><td colspan=2><input type=submit name='f_comp_ins' value='Add'></td></tr>\n";
  echo $buildpage;
}
//
// function build_updcomp_page() {
//
function build_updcomp_page($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$notes,$ctype) {
  global $ctype_vendor,$ctype_customer,$php_self;
  if ($ctype == $ctype_vendor) {
    $cname = 'Vendor';
  } else {
    $cname = 'Customer';
  }
raWikiPay.

  $buildpage  = "<tr><td class=label><input hidden type=text name='f_comp_id' value='".$id."'>\n";
  $buildpage .= "Name</td><td><input  size=30 type=text name='f_comp_name' value='".$name."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Addr1</td><td><input  size=30 type=text name='f_comp_addr1' value='".$addr1."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Addr2</td><td><input  size=30 type=text name='f_comp_addr2' value='".$addr2."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Addr3</td><td><input  size=30 type=text name='f_comp_addr3' value='".$addr3."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Phone</td><td><input  size=30 type=text  name='f_comp_phone1' value='".$phone1."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Alt Phone</td><td><input  size=30 type=text name='f_comp_phone2' value='".$phone2."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Fax</td><td><input  size=30 type=text name='f_comp_fax' value='".$fax."'></td></tr>\n";
  $buildpage .= "<tr><td class=label>Type</td><td><select name='f_comp_ctype'>";
  $buildpage .= "  <option value=".$ctype.">".$cname."</option>";
  $buildpage .= "  <option value=".$ctype_vendor.">Vendor</option>";
  $buildpage .= "  <option value=".$ctype_customer.">Customer</option></select></td></tr>\n";
  $buildpage .= "<tr><td class=label>Notes</td><td><input  size=30 type=text name='f_comp_notes'></td></tr>\n";
  $buildpage .= "<tr><td colspan=2><input type=submit name='f_comp_updsubmit' value='Submit'></td></tr>\n";
  echo $buildpage;
}
?>
