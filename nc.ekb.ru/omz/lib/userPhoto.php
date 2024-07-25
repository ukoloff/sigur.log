<?
function getPhoto($udn)
{
 global $CFG;

 $q=@ldap_read($CFG->AD->h, $udn, 'objectClass=*', Array('jpegPhoto', 'thumbnailPhoto'));
 $q=@ldap_first_entry($CFG->AD->h, $q);
 $e=@ldap_get_values_len($CFG->AD->h, $q, 'jpegPhoto');
 if($e) return $e[0];

// $q=@ldap_read($CFG->AD->h, $udn, 'objectClass=*', Array('thumbnailPhoto'));
// $q=@ldap_first_entry($CFG->AD->h, $q);
 $e=@ldap_get_values_len($CFG->AD->h, $q, 'thumbnailPhoto');
 if($e) return $e[0];

 $q=getEntry($udn, 'employeeID');
 $q=utf2str($q[$q[0]][0]);
 if(file_exists($fn=$_SERVER['DOCUMENT_ROOT']."/img/photo/$q.jpg")) return file_get_contents($fn);
 return;
}

function hasPhoto($udn)
{
 global $CFG;

 $q=@ldap_read($CFG->AD->h, $udn, '(|(jpegPhoto=*)(thumbnailPhoto=*))', Array('1.1'));
 $q=@ldap_first_entry($CFG->AD->h, $q);
 if($q) return true;
 $q=getEntry($udn, 'employeeID');
 $q=utf2str($q[$q[0]][0]);
 return file_exists($_SERVER['DOCUMENT_ROOT']."/img/photo/$q.jpg");
}

?>
