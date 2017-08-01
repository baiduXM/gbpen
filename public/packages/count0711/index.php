<?php

//内容
$contents = file_get_contents('index.html');
echo $contents;

//今日日期
$now = date('Y-m-d',time());

$db = new PDO('sqlite:visits.db');
//查询数据
$res=$db->prepare("select * from count");
//没有就创建
if($res==false){
	$sql = 'create table count(id int primary key not null,count_all int not null,count_pc int not null,count_mobile int not null,count_today int not null,record_date date not null)';
	$create = $db->exec($sql);
	$insert = 'insert into count (id,count_all,count_pc,count_mobile,count_today,record_date)values("1","0","0","0","0","'.$now.'")';
	$record = $db->exec($insert);
	$res=$db->prepare("select * from count");
}
//执行
$res->execute();
//获取日期对比，今日访问
$end = $res->fetchAll();
$date = $end[0]['record_date'];
//计数
if($now>$date){
	$sql = "update count set count_all = count_all + 1,count_pc = count_pc + 1,count_today = 1,record_date = '".$now."'";
}else{
	$sql = "update count set count_all = count_all + 1,count_pc = count_pc + 1,count_today = count_today + 1,record_date = '".$now."'";
}
$res = $db->exec($sql);

unset($db);