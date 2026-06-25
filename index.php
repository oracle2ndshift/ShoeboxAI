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
  <link rel="stylesheet" href="ShoeboxAI.css" type="text/css" />
  <link rel="stylesheet" href="shoeboxai_fonts.css" type="text/css" />
  <link rel="stylesheet" href="print.css" media="print" />
  <title>WikiPay</title>
<script type="text/javascript" src="Shoebox.js"></script>
  <script>
     var pfHeaderImgUrl = '';
     var pfHeaderTagline = '';
     var pfdisableClickToDel = 0;
     var pfHideImages = 0;
     var pfImageDisplayStyle = 'right';
     var pfDisablePDF = 0;
     var pfDisableEmail = 0;
     var pfDisablePrint = 0;
     var pfCustomCSS = '';
     var pfBtVersion='1';
     (function() {
        var js, pf;
        pf = document.createElement('script');
        pf.type = 'text/javascript';
        if('https:' == document.location.protocol){
            js='https://pf-cdn.printfriendly.com/ssl/main.js'
        }else{
          js='http://cdn.printfriendly.com/printfriendly.js'
        }
        pf.src=js;
        document.getElementsByTagName('head')[0].appendChild(pf)
      })();
  </script>
</head>
<body>
<?php 
  if (!isset($load_once) ) { 
    include "shoeboxai_tools_startup.php";
  } 
?>

Here is some text.
<!-- header -->
<div id="wrapper">
  <form name="HeaderForm" method="post" action="<?php echo($php_self); ?>">
<div id="header">
  <table>
    <tr><td align=left><img src="gif/icon.jpg"><img src="gif/logo.jpg"></td>
        <td>Wiki-pay</td></tr>
  </table>
</div>
<div id="nav">
  <table><tr><td align=right>
      <ul id="nav">
        <li title="<?php echo ($form_q_home); ?>">
            <input name="v_home" id="v_home" value="Home" type="submit">
        <li title="<?php echo ($form_q_apinv); ?>">
            <input name="v_apinv" id="v_apinv" value="AP Inv" type="submit">
        <li title="<?php echo ($form_q_apinv); ?>">
            <input name="v_appay" id="v_appay" value="AP Pay" type="submit">
        <li title="<?php echo ($form_q_arinv); ?>">
            <input name="v_arinv" id="v_arinv" value="AR Inv" type="submit">
        <li title="<?php echo ($form_q_arinv); ?>">
            <input name="v_arpay" id="v_arpay" value="AR Income" type="submit">
        <li title="<?php echo ($form_q_comp); ?>">
            <input name="v_comp" id="v_comp" value="Companies" type="submit">
        <li title="<?php echo ($form_q_acct); ?>">
            <input name="v_acct" id="v_acct" value="Accts" type="submit">
        <li>
            <input name="v_rpt" id="v_rpt" value="Reports" type="submit">
        <li>
            <select name="v_schedE" id="v_schedE">
              <?php
                if(!empty($_REQUEST['v_schedE'])) {
                 $schedE = $_REQUEST['v_schedE'];
                   echo "<option value='".$schedE."'>Schedule E: ".$schedE."</option>";
                 } else {
                   echo "<option value='None'>** Select Schedule E Entity **</option>";
                 }
              ?>
              <?php
                // dropdown is populated from sched.json at runtime, via $schedE_options in shoeboxai_env.php
                foreach ($schedE_options as $code => $label) {
                  echo "              <option value='".htmlspecialchars($code)."'>Schedule E: ".htmlspecialchars($label)."</option>\n";
                }
              ?>
            </select>
        <!--- li><a href='http://www.printfriendly.com' style='color:#6D9F00; text-decoration:none;' class='printfriendly' onclick='window.print(); return false;'><img style='border:none;-webkit-box-shadow:none;box-shadow:none;' src='http://cdn.printfriendly.com/button-print-gry20.png'></a  --->
      </ul>
  </td></tr></table>
</div>
<div style="clear: both;"></div>
<div>

<!---  main div ---------------------------------------------------------  --->
<!-----------------------------------------------------------------------  --->
<!---  invoices ap display ----------------------------------------------  --->
<?php
   if(!empty($_REQUEST['v_apinv'])) : 
?>

  <div><table><tr><td>AP Invoices</td></tr>
  <tr><td><div id="main_wrap"><table>
      <?php get_open_invoices($invtype_ap,$schedE); ?>
  </table></div></td></tr></table>

