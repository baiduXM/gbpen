<?php
$connect=mysql_connect("localhost","root","") or die("error connecting");
mysql_select_db("unify",$connect) or die("error selecting");


$url="./muban.txt";
$fp=fopen($url,'r');
while(!feof($fp)){
	$result.=fgets($fp,1024);
}
fclose($fp);
//转化成数组，以换行分割
$arr = explode("\n",$result);
foreach($arr as $k=>$v){
	$arr[$k]=split("\t",$v);
	foreach ($v as $n => $c) {
    	$arr[$k][$n]=trim($c);
    }
}

// var_dump($arr[1]);
foreach ($arr as $key => $value) {
	if($key!==0){
		if($value[2]&&$value[1]){
			$sql="update up_template set name_bak = '".$value[2]."' where name = '".$value[1]."' and type = '1'";
			// $sql="update up_template set name_bak = '".$value[2]."' where name = '".$value[1]."' and type = 'PC'";
			// echo $sql."<br/>";
			$result=mysql_query($sql,$connect) or die("error query");
		}
		
	}
		
		

}
