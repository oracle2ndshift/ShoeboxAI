# ShoeboxAI — Build Scripts

This directory holds the SQL needed to (re)create the `ShoeboxAI` database from
nothing: the schema and seed data live in `build.sql`, and the stored
functions/procedures live in `functions.sql`. Together they reproduce the full
set of database objects the PHP application talks to.

## Files

| File            | Purpose                                                                 |
|-----------------|-------------------------------------------------------------------------|
| `build.sql`     | Drops and recreates the `ShoeboxAI` schema, all tables, indexes, and seed rows (chart of accounts + Schedule E entities). |
| `functions.sql` | Creates the stored functions (`acct_get_sum*`, `acct_get_parent`) and report procedures (`rpt_gl`, `rpt_per_company`, `rpt_sched_e`, `rpt_debug`, `get_open`). |
| `readme.md`     | This file.                                                              |

## How to rebuild

`build.sql` is destructive — it `drop database ShoeboxAI` before recreating
it. Run it as a user with `DROP`/`CREATE` privileges (in this project, `dba`):

```bash
# from the repo root
mysql -u dba -p < build/build.sql
mysql -u dba -p ShoeboxAI < build/functions.sql
```

`build.sql` writes its progress to `build.lis` via `tee`. The credentials used
by the PHP app at runtime are hardcoded in `../shoeboxai_db.php`
(`localhost`, user `dba`, database `ShoeboxAI`).

## Schema

Six tables, all `InnoDB / latin1`. The accounting model is double-entry-lite:
invoices (`inv`) record amounts owed/owing, payments (`pay`) record cash in/out
against an invoice, and every invoice is filed under a chart-of-accounts node
(`acct`) and a Schedule E entity (`entities`).

### `profile` — single-record "who am I"

Holds the one user/business profile for this install. Seeded with one row at
build time.

| Column    | Type           | Notes                       |
|-----------|----------------|-----------------------------|
| `id`      | int, PK, auto  |                             |
| `login`   | varchar(25)    | login name (e.g. `dba`)     |
| `passwd`  | varchar(35)    | plaintext (personal-use app)|
| `name`    | varchar(25)    | display name                |
| `website` | varchar(50)    |                             |
| `addr1`   | varchar(25)    |                             |
| `addr2`   | varchar(25)    |                             |
| `addr3`   | varchar(25)    |                             |
| `phone`   | varchar(25)    |                             |
| `fax`     | varchar(25)    |                             |
| `notes`   | varchar(100)   |                             |

### `companies` — AP and AR counterparties

Customers and vendors both live here, discriminated by `ctype`.

| Column   | Type          | Notes                                                  |
|----------|---------------|--------------------------------------------------------|
| `id`     | int, PK, auto |                                                        |
| `name`   | varchar(25)   |                                                        |
| `addr1`  | varchar(25)   |                                                        |
| `addr2`  | varchar(25)   |                                                        |
| `addr3`  | varchar(25)   |                                                        |
| `phone1` | varchar(25)   |                                                        |
| `phone2` | varchar(25)   |                                                        |
| `fax`    | varchar(25)   |                                                        |
| `notes`  | varchar(25)   |                                                        |
| `ctype`  | int           | `0` = vendor, `1` = customer (mirrors `@ctype_*`)      |
| `active` | int           | `1` = active, `0` = inactive                           |
| `acct`   | int           | default chart-of-accounts node for this counterparty   |

### `acct` — chart of accounts (4-level tree)

A self-referential tree keyed by `pid` (parent id). `level` is the depth (1–4)
and is denormalised for fast rendering. `atype` segments the tree into Income
(`i`), Cost of Goods Sold (`c`), and Expenses (`e`).

