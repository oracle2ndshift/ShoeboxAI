--
-- function ShoeboxAI.acct_get_parent
--
drop function ShoeboxAI.acct_get_parent;
delimiter $$
create function ShoeboxAI.acct_get_parent(v_id varchar(4))
returns varchar(4) 
begin
  declare v_pid varchar(4);
  select pid into v_pid from ShoeboxAI.acct where id=v_id;
  return v_pid;
end;
$$
delimiter ;

--
-- function  ShoeboxAI.acct_get_sum
--
drop function ShoeboxAI.acct_get_sum;
delimiter $$
create function ShoeboxAI.acct_get_sum(
   v_id integer,
   v_bdate date,
   v_edate date,
   v_entity_id varchar(8))
returns decimal(12,2)
begin
  -- local vars
  declare v_total decimal(12,2) default 0;
  declare v_cid   integer      default 0;
  declare v_done  integer      default 0;
  -- cursor stuff
  declare c_get_child cursor for select id from ShoeboxAI.acct where pid=v_id;
  declare continue handler for not found set v_done = 1;
  --
  -- increment the running total
  --
  select ifnull(sum(p.amt),0) into v_total
    from ShoeboxAI.pay p, ShoeboxAI.inv i
    where i.id=p.iid
      and i.entity_id=v_entity_id
      and i.acct=v_id
      and p.pdate between v_bdate and v_edate;

  open c_get_child;
  l_get_child: loop
    fetch c_get_child into v_cid;

    if v_done = 1 then
      leave l_get_child;
    end if;

    -- call ourselves interactively on the child id
    set v_total = v_total + ShoeboxAI.acct_get_sum2(v_cid, v_bdate, v_edate, v_entity_id);
  end loop l_get_child;

  close c_get_child;
  return v_total;
end;
$$
delimiter ;

drop function ShoeboxAI.acct_get_sum2;
delimiter $$
create function ShoeboxAI.acct_get_sum2(
   v_id integer,
   v_bdate date, 
   v_edate date, 
   v_entity_id varchar(8))
returns decimal(12,2)
begin
  -- local vars
  declare v_total decimal(12,2) default 0;
  declare v_cid   integer      default 0;
  declare v_done  integer      default 0;
  -- cursor stuff
  declare c_get_child cursor for select id from ShoeboxAI.acct where pid=v_id;
  declare continue handler for not found set v_done = 1;
  --
  -- increment the running total
  --
  select ifnull(sum(p.amt),0) into v_total
    from ShoeboxAI.pay p, ShoeboxAI.inv i
    where i.id=p.iid
      and i.entity_id=v_entity_id
      and i.acct=v_id
      and p.pdate between v_bdate and v_edate;

  open c_get_child;
  l_get_child: loop
    fetch c_get_child into v_cid;

    if v_done = 1 then
      leave l_get_child;
    end if;

    -- call ourselves interactively on the child id
    set v_total = v_total + ShoeboxAI.acct_get_sum3(v_cid, v_bdate, v_edate, v_entity_id);
  end loop l_get_child;

  close c_get_child;
  return v_total;
end;
$$
delimiter ;

drop function ShoeboxAI.acct_get_sum3;
delimiter $$
create function ShoeboxAI.acct_get_sum3(
   v_id integer,
   v_bdate date, 
   v_edate date, 
   v_entity_id varchar(8))
returns decimal(12,2)
begin
  -- local vars
  declare v_total decimal(12,2) default 0;
  declare v_cid   integer      default 0;
  declare v_done  integer      default 0;
  -- cursor stuff
  declare c_get_child cursor for select id from ShoeboxAI.acct where pid=v_id;
  declare continue handler for not found set v_done = 1;
  --
  -- increment the running total
  --
  select ifnull(sum(p.amt),0) into v_total
    from ShoeboxAI.pay p, ShoeboxAI.inv i
    where i.id=p.iid
      and i.entity_id=v_entity_id
      and i.acct=v_id
      and p.pdate between v_bdate and v_edate;

  open c_get_child;
  l_get_child: loop
    fetch c_get_child into v_cid;

    if v_done = 1 then
      leave l_get_child;
    end if;

    -- call ourselves interactively on the child id
    set v_total = v_total + ShoeboxAI.acct_get_sum4(v_cid, v_bdate, v_edate, v_entity_id);
  end loop l_get_child;

  close c_get_child;
  return v_total;
end;
$$
delimiter ;

drop function ShoeboxAI.acct_get_sum4;
delimiter $$
create function ShoeboxAI.acct_get_sum4(
   v_id integer,
   v_bdate date, 
   v_edate date, 
   v_entity_id varchar(8))
returns decimal(12,2)
begin
  -- local vars
  declare v_total decimal(12,2) default 0;
  declare v_cid   integer      default 0;
  declare v_done  integer      default 0;
  --
  -- increment the running total
  --
  select ifnull(sum(p.amt),0) into v_total
    from ShoeboxAI.pay p, ShoeboxAI.inv i
    where i.id=p.iid
      and i.entity_id=v_entity_id
      and i.acct=v_id
      and p.pdate between v_bdate and v_edate;

  return v_total;
