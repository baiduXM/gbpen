<?php
//查询
$db = new PDO('sqlite:visits.db');
$res=$db->prepare("select * from count");
//今日日期
$now = date('Y-m-d',time());
//没有表则创建并插入数据
if($res==false){
	$sql = 'create table count(id int primary key not null,count_all int not null,count_pc int not null,count_mobile int not null,count_today int not null,record_date date not null)';
	$create = $db->exec($sql);
	$insert = 'insert into count (id,count_all,count_pc,count_mobile,count_today,record_date)values("1","0","0","0","0","'.$now.'")';
	$record = $db->exec($insert);
	$end = array();
}else{
	$res->execute();
	$end = $res->fetchAll();
	//如果最后一次访问在昨天，今日访问量返回0	
	if($now>$end[0]['record_date']){
		$end[0]['count_today'] = 0;
	}
}

//释放
unset($db);
echo json_encode($end);