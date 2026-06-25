# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`WikiPay` — a single-user PHP/MySQL bookkeeping app (companies, A/R + A/P invoices, chart of accounts, reports) the author has run on a local Apache for years. There is no build step, no package manager, no test suite. Editing a `.php` file and reloading is the dev loop.

A bundled third-party **PHP-Calendar** lives in `cal/` (GPL). Treat it as a vendored library — don't modify unless the user asks.

## Running and rebuilding

- **Run the app**: deploy under Apache + PHP + MySQL with this directory as docroot; entry point is `index.php`. There is no `composer.json` or equivalent.
- **Rebuild the schema (destructive — drops `ShoeboxAI`)**:
  ```
  mysql -u dba -p < build/pay/build.sql
  ```
- **Restore from a backup dump**:
  ```
  mysql -u dba -p ShoeboxAI < backup/shoeboxai_<timestamp>.dmp
  ```
- **DB credentials are hardcoded** in `shoeboxai_db.php` (`localhost`, user `dba`, db `ShoeboxAI`). Flip `$version` between `"5.0"` (legacy `mysql_*` extension) and `"8.0"` (PDO) to switch DB driver codepaths — every data-access function in `shoeboxai_tools*.php` branches on this string.
- **SELinux**: `my-httpd.te` / `my-httpd.pp` is a custom policy module that lets `httpd_t` read `user_home_t` and write `httpd_log_t` (needed because the app is served out of a user home directory).

## Architecture

### Front controller pattern

`index.php` is a giant `if / elseif` chain dispatching on which form button was pressed. The form input **name** is the route key — there is no router, no URL routing, no MVC. Pattern:

- `v_*` keys → view/nav buttons (`v_apinv`, `v_arinv`, `v_comp`, `v_acct`, `v_rpt`, `v_appay`, `v_arpay`, `v_schedE`, `v_rpt_exec`, `v_rpt_peak`)
- `f_*_add` / `f_*_ins` / `f_*_upd` / `f_*_updsubmit` / `f_*_del` → form action submits
- `f_*` (anything else) → form field values read via `$_REQUEST['f_*']`

So "where is the AP-invoice-add handler?" → grep `index.php` for `f_inv_add_ap`.

### Bootstrap

`index.php` opens by including `shoeboxai_tools_startup.php` exactly once, guarded by `if (!isset($load_once))`. That startup file includes, in order:

1. `shoeboxai_db.php` — opens the `$pdo` (or legacy `mysql_connect`) connection.
2. `shoeboxai_env.php` — declares **all** globals: status/role/app bitmaps, todo type/status enums, form hint strings, empty arrays that the tool modules populate (`$comp_name`, `$acct_name`, `$inv_*`, `$rpt_*`); loads `sched.json` into `$schedE_options` (the Schedule E entity dropdown); opens the daily log file `logs/shoeboxai_<YYYY-MM-DD>.log` and the report scratch file `logs/shoeboxai_rpt.tmp`. Status/role/app and enum constants are mirrored on the SQL side in `build/pay/build.sql` — **keep the two in sync** when adding a bit or enum value.
3. `shoeboxai_tools.php` — profile, `runsql`, `logger`, `getlastid`, date helpers.
4. `shoeboxai_tools_acct.php` — chart of accounts (parent/child tree, `$acct_pid`/`$acct_lvl`).
5. `shoeboxai_tools_comp.php` — companies (customers + vendors discriminated by `$ctype_customer` / `$ctype_vendor`).
6. `shoeboxai_tools_inv.php` — invoices (AR vs AP discriminated by `$invtype_ar` / `$invtype_ap`).
7. `shoeboxai_tools_rpt.php` — reports; `rpt_exec($..., 'preview')` writes to screen, `rpt_exec($..., 'exec')` writes to `$rptfile` and shells out to print.

Then it calls `get_companies()` and `get_accts()` to populate the in-memory arrays that every render function reads via `global`.

### Dual-driver codepath

Every query function looks like:

```php
if ($version == "5.0") {
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) { ... }
} else {
    foreach ($pdo->query($sql) as $row) { ... }
}
```

When adding a query, follow the same shape. Don't try to factor it out — the two branches diverge on cursor APIs, and `runsql()` in `shoeboxai_tools.php` is the only shared helper.

### "Login" is not authentication — scope is a Schedule E entity

There is no password check anywhere. The scope selector is the `v_schedE` `<select>` at the top of `index.php`, populated at render time from `$schedE_options` (loaded from `sched.json` by `shoeboxai_env.php`). Current entries are tax-prep buckets like `general`, `schedA`, `schedC`, `Rental`, `Royalty`, `Partnership1`, `Partnership2`, `SCorp`, `EstateTrust`, `OtherEntity`. The selected key is passed as `$schedE` / `$entity_id` and used as the scoping filter in every list, form, and report.

Add or rename entities by editing `sched.json` — keys are stored in the DB, values are the labels shown in the UI. No restart needed.

(Historical note: an earlier version used a hardcoded business-name list — `Hoff`, `Cameo`, `Agr`, `FVE`, `O2S`, `Murphys`, `Belmont`, `Constr`, `Swhite` — filtered on a `pid` column. Sample queries under `build/pay/samples/` still reference those names.)

### SQL is built by string concatenation

Every query is assembled with `$sql .= "... '".$var."' ..."`. There is no parameter binding even in the PDO branch. Don't add input from untrusted sources without escaping.

## Subdirectory map

- `build/pay/` — `build.sql` (`ShoeboxAI` schema: profile, companies, accounts, inv), `functions.sql`, and `samples/` (a large library of ad-hoc report queries: `gl_report.sql`, `rent_*_report.sql`, `invoices_<business>.sql`, `recurring_*.sql`, plus old `.lis` outputs). The samples are run by hand against the live DB.
- `build/tkt/` — Older `Admin` / `Portal` / `ToDo` / `Time` / `Doc` / `Logs` schemas. The current `index.php` does not touch them; treat as legacy unless the user says otherwise.
- `backup/` — `mysqldump` outputs (`*.dmp`). Don't commit new ones casually.
- `cal/` — vendored PHP-Calendar (third-party, GPL). See `cal/README`, `cal/INSTALL`.
- `pay/` — currently just a stub `index.php`.
- `fonts/`, `gif/` — static assets referenced from `Shoebox.css` / `index.php`.
- `dat/` — empty.
- `logs/` — daily app logs and the report scratch file.
- `ToDo/` — directory of the author's running design notes for forms/functions that map onto `index.php` route keys; useful as a feature map.
- `sched.json` — Schedule E entity list; populates the `v_schedE` dropdown.
- `README.md` — user-facing setup and usage docs.

## Known quirks to leave alone unless asked

- `shoeboxai_tools_rpt.php_save` is an intentional backup copy of the report tools file. Don't delete.
- The many `.*.swp` / `.*.swo` / `.*.sw[a-p]` files are vim swap files from past edits; ignore them.
- `Shoebox.css` and `shoeboxai_fonts.css` ship together; `print.css` is the print-media override; `pf.css` is for the bundled PrintFriendly button injected from `index.php`.