<!---  invoices add -----------------------------------------------------  --->
<?php
  // page for new inv - either ap or ar
  elseif(!empty($_REQUEST['f_inv_add_ap'])) :
?>
     <table><tr><td><div id="main_wrap"><table>
     <?php build_addinv_page(2); ?>
     </table></div></td></tr></table>

<!---  invoices add -----------------------------------------------------  --->
<?php
  // page for new inv - either ap or ar
  elseif(!empty($_REQUEST['f_inv_add_ar'])) :
?>
     <table><tr><td><div id="main_wrap"><table>
     <?php build_addinv_page(1); ?>
     </table></div></td></tr></table>

<!---  invoices insert --------------------------------------------------  --->
<?php
  // insert inv - either ap or ar
  elseif(!empty($_REQUEST['f_inv_ins'])) :

  $entity_id = $_REQUEST['v_schedE'];
  $cid       = $_REQUEST['f_inv_cid'];
  $idate     = $_REQUEST['f_inv_idate'];
  $itype     = $_REQUEST['f_inv_itype'];
  $amt       = $_REQUEST['f_inv_amt'];
  $acct      = $_REQUEST['f_inv_acct'];
  $auto      = $_REQUEST['f_inv_auto'];

  insinv($entity_id,$cid,$idate,$itype,$amt,$acct,$auto);
?>

<!---  invoices delete --------------------------------------------------  --->
<?php
  // delete inv - either ap or ar
  elseif(!empty($_REQUEST['f_inv_del'])) :

  $id      = $_REQUEST['f_inv_id'];
  delinv($id);
?>

<!---  invoices update submit  -----------------------------------------  --->
<?php
  // update inv - either ap or ar
  elseif(!empty($_REQUEST['f_inv_updsubmit'])) :

  $id        = $_REQUEST['f_inv_id'];
  $entity_id = $_REQUEST['v_schedE'];
  $cid       = $_REQUEST['f_inv_cid'];
  $idate     = $_REQUEST['f_inv_idate'];
  $itype     = $_REQUEST['f_inv_itype'];
  $amt       = $_REQUEST['f_inv_amt'];
  $acct      = $_REQUEST['f_inv_acct'];
  $balance   = $_REQUEST['f_inv_balance'];
  $auto      = $_REQUEST['f_inv_auto'];

  updinv($id,$entity_id,$cid,$idate,$itype,$amt,$acct,$balance,$auto);
?>
<!---  invoices ap pay ------------------------------------------------  --->
<?php
  // pay ap invoices
  elseif(!empty($_REQUEST['v_appay'])) :
  $schedE    = $_REQUEST['v_schedE'];
?>
  <div><table><tr><td>AP Invoices Pay</td></tr>
  <tr><td><div id="main_wrap"><table>
      <?php get_open_invpay($invtype_ap,$schedE); ?>
  </table></div></td></tr></table>

<!---  invoices ar display ---------------------------------------------  --->
<?php
  elseif(!empty($_REQUEST['v_arinv'])) :
    $schedE    = $_REQUEST['v_schedE'];
?>

  <div><table><tr><td>AR Invoices</td></tr>
  <tr><td><div id="main_wrap"><table>
      <?php get_open_invoices($invtype_ar,$schedE); ?>
  </table></div></td></tr></table>

<!---  invoices update 1 row --------------------------------------------  --->
<?php
  elseif(!empty($_REQUEST['f_inv_upd'])) :

  $id      = $_REQUEST['f_inv_id'];
?>

  <div><table><tr><td>Update Invoice</td></tr>
  <tr><td><div id="main_wrap"><table>
      <?php build_updinv_page($id); ?>
  </table></div></td></tr></table>

<!---  invoices ar pay ------------------------------------------------  --->
<?php
  // receive income ar invoices
  elseif(!empty($_REQUEST['v_arpay'])) :
    $schedE    = $_REQUEST['v_schedE'];
?>

  <div><table><tr><td>AR Invoices Income</td></tr>
  <tr><td><div id="main_wrap"><table>
      <?php get_open_invpay($invtype_ar,$schedE); ?>
  </table></div></td></tr></table>

<!---  invoices submit pay ---------------------------------------------  --->
<?php
  // receive income or make payments, ar and ap invoices
  elseif(!empty($_REQUEST['f_invpay_submit'])) :
     $itype  = $_REQUEST['f_invpay_type'];
     $invnum = $_REQUEST['f_invnum'];
     invpay_submit($itype,$invnum);
     $schedE    = $_REQUEST['v_schedE'];
