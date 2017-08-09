<?php

$imgzip = $_GET['img']?$_GET['img']:'img.zip';
if (file_exists($imgzip)) {
    copy($imgzip, 'mobile/'.$imgzip);
    $zip = new ZipArchive; //新建一个ZipArchive的对象
    if ($zip->open('mobile/'.$imgzip) === TRUE) {
        $res1 = $zip->extractTo('./mobile/'); //假设解压缩到在当前路径下images文件夹的子文件夹php
    }
    $zip->close(); //关闭处理的zip文件
    @unlink('./mobile/'.$imgzip);
    $zip = new ZipArchive; //新建一个ZipArchive的对象
    if ($zip->open($imgzip) === TRUE) {
        $res2 = $zip->extractTo('./'); //假设解压缩到在当前路径下images文件夹的子文件夹php
    }
    $zip->close(); //关闭处理的zip文件
    @unlink('./'.$imgzip);
    if($res1&&$res2){
        echo 1000;
    }
}