end;
$$
delimiter ; 

drop procedure ShoeboxAI.rpt_gl;
delimiter $$
create procedure ShoeboxAI.rpt_gl(
   v_entity_id varchar(8),
   v_type integer,
   v_year integer)
begin
  --
  -- increment the running total
  --
  select 'General Ledger \n'||v_entity_id||'\n2015 \n';
  select i.id                 as 'Inv#',
    c.name                    as Company,
    i.idate                  as Date,
    i.amt                     as Amount,
    if(i.itype=1,'AR','AP')   as type,
    i.acct                    as 'Acct#',
    a.name                    as Acct
 from inv i, companies c, acct a
 where i.entity_id=v_entity_id
 and i.idate>=v_year||'-01-01'
 and i.idate<=v_year||'-12-31'
 and i.itype=v_type
 and i.cid=c.id
 and a.id=i.acct
 order by c.name,i.itype,i.idate;
end;
$$
delimiter ;

-- Sample sql
--  select id,name,ShoeboxAI.acct_get_sum(id,'2015-1-1','2015-12-31',0,'Hoff') from ShoeboxAI.acct;


select a.id acct,
     a.name      name,
     a.atype     type,
     a.level     level,
     ShoeboxAI.acct_get_sum(a.id,'','','Hoff') amt 
     from ShoeboxAI.acct a 
     order by a.id;


--
-- procedure ShoeboxAI.get_open returns open invoices by entity_id and type
--
drop procedure ShoeboxAI.get_open;
delimiter $$
create procedure ShoeboxAI.get_open(
   v_entity_id varchar(8),
   v_type integer)
begin
  --
  -- increment the running total
  --
  select i.id,i.cid,c.name,i.amt,i.idate 
  from ShoeboxAI.inv i, ShoeboxAI.companies c 
  where i.cid=c.id and i.entity_id=v_entity_id and i.itype=v_type and i.balance>0
  order by c.name,i.idate;
end;
$$
delimiter ;


--
-- procedure ShoeboxAI.rpt_per_company(Company,year)
--
drop procedure ShoeboxAI.rpt_per_company;
delimiter $$
create procedure ShoeboxAI.rpt_per_company(
  v_comp varchar(50),
  v_year integer)
begin
  select i.id 'InvNo',
         i.cid 'CompNo',
         i.entity_id 'Schd CE',
         c.name 'Company',
         i.amt 'Amt',
         i.idate 'Date',
         i.acct 'Acct',
         a.name 'Acct',
         if(i.itype=1,'AR','AP') 'Type'
  from ShoeboxAI.inv i, ShoeboxAI.companies c, ShoeboxAI.acct a
  where i.cid=c.id 
    and i.acct = a.id
    and year(i.idate)=v_year 
    and c.name like v_comp
  order by c.name,i.itype,i.entity_id,i.idate;
end;
$$
delimiter ;

--
-- procedure ShoeboxAI.rpt_sched_e(Company,year)
--
drop procedure ShoeboxAI.rpt_sched_e;
delimiter $$
create procedure ShoeboxAI.rpt_sched_e(
  v_comp varchar(50),
  v_year integer) 
begin
  select i.id 'InvNo',
         i.cid 'CompNo',
         i.entity_id 'Schd CE',   
         c.name 'Company',
         i.amt 'Amt',
         i.idate 'Date',
         i.acct 'Acct', 
         a.name 'Acct',
         if(i.itype=1,'AR','AP') 'Type'
  from ShoeboxAI.inv i, ShoeboxAI.companies c, ShoeboxAI.acct a
  where i.cid=c.id 
    and i.acct = a.id
    and year(i.idate)=v_year 
    and i.entity_id like v_comp
  order by c.name,i.itype,i.entity_id,i.idate;
end;
$$  
delimiter ;


--
-- procedure ShoeboxAI.rpt_debug(Company,year)
--
drop procedure ShoeboxAI.rpt_debug;
delimiter $$
create procedure ShoeboxAI.rpt_debug(
  v_year integer)
begin
  select i.id 'InvNo',
         i.cid 'CompNo',
         i.entity_id 'Schd CE',
         c.name 'Company',
         i.amt 'Amt',
     sum(p.amt) 'Paid',
         i.idate 'Date',
         i.acct 'Acct',
         a.name 'Acct',
         if(i.itype=1,'AR','AP') 'Type'
  from ShoeboxAI.inv i, ShoeboxAI.pay p, ShoeboxAI.companies c, ShoeboxAI.acct a
  where i.cid=c.id
    and i.acct = a.id
    and i.id=p.iid
    and year(i.idate)=v_year
  group by i.id ,i.cid ,i.entity_id ,c.name,i.amt,i.idate,i.acct,a.name,i.itype
  having sum(p.amt) != i.amt
  order by c.name,i.itype,i.entity_id,i.idate;
end;
$$
delimiter ;