?>
  <div><table><tr><td>Invoices Payment/Income</td></tr>
  <tr><td><div id="main_wrap"><table>
      <?php get_open_invpay($itype,$schedE); ?>
  </table></div></td></tr></table>

<!-----------------------------------------------------------------------  --->
<!--- companies ---------------------------------------------------------  --->

<?php
  // display company list
  elseif(!empty($_REQUEST['v_comp'])) :
?>

  <div><table><tr><td>Companies</td></tr>
     <tr><td><div id="main_wrap"><table>
      <?php get_comp_list(); ?>
  </table></div></td></tr></table>

<!---  company add ------------------------------------------------------  --->
<?php
  // display add company page
  elseif(!empty($_REQUEST['f_comp_add'])) :
?>
  <div><table width=100% align=center><tr><td>Add Company</td></tr>
     <tr><td><div id="main_wrap"><table>
     <?php build_addcomp_page(); ?>
     </table></div></td></tr></table>

<!---  company insert submit --------------------------------------------  --->
<?php
  // add company 
  elseif(!empty($_REQUEST['f_comp_ins'])) :

//  $id      = $_REQUEST['f_comp_id'];
  $name    = $_REQUEST['f_comp_name'];
  $addr1   = $_REQUEST['f_comp_addr1'];
  $addr2   = $_REQUEST['f_comp_addr2'];
  $addr3   = $_REQUEST['f_comp_addr3'];
  $phone1  = $_REQUEST['f_comp_phone1'];
  $phone2  = $_REQUEST['f_comp_phone2'];
  $fax     = $_REQUEST['f_comp_fax'];
  $notes   = $_REQUEST['f_comp_notes'];
  $ctype   = $_REQUEST['f_comp_ctype'];
  $acct    = $_REQUEST['f_comp_acct'];

  mk_comp_array(0,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$acct,$notes,1);
?>

<!---  company update 1 row  --------------------------------------------  --->
<?php
  // display update company page
  elseif(!empty($_REQUEST['f_comp_upd'])) :
  ?>
  <div><table width=100% align=center><tr><td>Edit Company</td></tr>
     <tr><td><div id="main_wrap"><table>
  <?php
    $id = $_REQUEST['f_comp_id'];
    $row     = get_1_company($id);
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
 
    build_updcomp_page($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$notes,$ctype); ?>
   </table></div></td></tr></table>

<!---  company update submit --------------------------------------------  --->
<?php
  // submit edit company
  elseif(!empty($_REQUEST['f_comp_updsubmit'])) :

  $id      = $_REQUEST['f_comp_id'];
  $name    = $_REQUEST['f_comp_name'];
  $addr1   = $_REQUEST['f_comp_addr1'];
  $addr2   = $_REQUEST['f_comp_addr2'];
  $addr3   = $_REQUEST['f_comp_addr3'];
  $phone1  = $_REQUEST['f_comp_phone1'];
  $phone2  = $_REQUEST['f_comp_phone2'];
  $fax     = $_REQUEST['f_comp_fax'];
  $notes   = $_REQUEST['f_comp_notes'];
  $ctype   = $_REQUEST['f_comp_ctype'];
  upd_comp($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$notes); 
  mk_comp_array($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,0,$notes,1);
?>

<!---  company delete submit --------------------------------------------  --->
<?php
  // delete 1 company 
  elseif(!empty($_REQUEST['f_comp_del'])) :

  $id  = $_REQUEST['f_comp_id'];
  del_comp($id);
?>

<!-----------------------------------------------------------------------  --->
<!--- accts -------------------------------------------------------------  --->
<?php
  // display account list
  elseif(!empty($_REQUEST['v_acct'])) :
?>

  <div><table width=100% align=center><tr><td>Chart of Accounts</td></tr>
     <tr><td><div id="main_wrap"><table>
      <?php get_accts_page(); ?>
  </table></div></td></tr></table>

<!--- accts add submit --------------------------------------------------  --->
<?php
  // submit add account
  elseif(!empty($_REQUEST['f_acct_addsubmit'])) :

  $id     = $_REQUEST['f_acct_id'];
  $name   = $_REQUEST['f_acct_name'];
  $atype  = $_REQUEST['f_acct_atype'];
  add_acct($id,$name,$atype);
  get_accts();
?>