| Column  | Type          | Notes                                        |
|---------|---------------|----------------------------------------------|
| `id`    | int, PK       | numeric account code (100, 101, …)           |
| `level` | int           | 1 = top, 4 = leaf (max depth)                |
| `name`  | varchar(30)   |                                              |
| `atype` | varchar(1)    | `i` income, `c` cost of goods, `e` expense   |
| `pid`   | int           | parent id; `0` for top-level                 |

The depth cap of 4 is hardcoded in `functions.sql` via the
`acct_get_sum → acct_get_sum2 → acct_get_sum3 → acct_get_sum4` recursion chain
(MySQL doesn't allow direct recursion in stored functions). If you ever need a
fifth level you must add `acct_get_sum5` and update the chain — see Functions
below.

Seed data covers a standard accounting set: Income (100s), Cost of Goods Sold
(200s), Expenses (300s–900s — Advertising, Car/Truck, Depreciation, Insurance,
Professional Services, Office, Pension, Rent, Repairs/Maintenance, Supplies,
Tax/License, Travel, Utilities, Other), with sub-accounts to two or three
levels.

### `entities` — Schedule E entities

Each invoice is scoped to a Schedule E entity so reports can roll up by
property / partnership / S-corp / trust / royalty. Keyed by short `code` (used
verbatim in `inv.entity_id` and in the `v_schedE` `<select>` on `index.php`).
The seed rows are placeholders — a tax preparer rebrands them in place to
match the client's actual K-1 issuers and properties.

| Column              | Type          | Notes                                                      |
|---------------------|---------------|------------------------------------------------------------|
| `code`              | varchar(16) PK| e.g. `Rental1`, `SCorp`, `Royalty`                         |
| `name`              | varchar(50)   | display label                                              |
| `entity_type`       | varchar(20)   | `Rental`, `Royalty`, `Partnership`, `SCorp`, `EstateTrust`, `REMIC`, `Other` |
| `prop_type_code`    | int           | Sched E line 1b: 1=SFR, 2=Multi-Family, 3=Vacation/Short-Term, 4=Commercial, 5=Land, 6=Royalty, 7=Self-Rental, 8=Other |
| `fair_rental_days`  | int           | Sched E line 2a                                            |
| `personal_use_days` | int           | Sched E line 2b                                            |
| `qjv_flag`          | int(1)        | Qualified Joint Venture                                    |
| `addr1`, `addr2`    | varchar(50)   | property / issuer address                                  |
| `city`, `state`, `zip` |            | state is 2-letter                                          |
| `ein`               | varchar(11)   | `xx-xxxxxxx`, for K-1 issuers                              |
| `k1_ord_income`     | decimal(12,2) | K-1 box: ordinary business income (partnership/S-corp)     |
| `k1_net_rental_re`  | decimal(12,2) | K-1 box: net rental real estate income                     |
| `k1_other_rental`   | decimal(12,2) | K-1 box: other net rental income                           |
| `notes`             | varchar(255)  |                                                            |
| `active_flag`       | int(1)        | `1` active                                                 |

Seed `code`s: `Rental1`, `Rental2`, `Rental3`, `Royalty`, `Partnership1`,
`Partnership2`, `SCorp`, `EstateTrust`, `OtherEntity`.

### `inv` — invoices (AP and AR)

| Column      | Type           | Notes                                              |
|-------------|----------------|----------------------------------------------------|
| `id`        | int PK, auto   |                                                    |
| `entity_id` | varchar(16)    | FK by convention to `entities.code`                |
| `cid`       | int            | FK by convention to `companies.id`                 |
| `idate`     | date           | invoice date                                       |
| `itype`     | int            | `1` = AR, `2` = AP (mirrors `@invtype_*`)          |
| `amt`       | decimal(9,2)   | invoice amount                                     |
| `acct`      | int            | chart-of-accounts node (`acct.id`)                 |
| `balance`   | decimal(9,2)   | remaining amount owed; `0` once fully paid         |
| `recur`     | int            | recurrence: `0` none, `1` monthly, `2` weekly, `4` daily (mirrors `@invrecur_*`). When non-zero, `insinv()` auto-generates child invoices for every period through the end of the parent's year. |

Indexes:

- `idx_inv1 (entity_id, cid)` — joins/lookups by entity + counterparty.
- `idx_inv2 (entity_id, idate)` — date-range scans within an entity.

### `pay` — payments against invoices

| Column   | Type          | Notes                                            |
|----------|---------------|--------------------------------------------------|
| `id`     | int PK, auto  |                                                  |
| `iid`    | varchar(8)    | invoice id (matches `inv.id` cast to string)     |
| `pdate`  | date          | payment date                                     |
| `ptype`  | int           | `1` = AR (cash in), `2` = AP (cash out)          |
| `amt`    | decimal(5,2)  | payment amount **(note: tight range)**           |

Indexes:

- `idx_pay1 (iid)` — payments for a given invoice.
- `idx_pay2 (pdate)` — date-range scans.

> ⚠️ `pay.amt` is `decimal(5,2)`, capping a single payment at `999.99`. If you
> ever record larger payments you must widen this column **and** the
> `v_total` declarations in the `acct_get_sum*` functions.

## Functions

All live in `ShoeboxAI` and return `decimal(12,2)` (except `acct_get_parent`).

### `acct_get_parent(v_id varchar(4)) → varchar(4)`

Returns the `pid` of the given account row. Thin wrapper over a single
`select pid from acct where id = v_id`.

### `acct_get_sum(v_id, v_bdate, v_edate, v_entity_id) → decimal(12,2)`

Sums `pay.amt` for the account `v_id` and **all descendants** in the chart
tree, restricted to:

- payments whose `pdate` is between `v_bdate` and `v_edate`, and
- invoices belonging to Schedule E entity `v_entity_id`.

The descendant walk is hand-unrolled across four functions
(`acct_get_sum → _sum2 → _sum3 → _sum4`) because MySQL stored functions
can't recurse. Each level fetches children of the current node and calls the
next. `acct_get_sum4` is the leaf — it sums for one node only and does not
recurse further. **The chart-of-accounts tree must therefore never exceed 4
levels of depth.**

Parameters:

| Param         | Type        | Meaning                                                |
|---------------|-------------|--------------------------------------------------------|
| `v_id`        | int         | root account node                                      |
| `v_bdate`     | date        | inclusive lower bound on `pay.pdate`                   |
| `v_edate`     | date        | inclusive upper bound on `pay.pdate`                   |
| `v_entity_id` | varchar(8)  | Schedule E entity code (matches `inv.entity_id`)       |

## Procedures

### `rpt_gl(v_entity_id, v_type, v_year)`

General-ledger listing of invoices for a given entity, type (`1`=AR, `2`=AP),
and calendar year. Joins `inv`, `companies`, and `acct`; ordered by company,
type, date.

### `get_open(v_entity_id, v_type)`

Open invoices (`balance > 0`) for a given entity and type. Returns
`(id, cid, name, amt, idate)` ordered by company, then date.

### `rpt_per_company(v_comp varchar(50), v_year integer)`

Invoices in `v_year` whose company name matches the `LIKE` pattern `v_comp`
(use `%` wildcards). Ordered by company, type, entity, date.

### `rpt_sched_e(v_comp varchar(50), v_year integer)`

Same shape as `rpt_per_company`, but the `LIKE` pattern matches against
`inv.entity_id` instead of company name. Use to roll a single Schedule E
entity (or group, with `%`) for a year.

### `rpt_debug(v_year integer)`

Sanity check: lists invoices whose summed payments don't equal the invoice
amount. Useful for finding stray entries or rounding bugs.

## Constants and enums

These are declared as session vars at the top of `build.sql` and **mirrored
on the PHP side** in `../shoeboxai_env.php`. Keep both files in sync when
adding a bit or enum value.

| Var                  | Value | Meaning            | PHP mirror              |
|----------------------|-------|--------------------|-------------------------|
| `@ctype_vendor`      | `0`   | `companies.ctype`  | `$ctype_vendor`         |
| `@ctype_customer`    | `1`   | `companies.ctype`  | `$ctype_customer`       |
| `@invtype_ar`        | `1`   | `inv.itype`        | `$invtype_ar`           |
| `@invtype_ap`        | `2`   | `inv.itype`        | `$invtype_ap`           |
| `@invrecur_mon`      | `1`   | `inv.recur`        | `$invrecur_mon`         |
| `@invrecur_week`     | `2`   | `inv.recur`        | `$invrecur_week`        |
| `@invrecur_daily`    | `4`   | `inv.recur`        | `$invrecur_daily`       |

The 24-slot bitmap block at the top of `build.sql` is a reserved scratch for
status/role/app flags — most slots are still `Unused`.

## Sample queries

```sql
-- Income roll-up for an entity in 2015
select concat(a.id, '-', a.name, space(a.level*5),
              ShoeboxAI.acct_get_sum(a.id, '2015-01-01', '2015-12-31', 'Rental1')) line
  from ShoeboxAI.acct a
 where a.atype = 'i'
 order by a.id;

-- Same, but only print non-zero leaves
select concat(a.id, '-', a.name, space(a.level*5),
              ShoeboxAI.acct_get_sum(a.id, '2015-01-01', '2015-12-31', 'Rental1')) line
  from ShoeboxAI.acct a
 having (ShoeboxAI.acct_get_sum(a.id, '2015-01-01', '2015-12-31', 'Rental1') > 0 or a.level < 3)
    and a.atype = 'i';

-- All accounts with their amounts for one entity, no date filter
select a.id   as acct,
       a.name as name,
       a.atype as type,
       a.level as level,
       ShoeboxAI.acct_get_sum(a.id, '', '', 'Hoff') as amt
  from ShoeboxAI.acct a
 order by a.id;

-- Open AR for an entity
call ShoeboxAI.get_open('Rental1', 1);

-- General ledger
call ShoeboxAI.rpt_gl('Rental1', 1, 2015);

-- Cross-check: invoices whose payments don't add up
call ShoeboxAI.rpt_debug(2015);
```

## PHP entry points

For convenience, the PHP-side handlers and helpers that touch these tables.
Definitive source is `../shoeboxai_tools*.php`.

### Form handlers (POST targets in `index.php`)

```
v_login                    f_acct_addsubmit
v_apinv                    f_acct_updsubmit
v_arinv                    f_acct_delsubmit
v_comp                     f_profile_submit
v_acct                     f_inv_add / f_inv_ins / f_inv_del / f_inv_upd / f_inv_updsubmit
v_rpt                      f_comp_add / f_comp_ins / f_comp_upd / f_comp_updsubmit / f_comp_del
```

### Tool functions

```
shoeboxai_tools.php
  get_profile()
  upd_profile($name, $website, $addr1, $addr2, $addr3, $phone, $fax, $notes)

shoeboxai_tools_startup.php
  call get_companies()
  call get_accts()

shoeboxai_tools_acct.php
  mk_acct_array($id, $name, $atype)
  get_accts()
  get_accts_page()

shoeboxai_tools_comp.php
  mk_comp_array($id, $name, $addr1, $addr2, $addr3, $phone1, $phone2, $fax, $ctype, $notes, $bool)
  get_companies()
  get_comp_list()
  get_1_companies($id)
  upd_comp($id, $name, $addr1, $addr2, $addr3, $phone1, $phone2, $fax, $ctype, $notes)
  del_comp($id)
  build_addcomp_page()
  build_updcomp_page($id, $name, $addr1, $addr2, $addr3, $phone1, $phone2, $fax, $notes, $ctype)

shoeboxai_tools_inv.php
  get_open_invoices($invtype, $login)
  build_addinv_page(...)
  build_updinv_page(...)
```
