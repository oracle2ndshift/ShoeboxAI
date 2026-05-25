-- filename:  functions.sql
-- purpose:   build functions for orawiki schema.
-- usage:     source functions.sql
-- history:
--	swhite	26-feb-2013	created
--
-- --------------------------------------------------------------------------
-- name:       Admin.isset 
-- purpose:    Tests for variable set.
-- usage:      if not isset(@status_new) then ...
-- parameters: p1=string
-- returns:    boolean
-- --------------------------------------------------------------------------
drop function Admin.isset;
delimiter /
create function Admin.isset(v_str1 varchar(50))
returns boolean
not deterministic
reads sql data
begin
return if(isnull(v_str1),false,true);
end/
delimiter ;

-- --------------------------------------------------------------------------
drop function Admin.chars;
delimiter /
create function Admin.chars(v_str1 varchar(100))
returns varchar(100)
not deterministic
reads sql data
begin
  declare ret varchar(100);

  set ret = replace(replace(replace(
              v_str1,' ',''),
                     '-',''),
                     '''','');

  return ret;
end/
delimiter ;

-- --------------------------------------------------------------------------
-- name:       Admin.chars2
-- purpose:    Returns the string, minus any non alphanumeric char
-- parameters: p1=string
-- returns:    string
-- --------------------------------------------------------------------------
drop function Admin.chars2;
delimiter /
create function Admin.chars2(v_str1 varchar(100))
returns varchar(100)
not deterministic
reads sql data
begin
  declare ret varchar(100);

  set ret = replace(replace(replace(replace(replace(replace( replace(replace(replace(replace(replace(replace(replace(replace( replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(
              v_str1,' ',''),
                     '-',''),
                     '_',''),
                     '"',''),
                     '''',''),
                     '$',''),
                     '^',''),
                     '#',''),
                     '@',''),
                     '!',''),
                     '&',''),
                     '*',''),
                     '(',''),
                     ')',''),
                     '+',''),
                     '=',''),
                     '{',''),
                     '}',''),
                     '[',''),
                     ']',''),
                     '|',''),
                     '.',''),
                     ',',''),
                     '<',''),
                     '>',''),
                     '?',''),
                     ':',''),
                     ';','');


  return ret;
end/
delimiter ;

-- --------------------------------------------------------------------------
-- name:       Admin.capcase
-- purpose:    Get Capcase version of a string, where the first letter of each 
--             word is capitolized.  This for peoples names, and a string may 
--             include more than 1 name.  e.g. "Mary Beth"
-- parameters: p1=string
-- returns:    string
-- usage:      select Admin.capcase([string]);
-- --------------------------------------------------------------------------
drop function Admin.capcase;
delimiter //
create function Admin.capcase (v_str varchar(100))
  RETURNS varchar(100)
  READS SQL DATA
begin
  declare ret varchar(100);
  declare pos integer default 1;

  if v_str is null then
        return null;
  end if;

  set ret = concat(ucase(substring(v_str,1,1)),lcase(substr(v_str,2)));

  --
  -- loop through the string looking for spaces
  --
  str_loop: loop

    set pos = locate(' ',ret,pos+1);
    if pos = 0 then
      leave str_loop;
    end if;

    set ret = concat(substring(ret,1,pos),ucase(substring(ret,pos+1,1)),lcase(substr(ret,pos+2)));

  end loop;

  --
  -- loop through the string looking for dashes (e.g. hyphenated names)
  --
  set pos = 2;
  str_loop2: loop

    set pos = locate('-',ret,pos+1);
    if pos = 0 then
      leave str_loop2;
    end if;

    set ret = concat(substring(ret,1,pos),ucase(substring(ret,pos+1,1)),lcase(substr(ret,pos+2)));

  end loop;

  --
  -- loop through the string looking for single quotes
  --
  set pos = 1;
  str_loop3: loop

    set pos = locate('''',ret,pos+1);
    if pos = 0 then
      leave str_loop3;
    end if;

    set ret = concat(substring(ret,1,pos),ucase(substring(ret,pos+1,1)),lcase(substr(ret,pos+2)));

  end loop;

  return ret;
end//
delimiter ;

-- --------------------------------------------------------------------------
-- name:       Admin.check_date
-- purpose:    Get if the date is a valid date.
-- parameters: p1=date
-- returns:    integer  0=false, 1=true
-- usage:      select Admin.check_date([date]); 
-- --------------------------------------------------------------------------
drop function Admin.check_date;
delimiter //
create function Admin.check_date(v_date date)
returns integer
not deterministic
reads sql data
begin
  declare ret integer default 0;

  select date_sub(date_add(v_date, interval 1 day), interval 1 day) = v_date
         into ret;

  return ifnull(ret,0);
end//
delimiter ;

-- 
-- Admin.strSplit
--
drop function Admin.strSplit;
create function Admin.strSplit(x varchar(255), delim varchar(12), pos int) returns varchar(255)
return replace(substring(substring_index(x, delim, pos), length(substring_index(x, delim, pos - 1)) + 1), delim, '');

