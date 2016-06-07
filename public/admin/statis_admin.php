<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$db = new PDO("mysql:host=localhost;dbname=unify", "root", ""); //链接数据库
$query = $_SERVER['QUERY_STRING'];
if (empty($query)) {//===参数为空===
    echo "param is null";
    exit;
}
$array = explode('&', $query);
foreach ($array as $k => $v) {
    $t = explode('=', $v);
    $param[$t[0]] = $t[1];
}
$cus_id = $param['cus_id'];

$res = $db->query("select * from up_statis where cus_id=$cus_id limit 1", PDO::FETCH_ASSOC);
foreach ($res as $v) {
    $row = $v;
}

echo $row;
