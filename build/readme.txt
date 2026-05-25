main form
  v_login
  v_apinv
  f_inv_add
  f_inv_ins
  f_inv_del
  f_inv_upd
  f_inv_updsubmit
  v_arinv
  v_comp
  v_acct
  v_rpt
  f_comp_add
  f_comp_ins
  f_comp_upd
  f_comp_updsubmit
  f_comp_del
  f_acct_addsubmit
  f_acct_updsubmit
  f_acct_delsubmit
  f_profile_submit

tools
  get_profile()
  upd_profile($name,$website,$addr1,$addr2,$addr3,$phone,$fax,$notes)
tools_startup
  call get_companies()
  call get_accts()
accts
  mk_acct_array(id,$name,$atype)
  get_accts()
  get_accts_page()
comp
  mk_comp_array($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$notes,$bool)
  get_companies()
  get_comp_list()
  get_1_companies($id)
  upd_comp($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$ctype,$notes)
  del_comp($id)
  build_addcomp_page()
  build_updcomp_page($id,$name,$addr1,$addr2,$addr3,$phone1,$phone2,$fax,$notes,$ctype)
ap and ar
  get_open_invoices($invtype,$login)
  build_addinv_page
  build_updinv_page



