<?php
// filename  Shoebox_tools_acct.php
// purpose   collect php functions here 
// usage     include "Shoebox_tools_acct.php";
// history
//	swhite	25-may-14	created
//
// functions
//
//
// function mk_acct_array()
//
function mk_acct_array($id,$name,$atype,$pname,$pid,$level) {
  global $acct_name,$acct_type,$acct_lvl,$acct_pname,$acct_pid,$php_self;
  $acct_name[$id]   = $name;
  $acct_lvl[$id]    = $level;
  $acct_type[$id]   = $atype;
  $acct_pname[$id]  = $pname;
  $acct_pid[$id]    = $pid;
}
//
// function get_accts()
//
function get_accts() {
  global $version,$buildpage,$acct_name,$acct_type,$php_self,$pdo;
  $acct_name = array();
  $acct_type = array();

  $sql  = "select a.id id,a.name name,a.atype atype,a2.name pname,a2.id pid,a.level level";
  $sql .= " from Shoebox.acct a ";
  $sql .= " left join Shoebox.acct a2 on (a.pid=a2.id) ";
  $sql .= " order by 1";

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
    while($row = mysql_fetch_array($result)) {
      $id      = $row['id'];
      $name    = $row['name'];
      $atype   = $row['atype'];
      $pname   = $row['pname'];
      $pid     = $row['pid'];
      $level   = $row['level'];
      mk_acct_array($id,$name,$atype,$pname,$pid,$level);
    }
  } else {
    foreach ($pdo->query($sql) as $row) {
      $id      = $row['id'];
      $name    = $row['name'];
      $atype   = $row['atype'];
      $pname   = $row['pname'];
      $pid     = $row['pid'];
      $level   = $row['level'];
      mk_acct_array($id,$name,$atype,$pname,$pid,$level);
    }
  }
}
//
// function add_acct()
//
function add_acct($id,$name,$atype) {
  global $acct_name,$acct_type,$php_self,$pdo,$version;
  $sql  = "insert into Shoebox.acct (id,name,atype) values ";
  $sql .= "('".$id."','".$name."','".$atype."')";

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
  } else {
    if (!($pdo->exec($sql) > 0)) { echo("Failed query:  sql=$sql"); }
  }
}
//
// function upd_acct()
//
function upd_acct($id,$name,$atype,$pid) {
  global $acct_name,$acct_type,$php_self,$pdo,$version;
  $sql  = "update from Shoebox.acct ";
  $sql .= "name = '".$name."', ";
  $sql .= "atype = '".$atype."', ";
  $sql .= "pid = ".$pid." where id=".$id;

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
  } else {
    if (!($pdo->exec($sql) > 0)) { echo("Failed query:  sql=$sql"); }
  }
}
//
// function del_acct()
// 
function del_acct($id) {
  global $acct_name,$acct_type,$php_self,$pdo,$version;
  $sql  = "delete from Shoebox.acct where id=".$id;
  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
  } else {
    if (!($pdo->exec($sql) > 0)) { echo("Failed query:  sql=$sql"); }
  }
}
//
// function get_accts_page()
// 
function get_accts_page() {
  global $buildpage,$acct_name,$acct_type,$acct_lvl,$php_self;

  $buildpage  = "<tr><td class='acct2'>Id</td><td>Name</td><td class='acct2'>Type</td><td></td></tr>\n";
  
  while (list($id, $name) = each($acct_name)) {
    $name = str_pad($name,strlen($name)+($acct_lvl[$id]-1)*2," ",STR_PAD_LEFT);
    $buildpage .= "<tr><form name='get_accts_page' method='post' action='".$php_self."'>";
    $buildpage .= "<td><input size=5 type=text value='".$id."' name='f_acct_id'></td>";
    $buildpage .= "<td><input  size=30 type=text value='".$name."' name='f_acct_name'></td>";
    $buildpage .= "<td><input  size=5 type=text value='".$acct_type[$id]."' name='f_acct_atype'></td>";
    $buildpage .= "<td><input  size=5 type=text value='".$acct_pid[$id]."' pid='f_acct_pid'></td>";
    $buildpage .= "<td class='acct2'></td><td class='acct1'><input type=submit name='f_acct_updsubmit' value='Upd'>";
    $buildpage .= "<input type=submit name='f_acct_delsubmit' value='Del'></td></form></tr>\n";
  }
  $buildpage   .= "<tr><form name='get_accts_page' method='post' action='".$php_self."'>";
  $buildpage   .= "<td><input size=5 type=text value='' name='f_acct_id'></td>";
  $buildpage   .= "<td><input  size=30 type=text value='' name='f_acct_name'></td>";
  $buildpage   .= "<td><input  size=5 type=text value='' name='f_acct_atype'></td>";
  $buildpage   .= "<td class='acct2'></td><td class='acct1'>";
  $buildpage   .= "<input type=submit name='f_acct_addsubmit' value='Add'></td></form></tr>\n";
  echo $buildpage;
}
//
// function mk_acct_options () {
//
// function mk_acct_options () {
//   global $acct_name,$acct_type;
//   $opts = "";
//   while (list($id,$name) = each($acct_name)) {
//     $opts .= "<option value=".$id.">".$name." (".$acct_type[$id].")</option>\n";
//   }
//   return $opts;
// }
?>
