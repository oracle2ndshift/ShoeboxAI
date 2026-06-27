-- filename build.sql
-- purpose  schema objects for ShoeboxAI
-- usage
-- bitmaps:  
--  (1,'Unused');
--  (2,'Unused');
--  (4,'Unused');
--  (8,'Unused');
--  (16,'Unused');
--  (32,'Unused');
--  (64,'Unused');
--  (128,'Unused');
--  (264,'Unused');
--  (512,'Unused');
--  (1024,'Unused');
--  (2048,'Unused');
--  (4096,'Unused');
--  (8193,'Unused');
--  (16384,'Unused');
--  (32768,'Unused');
--  (65536,'Unused');
--  (131072,'Unused');
--  (262144,'Unused');
--  (524288,'Unused');
--  (1048576,'Unused');
--  (2097152,'Unused');
--  (4194304,'Unused');
--
-- history
--	swhite	18-may-14	created
--
select 'Drop databases';
drop database ShoeboxAI;

tee build.lis
--
-- Schema
--
select 'Create databases';
create database ShoeboxAI;
--
-- Constants
--
select 'Create constants';
-- bit map
-- customer or vendor
set @ctype_vendor   = 0;
set @ctype_customer = 1;
-- AR or AP invoice type
set @invtype_ar     = 1;
set @invtype_ap     = 2;
-- Inv cycle
set @invrecur_mon   = 1;
set @invrecur_week  = 2;
set @invrecur_daily = 4;

--
-- Tables
--
-- create database ShoeboxAI;
select 'Create ShoeboxAI.profile';  -- contains 1 record
create table ShoeboxAI.profile (
  id         integer not null auto_increment,
  login      varchar(25) not null,
  passwd     varchar(35) not null,
  name       varchar(25) not null,
  website    varchar(50),
  addr1      varchar(25),
  addr2      varchar(25),
  addr3      varchar(25),
  phone      varchar(25),
  fax        varchar(25),
  notes      varchar(100),
  primary key (id)
) engine=InnoDB default charset=latin1 comment 'Who Am I';

-- Change this to be your profile, default login user=dba
insert into ShoeboxAI.profile (login,passwd,name,website,addr1,addr2,addr3,phone,fax,notes) values (
 'dba',
 '<change me>',
 '<your name>',
 '<your company>',
 '<your address 1>',
 '<your address 2>',
 '<your address 3>',
 '<your phone>',
 '<your 2nd phone>',
 '');

-- active boolean, 1=true, 0=false
select 'Create ShoeboxAI.companies';
create table ShoeboxAI.companies (
  id         integer not null auto_increment,
  name       varchar(25) not null,
  addr1      varchar(25),
  addr2      varchar(25),
  addr3      varchar(25),
  phone1     varchar(25),
  phone2     varchar(25),
  fax        varchar(25),
  notes      varchar(25),
  ctype      integer not null default 0,  
  active     integer not null default 1,
  acct       integer not null default 0,
  primary key (id)
) engine=InnoDB default charset=latin1 comment 'AP and AR companies';

select 'Create ShoeboxAI.acct';
create table ShoeboxAI.acct (
  id         integer    not null,
  level      integer not null default 1,
  name       varchar(30) not null,
  atype      varchar(1) not null default 'e',
  pid        integer    not null default '0',
  primary key (id)
) engine=InnoDB default charset=latin1 comment 'Chart of accounts';

