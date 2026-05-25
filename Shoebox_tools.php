<?php
// filename  Shoebox_tools.php
// purpose   collect php functions here 
// usage     include "Shoebox_tools.php";
// history
//	swhite	25-may-14	created
//
// functions
//
// 
// function get_profile()
//
function get_profile() {
  global $pdo,$version;
  $sql  = "select name,website,addr1,addr2,addr3,phone,fax,notes";
  $sql .= " from Shoebox.profile ";

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
    while($row = mysql_fetch_array($result)) {
      $name    = $row['name'];
      $website = $row['website'];
      $addr1   = $row['addr1'];
      $addr2   = $row['addr2'];
      $addr3   = $row['addr3'];
      $phone   = $row['phone'];
      $fax     = $row['fax'];
      $notes   = $row['notes'];
    }
  } else {
    foreach ($pdo->query($sql) as $row) {
      $name    = $row['name'];
      $website = $row['website'];
      $addr1   = $row['addr1'];
      $addr2   = $row['addr2'];
      $addr3   = $row['addr3'];
      $phone   = $row['phone'];
      $fax     = $row['fax'];
      $notes   = $row['notes'];
    }
  }
  $page  = "<tr><td class='label'>Name</td> <td><input type=text value='".$name."'  name='f_profile_name'  id='f_profile_name'></td></tr>\n";
  $page .= "<tr><td class='label'>Addr</td> <td><input type=text value='".$addr1."' name='f_profile_addr1' id='f_profile_addr1'></td></tr>\n";
  $page .= "<tr><td class='label'>    </td> <td><input type=text value='".$addr2."' name='f_profile_addr2' id='f_profile_addr2'></td></tr>\n";
  $page .= "<tr><td class='label'>    </td> <td><input type=text value='".$addr3."' name='f_profile_addr3' id='f_profile_addr3'></td></tr>\n";
  $page .= "<tr><td class='label'>Phone</td><td><input type=text value='".$phone."' name='f_profile_phone' id='f_profile_phone'></td></tr>\n";
  $page .= "<tr><td class='label'>Fax</td>  <td><input type=text value='".$fax."'   name='f_profile_fax'   id='f_profile_fax'></td></tr>\n";
  $page .= "<tr><td class='label'>Notes</td><td><input type=text value='".$notes."' name='f_profile_notes' id='f_profile_notes'></td></tr>\n";
  $page .= "<tr><td class='label'></td><td><input type=submit name='f_profile_submit' id='f_profile_submit' value='Update'></td></tr>\n";
  echo $page;
}
//
// function upd_profile()
//
function upd_profile($name,$website,$addr1,$addr2,$addr3,$phone,$fax,$notes) {
  global $pdo,$version;
  $sql  = "update Shoebox.profile ";
  $sql .= "set name='".$name."'";
  $sql .= ",website='".$website."'";
  $sql .= ",addr1='".$addr1."'";
  $sql .= ",addr2='".$addr2."'";
  $sql .= ",addr3='".$addr3."'";
  $sql .= ",phone='".$phone."'";
  $sql .= ",fax='".$fax."'";
  $sql .= ",notes='".$notes."'";

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
  } else {
      if (!($pdo->exec($sql) > 0)) { echo("Failed query:  sql=$sql"); }
  }
  return 1;
}

//
// function runsql($sql) {
//
function runsql($sql) {
  global $pdo,$version;

  if ($version == "5.0") {
    $result = mysql_query($sql);
    if (!$result) { die("Failed query:  sql=$sql"); }
  }
  else {
    $pdo->query($sql);
  }
  logger($sql);
} 

//
// function getlastid() 
//
function getlastid() {
  global $pdo,$version;
  $id = -1;
  $sql = "select max(id) id from Shoebox.companies";
  if ($version == "5.0") { 
      $result = mysql_query($sql);
      if (!$result) { die("Failed query:  sql=$sql"); } 
      while($row = mysql_fetch_array($result)) { $id = $row['id']; } 
  } else { 
      if (!($pdo->exec($sql) > 0)) { echo("Failed query:  sql=$sql"); } 
      foreach ($pdo->query($sql) as $row) { $id = $row['id']; } 
  }
  return $id;
}

//
// function logger($text) 
//
function logger($text) {
  global $fp,$logfile;
  fwrite($fp,$text."\n");
}
//
// function get_day($thedate)
//
function get_day($thedate) {
  return date("d",strtotime($thedate));
}
//
// function get_month($thedate)
//
function get_month($thedate) {
  return date("d",strtotime($thedate));
}

?>
