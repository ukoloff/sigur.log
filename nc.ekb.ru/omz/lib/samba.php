<?
setlocale(LC_ALL, "ru_RU.cp1251");

Class smbClient
{
 var $Server, $Folder, $Path;

 function smbClient($path)
 {
  list($this->Server, $this->Folder, $this->Path)=explode('/', $this->normalizePath($path), 3);

 }

 function normalizePath($path)
 {
  $path=preg_replace("|[\\\\/]+|", '/', trim($path));
  return preg_replace('|^/|', '', preg_replace('|/$|', '', $path));
 }

 function winPath($path)
 {
  return strtr($this->normalizePath($this->Path.'/'.$path), '/', "\\");
 }

 function runSmbClient()
 {	# Почему работает такой quoting я так и не понял, но он работает
	# Проблемы отмечены только с русской буквой "я"
  global $CFG;
  $cmd='';
  foreach(func_get_args() as $s):
   if($cmd)$cmd.=' ';
   if(0==strlen($s) or preg_match('/\\s/', $s)) $s='"'.$s.'"';
   $cmd.=$s;
  endforeach;
  $cmd="/usr/bin/smbclient ".escapeShellArg('//'.$this->Server.'/'.$this->Folder).
    " -U ".escapeShellArg($CFG->AD->Domain."\\".$_SERVER['PHP_AUTH_USER'].'%'.$_SERVER['PHP_AUTH_PW'])." -c ".escapeShellArg(utf8($cmd));
  return utf2str(shell_exec($cmd));
 }

 function parseOutput($lines)
 {
  foreach(preg_split('/\\r\\n?|\\n/', $lines) as $x):
   if(!$x) continue;
   unset($z);
   preg_match('/^(.*)((\s+\S+){6})$/', $x, $match);
   list($size, $time)=preg_split('/\s+/', trim($match[2]), 2);
   if(!preg_match('/\s+\d+$/', $time)) continue;
   $z->Time=strtotime($time);
   $name=trim($match[1]);
   preg_match('/^(\S.*?)\s+([HSRAD]*)$/', $name, $match);
   $z->Name=$match[1];
   if($z->isFile=(strpos($z->Flags=$match[2], 'D')===false))
    $z->Size=$size;
   elseif('.'==$z->Name or '..'==$z->Name)
    continue;
   $Items[]=$z;
  endforeach;
  return $Items;
 }

 function listFolder($folder)
 {
  return $this->parseOutput($this->runSmbClient('ls', $this->winPath($folder.'/*')));
 }

 function fileAttrs($file)
 {
  $file=$this->winPath($file);
  $Files=&$this->parseOutput($this->runSmbClient('ls', $file));
  if(1!=count($Files)) return;
  $Files=$Files[0];
  if(strtolower(preg_replace("/^.*\\\\/", '', $file)) != strtolower($Files->Name))
    return;
  $Files->Folder=preg_replace("/\\\\[^\\\\]*$/", '', $file);
  return $Files;
 }

 function Destroy()
 {
  foreach($this->tempFiles as $f)
   unlink($f);
  unset($this->tempFiles);
 }

 function tempFile()
 {
  do{
   $r='/var/tmp/';
   for($i=7; $i>0; $i--) $r.=rand(0, 9);
  }while(file_exists($r));
  if(!$this->tempFiles)
   register_shutdown_function(array(&$this, 'Destroy'));
  $this->tempFiles[]=$r;
  return $r;
 }

 function getFile($smbPath, $localPath=NULL)
 {
  if(!isset($localPath)) $localPath=$this->tempFile();
  $this->runSmbClient('get', $smbPath, $localPath);
  return $localPath;
 }

 function createDirsFor($path)
 {
  $F=explode('/', $this->normalizePath($path));
  array_pop($F);
  $path='';
  foreach($F as $folder)
  {
   if($path)$path.="/";
   $path.=$folder;
   $this->runSmbClient('mkdir', $this->winPath($path));
  }
 }

 function putFile($localPath, $smbPath, $dirs)
 {
  if($dirs) $this->createDirsFor($smbPath);
  echo $this->runSmbClient('put', $localPath, $this->winPath($smbPath));
 }
}

function netLogonPath()
{
 global $CFG;
 $x=preg_split('/\s+/', $CFG->AD->Srv);
 $x=preg_replace('|^.*//|', '', $x[0]);
// $x=preg_replace('|[/\.].*|', '', $x);
 return "//$x/NetLogon";
}

?>
