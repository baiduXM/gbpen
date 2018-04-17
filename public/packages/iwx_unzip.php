<?php

$imgzip = $_GET['img']?$_GET['img']:'img.zip';
if (file_exists($imgzip)) {
    $zip = new ZipArchive; //新建一个ZipArchive的对象
    if ($zip->open($imgzip) === TRUE) {
        $res = $zip->extractTo('./'); //假设解压缩到在当前路径下images文件夹的子文件夹php
    } else {
        $res = 1001;
    }
    $zip->close(); //关闭处理的zip文件
    @unlink('./'.$imgzip);
    if($res){
        echo 1000;
    }else{
        @file_put_contents('jt_img.txt', $res.','.$imgzip.','.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
    }
} else {
    file_put_contents('exists_img.txt', $imgzip.','.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
}