<!--- accts update submit -----------------------------------------------  --->
<?php
  // submit edit account
  elseif(!empty($_REQUEST['f_acct_updsubmit'])) :

  $id     = $_REQUEST['f_acct_id'];
  $name   = $_REQUEST['f_acct_name'];
  $atype  = $_REQUEST['f_acct_atype'];
  $pid    = $_REQUEST['f_acct_pid'];
  upd_acct($id,$name,$atype,$pid);
  get_accts();
?>

<!--- accts delete submit -----------------------------------------------  --->
<?php
  // delete 1 acct
  elseif(!empty($_REQUEST['f_acct_delsubmit'])) :

  $id = $_REQUEST['f_acct_id'];
  del_acct($id);
  get_accts();
?>

<!----------------------------------------------------------------------  --->
<!--- reporting --------------------------------------------------------  --->

<?php
  // submit profile changes
  elseif(!empty($_REQUEST['v_rpt'])) :
  $schedE = $_REQUEST['v_schedE'];
?>
  <div><form name="ReportForm" method="post" action="<?php echo($php_self); ?>">
     <table><tr><td>Reports</td></tr>
     <tr><td><div id="main_wrap"><table>
        <?php get_rpt_page($schedE); ?>
     </table></div></td></tr></table>

<!--- rpt preview ------------------------------------------------------  --->
<?php
  // preview - displays to the screen
  elseif(!empty($_REQUEST['v_rpt_peak'])) :
  $schedE    = $_REQUEST['v_schedE'];
  $bdate    = $_REQUEST['f_rpt_bdate'];
  $edate    = $_REQUEST['f_rpt_edate'];
  $vendor   = $_REQUEST['v_rpt_v'];
  $customer = $_REQUEST['v_rpt_c'];
  $rtype    = $_REQUEST['v_rpt_type'];
?>

  <div><form name="ReportForm" method="post" action="<?php echo($php_self); ?>">
     <table><tr><td>Reports: Preview</td></tr>
     <tr><td><div id="main_wrap"><table>
        <?php rpt_exec($schedE,$bdate,$edate,$vendor,$customer,$rtype,'preview'); ?>
     </table></div></td></tr></table>
  
<!--- rpt print  ------------------------------------------------------  --->
<?php
  // run one report - writes to disk, then to printer
  elseif(!empty($_REQUEST['v_rpt_exec'])) :
  $schedE    = $_REQUEST['v_schedE'];
  $bdate    = $_REQUEST['f_rpt_bdate'];
  $edate    = $_REQUEST['f_rpt_edate'];
  $vendor   = $_REQUEST['v_rpt_v'];
  $customer = $_REQUEST['v_rpt_c'];
  $rtype    = $_REQUEST['v_rpt_type'];
?>

  <div><form name="ReportForm" method="post" action="<?php echo($php_self); ?>">
     <table><tr><td>Reports: Print</td></tr>
     <tr><td><div id="main_wrap"><table>
   <?php rpt_exec($schedE,$bdate,$edate,$vendor,$customer,$rtype,'exec'); ?>

  </table></div></td></tr></table>


<!----------------------------------------------------------------------  --->
<!--- profile ----------------------------------------------------------  --->

<?php
  // submit profile changes
  elseif(!empty($_REQUEST['f_profile_submit'])) :
  $name    = $_REQUEST['f_profile_name'];
  $website = '';
  $addr1   = $_REQUEST['f_profile_addr1'];
  $addr2   = $_REQUEST['f_profile_addr2'];
  $addr3   = $_REQUEST['f_profile_addr3'];
  $phone   = $_REQUEST['f_profile_phone'];
  $fax     = $_REQUEST['f_profile_fax'];
  $notes   = $_REQUEST['f_profile_notes'];
  upd_profile($name,$website,$addr1,$addr2,$addr3,$phone,$fax,$notes);
?>
  <table width=100% align=center><tr><td><div id="main_wrap"><table cols=2>
      <tr><th colspan=2>Home Page</th></tr>
      <?php get_profile(); ?>
  </table></div></td></tr></table>

<!----------------------------------------------------------------------  --->
<!--- home -------------------------------------------------------------  --->
<?php
  // home page
  else :
?>
  <table width=100% align=center><tr><td><div id="main_wrap"><table cols=2>
      <tr><th colspan=2>Home Page</th></tr>
      <?php get_profile(); ?>
  </table></div></td></tr></table>

<?php 
  endif;
?>

</div></div></form></div>
</body>
</html>