-- default chart of accounts
delete from ShoeboxAI.acct;
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (100,1,'Income',                'i',0);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (101,2,'Gross receipts',        'i',100);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (102,3,'Rent',                  'i',101);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (103,3,'Utilities',             'i',101);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (104,3,'Fees',                  'i',101);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (105,2,'Returns and allowances','i',100);
-- cost of good sold
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (200,1,'Cost of Goods Sold',    'c',0);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (201,2,'Wages',                 'c',200);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (202,2,'Materials',             'c',200);
-- expenses
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (300,1,'Expenses',              'e',0);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (301,2,'Advertising',           'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (302,2,'Car and Truck',         'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (303,2,'Commissions and Fees',  'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (304,2,'Contract Labor',        'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (305,2,'Depletion',             'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (306,2,'Depreciation',          'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (307,2,'Emp Benefits',          'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (308,2,'Insurance',             'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (309,2,'Interest Mortgage',     'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (310,2,'Interest Other',        'e',300);

insert into ShoeboxAI.acct (id,level,name,atype,pid) values (330,2,'Professional services', 'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (331,3,  'Legal',               'e',310);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (332,3,  'Architect',           'e',310);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (333,3,  'Engineer',            'e',310);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (334,3,  'Web',                 'e',310);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (335,3,  'Accounting',          'e',310);

insert into ShoeboxAI.acct (id,level,name,atype,pid) values (360,2,'Office',                'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (361,3,  'Supplies',            'e',317);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (362,3,  'Equipment',           'e',317);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (363,3,  'Bank',                'e',317);

insert into ShoeboxAI.acct (id,level,name,atype,pid) values (370,2,'Pension',               'e',300);

insert into ShoeboxAI.acct (id,level,name,atype,pid) values (380,2,'Rent',                  'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (381,3,  'Machinery',           'e',350);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (382,3,  'Property',            'e',350);

insert into ShoeboxAI.acct (id,level,name,atype,pid) values (400,2,'Repairs Maintenance',   'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (401,3,  'Substantial',         'e',400);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (402,4,    'Roof',              'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (403,4,    'Remodel 65',        'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (404,4,    'Remodel 67a',       'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (405,4,    'Remodel 67b',       'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (406,4,    'Remodel 67c',       'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (407,4,    'Remodel 67d',       'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (408,4,    'Concrete',          'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (409,4,    'Structural',        'e',401);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (450,3,  'Routine',             'e',400);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (451,4,    'Paint',             'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (452,4,    'Pest Control',      'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (453,4,    'Septic',            'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (454,4,    'Cleaning',          'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (455,4,    'Plumbing',          'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (456,4,    'Landscaping`',      'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (457,4,    'Locksmith`',        'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (459,4,    'Electrician',       'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (458,4,    'Misc',              'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (460,4,    'Fire Safety',       'e',450);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (500,2,'Supplies',              'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (501,3,  'Routine',             'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (502,3,  'Structural',          'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (600,2,'Tax and License',       'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (700,2,'Travel',                'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (701,3,  'Travel Meals/Ent',    'e',700);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (710,3,  'Travel (non Ent)',    'e',700);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (711,4,    'Mileage',           'e',710);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (712,4,    'Hotel',             'e',710);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (713,4,    'Bridge',            'e',710);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (714,4,    'Parking',           'e',710);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (800,2,'Utilities',             'e',300);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (801,3,  'Phone',               'e',800);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (802,3,  'Cell',                'e',800);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (803,3,  'DSL',                 'e',800);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (804,3,  'T1',                  'e',800);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (805,3,  'Gas and Electric',    'e',800);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (806,3,  'Water',               'e',800);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (807,3,  'Garbage',             'e',800);
insert into ShoeboxAI.acct (id,level,name,atype,pid) values (900,2,'Other',                 'e',300);

-- Sample sql:
--
-- Income, Cost of Goods Sold, Expenses
--  select concat(a.id,'-',a.name,space(a.level*5),ShoeboxAI.acct_get_sum(a.id,'2015-1-1','2015-12-31')) from  ShoeboxAI.acct a where a.atype='i';
--  select concat(a.id,'-',a.name,space(a.level*5),ShoeboxAI.acct_get_sum(a.id,'2015-1-1','2015-12-31')) from  ShoeboxAI.acct a where a.atype='c';
--  select concat(a.id,'-',a.name,space(a.level*5),ShoeboxAI.acct_get_sum(a.id,'2015-1-1','2015-12-31')) from  ShoeboxAI.acct a where a.atype='e';
--
-- Non-zero numbers in subcategories
--  select concat(a.id,'-',a.name,space(a.level*5),ShoeboxAI.acct_get_sum(a.id,'2015-1-1','2015-12-31')) from  ShoeboxAI.acct a having (ShoeboxAI.acct_get_sum(a.id,'2015-1-1','2015-12-31')>0 or a.level<3) and a.atype='i';

-- Entities are any way of organizing your records to meet your needs.  e.g. by Schedule C and E
--   rentals, royalties, partnerships, S-corps, trusts.
-- The 'code' column is the value stored in inv.entity_id and the v_schedE
-- form option value. A tax preparer rebrands the seed rows in place to match
-- the client's actual properties / K-1 issuers.
select 'Create ShoeboxAI.entities';
create table ShoeboxAI.entities (
  code              varchar(16) not null,
  name              varchar(50) not null,
  entity_type       varchar(20) not null,            -- Rental, Royalty, Partnership, SCorp, EstateTrust, REMIC, Other
  prop_type_code    integer,                         -- Sched E line 1b: 1=SFR, 2=Multi-Family, 3=Vacation/Short-Term, 4=Commercial, 5=Land, 6=Royalty, 7=Self-Rental, 8=Other
  fair_rental_days  integer,                         -- Sched E line 2a
  personal_use_days integer,                         -- Sched E line 2b
  qjv_flag          integer (1) not null default 0,  -- Qualified Joint Venture
  addr1             varchar(50),
  addr2             varchar(50),
  city              varchar(40),
  state             varchar(2),
  zip               varchar(10),
  ein               varchar(11),                     -- xx-xxxxxxx for K-1 issuers
  k1_ord_income     decimal(12,2),                   -- K-1 box: ordinary business income (partnership/S-corp)
  k1_net_rental_re  decimal(12,2),                   -- K-1 box: net rental real estate income
  k1_other_rental   decimal(12,2),                   -- K-1 box: other net rental income
  notes             varchar(255),
  active_flag       integer (1) not null default 1,
  primary key (code)
) engine=InnoDB default charset=latin1 comment 'Schedule entities';

-- edit this list to meet your preferences.  
insert into ShoeboxAI.entities (code,name,entity_type) values
  ('Rental1',      'Rental Property 1',  'Rental'),
  ('Rental2',      'Rental Property 2',  'Rental'),
  ('Rental3',      'Rental Property 3',  'Rental'),
  ('Royalty',      'Royalty Income',     'Royalty'),
  ('Partnership1', 'Partnership 1',      'Partnership'),
  ('Partnership2', 'Partnership 2',      'Partnership'),
  ('SCorp',        'S Corporation',      'SCorp'),
  ('EstateTrust',  'Estate or Trust',    'EstateTrust'),
  ('OtherEntity',  'Other Entity',       'Other');

-- recur: 0=none, 1=monthly, 2=weekly, 4=daily (see @invrecur_* above)
-- entity_id=Schedule E entity code, cid=customer id
select 'Create ShoeboxAI.inv';
create table ShoeboxAI.inv (
  id         integer not null auto_increment,
  entity_id  varchar(16) not null,
  cid        integer not null,
  idate      date not null,
  itype      integer not null default 1,
  amt        decimal (9,2) not null default 0,
  acct       integer not null default 0,
  balance    decimal (9,2),
  recur      integer not null default 0,
  primary key (id)
) engine=InnoDB default charset=latin1 comment 'Invoices';

create index idx_inv1 on ShoeboxAI.inv(entity_id,cid);
create index idx_inv2 on ShoeboxAI.inv(entity_id,idate);

select 'Create ShoeboxAI.pay';
-- ptype=2 ap, 1=ar
create table ShoeboxAI.pay (
  id         integer not null auto_increment,
  iid        varchar(8) not null,
  pdate      date not null,
  ptype      integer not null default 2,
  amt        decimal (5,2) not null default 0,
  primary key (id)
) engine=InnoDB default charset=latin1 comment 'Payments';

create index idx_pay1 on ShoeboxAI.pay(iid);
create index idx_pay2 on ShoeboxAI.pay(pdate);
 

  
