<?php

/**
 * 查看session_id是否存在对应的cus_id
 * 存在-->无操作
 * 不存在-->访问统计+1，
 */
session_start();
$query = $_SERVER['QUERY_STRING'];
if (empty($query)) {//===参数为空===
    echo "param is null";
    exit;
}
$db = new PDO("mysql:host=localhost;dbname=unify", "root", ""); //链接数据库
$array = explode('&', $query);
foreach ($array as $k => $v) {
    $t = explode('=', $v);
    $param[$t[0]] = $t[1];
}

$cus_id = $param['cus_id'];
$platform = $param['platform'];
$ip = $_SERVER['REMOTE_ADDR'];
$expires = date("Y-m-d H:i:s", time() + 60 * 30); //30分钟
$now = date("Y-m-d H:i:s");
$date = date("Y-m-d");
$sessid = session_id();
$log = findLog($cus_id, $ip, $db);
//查找日志是否存在
if (empty($log)) {
    //如果不存在添加    
    $dataLog = array(
        'cus_id' => $cus_id,
        'ip' => $ip,
        'expires' => $expires,
        'sessid' => $sessid
    );
    $insertLog = insertLog($dataLog, $db);
} else {
    $dataLog = array(
        'expires' => $expires,
        'ip' => $ip,
        'sessid' => $sessid
    );
    $updateLog = updateLog($cus_id, $dataLog, $db);
}
//查看是否存在统计记录
$counter = findCount($cus_id, $db);
if (empty($counter)) {
    //不存在，添加
    $dataCount = array(
        'cus_id' => $cus_id,
        'record_date' => $date
    );
    $insertCount = insertCount($dataCount, $db);
    $updateCount = updateCount($cus_id, $platform, $counter, $db);
} else {
    if ($log['sessid'] == $sessid && $log['expires'] >= $now) {//不更新统计数据
        echo 'not need update statis';
    } else {//更新
        $updateCount = updateCount($cus_id, $platform, $counter, $db);
        echo 'update statis succeed';
    }
    //更新记录
}

/**
 * 查找日志
 * @param type $cus_id
 * @param type $db
 * @return type
 */
function findLog($cus_id, $ip, $db) {
    $res = $db->query("select * from up_statis_log where cus_id=$cus_id and ip='$ip' limit 1", PDO::FETCH_ASSOC);
    foreach ($res as $v) {
        $row = $v;
    }
    if (empty($row)) {
        return 0;
    } else {
        return $row;
    }
}

/**
 * 插入日志
 * @param type $data
 * @param type $db
 * @return type
 */
function insertLog($data, $db) {
    $res = $db->query("insert into up_statis_log (cus_id, ip, sessid, expires) value ('$data[cus_id]', '$data[ip]', '$data[sessid]', '$data[expires]')");
    return $res;
}

/**
 * 更新日志
 * @param type $data
 * @param type $db
 * @return type
 */
function updateLog($cus_id, $data, $db) {
    $res = $db->query("update up_statis_log set ip='$data[ip]', sessid='$data[sessid]', expires='$data[expires]' where cus_id=" . $cus_id);
    return $res;
}

/**
 * 查找统计记录
 * @param type $cus_id
 * @param type $db
 * @return type
 */
function findCount($cus_id, $db) {
    $res = $db->query("select * from up_statis where cus_id=$cus_id limit 1", PDO::FETCH_ASSOC);
    foreach ($res as $v) {
        $row = $v;
    }
    if (empty($row)) {
        return 0;
    } else {
        return $row;
    }
}

/**
 * 添加统计记录
 * @param type $cus_id
 * @return type 成功-id，不成功-false
 */
function insertCount($data, $db) {
    $res = $db->query("insert into up_statis (cus_id, record_date) value ('$data[cus_id]', '$data[record_date]')");
    return $res;
}

/**
 * 更新统计记录
 * @param type $cus_id
 * @param type $platform
 * @param type $counter
 * @param type $db
 * @return type
 */
function updateCount($cus_id, $platform, $counter, $db) {
    $today = date("Y-m-d");
    $lastday = date('Y-m-d', strtotime("-1 day"));
    if ($platform == "pc") {
        if ($counter['record_date'] == $today) {//最后访问当天访问
            $res = $db->query("update up_statis set count_all=count_all+1, count_today=count_today+1 where cus_id=$cus_id");
        } elseif ($counter['record_date'] == $lastday) {//最后访问是昨天
            $res = $db->query("update up_statis set count_all=count_all+1, count_lastday=$counter[count_today], count_today=1, record_date='$today' where cus_id=$cus_id");
        } else {//最后访问是很久以前
            $res = $db->query("update up_statis set count_all=count_all+1, count_lastday=0, count_today=1, record_date='$today' where cus_id=$cus_id");
        }
    } elseif ($platform == "mobile") {
        if ($counter['record_date'] == $today) {//最后访问当天访问
            $res = $db->query("update up_statis set count_all=count_all+1, count_today=count_today+1, mobile_count=mobile_count+1 where cus_id=$cus_id");
        } elseif ($counter['record_date'] == $lastday) {//最后访问是昨天
            $res = $db->query("update up_statis set count_all=count_all+1, count_lastday=$counter[count_today], count_today=1, mobile_count=mobile_count+1, record_date='$today' where cus_id=$cus_id");
        } else {//最后访问是很久以前
            $res = $db->query("update up_statis set count_all=count_all+1, count_lastday=0, count_today=1, mobile_count=mobile_count+1, record_date='$today' where cus_id=$cus_id");
        }
    }
    return $res;
}
