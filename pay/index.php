<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--- ---------------------------------------------------------------------
* Copyright \302\251 2010-2011 Susie White
*
* Author       - Susie White
*
* Filename     - index.html
* 
* Purpose      - WikiPay
*
* Flow         - Top level script
*
--------------------------------------------------------------------   --->

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="cache-control" content="no-cache">
  <meta content="text/html;charset=ISO-8859-1" http-equiv="Content-Type">
  <link rel="shortcut icon" href="gif/favicon.ico" type=text/css>
  <link rel="stylesheet" href="../Shoebox.css" type="text/css" />
  <link rel="stylesheet" href="../Shoebox_fonts.css" type="text/css" />
  <title>WikiPay</title>
  <script type="text/javascript" src="../Shoebox.js"></script>
</head>
<body>

<?php if (!isset($load_once) ) {
   include "../Shoebox_env.php";
   include "../Shoebox_db.php";
   include "../Shoebox_tools.php";} ?>

<!-- header -->
<div id="wrapper">
<div id="header">
  <table>
    <tr><td align=left><img src="../gif/icon.jpg"><img src="../gif/logo.jpg"></td>
        <td>Wiki-pay</td></tr>
  </table>
</div>
<div id="nav">
  <table><tr><td align=right>
    <form name="HeaderForm" method="post" action="<?php echo($php_self); ?>">
      <ul id="nav">
        <li title="<?php echo ($form_q_home); ?>">
            <input name="v_home" id="v_home" value="Home" type="submit">
        <li title="<?php echo ($form_q_apinv); ?>">
            <input name="v_apinv" id="v_apinv" value="AP Inv" type="submit">
        <li title="<?php echo ($form_q_appay); ?>">
            <input name="v_appay" id="v_appay" value="AP Pay" type="submit">
        <li title="<?php echo ($form_q_arinv); ?>">
            <input name="v_arinv" id="v_arinv" value="AR Inv" type="submit">
        <li title="<?php echo ($form_q_arpay); ?>">
            <input name="v_arpay" id="v_arpay" value="AR Pay" type="submit">
        <li title="<?php echo ($form_q_comp); ?>">
            <input name="v_comp" id="v_comp" value="Companies" type="submit">
        <li title="<?php echo ($form_q_acct); ?>">
            <input name="v_acct" id="v_acct" value="Accts" type="submit">
      </ul>
    </form>
  </td></tr></table>
</div>
<div style="clear: both;"></div>
</div>

<!---  main div ---------------------------------------------------------  --->
<div id="nav_main_wrap" >

<!---  add div -----------------------------------------------------------  --->
<?php
   include "../Shoebox_db.php";
   if(!empty($_REQUEST['v_apinv'])) : 
?>

  <table><tr><td>AP Invoices</td></tr></table>

<?php 
  elseif(!empty($_REQUEST['v_appay'])) :
?>

  <table><tr><td>AP Pay Invoices</td></tr></table>

<?php
  elseif(!empty($_REQUEST['v_arinv'])) :
?>

  <table><tr><td>AR Invoices</td></tr></table>

<?php
  elseif(!empty($_REQUEST['v_arpay'])) :
?>

  <table><tr><td>AR Receipts</td></tr></table>

<?php
  elseif(!empty($_REQUEST['v_comp'])) :
?>

  <table><tr><td>Companies</td></tr></table>

<?php
  elseif(!empty($_REQUEST['v_acct'])) :
?>

  <table><tr><td>Chart of Accounts</td></tr></table>

<?php
  elseif(!empty($_REQUEST['f_profile_submit'])) :
  $name    = $_REQUEST['f_profile_name'];
  $website = $_REQUEST['f_profile_website'];
  $addr1   = $_REQUEST['f_profile_addr1'];
  $addr2   = $_REQUEST['f_profile_addr2'];
  $addr3   = $_REQUEST['f_profile_addr3'];
  $phone   = $_REQUEST['f_profile_phone'];
  $fax     = $_REQUEST['f_profile_fax'];
  $notes   = $_REQUEST['f_profile_notes'];
  upd_profile($name,$website,$addr1,$addr2,$addr3,$phone,$fax,$notes);
?>

<?php
  else :
?>
  <table width=100% align=center><tr><td><div id="main_wrap"><table cols=2>
      <tr><th colspan=2>Home Page</th></tr>
  <?php $page = get_profile(); echo $page; ?>
  </table></div></td></tr></table>
<?php 
  endif;
?>

</div>  
</body>
</html>

