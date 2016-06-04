<?php

/**
 * 查看session_id是否存在对应的cus_id
 * 存在-->无操作
 * 不存在-->访问统计+1，
 */
session_start();
$query = $_SERVER['QUERY_STRING'];
if (empty($query)) {//===参数为空===
//    echo "参数为空";
    exit;
}
$array = explode('&', $query);
foreach ($array as $k => $v) {
    $t = explode('=', $v);
    $param[$t[0]] = $t[1];
}
$cus_id = $param['cus_id'];
$platform = $param['platform'];
$ip = $_SERVER['REMOTE_ADDR'];
$now = date("Y-m-d H:i:s");
$expires = date("Y-m-d H:i:s", time() + 60 * 30); //30分钟
$db = new PDO("mysql:host=localhost;dbname=unify", "root", "");
$res = $db->query("select * from up_statis_log where cus_id=$cus_id limit 1", PDO::FETCH_ASSOC);
foreach ($res as $v) {
    $row = $v;
}
if (empty($row)) {//不存在，未访问过，添加纪录
    echo 1;
    $statement = $db->prepare("insert into up_statis_log (cus_id, ip, PHPSESSID, expires) value (?, ?, ?, ?)");
    $flag = $statement->execute(array($cus_id, $ip, session_id(), $expires));
}
if ($row['PHPSESSID'] != session_id()) {//存在访问记录,但sessionid变更
    echo 2;
    $db->query("update up_statis_log set PHPSESSID=" . session_id() . ", expires=" . $expires . " where cus_id=" . $cus_id);
} else {//更新过期时间
    echo 3;
    $db->query("update up_statis_log set expires=" . $expires . " where cus_id=" . $cus_id);
}
//if()

echo '0';
//var_dump($row);
//echo '<br>===$row===<br>';
//$_SESSION['cus_id'] = $cus_id;

//if ($flag) {
    //如果为真，那么
//}


    