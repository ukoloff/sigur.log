<?
#if(!function_exists('sqlite3_open')) dl('sqlite3.so');

function sqlite3_escape($S)
{
 if(isset($S)) return "'".strtr($S, array("'"=>"''"))."'";
 return 'NULL';
}

function sqlite3_open($S)
{
 return new SQLite3($S);
}

function sqlite3_exec($h, $S)
{
 return $h->exec($S);
}

function sqlite3_query($h, $S)
{
 return $h->query($S);
}

function sqlite3_fetch_array($r)
{
 return $r->fetchArray(SQLITE3_ASSOC);
}

function sqlite3_fetch($r)
{
 return $r->fetchArray(SQLITE3_NUM);
}

function sqlite3_query_close($r)
{
 $r->finalize();
}

function sqlite3_last_insert_rowid($h)
{
 return $h->lastInsertRowID();
}

function sqlite3_error($h)
{
 return $h->lastErrorMsg();
}

?>
