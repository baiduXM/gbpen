<?php

/**
 * 
 */
session_start();

$db = new PDO("mysql:host=localhost;dbname=unify", "root", "");
$res = $db->query("select * from up_statis", PDO::FETCH_ASSOC);
foreach ($res as $row) {
    var_dump($row);
    echo '<br>===$row===<br>';
// $row是一个关联数组，可以直接显示，如$row['id']
}
//$res = $db->query("select * from up_statis");
//$row = mysql_fetch_row($res);
//var_dump($row);
$_SESSION['cus_id'] = 1;
$statement = $db->prepare("insert into up_statis_log (cus_id, ip, session_id, expires) value (?, ?, ?, ?)");
$statement->execute(array($cus_id, $ip, session_id(), $expires));

var_dump($_COOKIE);
echo '<br>===$_COOKIE===<br>';
var_dump($_SESSION);
echo '<br>===$_SESSION===<br>';
//getUrlParam();
